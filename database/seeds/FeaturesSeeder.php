<?php

use Illuminate\Database\Seeder;

class FeaturesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('features')->insert([
            'name' => 'Standard Filing Service',
            'description' => '',
            'is_active' => true,
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
        ]);
        DB::table('features')->insert([
            'name' => 'Registered Agent',
            'description' => '',
            'is_active' => true,
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
        ]);
        DB::table('features')->insert([
            'name' => 'Operating Agreement',
            'description' => '',
            'is_active' => true,
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
        ]);
        DB::table('features')->insert([
            'name' => 'Expedite Filing Service',
            'description' => '',
            'is_active' => true,
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
        ]);
        DB::table('features')->insert([
            'name' => 'Employer ID Number (EIN)',
            'description' => '',
            'is_active' => true,
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
        ]);
    }
}
