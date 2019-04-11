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
        DB::table('students')->insert([
            'id' => 1,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'e_mail' => 'john.doe@foo.com',
            'phone' => '1234-567890',
            'nationality' => 'GB',
        ]);
        DB::table('students')->insert([
            'id' => 2,
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'e_mail' => 'jane.doe@bar.com',
            'nationality' => 'CA',
        ]);
        DB::table('students')->insert([
            'id' => 3,
            'first_name' => 'Jim',
            'last_name' => 'Doe',
            'e_mail' => 'jim.doe@baz.com',
            'nationality' => 'US',
        ]);
        DB::table('students')->insert([
            'id' => 4,
            'first_name' => 'Joan',
            'last_name' => 'Doe',
            'e_mail' => 'joan.doe@foo.com',
            'nationality' => 'IE',
        ]);
        DB::table('students')->delete(3);
    }
}
