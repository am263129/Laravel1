<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Casts;
use Auth;

class ActorsController extends Controller
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
     * Get all casts of movies and series..
     *
     * @return [array]
     */
    public function getAllActors()
    {
        $getActors = Casts::selectRaw('c_id AS id, credit_id, c_name AS name, c_image as image, c_cloud as cloud')->paginate(15);

        // Check if there is no result
        if (empty($getActors->all())) {
            $getActors = null;
        }

        return response()->json(
            ['data' => [
                'actors' => $getActors,
               ]],
            200
        );
    }

    /**
     * Update actor details, [image,name]
     *
     * @param Request $request
     * @param [type] $id
     * @return void
     */
    public function update(Request $request)
    {
        $request->validate([
            'id'    => 'required|numeric',
            'image' => 'nullable|mimes:jpg,jpeg,png|max:4096',
            'name'  => 'required|max:40|regex:/^[a-z0-9 .\-]+$/i'
        ]);

        $update = Casts::find($request->id);

        if (is_null($update)) {
            return response()->json(['status' => 'failed', 'message' => 'There is no id found'], 422);
        }

        if ($request->has('image') && $request->image !== 'undefined') {

            // Upload the new iamge
            $new_name = str_random(20).'.jpg';
            $convert = \Image::make($request->image)->widen(200)->encode('jpg');
            Storage::disk('public')->put('casts/' . $new_name, $convert->__toString());

            // Save in database
            $update->c_image = '/storage/casts/'.  $new_name;
            $update->save();
            return response()->json(['status' => 'success', 'message' => 'successful update '. $request->name ,'image' => $update->c_image, 'name' => $update->c_name]);
        } else {
            $update->c_name = $request->name;
            $update->save();
            return response()->json(['status' => 'success', 'message' => 'successful update '. $request->name ,'image' => $update->c_image, 'name' => $update->c_name]);
        }
    }

    /**
     * Create new actor
     *
     * @param Request $request
     * @return void
     */
    public function create(Request $request)
    {
        $request->validate([
          'photo' => 'required|mimes:jpg,jpeg,png',
          'name'  => 'required|max:40|regex:/^[a-z0-9 .\-]+$/i'
        ]);

        $store = new Casts();
        $store->c_name = $request->name;
        $store->c_cloud = 'local';
        $store->credit_id = str_random(20);

        // Upload the new iamge
        $new_name = str_random(20).'.jpg';
        $convert = \Image::make($request->photo)->widen(200)->encode('jpg');
        Storage::disk('public')->put('casts/'. $new_name, $convert->__toString());

        // Save in database
        $store->c_image = '/storage/casts/'.  $new_name;
        $store->save();

        return response()->json(['status' => 'success', 'message' => 'Successful create '. $store->c_name, 'actor' => ['id' => $store->c_id, 'name' => $store->c_name, 'image' => $store->c_image, 'cloud' => $store->c_cloud]]);
    }

    /**
     * Delete actor
     *
     * @param [type] $id
     * @return void
     */
    public function delete($id)
    {
        $delete = Casts::find($id);
        if (is_null($delete)) {
            return response()->json(['status' => 'failed'], 404);
        } else {
            $delete->delete();
            return response()->json(['status' => 'success', 'message' => 'Successful delete '. $delete->c_name]);
        }
    }


    public function search(Request $request) {

        $request->validate([
            'query' => 'required|string|max:25'
        ]);

        $getActors = Casts::selectRaw('c_id AS id, credit_id, c_name AS name, c_image as image,  c_cloud as cloud')->where('c_name', 'like', $request->input('query'). '%')->limit(10)->get();

        // Check if there is no result
        if (count($getActors) === 0) {
            $getActors = null;
        }

        return response()->json(
            ['data' => [
                'actors' => $getActors,
                ]],
            200
        );
    }
}
