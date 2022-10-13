<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cities = collect(json_decode(file_get_contents(database_path('seeders/data/cities.json')), true));
        $total_cities = count($cities);

        $current = 0;
        foreach ($cities->chunk(5000) as $i => $chunk) {
            $current += count($chunk);
            $this->command->info(($i + 1) . ': Importing ' . $current . '/' . $total_cities . ' cities...');
            DB::table('cities')->insert($chunk->toArray());

        }
    }
}
