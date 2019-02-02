<?php
declare(strict_types = 1);

class StagesTest extends TestCase
{

    /**
     * Get all stages.
     */
    public function testGet()
    {

        $this->json('GET', '/stages')
            ->seeJsonEquals([
                'status_code' => 200,
                'status' => 'OK',
                'message' => 'Resource(s) found',
                'data' => [
                    [
                        'id' => 1,
                        'location' => 'Location 1',
                        'sub_location' => 'Sub-location 1',
                        'student' => [
                            'id' => 1,
                            'first_name' => 'John',
                            'last_name' => 'Doe',
                            'e_mail' => 'john.doe@foo.com',
                            'phone' => '1234-567890',
                            'nationality' => 'GB',
                        ],
                        'start_date' => '2019-01-10',
                        'end_date' => '2019-01-24',
                        'hour_amount' => 123,
                        'other_amount' => 5,
                        'is_optional' => false,
                        'is_interrupted' => false
                    ],
                ]
            ])
            ->seeStatusCode(200);

    }

    /**
     * Get stage by ID.
     */
    public function testGetById()
    {

        // Existing
        $this->json('GET', '/stages/1')
            ->seeJsonEquals([
                'status_code' => 200,
                'status' => 'OK',
                'message' => 'Resource successfully retrieved/created/modified',
                'data' => [
                    'id' => 1,
                    'location' => 'Location 1',
                    'sub_location' => 'Sub-location 1',
                    'student' => [
                        'id' => 1,
                        'first_name' => 'John',
                        'last_name' => 'Doe',
                        'e_mail' => 'john.doe@foo.com',
                        'phone' => '1234-567890',
                        'nationality' => 'GB',
                    ],
                    'start_date' => '2019-01-10',
                    'end_date' => '2019-01-24',
                    'hour_amount' => 123,
                    'other_amount' => 5,
                    'is_optional' => false,
                    'is_interrupted' => false
                ],
            ])
            ->seeStatusCode(200);

    }

    /**
     * Get stage by ID: failure.
     */
    public function testGetByIdFailure()
    {

        // Non existing
        $this->json('GET', '/stages/9999')
            ->seeJsonEquals([
                'status_code' => 404,
                'status' => 'Not Found',
                'message' => 'Resource(s) not found',
            ])
            ->seeStatusCode(404);

        // Invalid ID
        $this->json('GET', '/stages/abc')
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
     * Get student's stages: success.
     */
    public function testGetRelatedToStudent()
    {

        // Existing
        $this->json('GET', '/students/1/stages')
            ->seeJsonEquals([
                'status_code' => 200,
                'status' => 'OK',
                'message' => 'Resource(s) found',
                'data' => [
                    [
                        'id' => 1,
                        'location' => 'Location 1',
                        'sub_location' => 'Sub-location 1',
                        'start_date' => '2019-01-10',
                        'end_date' => '2019-01-24',
                        'hour_amount' => 123,
                        'other_amount' => 5,
                        'is_optional' => false,
                        'is_interrupted' => false
                    ]
                ],
            ])
            ->seeStatusCode(200);

    }

    /**
     * Get student's stages: failure.
     */
    public function testGetRelatedToStudentFailure()
    {

        // Non existing stages
        $this->json('GET', '/students/2/stages')
            ->seeJsonEquals([
                'status_code' => 404,
                'status' => 'Not Found',
                'message' => 'Resource(s) not found',
            ])
            ->seeStatusCode(404);

        // Non existing student
        $this->json('GET', '/students/999/stages')
            ->seeJsonEquals([
                'status_code' => 404,
                'status' => 'Not Found',
                'message' => 'Resource(s) not found',
            ])
            ->seeStatusCode(404);

        // Invalid student ID
        $this->json('GET', '/students/abc/stages')
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
     * Create a student's stage: success.
     */
    public function testCreateRelatedToStudent()
    {

        // Existing student
        $this->json('POST',
            '/students/1/stages',
            [
                'location' => 'Location 1',
                'sub_location' => 'Sub-location 1',
                'start_date' => '2019-01-25',
                'end_date' => '2019-01-31',
                'hour_amount' => 0,
                'other_amount' => 0,
                'is_optional' => true,
                'is_interrupted' => false
            ]
        )
            ->seeJsonEquals([
                'status_code' => 200,
                'status' => 'OK',
                'message' => 'Resource successfully retrieved/created/modified',
                'data' => [
                    'id' => 2,
                    'location' => 'Location 1',
                    'sub_location' => 'Sub-location 1',
                    'start_date' => '2019-01-25',
                    'end_date' => '2019-01-31',
                    'hour_amount' => 0,
                    'other_amount' => 0,
                    'is_optional' => true,
                    'is_interrupted' => false
                ],
            ])
            ->seeStatusCode(200)
            ->seeInDatabase('stages', ['id' => 2, 'student_id' => 1, 'location_id' => 1, 'sub_location_id' => 1, 'deleted_at' => null])
            ->notSeeInDatabase('stages', ['id' => 3]);

    }

    /**
     * Delete a stage: success.
     */
    public function testDeleteById()
    {

        // Existing stage
        $this->json('DELETE', '/stages/1')
            ->seeJsonEquals([
                'status_code' => 200,
                'status' => 'OK',
                'message' => 'Resource deleted',
            ])
            ->seeStatusCode(200)
            ->seeInDatabase('stages', ['id' => 1, 'deleted_at' => date('Y-m-d H:i:s')])
            ->notSeeInDatabase('stages', ['id' => 1, 'deleted_at' => null]);

    }

    /**
     * Delete a stage: failure.
     */
    public function testDeleteByIdFailure()
    {

        // Non existing stage
        $this->json('DELETE', '/stages/999')
            ->seeJsonEquals([
                'status_code' => 404,
                'status' => 'Not Found',
                'message' => 'Resource(s) not found',
            ])
            ->seeStatusCode(404)
            ->notSeeInDatabase('stages', ['id' => 999]);

        // Invalid ID
        $this->json('DELETE', '/stages/abc')
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
            ->notSeeInDatabase('stages', ['id' => 'abc']);

    }

}
