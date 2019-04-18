<?php
declare(strict_types = 1);

class AuditsTest extends TestCase
{

    /**
     * Get annotation(s) with audits.
     */
    public function testGet()
    {
        $this->json('GET', '/annotations?with_audits=true')
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
                        "audits" => [
                [
                    "id"=> 6,
                    "event"=> "created",
                    "new_values" => [
                        "title"=> "First title",
                        "content"=> "First content",
                        "student_id"=> 1,
                        "id"=> 1
                    ],
                    "user_id"=> 0,
                    "created_at"=> "2019-01-01 01:00:00"
                ],
                ],
                    ],
                ]
            ])
            ->seeStatusCode(200);
    }

}