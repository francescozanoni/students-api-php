<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('stages')->insert([
            'student_id' => 1,
            'location_id' => 1,
            'sub_location_id' => 1,
            'start_date' => '2018-12-10',
            'end_date' => '2018-12-24',
            'hour_amount' => 123,
            'other_amount' => 5,
            'is_optional' => false,
            'is_interrupted' => false,
            'created_at' => '2019-01-01 02:00:00',
            'updated_at' => '2019-01-01 02:00:00',
        ]);
    }
}
