<?php

use Illuminate\Database\Seeder;


class StudiesTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $studies = factory(App\Study::class)->states('fizzyDrinks')->create();
        $studies = factory(App\Study::class)->states('primarySchools')->create();
        $studies = factory(App\Study::class)->states('primarySchoolPupilEthnicity')->create();
        $studies = factory(App\Study::class)->states('primarySchoolTeacherDetails')->create();
    }
}
