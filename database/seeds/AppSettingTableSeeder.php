<?php

use Illuminate\Database\Seeder;

class AppSettingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // PayPal After submission setting
        \App\AppSetting::firstOrCreate([
            'key' => 'is_paypal_after_submission',
            'value' => 'true'
        ]);
    }
}
