<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Auth;
use DB;

class CheckAdminPermission extends Controller
{
    public function checkPermission()
    {
        $get = DB::table('admins')
            ->selectRaw('admins.id,admins.name,admins.email,admins.image,admins.created_at,admins.updated_at,admin_role.role_id')
            ->join('admin_role', 'admin_role.admin_id', '=', 'admins.id')
            ->where('admins.id', Auth::id())
            ->first();

        return response()->json([
            'status' => 'success',
            'data' => $get
        ]);
    }
}
