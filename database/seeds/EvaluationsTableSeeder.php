<?php
declare(strict_types = 1);

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EvaluationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('evaluations')->insert([
            'stage_id' => 1,
            'clinical_tutor_id' => 456,
            'notes' => 'First evaluation notes',
            'created_at' => '2019-01-25 02:00:00',
            'updated_at' => '2019-01-25 02:00:00',
        ]);
        DB::table('evaluations')->insert([
            'stage_id' => 1,
            'clinical_tutor_id' => 789,
            'notes' => 'Second evaluation notes',
            'created_at' => '2019-01-26 02:00:00',
            'updated_at' => '2019-01-26 02:00:00',
            'deleted_at' => '2019-01-26 03:00:00',
        ]);
    }
}