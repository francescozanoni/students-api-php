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
    
    public function testResponseValidation()
    {
    
        // OpenAPI schema is altered to make response validation fail.
        $newSchemaFileContent = file_get_contents($this->app['config']['openapi.schema_file_path']);
        $newSchemaFileContent = str_replace("type: boolean", "type: integer # boolean", $newSchemaFileContent);
        file_put_contents($this->app['config']['openapi.schema_file_path'], $newSchemaFileContent);
        
        $this->json('GET', '/internships/1')
            ->seeJsonEquals([
                'status_code' => 500,
                'status' => 'Internal Server Error',
                'message' => 'An internal server error occurred',
                'data' => [
                    'data.is_optional' => [
            "code error_type",
            "value ",
            "in body",
            "expected integer",
            "used boolean"
        ],
        "data.is_interrupted" => [
            "code error_type",
            "value ",
            "in body",
            "expected integer",
            "used boolean"
            ]
                ]
            ])
            ->seeStatusCode(500);
            
        // Original OpenAPI schema is restored.
        $newSchemaFileContent = str_replace("type: integer # boolean", "type: boolean", $newSchemaFileContent);
        file_put_contents($this->app['config']['openapi.schema_file_path'], $newSchemaFileContent);
    }

}