<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $states = json_decode(file_get_contents(database_path('seeders/data/states.json')), true);
        $this->command->info('Importing ' . count($states) . ' states...');
        DB::table('states')->insert($states);
    }
}
