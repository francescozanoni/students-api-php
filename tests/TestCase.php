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
     * by making "created_at", "updated_at" and "deleted_at" comparison less strict.
     *
     * @param  array $data
     * @return self
     */
    public function _seeJsonEquals(array $data) : self
    {

        $actual = json_encode(array_sort_recursive(
            json_decode($this->response->getContent(), true)
        ));

        $data = json_encode(array_sort_recursive(
            json_decode(json_encode($data), true)
        ));

        // If no date/time field is part of $data, standard assertion is applied.
        if (strpos('created_at', $actual) === false &&
            strpos('updated_at', $actual) === false &&
            strpos('deleted_at', $actual) === false) {
            PHPUnit::assertEquals($data, $actual);
            return $this;
        }

        $actualMinusOneSecond = $actual;

        if (strpos('created_at', $actualMinusOneSecond) !== false) {
            $actualMinusOneSecond = json_decode($actualMinusOneSecond, true);
            array_walk_recursive($actualMinusOneSecond, function (&$item, $key) {
                if ($key === 'created_at') {
                    $dateTime = DateTime::createFromFormat('Y-m-d H:i:s', $item);
                    $dateTime->sub(new DateInterval('PT1S'));
                    $dateTime = $dateTime->format('Y-m-d H:i:s');
                    $item = $dateTime;
                }
            });
            $actualMinusOneSecond = json_encode($actualMinusOneSecond);
        }
        if (strpos('updated_at', $actualMinusOneSecond) !== false) {
            $actualMinusOneSecond = json_decode($actualMinusOneSecond, true);
            array_walk_recursive($actualMinusOneSecond, function (&$item, $key) {
                if ($key === 'updated_at') {
                    $dateTime = DateTime::createFromFormat('Y-m-d H:i:s', $item);
                    $dateTime->sub(new DateInterval('PT1S'));
                    $dateTime = $dateTime->format('Y-m-d H:i:s');
                    $item = $dateTime;
                }
            });
            $actualMinusOneSecond = json_encode($actualMinusOneSecond);
        }
        if (strpos('deleted_at', $actualMinusOneSecond) !== false) {
            $actualMinusOneSecond = json_decode($actualMinusOneSecond, true);
            array_walk_recursive($actualMinusOneSecond, function (&$item, $key) {
                if ($key === 'deleted_at') {
                    $dateTime = DateTime::createFromFormat('Y-m-d H:i:s', $item);
                    $dateTime->sub(new DateInterval('PT1S'));
                    $dateTime = $dateTime->format('Y-m-d H:i:s');
                    $item = $dateTime;
                }
            });
            $actualMinusOneSecond = json_encode($actualMinusOneSecond);
        }

        PHPUnit::assertTrue(in_array($data, [$actual, $actualMinusOneSecond]));

        return $this;

    }

}
