<?php

use Illuminate\Database\Seeder;
use App\Tmdb;

class TmdbTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $theme = new Tmdb();
        $theme->api = 'xxxxxxxxxxxxxxxxxxxxxxxxx';
        $theme->language = 'xxxxxxxxxxxxxxxxxxxxxxxxx';
        $theme->save();
    }
}
