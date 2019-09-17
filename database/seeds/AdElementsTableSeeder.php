<?php

use Illuminate\Database\Seeder;
use App\AdElement;

class AdElementsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AdElement::create([
            'place' => 'tv',
            'data_ad_client' => env('AD_SENSE_CLIENT_ID', ''),
            'data_ad_slot' => '2433174843'
        ]);
        AdElement::create([
            'place' => 'movie',
            'data_ad_client' => env('AD_SENSE_CLIENT_ID', ''),
            'data_ad_slot' => '2433174843'
        ]);
        AdElement::create([
            'place' => 'series',
            'data_ad_client' => env('AD_SENSE_CLIENT_ID', ''),
            'data_ad_slot' => '2433174843'
        ]);
        AdElement::create([
            'place' => 'video',
            'data_ad_client' => env('AD_SENSE_CLIENT_ID', ''),
            'data_ad_slot' => '2433174843'
        ]);
    }
}
