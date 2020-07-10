<?php

use Illuminate\Database\Seeder;

class EntityTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('entity_types')->insert([
            'name' => 'LLC',
            'is_active' => true,
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
        ]);
        DB::table('entity_types')->insert([
            'name' => 'S - Corp',
            'is_active' => true,
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
        ]);
        DB::table('entity_types')->insert([
            'name' => 'C - Corp',
            'is_active' => true,
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
        ]);
        DB::table('entity_types')->insert([
            'name' => 'Non - Profit',
            'is_active' => true,
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
        ]);
    }
}
