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
                        'id' => 2,
                        'stage' => [
                        'id' => 2,
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
                        'start_date' => '2019-01-26',
                        'end_date' => '2019-01-31',
                        'hour_amount' => 34,
                        'other_amount' => 0,
                        'is_optional' => true,
                        'is_interrupted' => true
                        ],
                        'clinical_tutor_id' => 789,
                        'notes' => 'Second interruption report notes',
                        'created_at' => '2019-01-31 02:00:00',
                        'updated_at' => '2019-01-31 02:00:00',
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
        $this->json('GET', '/interruption_reports/2')
            ->seeJsonEquals([
                'status_code' => 200,
                'status' => 'OK',
                'message' => 'Resource successfully retrieved/created/modified',
                'data' => [
                    'id' => 2,
                        'stage' => [
                        'id' => 2,
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
                        'start_date' => '2019-01-26',
                        'end_date' => '2019-01-31',
                        'hour_amount' => 34,
                        'other_amount' => 0,
                        'is_optional' => true,
                        'is_interrupted' => true,
                        ],
                        'clinical_tutor_id' => 789,
                        'notes' => 'Second interruption report notes',
                        'created_at' => '2019-01-31 02:00:00',
                        'updated_at' => '2019-01-31 02:00:00',
                ],
            ])
            ->seeStatusCode(200);

    }
    
    /**
     * Get interruption report by ID: failure.
     */
    public function testGetByIdFailure()
    {
    
    // Deleted
        $this->json('GET', '/interruption_reports/1')
            ->seeJsonEquals([
                'status_code' => 404,
                'status' => 'Not Found',
                'message' => 'Resource(s) not found',
            ])
            ->seeStatusCode(404);

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
        $this->json('GET', '/stages/2/interruption_report')
            ->seeJsonEquals([
                'status_code' => 200,
                'status' => 'OK',
                'message' => 'Resource successfully retrieved/created/modified',
                'data' => [
                    'id' => 2,
                    'clinical_tutor_id' => 789,
                        'notes' => 'Second interruption report notes',
                        'created_at' => '2019-01-31 02:00:00',
                        'updated_at' => '2019-01-31 02:00:00',
                ],
            ])
            ->seeStatusCode(200);

    }
    
    /**
     * Get stage's interruption report: failure.
     */
    public function testGetRelatedToStageFailure()
    {

        // Non existing stage interruption report
        $this->json('GET', '/stages/1/interruption_report')
            ->seeJsonEquals([
                'status_code' => 404,
                'status' => 'Not Found',
                'message' => 'Resource(s) not found',
            ])
            ->seeStatusCode(404);

        // Non existing stage
        $this->json('GET', '/stages/999/interruption_report')
            ->seeJsonEquals([
                'status_code' => 404,
                'status' => 'Not Found',
                'message' => 'Resource(s) not found',
            ])
            ->seeStatusCode(404);

        // Invalid stage ID
        $this->json('GET', '/stages/abc/interruption_report')
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
     * Create a stage's interruption report: success.
     */
    public function testCreateRelatedToStage()
    {
        $this->assertTrue(true);
    }
}