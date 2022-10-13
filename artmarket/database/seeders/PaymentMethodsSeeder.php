<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentMethodsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $paymentMethods = json_decode(file_get_contents(database_path('seeders/data/payment_methods.json')), true);
        $this->command->info('Importing ' . count($paymentMethods) . ' payment methods...');

        DB::table('payment_methods')->insert($paymentMethods);
    }
}
