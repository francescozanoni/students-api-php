<?php
declare(strict_types = 1);

use Laravel\Lumen\Testing\DatabaseMigrations;

class AnnotationsTest extends TestCase
{

    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();
        app(DatabaseSeeder::class)->run();
    }

    /**
     * Get all annotations.
     */
    public function testGet()
    {
        $this->json('GET', '/annotations')
            ->seeStatusCode(200)
            ->seeJsonEquals([
                'status_code' => 200,
                'status' => 'OK',
                'message' => 'Resource(s) found',
                'data' => [
                    [
                        'id' => 1,
                        'student_id' => 1,
                        'title' => 'First title',
                        'content' => 'First content',
                        'user_id' => 123,
                        'created_at' => '2019-01-01 01:00:00',
                        'updated_at' => '2019-01-01 01:00:00',
                    ],
                ]
            ]);
    }

}
