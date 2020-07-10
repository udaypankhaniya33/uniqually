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
        DB::table('input_fields')->insert([
            'type' => 'text',
            'limit' => 10,
            'is_required' => true,
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
        ]);
        DB::table('input_fields')->insert([
            'type' => 'radio',
            'limit' => 20,
            'is_required' => true,
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
        ]);
        DB::table('input_fields')->insert([
            'type' => 'text area',
            'limit' => 50,
            'is_required' => true,
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
        ]);
    }
}
