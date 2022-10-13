<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $countries = json_decode(file_get_contents(database_path('seeders/data/countries.json')), true);
        $this->command->info('Importing ' . count($countries) . ' countries...');
        DB::table('countries')->insert($countries);
    }
}
