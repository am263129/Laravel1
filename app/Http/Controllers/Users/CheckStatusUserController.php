<?php

namespace App\Http\Controllers\Users;

use App\Helpers;
use App\Http\Controllers\Controller;
use App\Location_log;
use App\Mail\ConfirmDevice;
use Auth;
use Mail;
use App\Siteinfo;

use App\Entities\User;
use DateTime;
use GuzzleHttp\Psr7\Response;
use Illuminate\Events\Dispatcher;
use Laravel\Passport\Bridge\AccessToken;
use Laravel\Passport\Bridge\AccessTokenRepository;
use Laravel\Passport\Bridge\Client;
use Laravel\Passport\Bridge\RefreshTokenRepository;
use Laravel\Passport\Passport;
use Laravel\Passport\TokenRepository;
use League\OAuth2\Server\CryptKey;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\Exception\UniqueTokenIdentifierConstraintViolationException;
use League\OAuth2\Server\ResponseTypes\BearerTokenResponse;

class CheckStatusUserController extends Controller
{

    public function checkUserStatus()
    {

        // Get user
        $user = Auth::user();

        // Get Site Info
        $getSiteInfo = Siteinfo::first()->payment_status;

        // If has BraintreeId
        // if payment status is true check the payment

        if (!$getSiteInfo) {

            // Check location And Set Language
            $checkLocation = true;            
            //$checkLocation = $this->checkLocation();

            if (!$checkLocation) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Please confirm the new location from your mail to access to your account'], 401);
            }


            if ($user->hasBraintreeId()) {

                $payment_details = Auth::user()->subscription('main')->asBraintreeSubscription();


                if ($user->period_end >= date("Y-m-d")) {
                    $cancel_time = $user->subscription('main')->ends_at;
                    if ($cancel_time !== null) {
                        $user->period_end = $cancel_time;
                        $user->save();
                        return response()->json([
                            'user_id' => $user->id,
                            'name' => $user->name,
                            'email' => $user->email,
                            'language' => $user->language,
                            'status' => 'cancel',
                            'cancel_time' => $user->subscription('main')->ends_at,
                            'caption' => $user->caption,
                        ]);
                    } else {

                        // When the mail is not confirmed
                        if ($user->confirmed === 0) {
                            return response()->json([
                                'user_id' => $user->id,
                                'name' => Auth::user()->name,
                                'email' => Auth::user()->email,
                                'status' => 'confirm_step',
                                'language' => $user->language,
                                'caption' => $user->caption]);
                        } else {
                            return response()->json([
                                'user_id' => $user->id,
                                'name' => $user->name,
                                'email' => $user->email,
                                'status' => 'active',
                                'language' => $user->language,
                                'caption' => $user->caption]);
                        }
                    }
                } else {
                    // Get subscription details

                    $period_end_date = $payment_details->billingPeriodEndDate->format('Y-m-d');

                    $detect_status = $payment_details->status;

                    //When an invoice payment on a subscription fails, the subscription becomes past_due.
                    //After Braintree exhausts all payment retry attempts, the subscription remains past_due or moves to a status of either canceled or unpaid, depending upon your retry settings.
                    if ($detect_status === 'past_due' || $detect_status === 'canceled' || $detect_status === 'unpaid') {
                        $user->save();
                        return response()->json([
                            'user_id' => $user->id,
                            'plan' => '1',
                            'email' => $user->email,
                            'name' => $user->name,
                            'status' => 'payment_reactive',
                            'language' => 'en',
                        ]);
                    }

                    // When subscription is active
                    if ($detect_status === 'active' || $detect_status === 'trialing' && $period_end_date >= date("Y-m-d")) {
                        $user->period_end = $period_end_date;
                        $user->save();
                        return response()->json([
                            'user_id' => $user->id,
                            'name' => $user->name,
                            'email' => $user->email,
                            'status' => 'active',
                            'language' => $user->language,
                            'caption' => $user->caption]);
                    }
                }
            } elseif ($user->period_end >= date("Y-m-d")) {
                if ($user->period_end >= date("Y-m-d")) {
                    return response()->json([
                        'user_id' => $user->id,
                        'name' => Auth::user()->name,
                        'email' => Auth::user()->email,
                        'status' => 'active',
                        'language' => $user->language,
                        'caption' => $user->caption], 200);
                } else {
                    return response()->json([
                        'user_id' => $user->id,
                        'name' => Auth::user()->name,
                        'email' => Auth::user()->email,
                        'status' => 'payment_reactive',
                        'language' => $user->language,
                        'caption' => $user->caption], 200);
                }
            } else {
                return response()->json([
                    'user_id' => $user->id,
                    'name' => Auth::user()->name,
                    'email' => Auth::user()->email,
                    'status' => 'payment_step',
                    'language' => $user->language,
                    'caption' => $user->caption], 200);
            }
        } else {

            // Set Language
            $this->setLanguage();

            // When the mail is not confirmed
            if ($user->confirmed === 0) {
                return response()->json([
                    'user_id' => $user->id,
                    'name' => Auth::user()->name,
                    'email' => Auth::user()->email,
                    'status' => 'confirm_step',
                    'language' => $user->language,
                    'caption' => $user->caption]);
            } else {
                return response()->json([
                    'user_id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'status' => 'active',
                    'language' => $user->language,
                    'caption' => $user->caption]);
            }
        }
    }

    /**
     * Check Location Of User
     *
     */
    public function checkLocation()
    {
        // Get Location by IP
        $ipLocation = Helpers::getIp();
        $getLocationLogs = Location_log::where('uid', Auth::id())->first();

        // When First Time Access To Account
        if (is_null($getLocationLogs)) {
            $setLocation = new Location_log();
            $setLocation->status = 'on';
            $setLocation->country_code = $ipLocation['countryCode'];
            $setLocation->country = $ipLocation['country'];
            $setLocation->city = $ipLocation['city'];
            $setLocation->ip = $ipLocation['query'];
            $setLocation->zip_code = $ipLocation['zip'];
            $setLocation->isp = $ipLocation['isp'];
            $setLocation->uid = Auth::id();
            $setLocation->save();

            // Set Language
            $this->setLanguage();

            return true;
        } else {

            $geAlltLocationLogsOff = Location_log::where('uid', Auth::id())->where('status', 'off')->get();
            $getLocationLogsOn = Location_log::where('uid', Auth::id())->where('status', 'on')->first();

            if (!is_null($getLocationLogsOn)) {
                if ($getLocationLogsOn->country_code == $ipLocation['countryCode'] &&
                    $getLocationLogsOn->city == $ipLocation['city']) {

                    // Set on
                    $setStatus = Location_log::find($getLocationLogsOn->id);
                    $setStatus->status = 'on';
                    $setStatus->updated_at = NOW();
                    $setStatus->save();

                    // Set Language
                    $this->setLanguage();
                    return true;
                } else {
                    if (!$geAlltLocationLogsOff->isEmpty()) {

                        foreach ($geAlltLocationLogsOff as $key => $value) {
                            if ($value->country_code == $ipLocation['countryCode'] &&
                                $value->city == $ipLocation['city']) {

                                // Set off all row
                                Location_log::where('uid', Auth::id())->where('status', 'on')->update(['status' => 'off']);

                                // Set on
                                $setStatus = Location_log::find($value->id);
                                $setStatus->status = 'on';
                                $setStatus->save();

                                // Set Language
                                $this->setLanguage();
                                return true;

                            } else {
                                $checkFailedIpExist = Location_log::where(['uid' => Auth::id(), 'ip' => $value->ip])->first();

                                if (is_null($checkFailedIpExist)) {
                                    $setLocation = new Location_log();
                                    $setLocation->status = 'failed';
                                    $setLocation->country_code = $ipLocation['countryCode'];
                                    $setLocation->country = $ipLocation['country'];
                                    $setLocation->city = $ipLocation['city'];
                                    $setLocation->ip = $ipLocation['query'];
                                    $setLocation->zip_code = $ipLocation['zip'];
                                    $setLocation->isp = $ipLocation['isp'];
                                    $setLocation->uid = Auth::id();
                                    $setLocation->confirm_hash = str_random(50);
                                    $setLocation->save();

                                    // Send Mail With Confrim Device Link
                                    Mail::to(Auth::user())
                                        ->send(new ConfirmDevice($setLocation));

                                    return false;
                                } else {
                                    // Send Mail With Confrim Device Link
                                    Mail::to(Auth::user())
                                        ->send(new ConfirmDevice($checkFailedIpExist));
                                    return false;
                                }
                            }

                        }
                    } else {

                        $checkFailedIpExist = Location_log::where(['uid' => Auth::id(), 'ip' => $ipLocation['query']])->first();

                        if (is_null($checkFailedIpExist)) {
                            $setLocation = new Location_log();
                            $setLocation->status = 'failed';
                            $setLocation->country_code = $ipLocation['countryCode'];
                            $setLocation->country = $ipLocation['country'];
                            $setLocation->city = $ipLocation['city'];
                            $setLocation->ip = $ipLocation['query'];
                            $setLocation->zip_code = $ipLocation['zip'];
                            $setLocation->isp = $ipLocation['isp'];
                            $setLocation->uid = Auth::id();
                            $setLocation->confirm_hash = str_random(50);
                            $setLocation->save();
                            // Send Mail With Confrim Device Link
                            Mail::to(Auth::user())
                                ->send(new ConfirmDevice($setLocation));
                            return false;
                        } else {
                            // Send Mail With Confrim Device Link
                            Mail::to(Auth::user())
                                ->send(new ConfirmDevice($checkFailedIpExist));
                            return false;
                        }

                    }
                }

            } else {

                if (!$geAlltLocationLogsOff->isEmpty()) {

                    foreach ($geAlltLocationLogsOff as $key => $value) {
                        if ($value->country_code == $ipLocation['countryCode'] &&
                            $value->city == $ipLocation['city']) {

                            // Set off all row
                            Location_log::where('uid', Auth::id())->where('status', 'on')->update(['status' => 'off']);

                            // Set on
                            $setStatus = Location_log::find($value->id);
                            $setStatus->status = 'on';
                            $setStatus->save();

                            // Set Language
                            $this->setLanguage();
                            return true;

                        } else {
                            $checkFailedIpExist = Location_log::where(['uid' => Auth::id(), 'ip' => $value->ip])->first();

                            if (is_null($checkFailedIpExist)) {
                                $setLocation = new Location_log();
                                $setLocation->status = 'failed';
                                $setLocation->country_code = $ipLocation['countryCode'];
                                $setLocation->country = $ipLocation['country'];
                                $setLocation->city = $ipLocation['city'];
                                $setLocation->ip = $ipLocation['query'];
                                $setLocation->zip_code = $ipLocation['zip'];
                                $setLocation->isp = $ipLocation['isp'];
                                $setLocation->uid = Auth::id();
                                $setLocation->confirm_hash = str_random(50);
                                $setLocation->save();
                                // Send Mail With Confrim Device Link
                                Mail::to(Auth::user())
                                    ->send(new ConfirmDevice($setLocation));
                                return false;
                            } else {
                                // Send Mail With Confrim Device Link
                                Mail::to(Auth::user())
                                    ->send(new ConfirmDevice($checkFailedIpExist));
                                return false;
                            }
                        }

                    }
                } else {

                    $checkFailedIpExist = Location_log::where(['uid' => Auth::id(), 'ip' => $ipLocation['query']])->first();

                    if (is_null($checkFailedIpExist)) {
                        $setLocation = new Location_log();
                        $setLocation->status = 'failed';
                        $setLocation->country_code = $ipLocation['countryCode'];
                        $setLocation->country = $ipLocation['country'];
                        $setLocation->city = $ipLocation['city'];
                        $setLocation->ip = $ipLocation['query'];
                        $setLocation->zip_code = $ipLocation['zip'];
                        $setLocation->isp = $ipLocation['isp'];
                        $setLocation->uid = Auth::id();
                        $setLocation->confirm_hash = str_random(50);
                        $setLocation->save();
                        return false;
                        // Send Mail With Confrim Device Link
                        Mail::to(Auth::user())
                            ->send(new ConfirmDevice($setLocation));
                    } else {
                        // Send Mail With Confrim Device Link
                        Mail::to(Auth::user())
                            ->send(new ConfirmDevice($checkFailedIpExist));
                        return false;
                    }

                }
            }

        }
    }

    public function setLanguage()
    {
        // get user
        $user = Auth::user();

        // Add Language By Location
        if (is_null($user->language)) {
            $ipLocation = Helpers::getIp();

            // Language in script
            $language = config('language');
            $countryCode = strtolower($ipLocation['countryCode']);

            if (in_array($countryCode, $language)) {
                $user->language = strtolower($ipLocation['countryCode']);
            } else {
                $user->language = 'en';
            }
        }
    }

    public function otpLogin(Request $request) {

        $request->validate([
            'phone' => 'required'
        ]);

        $user = \App\User::wherePhone($request->phone)->first();
        $tokenResponse = $this->getBearerTokenByUser($user, 2, false);
        return response()->json([
            'access_token' => $tokenResponse->access_token,
            'expires_in' => $tokenResponse->expires_in,
            'refresh_token' => $tokenResponse->refresh_token,
            'name' => $user->name,
            'email' => $user->email,
            'language' => $user->language,
            'user_id' => $user->id,
            ]);

    }


    // trait stuff

    /**
     * Generate a new unique identifier.
     *
     * @param int $length
     *
     * @throws OAuthServerException
     *
     * @return string
     */
    private function generateUniqueIdentifier($length = 40)
    {
        try {
            return bin2hex(random_bytes($length));
            // @codeCoverageIgnoreStart
        } catch (\TypeError $e) {
            throw OAuthServerException::serverError('An unexpected error has occurred');
        } catch (\Error $e) {
            throw OAuthServerException::serverError('An unexpected error has occurred');
        } catch (\Exception $e) {
            // If you get this message, the CSPRNG failed hard.
            throw OAuthServerException::serverError('Could not generate a random string');
        }
        // @codeCoverageIgnoreEnd
    }

    private function issueRefreshToken(AccessTokenEntityInterface $accessToken)
    {
        $maxGenerationAttempts = 10;
        $refreshTokenRepository = app(RefreshTokenRepository::class);

        $refreshToken = $refreshTokenRepository->getNewRefreshToken();
        $refreshToken->setExpiryDateTime((new \DateTime())->add(Passport::refreshTokensExpireIn()));
        $refreshToken->setAccessToken($accessToken);

        while ($maxGenerationAttempts-- > 0) {
            $refreshToken->setIdentifier($this->generateUniqueIdentifier());
            try {
                $refreshTokenRepository->persistNewRefreshToken($refreshToken);

                return $refreshToken;
            } catch (UniqueTokenIdentifierConstraintViolationException $e) {
                if ($maxGenerationAttempts === 0) {
                    throw $e;
                }
            }
        }
    }

    protected function createPassportTokenByUser(User $user, $clientId)
    {
        $accessToken = new AccessToken($user->id);
        $accessToken->setIdentifier($this->generateUniqueIdentifier());
        $accessToken->setClient(new Client($clientId, null, null));
        $accessToken->setExpiryDateTime((new DateTime())->add(Passport::tokensExpireIn()));

        $accessTokenRepository = new AccessTokenRepository(new TokenRepository(), new Dispatcher());
        $accessTokenRepository->persistNewAccessToken($accessToken);
        $refreshToken = $this->issueRefreshToken($accessToken);

        return [
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken,
        ];
    }

    protected function sendBearerTokenResponse($accessToken, $refreshToken)
    {
        $response = new BearerTokenResponse();
        $response->setAccessToken($accessToken);
        $response->setRefreshToken($refreshToken);

        $privateKey = new CryptKey('file://'.Passport::keyPath('oauth-private.key'));

        $response->setPrivateKey($privateKey);
        $response->setEncryptionKey(app('encrypter')->getKey());

        return $response->generateHttpResponse(new Response);
    }

    /**
     * @param \App\Entities\User $user
     * @param $clientId
     * @param bool $output default = true
     * @return array | \League\OAuth2\Server\ResponseTypes\BearerTokenResponse
     */
    protected function getBearerTokenByUser(User $user, $clientId, $output = true)
    {
        $passportToken = $this->createPassportTokenByUser($user, $clientId);
        $bearerToken = $this->sendBearerTokenResponse($passportToken['access_token'], $passportToken['refresh_token']);

        if (! $output) {
            $bearerToken = json_decode($bearerToken->getBody()->__toString(), true);
        }

        return $bearerToken;
    }
    // end trait stuff
}

