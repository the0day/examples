<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GlossarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->importFromJson("glossary_option_groups");
        $this->importFromJson("glossary_offer_types");
        $this->importFromJson("glossary_option_groups_offer_types");
        $this->importFromJson("glossary_categories");
        $this->importFromJson("glossary_offer_purposes");
        $this->importFromJson("glossary_options");
    }

    private function importFromJson(string $table)
    {
        $filename = $table;
        $data = json_decode(file_get_contents(database_path('seeders/data/' . $filename . '.json')), true);
        $this->command->info('Importing ' . count($data) . ' ' . $filename . '...');
        DB::table($table)->insert($data);
    }
}
