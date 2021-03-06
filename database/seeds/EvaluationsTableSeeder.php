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
            'id' => 1,
            'internship_id' => 1,
            'notes' => 'First evaluation notes',
        ];
        $record = array_merge($record, self::generateItemValues(config('internships.evaluations.items')));
        DB::table('evaluations')->insert($record);

        $record = [
            'id' => 2,
            'internship_id' => 1,
            'notes' => 'Second evaluation notes',
        ];
        $record = array_merge($record, self::generateItemValues(config('internships.evaluations.items')));
        DB::table('evaluations')->insert($record);
        DB::table('evaluations')->delete(2);

    }

    /**
     * Generate internship item values.
     *
     * This logic is made public because shared with:
     *  - OpenAPI schema file customizer (scripts/setup.php)
     *  - evaluation tests (tests/EvaluationsTest.php)
     *
     * @param array $itemDefinitions e.g. Array (
     *                                      [0] => Array (
     *                                        [name] => item_1
     *                                        [values] => Array (
     *                                          [0] => A
     *                                          [1] => B
     *                                          [2] => C
     *                                          [3] => D
     *                                          [4] => E
     *                                        )
     *                                        [required] => true
     *                                      )
     *                                      [...]
     *                                    )
     *
     * @return array e.g. Array (
     *                      [item_1] => A
     *                      [...]
     *                    )
     */
    public static function generateItemValues(array $itemDefinitions) : array
    {

        $itemValues = [];

        if (empty($itemDefinitions) === true) {
            return $itemValues;
        }

        foreach ($itemDefinitions as $index => $item) {

            // The n-th item is assigned the n-th of its possible values.
            // If n is greater than the number of possible values,
            // value position count restarts from the beginning of the possible value array.
            $itemValues[$item['name']] = $item['values'][$index % count($item['values'])];

            // Items at power-of-two position (zero-based) are removed, if not required.
            if (in_array($index, [2, 4, 8, 16, 32, 64, 128, 256]) &&
                $item['required'] === false) {
                unset($itemValues[$item['name']]);
            }

        }

        return $itemValues;

    }

}
