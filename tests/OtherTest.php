<?php
declare(strict_types = 1);

class OtherTest extends TestCase
{

    public function testRoot()
    {
        $this->json('GET', '/')
            ->seeStatusCode(302);
        // @todo test redirection destination URL and content
    }

    public function testException()
    {
        $this->json('GET', '/test')
            ->seeJsonEquals([
                'status_code' => 500,
                'status' => 'Internal Server Error',
                'message' => 'An internal server error occurred',
                'data' => [
                    'class' => 'Exception',
                    'message' => 'TEST EXCEPTION',
                    'file' => realpath(base_path('routes/web.php')),
                    'line' => 99
                ]
            ])
            ->seeStatusCode(500);
    }

    public function testUrlNotFound()
    {
        $this->json('GET', '/url_not_found')
            ->seeStatusCode(404);
    }

}
