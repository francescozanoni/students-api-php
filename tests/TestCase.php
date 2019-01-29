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
     * Example:
     * value {"created_at": "2019-01-01 12:12:12"} is compared with both following:
     *  - {"created_at": "2019-01-01 12:12:12"}
     *  - {"created_at": "2019-01-01 12:12:11"}
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

                // If the date/time field contains a very old value (typically,
                // in case of static value provided by seeder), it is not considered.
                $currentDateTime = new DateTime();
                $interval = $currentDateTime->diff($dateTime);
                if ((int)$interval->format('%s') > 10) {
                    return;
                }

                $dateTime->sub(new DateInterval('PT1S'));
                $item = $dateTime->format('Y-m-d H:i:s');
            }
        });

        $dataMinusOneSecond = json_encode($dataMinusOneSecond);

        PHPUnit::assertTrue(in_array($actual, [$data, $dataMinusOneSecond]));

        return $this;

    }

    /**
     * Assert that a given where condition exists in the database,
     * by making "created_at", "updated_at" and "deleted_at" comparison less strict (one second).
     *
     * Example:
     * value {"created_at": "2019-01-01 12:12:12"} is compared with both following:
     *  - {"created_at": "2019-01-01 12:12:12"}
     *  - {"created_at": "2019-01-01 12:12:11"}
     *
     * @param  string $table
     * @param  array $data
     * @param  string|null $onConnection
     *
     * @return self
     */
    public function seeInDatabase($table, array $data, $onConnection = null) : self
    {

        // If no date/time field is part of $data, standard assertion is applied.
        if (array_key_exists('created_at', $data) === false &&
            array_key_exists('updated_at', $data) === false &&
            array_key_exists('deleted_at', $data) === false) {

            $count = $this->app->make('db')->connection($onConnection)->table($table)->where($data)->count();

            $this->assertGreaterThan(0, $count, sprintf(
                'Unable to find row in database table [%s] that matched attributes [%s].', $table, json_encode($data)
            ));

            return $this;

        }

        $dataMinusOneSecond = $data;

        array_walk_recursive($dataMinusOneSecond, function (&$item, $key) {
            if (in_array($key, ['created_at', 'updated_at', 'deleted_at']) === true &&
                is_string($item) === true) {
                $dateTime = DateTime::createFromFormat('Y-m-d H:i:s', $item);

                // If the date/time field contains a very old value (typically,
                // in case of static value provided by seeder), it is not considered.
                $currentDateTime = new DateTime();
                $interval = $currentDateTime->diff($dateTime);
                if ((int)$interval->format('%s') > 10) {
                    return;
                }

                $dateTime->sub(new DateInterval('PT1S'));
                $item = $dateTime->format('Y-m-d H:i:s');
            }
        });

        $count = $this->app->make('db')->connection($onConnection)->table($table)->where($data)->orWhere($dataMinusOneSecond)->count();

        $this->assertGreaterThan(0, $count, sprintf(
            'Unable to find row in database table [%s] that matched attributes [%s].', $table, json_encode($data)
        ));

        return $this;

    }

}
