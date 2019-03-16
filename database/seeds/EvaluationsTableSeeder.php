<?php
declare(strict_types = 1);

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EvaluationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $record = [
            'stage_id' => 1,
            'clinical_tutor_id' => 456,
            'notes' => 'First evaluation notes',
            'created_at' => '2019-01-25 02:00:00',
            'updated_at' => '2019-01-25 02:00:00',
        ];
        foreach (config('app.evaluations.items') as $index => $item) {
            // The n-th item is assigned the n-th of its possible values.
            // If n is greater than the number of possible values,
            // value position count restarts from the beginning of the possible value array.
            $record[$item['name']] = $item['values'][$index % count($item['values'])];
            // Items at power-of-two position are set to null, if not required.
            if (in_array($index, [2, 4, 8, 16, 32])) {
                if ($item['required'] === false) {
                    $record[$item['name']] = null;
                }
            }
        }
        DB::table('evaluations')->insert($record);

        $record = [
            'stage_id' => 1,
            'clinical_tutor_id' => 789,
            'notes' => 'Second evaluation notes',
            'created_at' => '2019-01-26 02:00:00',
            'updated_at' => '2019-01-26 02:00:00',
            'deleted_at' => '2019-01-26 03:00:00',
        ];
        foreach (config('app.evaluations.items') as $index => $item) {
            // The n-th item is assigned the n-th of its possible values.
            // If n is greater than the number of possible values,
            // value position count restarts from the beginning of the possible value array.
            $record[$item['name']] = $item['values'][$index % count($item['values'])];
            // Items at power-of-three position are set to null, if not required.
            if (in_array($index, [3, 9, 27])) {
                if ($item['required'] === false) {
                    $record[$item['name']] = null;
                }
            }
        }
        DB::table('evaluations')->insert($record);
    }
}
