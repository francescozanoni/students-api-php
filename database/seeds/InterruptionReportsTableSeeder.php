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
            'id' => 1,
            'internship_id' => 2,
            'notes' => 'First interruption report notes',
        ]);
        DB::table('interruption_reports')->insert([
            'id' => 2,
            'internship_id' => 2,
            'notes' => 'Second interruption report notes',
        ]);
        DB::table('interruption_reports')->delete(1);
    }
}
