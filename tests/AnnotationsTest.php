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
            ->seeJsonEquals([
                'status_code' => 200,
                'status' => 'OK',
                'message' => 'Resource(s) found',
                'data' => [
                    [
                        'id' => 1,
                        'title' => 'First title',
                        'content' => 'First content',
                        'student' => [
                            'id' => 1,
                            'first_name' => 'John',
                            'last_name' => 'Doe',
                            'e_mail' => 'john.doe@foo.com',
                            'phone' => '1234-567890',
                            'nationality' => 'UK',
                        ],
                        'user_id' => 123,
                        'created_at' => '2019-01-01 01:00:00',
                        'updated_at' => '2019-01-01 01:00:00',
                    ],
                ]
            ])
            ->seeStatusCode(200);
    }

    /**
     * Get annotation by ID.
     */
    public function testGetById()
    {

        // Existing
        $this->json('GET', '/annotations/1')
            ->seeJsonEquals([
                'status_code' => 200,
                'status' => 'OK',
                'message' => 'Resource successfully retrieved/created/modified',
                'data' => [
                    'id' => 1,
                    'title' => 'First title',
                    'content' => 'First content',
                    'student' => [
                        'id' => 1,
                        'first_name' => 'John',
                        'last_name' => 'Doe',
                        'e_mail' => 'john.doe@foo.com',
                        'phone' => '1234-567890',
                        'nationality' => 'UK',
                    ],
                    'user_id' => 123,
                    'created_at' => '2019-01-01 01:00:00',
                    'updated_at' => '2019-01-01 01:00:00',
                ],
            ])
            ->seeStatusCode(200);

        // Non existing
        $this->json('GET', '/annotations/9999')
            ->seeJsonEquals([
                'status_code' => 404,
                'status' => 'Not Found',
                'message' => 'Resource(s) not found',
            ])
            ->seeStatusCode(404);

        // Invalid ID
        $this->json('GET', '/annotations/abc')
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
            ])
            ->seeStatusCode(400);

    }

    /**
     * Get student's annotations.
     */
    public function testGetRelatedToStudent()
    {

        // Existing
        $this->json('GET', '/students/1/annotations')
            ->seeJsonEquals([
                'status_code' => 200,
                'status' => 'OK',
                'message' => 'Resource(s) found',
                'data' => [
                    [
                        'id' => 1,
                        'title' => 'First title',
                        'content' => 'First content',
                        'user_id' => 123,
                        'created_at' => '2019-01-01 01:00:00',
                        'updated_at' => '2019-01-01 01:00:00',
                    ]
                ],
            ])
            ->seeStatusCode(200);

        // Non existing annotations
        $this->json('GET', '/students/2/annotations')
            ->seeJsonEquals([
                'status_code' => 404,
                'status' => 'Not Found',
                'message' => 'Resource(s) not found',
            ])
            ->seeStatusCode(404);

        // Non existing student
        $this->json('GET', '/students/999/annotations')
            ->seeJsonEquals([
                'status_code' => 404,
                'status' => 'Not Found',
                'message' => 'Resource(s) not found',
            ])
            ->seeStatusCode(404);

        // Invalid student ID
        $this->json('GET', '/students/abc/annotations')
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
            ])
            ->seeStatusCode(400);

    }

    /**
     * Create a student's annotation.
     */
    public function testCreateRelatedToStudent()
    {

        // Existing student
        $this->json('POST',
            '/students/1/annotations',
            [
                'title' => 'Second title',
                'content' => 'Second content',
                'user_id' => 456,
            ]
        )
            ->seeJsonEquals([
                'status_code' => 200,
                'status' => 'OK',
                'message' => 'Resource successfully retrieved/created/modified',
                'data' => [
                    'id' => 2,
                    'title' => 'Second title',
                    'content' => 'Second content',
                    'user_id' => 456,
                    // @todo CHANGE THE FOLLOWING TWO LINES, TO AVOID TEST FAILURE BECAUSE OF CROSS-SECOND EXECUTION
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
            ])
            ->seeStatusCode(200)
            ->seeInDatabase('annotations', ['id' => 2, 'deleted_at' => null])
            ->notSeeInDatabase('annotations', ['id' => 3]);

        // Non existing student
        $this->json('POST',
            '/students/999/annotations',
            [
                'title' => 'Second title',
                'content' => 'Second content',
                'user_id' => 456,
            ]
        )
            ->seeJsonEquals([
                'status_code' => 404,
                'status' => 'Not Found',
                'message' => 'Resource(s) not found',
            ])
            ->seeStatusCode(404)
            ->notSeeInDatabase('annotations', ['id' => 3]);

        // Invalid student ID
        $this->json('POST',
            '/students/abc/annotations',
            [
                'title' => 'Second title',
                'content' => 'Second content',
                'user_id' => 456,
            ]
        )
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
            ])
            ->seeStatusCode(400)
            ->notSeeInDatabase('annotations', ['id' => 3]);

        // @todo test required annotation properties

    }
}
