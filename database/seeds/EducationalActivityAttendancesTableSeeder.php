<?php
declare(strict_types = 1);

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EducationalActivityAttendancesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('educational_activity_attendances')->insert([
            'educational_activity' => 'First educational activity',
            'start_date' => '2019-01-08',
            'end_date' => '2019-01-09',
            'credits' => 1.2,
            'student_id' => 1,
            'created_at' => '2019-01-10 01:00:00',
            'updated_at' => '2019-01-10 01:00:00',
        ]);
        DB::table('educational_activity_attendances')->insert([
            'educational_activity' => 'Second educational activity',
            'start_date' => '2019-01-03',
            'end_date' => null,
            'credits' => 1.0,
            'student_id' => 1,
            'created_at' => '2019-01-05 01:00:00',
            'updated_at' => '2019-01-05 01:00:00',
            'deleted_at' => '2019-01-06 01:00:00',
        ]);
    }
}
