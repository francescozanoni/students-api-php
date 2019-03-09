<?php
declare(strict_types = 1);

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {

        // Standard stage, with evaluation
        DB::table('stages')->insert([
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

        // Optional interrupted stage, with interruption report.
        DB::table('stages')->insert([
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

        // Interrupted stage.
        DB::table('stages')->insert([
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

    }
}
