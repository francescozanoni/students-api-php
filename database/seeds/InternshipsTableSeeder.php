<?php
declare(strict_types = 1);

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InternshipsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @throws Exception
     */
    public function run()
    {

        // Standard internship, with evaluation
        DB::table('internships')->insert([
            'student_id' => 1,
            'location_id' => 1,
            'sub_location_id' => 1,
            'start_date' => '2019-01-10',
            'end_date' => '2019-01-24',
            'hour_amount' => 123,
            'other_amount' => 5,
            'is_optional' => false,
            'is_interrupted' => false,
            'created_at' => '2019-01-09 02:00:00',
            'updated_at' => '2019-01-09 02:00:00',
        ]);

        // Optional interrupted internship, with interruption report.
        DB::table('internships')->insert([
            'student_id' => 1,
            'location_id' => 1,
            'sub_location_id' => 1,
            'start_date' => '2019-01-26',
            'end_date' => '2019-01-31',
            'hour_amount' => 34,
            'other_amount' => 0,
            'is_optional' => true,
            'is_interrupted' => true,
            'created_at' => '2019-01-25 02:00:00',
            'updated_at' => '2019-01-25 02:00:00',
        ]);

        // Interrupted internship.
        DB::table('internships')->insert([
            'student_id' => 4,
            'location_id' => 1,
            'sub_location_id' => 1,
            'start_date' => '2019-01-26',
            'end_date' => '2019-01-31',
            'hour_amount' => 34,
            'other_amount' => 0,
            'is_optional' => false,
            'is_interrupted' => true,
            'created_at' => '2019-01-25 02:00:00',
            'updated_at' => '2019-01-28 02:00:00',
        ]);

        // Interrupted internship, in the future (very unlikely :-) ).
        DB::table('internships')->insert([
            'student_id' => 4,
            'location_id' => 1,
            'sub_location_id' => 1,
            // 10 days in the future.
            'start_date' => (new DateTime())->add(new DateInterval('P10D'))->format('Y-m-d'),
            // 20 days in the future.
            'end_date' => (new DateTime())->add(new DateInterval('P20D'))->format('Y-m-d'),
            'hour_amount' => 0,
            'other_amount' => 0,
            'is_optional' => false,
            'is_interrupted' => true,
            'created_at' => '2019-01-25 02:00:00',
            'updated_at' => '2019-01-28 02:00:00',
        ]);

    }
}
