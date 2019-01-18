<?php
declare(strict_types = 1);

use Laravel\Lumen\Testing\DatabaseMigrations;

class StudentsTest extends TestCase
{

    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();
        app(DatabaseSeeder::class)->run();
    }

    /**
     * Get all students.
     */
    public function testGet()
    {
        $this->json('GET', '/students')
            ->seeStatusCode(200)
            ->seeJsonEquals([
                'status_code' => 200,
                'status' => 'OK',
                'message' => 'Resource(s) found',
                'data' => [
                    [
                        'id' => 1,
                        'first_name' => 'John',
                        'last_name' => 'Doe',
                        'e_mail' => 'john.doe@foo.com',
                        'phone' => '1234-567890',
                        'nationality' => 'UK',
                    ],
                    [
                        'id' => 2,
                        'first_name' => 'Jane',
                        'last_name' => 'Doe',
                        'e_mail' => 'jane.doe@bar.com',
                        'phone' => null,
                        'nationality' => 'CA',
                    ],
                ]
            ]);
    }

    /**
     * Get a student.
     */
    public function testGetById()
    {

        // Existing
        $this->json('GET', '/students/1')
            ->seeStatusCode(200)
            ->seeJsonEquals([
                'status_code' => 200,
                'status' => 'OK',
                'message' => 'Resource successfully retrieved/created/modified',
                'data' => [
                    'id' => 1,
                    'first_name' => 'John',
                    'last_name' => 'Doe',
                    'e_mail' => 'john.doe@foo.com',
                    'phone' => '1234-567890',
                    'nationality' => 'UK',
                ]
            ]);

        // Non existing
        $this->json('GET', '/students/9999')
            ->seeStatusCode(404)
            ->seeJsonEquals([
                'status_code' => 404,
                'status' => 'Not Found',
                'message' => 'Resource(s) not found',
            ]);

        // Invalid ID
        $this->json('GET', '/students/abc')
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
     * Create a student.
     */
    public function testCreate()
    {

        // Valid data
        $this->json('POST',
            '/students',
            [
                'first_name' => 'Jack',
                'last_name' => 'Doe',
                'e_mail' => 'jack.doe@faz.com',
                'phone' => '0000-11111111',
                'nationality' => 'AU',
            ]
        )
            ->seeStatusCode(200)
            ->seeJsonEquals([
                'status_code' => 200,
                'status' => 'OK',
                'message' => 'Resource successfully retrieved/created/modified',
                'data' => [
                    'id' => 4,
                    'first_name' => 'Jack',
                    'last_name' => 'Doe',
                    'e_mail' => 'jack.doe@faz.com',
                    'phone' => '0000-11111111',
                    'nationality' => 'AU',
                ]
            ])
            ->seeInDatabase('students', ['id' => 4, 'deleted_at' => null])
            ->notSeeInDatabase('students', ['id' => 5]);

        // Missing required first_name
        $this->json('POST',
            '/students',
            [
                'last_name' => 'Doe',
                'e_mail' => 'jack.doe@faz.com',
                'phone' => '0000-11111111',
                'nationality' => 'AU',
            ]
        )
            ->seeStatusCode(400)
            ->seeJsonEquals([
                'status_code' => 400,
                'status' => 'Bad Request',
                'message' => 'Request is not valid',
                'data' => [
                    'first_name' => [
                        'code error_required',
                    ]
                ]
            ])
            ->notSeeInDatabase('students', ['id' => 5]);

        // Missing required last_name
        $this->json('POST',
            '/students',
            [
                'first_name' => 'Jack',
                'e_mail' => 'jack.doe@faz.com',
                'phone' => '0000-11111111',
                'nationality' => 'AU',
            ]
        )
            ->seeStatusCode(400)
            ->seeJsonEquals([
                'status_code' => 400,
                'status' => 'Bad Request',
                'message' => 'Request is not valid',
                'data' => [
                    'last_name' => [
                        'code error_required',
                    ]
                ]
            ])
            ->notSeeInDatabase('students', ['id' => 5]);

        // Missing required e_mail
        $this->json('POST',
            '/students',
            [
                'first_name' => 'Jack',
                'last_name' => 'Doe',
                'phone' => '0000-11111111',
                'nationality' => 'AU',
            ]
        )
            ->seeStatusCode(400)
            ->seeJsonEquals([
                'status_code' => 400,
                'status' => 'Bad Request',
                'message' => 'Request is not valid',
                'data' => [
                    'e_mail' => [
                        'code error_required',
                    ]
                ]
            ])
            ->notSeeInDatabase('students', ['id' => 5]);

        // Missing required nationality
        $this->json('POST',
            '/students',
            [
                'first_name' => 'Jack',
                'last_name' => 'Doe',
                'e_mail' => 'jack.doe@faz.com',
                'phone' => '0000-11111111',
            ]
        )
            ->seeStatusCode(400)
            ->seeJsonEquals([
                'status_code' => 400,
                'status' => 'Bad Request',
                'message' => 'Request is not valid',
                'data' => [
                    'nationality' => [
                        'code error_required',
                    ]
                ]
            ])
            ->notSeeInDatabase('students', ['id' => 5]);

    }

    /**
     * Modify a student.
     */
    public function testModifyById()
    {

        // Success
        $this->json('PUT',
            '/students/2',
            [
                'id' => 2,
                'first_name' => 'Jane',
                'last_name' => 'Doe',
                'e_mail' => 'jane.doe@bar.com',
                'phone' => '3333-11111111',
                'nationality' => 'IE',
            ]
        )
            ->seeStatusCode(200)
            ->seeJsonEquals([
                'status_code' => 200,
                'status' => 'OK',
                'message' => 'Resource successfully retrieved/created/modified',
                'data' => [
                    'id' => 2,
                    'first_name' => 'Jane',
                    'last_name' => 'Doe',
                    'e_mail' => 'jane.doe@bar.com',
                    'phone' => '3333-11111111',
                    'nationality' => 'IE',
                ]
            ]);

        // Unmatching ID in path
        $this->json('PUT',
            '/students/3',
            [
                'id' => 2,
                'first_name' => 'Jane',
                'last_name' => 'Doe',
                'e_mail' => 'jane.doe@bar.com',
                'phone' => '3333-11111111',
                'nationality' => 'IE',
            ]
        )
            ->seeStatusCode(400)
            ->seeJsonEquals([
                'status_code' => 400,
                'status' => 'Bad Request',
                'message' => 'Request is not valid',
                'data' => [
                    'id' => ['The id must be one of the following values: 3'],
                ]
            ])
            ->notSeeInDatabase('students', ['id' => 3, 'first_name' => 'Jane']);

        // Unmatching ID in body
        $this->json('PUT',
            '/students/2',
            [
                'id' => 3,
                'first_name' => 'Jane',
                'last_name' => 'Doe',
                'e_mail' => 'jane.doe@bar.com',
                'phone' => '3333-11111111',
                'nationality' => 'IE',
            ]
        )
            ->seeStatusCode(400)
            ->seeJsonEquals([
                'status_code' => 400,
                'status' => 'Bad Request',
                'message' => 'Request is not valid',
                'data' => [
                    'id' => ['The id must be one of the following values: 2'],
                ]
            ])
            ->notSeeInDatabase('students', ['id' => 3, 'first_name' => 'Jane']);

        // Non existing student
        $this->json('PUT',
            '/students/999',
            [
                'id' => 999,
                'first_name' => 'AAA',
                'last_name' => 'BBB',
                'e_mail' => 'aaa.bbb@ccc.com',
                'phone' => '3333-11111111',
                'nationality' => 'NO',
            ]
        )
            ->seeStatusCode(404)
            ->seeJsonEquals([
                'status_code' => 404,
                'status' => 'Not Found',
                'message' => 'Resource(s) not found',
            ]);

    }

    /**
     * Delete a student.
     */
    public function testDeleteById()
    {

        // Existing student
        $this->json('DELETE', '/students/2')
            ->seeStatusCode(200)
            ->seeJsonEquals([
                'status_code' => 200,
                'status' => 'OK',
                'message' => 'Resource deleted',
            ])
            ->notSeeInDatabase('students', ['id' => 2, 'deleted_at' => null]);

        // Non existing student
        $this->json('DELETE', '/students/999')
            ->seeStatusCode(404)
            ->seeJsonEquals([
                'status_code' => 404,
                'status' => 'Not Found',
                'message' => 'Resource(s) not found',
            ]);

    }

}
