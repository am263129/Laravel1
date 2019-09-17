<?php

namespace App;

use Illuminate\Support\Facades\File;

class Helpers
{

    private $percent;

    /*  Static function to Calculate the percentage
     *
     *  @params $old The old number (Int)
     *  @params $new The new number (Int)
     *  @return Int
     */

    public static function getPercentage($old, $new)
    {
        $percentage = (($new - $old) / $old) * 100;
        return round($percentage, 0) . '%';
    }

    /*  Static function to check if the array is empty or not
     *
     *  @params $array The array
     *  @return array - status code 204 No content
     */

    public static function checkIsEmptyArray($array)
    {
        if (count($array->all()) < 0 || $array->isEmpty()) {
            return true;
        }
        return false;

    }

    /*  Static function to check if there is id exist escpecilly works with first() function
     *
     *  @params $array
     *  @return array - status code 404 No Found
     */

    public static function checkIsEmptyId($id)
    {
        if (is_null($id)) {
            return true;
        }
        return false;

    }

    /**
     * @return mixed
     *
     */
    public static function getIp()
    {
        $ip = $_SERVER['REMOTE_ADDR'];
        $query = @unserialize(file_get_contents('http://ip-api.com/php/' . $ip));
        $query['query'];
        if ($query && $query['status'] == 'success') {
            return $query;
        }
    }

    public static function checkUserPayment($user)
    {
        $getSiteInfo = Siteinfo::first()->payment_status;        
        if ($getSiteInfo) {
            return true;
        } else {                        

            if (is_null($user['period_end'])) {                
                abort(403, 'Unauthorized action.');                
            } else {                
                if ($user->period_end < date("Y-m-d")) {
                    abort(403, 'Unauthorized action.');
                }
                return true;
            }
        }


    }
}
