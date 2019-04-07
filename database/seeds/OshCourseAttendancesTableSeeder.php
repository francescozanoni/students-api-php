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
            'start_date' => '2018-12-08',
            'end_date' => '2019-12-07',
            'student_id' => 1,
            'created_at' => '2018-12-10 01:00:00',
            'updated_at' => '2018-12-10 01:00:00',
        ]);
        DB::table('osh_course_attendances')->insert([
            'start_date' => '2018-12-08',
            'end_date' => '2019-12-07',
            'student_id' => 4,
            'created_at' => '2018-12-10 01:00:00',
            'updated_at' => '2018-12-10 01:00:00',
            'deleted_at' => '2018-12-11 04:00:00',
        ]);
        DB::table('osh_course_attendances')->insert([
            'start_date' => '2018-12-14',
            'end_date' => '2019-12-13',
            'student_id' => 4,
            'created_at' => '2018-12-15 01:00:00',
            'updated_at' => '2018-12-15 01:00:00',
        ]);
    }
}