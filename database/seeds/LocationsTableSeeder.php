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
            'name' => 'Location 1',
            'created_at' => '2019-01-01 00:00:00',
            'updated_at' => '2019-01-01 00:00:00',
        ]);
        DB::table('locations')->insert([
            'name' => 'Deleted location',
            'created_at' => '2019-01-02 00:00:00',
            'updated_at' => '2019-01-02 00:00:00',
            'deleted_at' => '2019-01-03 00:00:00',
        ]);
    }
}
