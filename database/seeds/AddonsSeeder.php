<?php

use Illuminate\Database\Seeder;

class AddonsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('addons')->insert([
            'name' => 'Prepare and File LLC',
            'is_active' => true,
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
        ]);
        DB::table('addons')->insert([
            'name' => 'Name Availability Search',
            'is_active' => true,
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
        ]);
        DB::table('addons')->insert([
            'name' => 'Phone and Email Support',
            'is_active' => true,
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
        ]);
        DB::table('addons')->insert([
            'name' => 'Online Document Access',
            'is_active' => true,
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
        ]);
        DB::table('addons')->insert([
            'name' => 'Special Credit Card Offer',
            'is_active' => true,
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
        ]);
        DB::table('addons')->insert([
            'name' => 'Statement of Organizer',
            'is_active' => true,
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
        ]);
    }
}
