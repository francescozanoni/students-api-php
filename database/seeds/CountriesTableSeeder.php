<?php
declare(strict_types = 1);

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('countries')->insert([
            'name' => 'Canada',
            'code' => 'CA',
            'created_at' => '2019-01-01 00:00:00',
            'updated_at' => '2019-01-01 00:00:00',
        ]);
        DB::table('countries')->insert([
            'name' => 'United Kingdom',
            'code' => 'GB',
            'created_at' => '2019-01-02 00:00:00',
            'updated_at' => '2019-01-02 00:00:00',
        ]);
        DB::table('countries')->insert([
            'name' => 'Ireland',
            'code' => 'IE',
            'created_at' => '2019-01-02 00:00:00',
            'updated_at' => '2019-01-02 00:00:00',
        ]);
        DB::table('countries')->insert([
            'name' => 'Australia',
            'code' => 'AU',
            'created_at' => '2019-01-02 00:00:00',
            'updated_at' => '2019-01-02 00:00:00',
        ]);
        DB::table('countries')->insert([
            'name' => 'Norway',
            'code' => 'NO',
            'created_at' => '2019-01-02 00:00:00',
            'updated_at' => '2019-01-02 00:00:00',
        ]);
        DB::table('countries')->insert([
            'name' => 'United States of America',
            'code' => 'US',
            'created_at' => '2019-01-02 00:00:00',
            'updated_at' => '2019-01-02 00:00:00',
        ]);
        DB::table('countries')->insert([
            'name' => 'Italy',
            'code' => 'IT',
            'created_at' => '2019-01-02 00:00:00',
            'updated_at' => '2019-01-02 00:00:00',
            'deleted_at' => '2019-01-03 00:00:00',
        ]);
    }
}
