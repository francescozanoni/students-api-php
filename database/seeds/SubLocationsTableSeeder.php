<?php
declare(strict_types = 1);

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubLocationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('sub_locations')->insert([
            'id' => 1,
            'name' => 'Sub-location 1',
        ]);
        DB::table('sub_locations')->insert([
            'id' => 2,
            'name' => 'Deleted sub-location',
        ]);
        DB::table('sub_locations')->delete(2);
    }
}
