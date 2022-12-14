<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database. 
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        $this->call(AppSettingTableSeeder::class);
        $this->call(OrderStatusTableSeeder::class);
        $this->call(EntityTypesSeeder::class);
        $this->call(LocationSeeder::class);
        $this->call(FeaturesSeeder::class);
        $this->call(AddonsSeeder::class);
        $this->call(FormationStepsSeeder::class);
        $this->call(FormsSeeder::class);
        $this->call(InputFieldsSeeder::class);
        $this->call(FormWizardsSeeder::class);
    }
}
