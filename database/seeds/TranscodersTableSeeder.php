<?php

use Illuminate\Database\Seeder;
use App\Transcoder;
class TranscodersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $create = new Transcoder();
        $create->watermark_url = NULL;
        $create->watermark_position = NULL;
        $create->segment_duration = 10;
        $create->save();
    }
}
