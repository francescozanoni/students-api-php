<?php
declare(strict_types = 1);

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AnnotationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('annotations')->insert([
            'student_id' => 1,
            'title' => 'First title',
            'content' => 'First content',
            'user_id' => 123,
            'created_at' => '2019-01-01 01:00:00',
            'updated_at' => '2019-01-01 01:00:00',
        ]);
    }
}
