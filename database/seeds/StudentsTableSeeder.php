<?php
declare(strict_types = 1);

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StudentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('students')->insert((new StudentBuilder('john'))->build());
        DB::table('students')->insert((new StudentBuilder('jane'))->build());
        DB::table('students')->insert((new StudentBuilder('jim'))->build());
        DB::table('students')->insert((new StudentBuilder('joan'))->build());
        
        DB::table('students')->delete((new StudentBuilder('jim'))->build()['id']);
    }
}
