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
            'name' => 'Sub-location 1',
            'created_at' => '2019-01-01 00:00:00',
            'updated_at' => '2019-01-01 00:00:00',
        ]);
        DB::table('sub_locations')->insert([
            'name' => 'Sub-location 2',
            'created_at' => '2019-01-02 00:00:00',
            'updated_at' => '2019-01-02 00:00:00',
            'deleted_at' => '2019-01-03 00:00:00',
        ]);
    }
}
