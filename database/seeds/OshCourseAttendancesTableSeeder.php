<?php
declare(strict_types = 1);

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OshCourseAttendancesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('osh_course_attendances')->insert([
            'id' => 1,
            'start_date' => '2018-12-08',
            'end_date' => '2019-12-07',
            'student_id' => 1,
        ]);
        DB::table('osh_course_attendances')->insert([
            'id' => 2,
            'start_date' => '2018-12-08',
            'end_date' => '2019-12-07',
            'student_id' => 4,
        ]);
        DB::table('osh_course_attendances')->insert([
            'id' => 3,
            'start_date' => '2018-12-14',
            'end_date' => '2019-12-13',
            'student_id' => 4,
        ]);
        DB::table('osh_course_attendances')->delete(2);
    }
}
