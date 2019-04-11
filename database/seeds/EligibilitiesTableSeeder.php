<?php
declare(strict_types = 1);

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EligibilitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @throws Exception
     */
    public function run()
    {
        DB::table('eligibilities')->insert([
            'id' => 1,
            'student_id' => 1,
            'start_date' => '2019-01-01',
            'end_date' => '2019-12-01',
            'notes' => 'Zero eligibility notes',
            'is_eligible' => false,
        ]);
        DB::table('eligibilities')->insert([
            'id' => 2,
            'student_id' => 1,
            'start_date' => '2019-01-01',
            'end_date' => '2019-12-01',
            'notes' => 'First eligibility notes',
            'is_eligible' => true,
        ]);
        DB::table('eligibilities')->insert([
            'id' => 3,
            'student_id' => 2,
            'start_date' => '2019-01-01',
            'end_date' => '2019-12-01',
            'is_eligible' => false,
        ]);
        DB::table('eligibilities')->delete(1);
    }
}
