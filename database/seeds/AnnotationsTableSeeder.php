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
            'id' => 1,
            'student_id' => 1,
            'title' => 'First title',
            'content' => 'First content',
        ]);
    }
}
