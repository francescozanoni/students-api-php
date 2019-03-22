<?php
declare(strict_types = 1);

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EligibilitesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @throws Exception
     */
    public function run()
    {

        DB::table('eligibilities')->insert([
            'student_id' => 1,
            'start_date' => '2019-01-01',
            'end_date' => '2019-12-01',
            'notes' => 'Zero eligibility notes',
            'is_eligible' => false,
            'created_at' => '2019-01-02 01:00:00',
            'updated_at' => '2019-01-02 01:00:00',
            'deleted_at' => '2019-01-02 01:10:00',
        ]);

        DB::table('eligibilities')->insert([
            'student_id' => 1,
            'start_date' => '2019-01-01',
            'end_date' => '2019-12-01',
            'notes' => 'First eligibility notes',
            'is_eligible' => true,
            'created_at' => '2019-01-02 02:00:00',
            'updated_at' => '2019-01-02 02:00:00',
        ]);

        DB::table('eligibilities')->insert([
            'student_id' => 2,
            'start_date' => '2019-01-01',
            'end_date' => '2019-12-01',
            'is_eligible' => false,
            'created_at' => '2019-01-02 03:00:00',
            'updated_at' => '2019-01-02 03:00:00',
        ]);

    }
}
