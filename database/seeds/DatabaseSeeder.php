<?php
declare(strict_types = 1);

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $this->call('CountriesTableSeeder');
        $this->call('LocationsTableSeeder');
        $this->call('SubLocationsTableSeeder');
        $this->call('StudentsTableSeeder');
        $this->call('AnnotationsTableSeeder');
        $this->call('InternshipsTableSeeder');
        $this->call('EducationalActivityAttendancesTableSeeder');
        $this->call('EvaluationsTableSeeder');
        $this->call('InterruptionReportsTableSeeder');
        $this->call('EligibilitesTableSeeder');
        $this->call('OshCourseAttendancesTableSeeder');
    }
}
