<?php

use Illuminate\Database\Seeder;

class StudentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('students')->insert([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'e_mail' => 'john.doe@foo.com',
            'phone' => '1234-567890',
            'nationality' => 'UK',
            'created_at' => '2019-01-01 00:00:00',
            'updated_at' => '2019-01-01 00:00:00',
        ]);
        DB::table('students')->insert([
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'e_mail' => 'jane.doe@bar.com',
            'nationality' => 'CA',
            'created_at' => '2019-01-02 00:00:00',
            'updated_at' => '2019-01-02 00:00:00',
        ]);
        DB::table('students')->insert([
            'first_name' => 'Jim',
            'last_name' => 'Doe',
            'e_mail' => 'jim.doe@baz.com',
            'nationality' => 'US',
            'created_at' => '2019-01-03 00:00:00',
            'updated_at' => '2019-01-03 00:00:00',
            'deleted_at' => '2019-01-04 00:00:00',
        ]);
    }
}
