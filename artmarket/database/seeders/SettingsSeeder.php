<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $settings = json_decode(file_get_contents(database_path('seeders/data/settings.json')), true);
        $this->command->info('Importing ' . count($settings) . ' settings...');
        DB::table('settings')->upsert($settings, ['name']);
    }
}
