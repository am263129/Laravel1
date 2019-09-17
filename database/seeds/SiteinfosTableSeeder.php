<?php

use Illuminate\Database\Seeder;
use App\Siteinfo;
class SiteinfosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $siteinfo = new Siteinfo();
        $siteinfo->social_facebook = 'https://facaebook.com';
        $siteinfo->social_twitter = 'https://twitter.com';
        $siteinfo->social_instagram = 'https://instagram.com';
        $siteinfo->privacy = 'Here is privacy';
        $siteinfo->terms = 'Here is terms';
        $siteinfo->about = 'Here is about';
        $siteinfo->payment_status = 1;
        $siteinfo->save();
    }
}
