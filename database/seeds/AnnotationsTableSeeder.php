<?php

use Illuminate\Database\Seeder;

class AnnotationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('annotations')->insert([
            'student_id' => 1,
            'title' => 'First title',
            'content' => 'First content',
            'user_id' => 123,
        ]);
    }
}
