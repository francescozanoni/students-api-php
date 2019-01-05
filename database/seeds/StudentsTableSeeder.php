<?php

use Illuminate\Database\Seeder;

class StudentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('students')->insert([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'e_mail' => 'john.doe@foo.com',
            'phone' => '1234-567890',
            'nationality' => 'UK',
        ]);
        DB::table('students')->insert([
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'e_mail' => 'jane.doe@bar.com',
            'nationality' => 'CA',
        ]);
    }
}
