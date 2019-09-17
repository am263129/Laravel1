<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{

   /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->admin = Auth::user()->authorizeRoles(['admin','manager']);
            return $next($request);
        });
    }
    
    /**
     * Undocumented function
     *
     * @return void
     */
    public function getUser()
    {
        return response()->json([Auth::user()]);
    }

    // Upload avatar image
    public function updateImage(Request $request)
    {
        $this->validate($request, [
            'image' => 'required|mimes:jpeg,jpg,png|max:1000',
        ]);
        // upload to local storage
        $image = \Image::make($request->image)->widen(100)->encode('jpg');
        $name  = str_random(10).'.jpg';
        $upload = Storage::disk('public')->put('users_image/' .$name, $image->__toString());
        if ($upload) {
            $user = Auth::user();
            $user->image =  '/storage/users_image/' . $name;
            $user->save();
            return response()->json(['status' => 'success', 'image' => $user->image]);
        }
    }
    
    /**
     * Update details of  users
     *
     * @param Request $request
     * @return void
     */
    public function updateDetails(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:50|regex:/^[a-z0-9 .\-]+$/i',
            'email' => 'required|email|max:150',
        ]);

        $user = Auth::user();
        $user->name  = $request->input('name');
        $user->email  = $request->input('email');
        $user->save();
        return response()->json(['status' => 'success']);
    }
    
    /**
     * Update password
     *
     * @param Request $request
     * @return void
     */
    public function passwordUpdate(Request $request)
    {
        $this->validate($request, [
            'password' => 'required|confirmed|min:8'
        ]);

        $user = Auth::user();
        $user->password  = Hash::make($request->password);
        $user->save();
        Auth::logout();
        return response()->json(['status' => 'success']);
    }
}
