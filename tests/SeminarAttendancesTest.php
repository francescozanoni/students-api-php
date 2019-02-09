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
                            'nationality' => 'GB'
                        ]
                    ]
                ]
            ])
            ->seeStatusCode(200);

    }
    
}