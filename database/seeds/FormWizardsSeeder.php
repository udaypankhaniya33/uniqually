<?php

use Illuminate\Database\Seeder;

class FormWizardsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\FormWizard::firstOrNew([
            'name' => 'sample',
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
        ]);
        \App\FormWizard::firstOrNew([
            'name' => 'quick',
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
        ]);
    }
}
