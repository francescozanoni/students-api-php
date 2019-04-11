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
            'id' => 1,
            'educational_activity' => 'First educational activity',
            'start_date' => '2019-01-08',
            'end_date' => '2019-01-09',
            'credits' => 1.2,
            'student_id' => 1,
        ]);
        DB::table('educational_activity_attendances')->insert([
            'id' => 2,
            'educational_activity' => 'Second educational activity',
            'start_date' => '2019-01-03',
            'end_date' => null,
            'credits' => 1.0,
            'student_id' => 1,
        ]);
        DB::table('educational_activity_attendances')->delete(2);
    }
}
