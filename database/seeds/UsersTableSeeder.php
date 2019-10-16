<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Default Admin',
            'email' => 'admin@vmanagetax.com',
            'password' => bcrypt('adminpass'),
            'type' => config('constances.user_types')['ADMIN'],
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
        ]);
        DB::table('users')->insert([
            'name' => 'Default Customer',
            'email' => 'customer@vmanagetax.com',
            'password' => bcrypt('customerpass'),
            'type' => config('constances.user_types')['CUSTOMER'],
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
        ]);
        DB::table('users')->insert([
            'name' => 'Default Expert',
            'email' => 'expert@vmanagetax.com',
            'password' => bcrypt('expertpass'),
            'type' => config('constances.user_types')['EXPERT'],
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
        ]);
        DB::table('users')->insert([
            'name' => 'Default OM',
            'email' => 'om@vmanagetax.com',
            'password' => bcrypt('ompass'),
            'type' => config('constances.user_types')['OM'],
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
        ]);
    }
}
