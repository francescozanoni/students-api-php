<?php
declare(strict_types = 1);

class SeminarAttendancesTest extends TestCase
{

    /**
     * Get all seminar attendances.
     */
    public function testGet()
    {

        $this->json('GET', '/seminar_attendances')
            ->seeJsonEquals([
                'status_code' => 200,
                'status' => 'OK',
                'message' => 'Resource(s) found',
                'data' => [
                    [
                        'id' => 1,
                        'seminar' => 'First seminar',
                        'start_date' => '2019-01-08',
                        'end_date' => '2019-01-09',
                        'ects_credits' => 1.2,
                        'student' => [
                            'id' => 1,
                            'first_name' => 'John',
                            'last_name' => 'Doe',
                            'e_mail' => 'john.doe@foo.com',
                            'phone' => '1234-567890',
                            'nationality' => 'GB',
                        ],
                    ],
                ],
            ])
            ->seeStatusCode(200);

    }
    
    /**
     * Get seminar attendance by ID.
     */
    public function testGetById()
    {

        // Existing
        $this->json('GET', '/seminar_attendances/1')
            ->seeJsonEquals([
                'status_code' => 200,
                'status' => 'OK',
                'message' => 'Resource successfully retrieved/created/modified',
                'data' => [
                    'id' => 1,
                    'seminar' => 'First seminar',
                    'start_date' => '2019-01-08',
                    'end_date' => '2019-01-09',
                    'ects_credits' => 1.2,
                    'student' => [
                        'id' => 1,
                        'first_name' => 'John',
                        'last_name' => 'Doe',
                        'e_mail' => 'john.doe@foo.com',
                        'phone' => '1234-567890',
                        'nationality' => 'GB',
                    ],
                ],
            ])
            ->seeStatusCode(200);

    }
    
    /**
     * Get seminar attendance by ID: failure.
     */
    public function testGetByIdFailure()
    {

        // Non existing
        $this->json('GET', '/seminar_attendances/9999')
            ->seeJsonEquals([
                'status_code' => 404,
                'status' => 'Not Found',
                'message' => 'Resource(s) not found',
            ])
            ->seeStatusCode(404);

        // Invalid ID
        $this->json('GET', '/seminar_attendances/abc')
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
     * Get student's seminar attendances: success.
     */
    public function testGetRelatedToStudent()
    {

        // Existing
        $this->json('GET', '/students/1/seminar_attendances')
            ->seeJsonEquals([
                'status_code' => 200,
                'status' => 'OK',
                'message' => 'Resource(s) found',
                'data' => [
                    [
                        'id' => 1,
                        'seminar' => 'First seminar',
                        'start_date' => '2019-01-08',
                        'end_date' => '2019-01-09',
                        'ects_credits' => 1.2,
                    ]
                ],
            ])
            ->seeStatusCode(200);

    }

    /**
     * Get student's seminar attendances: failure.
     */
    public function testGetRelatedToStudentFailure()
    {

        // Non existing seminar attendances
        $this->json('GET', '/students/2/seminar_attendances')
            ->seeJsonEquals([
                'status_code' => 404,
                'status' => 'Not Found',
                'message' => 'Resource(s) not found',
            ])
            ->seeStatusCode(404);

        // Non existing student
        $this->json('GET', '/students/999/seminar_attendances')
            ->seeJsonEquals([
                'status_code' => 404,
                'status' => 'Not Found',
                'message' => 'Resource(s) not found',
            ])
            ->seeStatusCode(404);

        // Invalid student ID
        $this->json('GET', '/students/abc/seminar_attendances')
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
     * Delete a seminar attendance: success.
     */
    public function testDeleteById()
    {

        // Existing annotation
        $this->json('DELETE', '/seminar_attendances/1')
            ->seeJsonEquals([
                'status_code' => 200,
                'status' => 'OK',
                'message' => 'Resource deleted',
            ])
            ->seeStatusCode(200)
            ->seeInDatabase('seminar_attendances', ['id' => 1, 'deleted_at' => date('Y-m-d H:i:s')])
            ->notSeeInDatabase('seminar_attendances', ['id' => 1, 'deleted_at' => null]);

    }

    /**
     * Delete a seminar attendance: failure.
     */
    public function testDeleteByIdFailure()
    {

        // Non existing annotation
        $this->json('DELETE', '/seminar_attendances/999')
            ->seeJsonEquals([
                'status_code' => 404,
                'status' => 'Not Found',
                'message' => 'Resource(s) not found',
            ])
            ->seeStatusCode(404)
            ->notSeeInDatabase('seminar_attendances', ['id' => 999]);

        // Invalid ID
        $this->json('DELETE', '/seminar_attendances/abc')
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
            ->notSeeInDatabase('seminar_attendances', ['id' => 'abc']);

    }
    
}