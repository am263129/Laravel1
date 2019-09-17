<?php

namespace App\Http\Controllers\Admin;

use App\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Auth;
use App\Admin;

class AdminsUsersController extends Controller
{

    /**
     * Only admin can access
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->admin = Auth::user()->authorizeRoles(['admin']);
            return $next($request);
        });
    }

    /**
     * Get all admin users
     *
     * @return void
     */
    public function getAllAdmins()
    {
        $getAdmins = DB::table('admins')
            ->selectRaw('admins.id,admins.name,admins.email,admins.image,admins.created_at,admins.updated_at,admin_role.role_id')
            ->join('admin_role', 'admin_role.admin_id', '=', 'admins.id')
            ->get();


        // Check if empty result
        if ($getAdmins->isEmpty()) {
            $getAdmins = null;
        }
        return response()->json([
                'status' => 'success',
                'data' => [
                    'admins' => $getAdmins
                ],
            ]);
    }

    /**
     * Delete admin user
     *
     * @param [uuid] $id
     * @return void
     */
    public function delete($id)
    {
        $getAdmin = Admin::find($id);

        if (is_null($getAdmin)) {
            return response()->json(['status' => 'failed', 'message' => 'There is no id'], 422);
        }

        $getAdmin->delete();
        return response()->json(['status' => 'success', 'message' => 'Successful delete '. $getAdmin->name]);
    }



    /**
     * Get admin details
     *
     * @param [uuid] $id
     * @return void
     */
    public function getAdminDetails($id)
    {
        $getAdmin = DB::table('admins')
            ->selectRaw('admins.id,admins.name,admins.email,admins.image,admins.created_at,admins.updated_at,admin_role.role_id')
            ->join('admin_role', 'admin_role.admin_id', '=', 'admins.id')
            ->where('admins.id', $id)
            ->first();


        if (is_null($getAdmin)) {
            return response()->json(['status' => 'failed', 'message' => 'There is no id'], 422);
        }

        return response()->json(
            ['status' => 'success',
             'data' => [
                'details' => $getAdmin
             ]]
        );
    }


    /**
     * Update image
     *
     * @param Request $request
     * @return void
     */
    public function updateImage(Request $request)
    {
        $request->validate([
            'image' => 'required | mimes:jpeg,jpg,png | max:1000',
            'id' => 'required|uuid'
        ]);
        // upload to local storage
        $image = \Image::make($request->image)->widen(100)->encode('jpg');
        $name  = str_random().'.jpg';
        $upload = Storage::disk('public')->put('users_image/' .$name, $image->__toString());
        if ($upload) {
            $user = Admin::find($request->id);
            $user->image = $name;
            $user->save();
            return response()->json(['status' => 'success', 'image' => $user->image]);
        }
    }

    /**
     * Update details
     *
     * @param Request $request
     * @return void
     */
    public function updateDetails(Request $request)
    {
        $request->validate([
            'id' => 'required|uuid',
            'name' => 'required|max:40|regex:/^[a-z0-9 .\-]+$/i',
            'email' => 'required|email| max:150',
            'password' => 'nullable|min:8',
            'permission' => 'required|numeric|max:2'
        ]);

        $store = Admin::find($request->input('id'));

        if (is_null($store)) {
            return response()->json(['status' => 'failed', 'message' => 'There is no id'], 422);
        }

        // if input not empty then save it
        if ($request->has('name')) {
            $store->name = $request->input('name');
        }
        if ($request->has('email')) {
            $store->email = $request->input('email');
        }
        if ($request->has('password') && $request->input('password') !== null) {
            $store->password = Hash::make($request->input('password'));
        }
        if ($request->has('permission')) {
            DB::table('admin_role')->where('admin_id', $request->input('id'))->update(['role_id' => $request->input('permission')]);
        }
        $store->save();
        return response()->json(['status' => 'success', 'message' => 'Successful update '. $store->name]);
    }

    /**
     * Create new admin admin
     *
     * @param Request $request
     * @return void
     */
    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|max:40|regex:/^[a-z0-9 .\-]+$/i',
            'email' => 'required|email|max:150',
            'password' => 'required|confirmed|min:8',
            'permission' => 'required|numeric|max:2'
        ]);

        $check = Admin::where('email', $request->input('email'))->first();
        if (! is_null($check)) {
            return response()->json(['status' => 'failed', 'message' => 'The email already exists'], 422);
        }

        $user = new Admin;
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->save();

        // Insert permission of user;
        DB::table('admin_role')->insert(
            ['role_id' => $request->input('permission'), 'admin_id' => $user->id]
        );
        return response()->json(['status' => 'success', 'message' => 'Successful create '. $user->name]);
    }
}
