<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LanguagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $languages = json_decode(file_get_contents(database_path('seeders/data/languages.json')), true);
        $this->command->info('Importing ' . count($languages) . ' languages...');
        DB::table('languages')->insert($languages);
    }
}
