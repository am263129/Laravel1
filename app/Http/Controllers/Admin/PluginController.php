<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use App\Plugin;
use Auth;

class PluginController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->admin = Auth::user()->authorizeRoles(['admin']);
            return $next($request);
        });
    }


    /**
     * Get all themes
     *
     * @return void
     */
    public function get()
    {
        $get = Plugin::where('type', '=', 'theme')->get();

        return response()->json($get);
    }

    /**
     * Add new theme
     *
     * @param Request $request
     * @return array
     */
    public function upload(Request $request)
    {
        $request->validate([
            'zip' => 'mimes:application/zip, application/octet-stream'
        ]);
        $zipper = new \Chumper\Zipper\Zipper;
        
        // Get theme folder
        // The theme name is the name of folder that files .vue is inside it.
        $listFile = $zipper->make($request->file('theme-zip'))->listFiles('/\.vue$/i');
        
        if (!isset($listFile[0])) {
            return response()->json(['message' => 'Error in theme files']);
        }
        $theme_name = explode("/", $listFile[0], 2);

        // Check if theme is already
        $check = Plugin::where('name', '=', $theme_name[0])->first();

        if (! is_null($check)) {
            return response()->json(['message' => 'The theme is already']);
        }

        // Extract folder
        $extract_asset = $zipper->make($request->file('theme-zip'))->folder('asset')->extractMatchingRegex(public_path('themes/'.strtolower($theme_name[0])), '/\.jpg$|\.svg$|\.png$|\.gif$|\.jpeg$|\.sass$|\.css$|\font$|\.js$|\.scss$|\.ttf$|\.woff$|\.woff2$|.eot$/i');
        $extract_template = $zipper->make($request->file('theme-zip'))->folder($theme_name[0])->extractMatchingRegex(storage_path('themes/'.strtolower($theme_name[0]).'/'.$theme_name[0]), '/\.jpg$|\.png$|\.gif$|\.jpeg$|\.vue$|\.js$/i');
        $extract_template = $zipper->make($request->file('theme-zip'))->folder('asset')->extractMatchingRegex(storage_path('themes/'.strtolower($theme_name[0]).'/asset'), '/\.jpg$|\.svg$|\.png$|\.gif$|\.jpeg$|\.sass$|\.css$|\.font$|\.js$|\.scss$|\.ttf$|\.woff$|\.woff2$|.eot$/i');

        $store = new Plugin();
        $store->name = strtolower($theme_name[0]);
        $store->type = 'theme';
        $store->thumbnail = '/themes/'.strtolower($theme_name[0]).'/thumbnail.png';
        $store->save();

        $zipper->close();

        return response()->json(['status' => 'success', $store]);
    }


    /**
     * Set theme
     *
     * @param Request $request
     * @return void
     */
    public function set(Request $request)
    {
        $request->validate([
            'id' => 'required|numeric'
        ]);

        // Check if theme is available

        $store = Plugin::find($request->input('id'));
        if (is_null($store)) {
            return response()->json(['message' => 'Error no id']);
        }


        // Check old theme available and inactive it

        $change = Plugin::where('status', 1)->first();
        if (! is_null($change)) {
            $change->status = 0;
            $change->save();
        } else {
            return response()->json(['message' => 'Error no id']);
        }

        // Copy folder theme to assets
        $delete_old = File::deleteDirectory(resource_path('assets/js/users/views/'.$change->name));
        
        if ($delete_old) {

        // Delete old theme in assets
            File::copyDirectory(storage_path('themes/'.$store->name.'/'.$store->name.'/'), resource_path('assets/js/users/views/'.$store->name));
        } else {
            return response()->json(['message' => 'Error to delete old folder theme']);
        }
        // Update
        $store->status = 1;
        $store->save();

        
        // Compiling asset
        $process = new Process('npm run prod');
        $process->run();

        // executes after the command finishes
        if (!$process->isSuccessful()) {
            $nvm = new Process('nvm run prod');
            $nvm->run();
            if (!$nvm->isSuccessful()) {
                return response()->json(['message' => 'Install Node.js with nvm or npm']);
            }
        }

       
        $get = Plugin::where('type', '=', 'theme')->get();

        return response()->json(['status' => 'success', $get]);
    }

    /**
     * Set theme
     *
     * @param [integer] $id
     * @return []
     */
    public function delete($id)
    {
        $store = Plugin::find($id);
        
        if (is_null($store)) {
            return response()->json(['message' => 'Error no id']);
        }

        if ($store->status === 1) {
            $current = Plugin::where('name', '=', 'default')->first();
            $current->status = 1;
            $current->save();
        }
        File::deleteDirectory(public_path('themes/'.$store->name));
        File::deleteDirectory(storage_path('themes/'.$store->name));
        $store->delete();
        return response()->json(['status' => 'success']);
    }
}
