<?php
declare(strict_types = 1);

class InterruptionReportsTest extends TestCase
{

    /**
     * Get all interruption reports.
     */
    public function testGet()
    {
        $this->json('GET', '/interruption_reports')
            ->seeJsonEquals([
                'status_code' => 200,
                'status' => 'OK',
                'message' => 'Resource(s) found',
                'data' => [
                    [
                        'id' => 1,
                        'stage' => [
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
                        'is_interrupted' => false,
                        ],
                        'clinical_tutor_id' => 456,
                        'notes' => 'First interruption report notes',
                        'created_at' => '2019-01-25 02:00:00',
                        'updated_at' => '2019-01-25 02:00:00',
                    ],
                ]
            ])
            ->seeStatusCode(200);
    }
    
    /**
     * Get interruption report by ID.
     */
    public function testGetById()
    {

        // Existing
        $this->json('GET', '/interruption_reports/1')
            ->seeJsonEquals([
                'status_code' => 200,
                'status' => 'OK',
                'message' => 'Resource successfully retrieved/created/modified',
                'data' => [
                    'id' => 1,
                        'stage' => [
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
                        'is_interrupted' => false,
                        ],
                        'clinical_tutor_id' => 456,
                        'notes' => 'First interruption report notes',
                        'created_at' => '2019-01-25 02:00:00',
                        'updated_at' => '2019-01-25 02:00:00',
                ],
            ])
            ->seeStatusCode(200);

    }
    
    /**
     * Get interruption report by ID: failure.
     */
    public function testGetByIdFailure()
    {

        // Non existing
        $this->json('GET', '/interruption_reports/9999')
            ->seeJsonEquals([
                'status_code' => 404,
                'status' => 'Not Found',
                'message' => 'Resource(s) not found',
            ])
            ->seeStatusCode(404);

        // Invalid ID
        $this->json('GET', '/interruption_reports/abc')
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
     * Get stage's interruption report: success.
     */
    public function testGetRelatedToStage()
    {

        // Existing
        $this->json('GET', '/stages/1/interruption_report')
            ->seeJsonEquals([
                'status_code' => 200,
                'status' => 'OK',
                'message' => 'Resource successfully retrieved/created/modified',
                'data' => [
                    'id' => 1,
                    'clinical_tutor_id' => 456,
                    'notes' => 'First interruption report notes',
                    'created_at' => '2019-01-25 02:00:00',
                    'updated_at' => '2019-01-25 02:00:00',
                ],
            ])
            ->seeStatusCode(200);

    }
    
}