<?php
declare(strict_types = 1);

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('locations')->insert([
            'id' => 1,
            'name' => 'Location 1',
        ]);
        DB::table('locations')->insert([
            'id' => 2,
            'name' => 'Deleted location',
        ]);
        DB::table('locations')->delete(2);
    }
}
