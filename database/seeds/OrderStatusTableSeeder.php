<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderStatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\OrderStatus::firstOrNew([
            'title' => 'Order Placed',
            'desc' => 'Order has been submitted by customer',
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
        ]);
        \App\OrderStatus::firstOrNew([
            'title' => 'Documents Reviewed',
            'desc' => 'Customer submitted documents has been reviewed',
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
        ]);
        \App\OrderStatus::firstOrNew([
            'title' => 'Review Complete',
            'desc' => 'Accepted all customer submitted documents',
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
        ]);
    }
}
