<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Plugin;
use Schema;
use DB;

class ThemeServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {

        // Test database connection
        try {
            if (DB::connection()->getPdo()) {
                if (Schema::hasTable('plugins')) {
                    // Get current theme
                    $get = Plugin::where('status', 1)->where('type', 'theme')->first();
                    if (!is_null($get)) {
                        config(['plugin.theme' => $get->name]);
                    } else {
                        config(['plugin.theme' => 'default']);
                    }
                }
            }
        } catch (\Exception $e) {
            // die("Could not connect to the database.  Please check your configuration.");
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
