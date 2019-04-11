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
            'id' => 1,
            'name' => 'Canada',
            'code' => 'CA',
        ]);
        DB::table('countries')->insert([
            'id' => 2,
            'name' => 'United Kingdom',
            'code' => 'GB',
        ]);
        DB::table('countries')->insert([
            'id' => 3,
            'name' => 'Ireland',
            'code' => 'IE',
        ]);
        DB::table('countries')->insert([
            'id' => 4,
            'name' => 'Australia',
            'code' => 'AU',
        ]);
        DB::table('countries')->insert([
            'id' => 5,
            'name' => 'Norway',
            'code' => 'NO',
        ]);
        DB::table('countries')->insert([
            'id' => 6,
            'name' => 'United States of America',
            'code' => 'US',
        ]);
        DB::table('countries')->insert([
            'id' => 7,
            'name' => 'Italy',
            'code' => 'IT',
        ]);
        DB::table('countries')->delete(7);
    }
}
