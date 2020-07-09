<?php

use Illuminate\Database\Seeder;

class FormationStepsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('formation_steps')->insert([
            'title' => 'Name Your California LLC',
            'description' => 'Your first consideration when choosing a name for your LLC is that it be unique from any other business in the state of California. The state also requires that the company name in no way be misleading to the public. You can quickly and easily check your company’s potential name options at the California Secretary of State website business name database. For a $10 fee through said website, you can also reserve a company na.',
            'img' => '',
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
        ]);
        DB::table('formation_steps')->insert([
            'title' => 'Appoint a Registered Agent in California',
            'description' => 'The state of California requires that any LLC have a registered agent for service of process. This means your LLC must have an entity that agrees to physically accept any legal papers on the company’s behalf should it be sued. This entity does not have to be an individual person but cannot be the LLC itself, though affiliation is allowed. The registered agent can be any resident of the state of California or a business entity authorized ..',
            'img' => '',
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
        ]);
    }
}
