<?php
declare(strict_types = 1);

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InterruptionReportsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('interruption_reports')->insert([
            'internship_id' => 2,
            'notes' => 'First interruption report notes',
            'created_at' => '2019-01-30 02:00:00',
            'updated_at' => '2019-01-30 02:00:00',
            'deleted_at' => '2019-01-30 03:00:00',
        ]);
        DB::table('interruption_reports')->insert([
            'internship_id' => 2,
            'notes' => 'Second interruption report notes',
            'created_at' => '2019-01-31 02:00:00',
            'updated_at' => '2019-01-31 02:00:00',
        ]);
    }
}
