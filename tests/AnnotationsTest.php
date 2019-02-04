<?php
declare(strict_types = 1);

class AnnotationsTest extends TestCase
{

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
                            'nationality' => 'GB',
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
                        'nationality' => 'GB',
                    ],
                    'user_id' => 123,
                    'created_at' => '2019-01-01 01:00:00',
                    'updated_at' => '2019-01-01 01:00:00',
                ],
            ])
            ->seeStatusCode(200);

    }

    /**
     * Get annotation by ID: failure.
     */
    public function testGetByIdFailure()
    {

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
                        'in path',
                    ],
                ]
            ])
            ->seeStatusCode(400);

    }

    /**
     * Get student's annotations: success.
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

    }

    /**
     * Get student's annotations: failure.
     */
    public function testGetRelatedToStudentFailure()
    {

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
                        'in path',
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
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
            ])
            ->seeStatusCode(200)
            ->seeInDatabase('annotations', ['id' => 2, 'student_id' => 1, 'deleted_at' => null])
            ->notSeeInDatabase('annotations', ['id' => 3]);

    }

    /**
     * Create a student's annotation: failure.
     */
    public function testCreateRelatedToStudentFailure()
    {

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
            ->notSeeInDatabase('annotations', ['id' => 3])
            ->notSeeInDatabase('annotations', ['student_id' => 999]);

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
                        'in path',
                    ],
                ]
            ])
            ->seeStatusCode(400)
            ->notSeeInDatabase('annotations', ['id' => 3])
            ->notSeeInDatabase('annotations', ['student_id' => 'abc']);

        // Missing required title
        $this->json('POST',
            '/students/1/annotations',
            [
                'content' => 'Second content',
                'user_id' => 456,
            ]
        )
            ->seeJsonEquals([
                'status_code' => 400,
                'status' => 'Bad Request',
                'message' => 'Request is not valid',
                'data' => [
                    'title' => [
                        'code error_required',
                        'in body',
                    ]
                ]
            ])
            ->seeStatusCode(400)
            ->notSeeInDatabase('annotations', ['id' => 3]);

        // Too short title
        $this->json('POST',
            '/students/1/annotations',
            [
                'title' => 'A',
                'content' => 'Second content',
                'user_id' => 456,
            ]
        )
            ->seeJsonEquals([
                'status_code' => 400,
                'status' => 'Bad Request',
                'message' => 'Request is not valid',
                'data' => [
                    'title' => [
                        'code error_minLength',
                        'length 1',
                        'min 3',
                        'value A',
                        'in body',
                    ]
                ]
            ])
            ->seeStatusCode(400)
            ->notSeeInDatabase('annotations', ['id' => 3]);

        // Missing required content
        $this->json('POST',
            '/students/1/annotations',
            [
                'title' => 'Second title',
                'user_id' => 456,
            ]
        )
            ->seeJsonEquals([
                'status_code' => 400,
                'status' => 'Bad Request',
                'message' => 'Request is not valid',
                'data' => [
                    'content' => [
                        'code error_required',
                        'in body',
                    ]
                ]
            ])
            ->seeStatusCode(400)
            ->notSeeInDatabase('annotations', ['id' => 3]);

        // Too short content
        $this->json('POST',
            '/students/1/annotations',
            [
                'title' => 'Second title',
                'content' => 'A',
                'user_id' => 456,
            ]
        )
            ->seeJsonEquals([
                'status_code' => 400,
                'status' => 'Bad Request',
                'message' => 'Request is not valid',
                'data' => [
                    'content' => [
                        'code error_minLength',
                        'length 1',
                        'min 3',
                        'value A',
                        'in body',
                    ]
                ]
            ])
            ->seeStatusCode(400)
            ->notSeeInDatabase('annotations', ['id' => 3]);

        // Missing required user_id
        $this->json('POST',
            '/students/1/annotations',
            [
                'title' => 'Second title',
                'content' => 'Second content',
            ]
        )
            ->seeJsonEquals([
                'status_code' => 400,
                'status' => 'Bad Request',
                'message' => 'Request is not valid',
                'data' => [
                    'user_id' => [
                        'code error_required',
                        'in body',
                    ],
                ]
            ])
            ->seeStatusCode(400)
            ->notSeeInDatabase('annotations', ['id' => 3]);

        // Invalid user_id
        $this->json('POST',
            '/students/1/annotations',
            [
                'title' => 'Second title',
                'content' => 'Second content',
                'user_id' => 'abc',
            ]
        )
            ->seeJsonEquals([
                'status_code' => 400,
                'status' => 'Bad Request',
                'message' => 'Request is not valid',
                'data' => [
                    'user_id' => [
                        'code error_type',
                        'value abc',
                        'expected integer',
                        'used string',
                        'in body',
                    ],
                ]
            ])
            ->seeStatusCode(400)
            ->notSeeInDatabase('annotations', ['id' => 3]);

    }

    /**
     * Modify an annotation: success.
     */
    public function testModifyById()
    {

        // Success
        $this->json('PUT',
            '/annotations/1',
            [
                'title' => 'First title modified',
                'content' => 'First content',
                'user_id' => 123,
            ]
        )
            ->seeJsonEquals([
                'status_code' => 200,
                'status' => 'OK',
                'message' => 'Resource successfully retrieved/created/modified',
                'data' => [
                    'id' => 1,
                    'title' => 'First title modified',
                    'content' => 'First content',
                    'student' => [
                        'id' => 1,
                        'first_name' => 'John',
                        'last_name' => 'Doe',
                        'e_mail' => 'john.doe@foo.com',
                        'phone' => '1234-567890',
                        'nationality' => 'GB',
                    ],
                    'user_id' => 123,
                    'created_at' => '2019-01-01 01:00:00',
                    'updated_at' => date('Y-m-d H:i:s'),
                ]
            ])
            ->seeStatusCode(200)
            ->seeInDatabase('annotations', ['id' => 1, 'title' => 'First title modified'])
            ->notSeeInDatabase('annotations', ['id' => 1, 'title' => 'First title']);

    }

    /**
     * Modify an annotation: failure.
     */
    public function testModifyByIdFailure()
    {

        // Different user_id
        $this->json('PUT',
            '/annotations/1',
            [
                'title' => 'First title modified',
                'content' => 'First content',
                'user_id' => 456,
            ]
        )
            ->seeJsonEquals([
                'status_code' => 400,
                'status' => 'Bad Request',
                'message' => 'Request is not valid',
                'data' => [
                    'user_id' => [
                        'The user id cannot be changed',
                    ],
                ]
            ])
            ->seeStatusCode(400)
            ->seeInDatabase('annotations', ['id' => 1, 'user_id' => 123])
            ->notSeeInDatabase('annotations', ['id' => 1, 'title' => 'First title modified'])
            ->notSeeInDatabase('annotations', ['id' => 1, 'user_id' => 456]);

        // Invalid ID
        $this->json('PUT',
            '/annotations/abc',
            [
                'title' => 'Second title',
                'content' => 'Second content',
                'user_id' => 123,
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
                        'in path',
                    ],
                ]
            ])
            ->seeStatusCode(400)
            ->notSeeInDatabase('annotations', ['id' => 'abc'])
            ->notSeeInDatabase('annotations', ['id' => 3]);

        // Non existing ID
        $this->json('PUT',
            '/annotations/999',
            [
                'title' => 'Second title',
                'content' => 'Second content',
                'user_id' => 123,
            ]
        )
            ->seeJsonEquals([
                'status_code' => 404,
                'status' => 'Not Found',
                'message' => 'Resource(s) not found',
            ])
            ->seeStatusCode(404)
            ->notSeeInDatabase('annotations', ['id' => 999])
            ->notSeeInDatabase('annotations', ['id' => 3]);

        // @todo add further tests related to missing required fields

    }

    /**
     * Delete an annotation: success.
     */
    public function testDeleteById()
    {

        // Existing annotation
        $this->json('DELETE', '/annotations/1')
            ->seeJsonEquals([
                'status_code' => 200,
                'status' => 'OK',
                'message' => 'Resource deleted',
            ])
            ->seeStatusCode(200)
            ->seeInDatabase('annotations', ['id' => 1, 'deleted_at' => date('Y-m-d H:i:s')])
            ->notSeeInDatabase('annotations', ['id' => 1, 'deleted_at' => null]);

    }

    /**
     * Delete an annotation: failure.
     */
    public function testDeleteByIdFailure()
    {

        // Non existing annotation
        $this->json('DELETE', '/annotations/999')
            ->seeJsonEquals([
                'status_code' => 404,
                'status' => 'Not Found',
                'message' => 'Resource(s) not found',
            ])
            ->seeStatusCode(404)
            ->notSeeInDatabase('annotations', ['id' => 999]);

        // Invalid ID
        $this->json('DELETE', '/annotations/abc')
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
                        'in path',
                    ],
                ]
            ])
            ->seeStatusCode(400)
            ->notSeeInDatabase('annotations', ['id' => 'abc']);

        // @todo assess whether to add check of user_id

    }

}
