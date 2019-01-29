<?php
declare(strict_types = 1);

use Laravel\Lumen\Testing\DatabaseMigrations;
use PHPUnit\Framework\Assert as PHPUnit;

abstract class TestCase extends Laravel\Lumen\Testing\TestCase
{

    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();
        app(DatabaseSeeder::class)->run();
    }

    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {
        return require __DIR__ . '/../bootstrap/app.php';
    }

    /**
     * Assert that the response contains an exact JSON array,
     * by making "created_at", "updated_at" and "deleted_at" comparison less strict (one second).
     *
     * @param  array $data
     * @return self
     */
    public function seeJsonEquals(array $data) : self
    {

        $actual = json_encode(array_sort_recursive(
            json_decode($this->response->getContent(), true)
        ));

        $data = json_encode(array_sort_recursive(
            json_decode(json_encode($data), true)
        ));

        // If no date/time field is part of $data, standard assertion is applied.
        if (strpos($actual, 'created_at') === false &&
            strpos($actual, 'updated_at') === false &&
            strpos($actual, 'deleted_at') === false) {
            PHPUnit::assertEquals($data, $actual);
            return $this;
        }

        $dataMinusOneSecond = json_decode($data, true);

        array_walk_recursive($dataMinusOneSecond, function (&$item, $key) {
            if (in_array($key, ['created_at', 'updated_at', 'deleted_at']) === true) {
                $dateTime = DateTime::createFromFormat('Y-m-d H:i:s', $item);

                // If the date/time field contains a very old value (static value provided by seeder),
                // it is not considered.
                $currentDateTime = DateTime::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'));
                $interval = $currentDateTime->diff($dateTime);
                if ((int)$interval->format('%s') > 10) {
                    return;
                }

                $dateTime->sub(new DateInterval('PT1S'));
                $dateTime = $dateTime->format('Y-m-d H:i:s');
                $item = $dateTime;
            }
        });

        $dataMinusOneSecond = json_encode($dataMinusOneSecond);

        PHPUnit::assertTrue(in_array($actual, [$data, $dataMinusOneSecond]));

        return $this;

    }

}
