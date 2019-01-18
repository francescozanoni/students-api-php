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

    /**
     * Get annotation by ID.
     */
    public function testGetById()
    {

        // Existing
        $this->json('GET', '/annotations/1')
            ->seeStatusCode(200)
            ->seeJsonEquals([
                'status_code' => 200,
                'status' => 'OK',
                'message' => 'Resource(s) found',
                'data' => [
                    'id' => 1,
                    'student_id' => 1,
                    'title' => 'First title',
                    'content' => 'First content',
                    'user_id' => 123,
                    'created_at' => '2019-01-01 01:00:00',
                    'updated_at' => '2019-01-01 01:00:00',
                ],
            ]);

        // Non existing
        $this->json('GET', '/annotations/9999')
            ->seeStatusCode(404)
            ->seeJsonEquals([
                'status_code' => 404,
                'status' => 'Not Found',
                'message' => 'Resource(s) not found',
            ]);

        // Invalid ID
        $this->json('GET', '/annotations/abc')
            ->seeStatusCode(400)
            ->seeJsonEquals([
                'status_code' => 400,
                'status' => 'Bad Request',
                'message' => 'Request is not valid',
                'data' => [
                    'id' => [
                        'code error_type',
                        'value abc',
                        'expected integer',
                        'used string',
                    ],
                ]
            ]);

    }

    /**
     * Get student's annotations.
     */
    public function testGetRelatedToStudent()
    {

        // Existing
        $this->json('GET', '/students/1/annotations')
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
                    ]
                ],
            ]);

        // Non existing annotations
        $this->json('GET', '/students/2/annotations')
            ->seeStatusCode(404)
            ->seeJsonEquals([
                'status_code' => 404,
                'status' => 'Not Found',
                'message' => 'Resource(s) not found',
            ]);

        // Non existing student
        $this->json('GET', '/students/999/annotations')
            ->seeStatusCode(404)
            ->seeJsonEquals([
                'status_code' => 404,
                'status' => 'Not Found',
                'message' => 'Resource(s) not found',
            ]);

        // Invalid student ID
        $this->json('GET', '/students/abc/annotations')
            ->seeStatusCode(400)
            ->seeJsonEquals([
                'status_code' => 400,
                'status' => 'Bad Request',
                'message' => 'Request is not valid',
                'data' => [
                    'id' => [
                        'code error_type',
                        'value abc',
                        'expected integer',
                        'used string',
                    ],
                ]
            ]);

    }

}
