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
        \App\Feature::firstOrNew([
            'name' => 'Standard Filing Service',
            'description' => '',
            'is_active' => true,
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
        ]);
        \App\Feature::firstOrNew([
            'name' => 'Registered Agent',
            'description' => '',
            'is_active' => true,
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
        ]);
        \App\Feature::firstOrNew([
            'name' => 'Operating Agreement',
            'description' => '',
            'is_active' => true,
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
        ]);
        \App\Feature::firstOrNew([
            'name' => 'Expedite Filing Service',
            'description' => '',
            'is_active' => true,
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
        ]);
        \App\Feature::firstOrNew([
            'name' => 'Employer ID Number (EIN)',
            'description' => '',
            'is_active' => true,
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
        ]);
    }
}
