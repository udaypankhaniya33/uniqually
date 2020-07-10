<?php

use Illuminate\Database\Seeder;

class InputFieldsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\InputField::firstOrNew([
            'type' => 'text',
            'limit' => 10,
            'is_required' => true,
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
        ]);
        \App\InputField::firstOrNew([
            'type' => 'radio',
            'limit' => 20,
            'is_required' => true,
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
        ]);
        \App\InputField::firstOrNew([
            'type' => 'text area',
            'limit' => 50,
            'is_required' => true,
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
        ]);
    }
}
