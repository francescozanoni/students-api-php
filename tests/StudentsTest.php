<?php
declare(strict_types = 1);

class StudentsTest extends TestCase
{

    /**
     * Get all students.
     */
    public function testGet()
    {
        $this->json('GET', '/students')
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
            ])
            ->seeStatusCode(200);
    }

    /**
     * Get a student: success.
     */
    public function testGetById()
    {

        // Existing
        $this->json('GET', '/students/1')
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
            ])
            ->seeStatusCode(200);

    }

    /**
     * Get a student: failure.
     */
    public function testGetByIdFailure()
    {

        // Non existing
        $this->json('GET', '/students/9999')
            ->seeJsonEquals([
                'status_code' => 404,
                'status' => 'Not Found',
                'message' => 'Resource(s) not found',
            ])
            ->seeStatusCode(404);

        // Invalid ID
        $this->json('GET', '/students/abc')
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
     * Create a student: success.
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
            ->seeStatusCode(200)
            ->seeInDatabase('students', ['id' => 4, 'deleted_at' => null])
            ->notSeeInDatabase('students', ['id' => 5]);

    }

    /**
     * Create a student: failure.
     */
    public function testCreateFailure()
    {

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
            ->seeStatusCode(400)
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
            ->seeStatusCode(400)
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
            ->seeStatusCode(400)
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
            ->seeStatusCode(400)
            ->notSeeInDatabase('students', ['id' => 5]);

        // @todo add invalid and minLength test

    }

    /**
     * Modify a student: success.
     */
    public function testModifyById()
    {

        // Success
        $this->json('PUT',
            '/students/2',
            [
                'first_name' => 'Jane',
                'last_name' => 'Doe',
                'e_mail' => 'jane.doe@bar.com',
                'phone' => '3333-11111111',
                'nationality' => 'IE',
            ]
        )
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
            ])
            ->seeStatusCode(200)
            ->seeInDatabase('students', ['id' => 2, 'nationality' => 'IE'])
            ->notSeeInDatabase('students', ['id' => 2, 'nationality' => 'CA']);

    }

    /**
     * Modify a student: failure.
     */
    public function testModifyByIdFailure()
    {

        // Non existing student
        $this->json('PUT',
            '/students/999',
            [
                'first_name' => 'AAA',
                'last_name' => 'BBB',
                'e_mail' => 'aaa.bbb@ccc.com',
                'phone' => '3333-11111111',
                'nationality' => 'NO',
            ]
        )
            ->seeJsonEquals([
                'status_code' => 404,
                'status' => 'Not Found',
                'message' => 'Resource(s) not found',
            ])
            ->seeStatusCode(404)
            ->notSeeInDatabase('students', ['id' => 999]);

        // Non existing student
        $this->json('PUT',
            '/students/abc',
            [
                'first_name' => 'AAA',
                'last_name' => 'BBB',
                'e_mail' => 'aaa.bbb@ccc.com',
                'phone' => '3333-11111111',
                'nationality' => 'NO',
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
            ->notSeeInDatabase('students', ['id' => 999]);

        // @todo add required and minLength tests

    }

    /**
     * Delete a student: success.
     */
    public function testDeleteById()
    {

        // Existing student
        $this->json('DELETE', '/students/2')
            ->seeJsonEquals([
                'status_code' => 200,
                'status' => 'OK',
                'message' => 'Resource deleted',
            ])
            ->seeStatusCode(200)

            ->seeInDatabase('students', ['id' => 2, 'deleted_at' => date('Y-m-d H:i:s')])
            ->notSeeInDatabase('students', ['id' => 2, 'deleted_at' => null]);

    }

    /**
     * Delete a student: failure.
     */
    public function testDeleteByIdFailure()
    {

        // Non existing student
        $this->json('DELETE', '/students/999')
            ->seeJsonEquals([
                'status_code' => 404,
                'status' => 'Not Found',
                'message' => 'Resource(s) not found',
            ])
            ->seeStatusCode(404)
            ->notSeeInDatabase('students', ['id' => 999]);

        // Invalid ID
        $this->json('DELETE', '/students/abc')
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

        // Missing ID
        $this->json('DELETE', '/students/abc')
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

}
