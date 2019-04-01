<?php
declare(strict_types = 1);

use Illuminate\Support\Facades\Artisan;

class ConfigureOpenApiSchemaTest extends TestCase
{

    public function testSuccess()
    {
        $originalFileContent = file_get_contents(__DIR__ . '/../../public/openapi.yaml');

        unlink(__DIR__ . '/../../public/openapi.yaml');
        copy(__DIR__ . '/fake_openapi.yaml', __DIR__ . '/../../public/openapi.yaml');

        $newFileContent = file_get_contents(__DIR__ . '/../../public/openapi.yaml');

        $this->assertNotEquals($originalFileContent, $newFileContent);

        Artisan::call('openapi:configure');

        $newFileContent = file_get_contents(__DIR__ . '/../../public/openapi.yaml');

        $this->assertEquals($originalFileContent, $newFileContent);
    }

}