<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RoleTableSeeder::class);
        $this->call(AdminsTableSeeder::class);
        $this->call(PluginsTableSeeder::class);
        $this->call(TmdbTableSeeder::class);
        $this->call(TranscodersTableSeeder::class);
        $this->call(SiteinfosTableSeeder::class);
        $this->call(AdElementsTableSeeder::class);
    }
}
