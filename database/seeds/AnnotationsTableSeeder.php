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
        DB::table('annotations')->insert(
            (new AnnotationBuilder('first'))
                ->with('student_id', (new StudentBuilder('john'))->build()['id'])
                ->build()
        );
    }
}
