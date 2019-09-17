<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Categorie;
use Illuminate\Support\Facades\Storage;

class CategoriesController extends Controller
{

    /**
     * Get all categories by KIND
     *
     * @param $kind
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllCategories()
    {

        $getCategories = Categorie::get();

        // Check if there is no castmig
        if ($getCategories->isEmpty()) {
            $getCategories = null;
        }

        return response()->json(
            [
                'status' => 'success',
                'data' => [
                    'categories' => $getCategories,
                ]],
            200
        );

    }

    /**
     * Get all categories by KIND
     *
     * @param $kind
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCategoryBySort($kind)
    {

        $getCategories = Categorie::where('kind', $kind)->get();

        // Check if there is no cast
        if ($getCategories->isEmpty()) {
            $getCategories = null;
        }

        return response()->json(
            [
                'status' => 'success',
                'data' => [
                    'categories' => $getCategories,
                ]],
            200
        );

    }

    /**
     * Get category info
     *
     * @param $kind
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCategory($id)
    {

        $getCategory = Categorie::find($id);


        if (is_null($getCategory)) {
            return response()->json(['status' => 'failed', 'message' => 'Error, input failed'], 422);
        }


        return response()->json(
            [
                'status' => 'success',
                'data' => $getCategory
            ],
            200
        );

    }

    /**
     * Create new category
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createCategory(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:100',
            'kind' => 'required|string|max:15',
            'image' => 'required|mimes:jpeg,png'
        ]);

        $kindList = ['movie', 'videosong', 'series', 'kids', 'live'];

        if (!in_array($request->kind, $kindList)) {
            return response()->json(['status' => 'failed', 'message' => 'Error, The data not match our requirements'], 422);
        }

        // Upload Image
        $backdropName = str_random(20) . '.jpg';
        $backdropEncode = \Image::make($request->file('image'))->encode('jpg');
        $upload = Storage::disk('public')->put('categories/' . $backdropName, $backdropEncode->__toString());

        if ($upload) {
            $create = new Categorie();
            $create->name = $request->name;
            $create->kind = $request->kind;
            $create->image = '/storage/categories/' . $backdropName;
            $create->save();
            return response()->json(['status' => 'success', 'message' => 'Successful create ' . $create->name . ' category'], 200);

        }
        return response()->json(['status' => 'failed', 'message' => 'Failed to upload image'], 422);

    }


    /**
     * Update Category
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateCategory(Request $request, $id)
    {
        $request->validate([
            'name' => 'string|alpha|max:50|nullable',
            'kind' => 'string|alpha|max:15|nullable',
            'image' => 'mimes:jpeg,png|nullable'
        ]);

        $kindList = ['movie', 'videosong','series', 'kids', 'live'];

        if (!in_array($request->input('kind'), $kindList)) {
            return response()->json(['status' => 'failed', 'message' => 'Error, The data not match our requirements'], 422);
        }

        // Create new object
        $create = Categorie::find($id);


        if (is_null($create)) {
            return response()->json(['status' => 'failed', 'message' => 'Error, input failed'], 422);
        }

        // Upload image
        if ($request->file('image')) {
            // Upload Image
            $backdropName = str_random(20) . '.jpg';
            $backdropEncode = \Image::make($request->image)->encode('jpg');
            $upload = Storage::disk('public')->put('categories/' . $backdropName, $backdropEncode->__toString());

            if ($upload) {
                $create->image = '/storage/categories/' . $backdropName;
            } else {
                return response()->json(['status' => 'failed', 'message' => 'Failed to upload'], 422);
            }
        }


        $create->name = $request->input('name');
        $create->kind = $request->input('kind');
        $create->save();

        return response()->json(['status' => 'success', 'message' => 'Successful create ' . $create->name . ' category'], 200);
    }


    /**
     * Delete category
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     *
     */

    public function deleteCategory($id)
    {

        $delete = Categorie::find($id)->delete();

        if ($delete) {
            return response()->json(['status' => 'success', 'message' => 'Successful delete'], 200);
        }

        return response()->json(['status' => 'failed', 'message' => 'Failed to delete'], 422);
    }

    public function activeCategory(Request $request)
    {

        $request->validate([
            'id' => 'required|integer',
        ]);

        $update = Categorie::find($request->input('id'));

        if (is_null($update)) {
            return response()->json(['status' => 'failed', 'message' => 'Error, input failed'], 422);
        }

        if ($request->input('type') === 'active') {

            if ($update->active) {
                $update->active = false;
                $update->save();
                return response()->json(['status' => 'success', 'active' => false, 'message' => 'Successful inactive ' . $update->name . ' category'], 200);
            }

            $update->active = true;
            $update->save();

            return response()->json(['status' => 'success', 'active' => true, 'message' => 'Successful active ' . $update->name . ' category'], 200);

        } else if ($request->type === 'subscription') {

            if ($update->show_in_sub) {
                $update->show_in_sub = false;
                $update->save();
                return response()->json(['status' => 'success', 'subscription' => false, 'message' => 'Successful remove  ' . $update->name . ' category to subscription'], 200);
            }

            $update->show_in_sub = true;
            $update->save();
            return response()->json(['status' => 'success', 'subscription' => true,'message' => 'Successful add ' . $update->name . ' category to subscription'], 200);

        }

        return response()->json(['status' => 'failed', 'message' => 'Failed to active/add'], 422);
    }


}
