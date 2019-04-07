<?php
declare(strict_types = 1);

use Illuminate\Support\Facades\Artisan;

class ConfigureOpenApiSchemaTest extends TestCase
{

    /**
     * @var string real OpenAPI schema file path
     */
    private $originalSchemaFilePath;

    /**
     * @var string backup file path of real OpenAPI schema during test
     */
    private $backupSchemaFilePath;

    /**
     * @var string test OpenAPI schema file path (it contains the expected output of "openapi:configure" command)
     */
    private $testSchemaFilePath;

    public function setup() : void
    {
        parent::setup();

        $this->originalSchemaFilePath = $this->app['config']['openapi.schema_file_path'];
        $this->backupSchemaFilePath = $this->app['config']['openapi.schema_file_path'] . '.backup';
        $this->testSchemaFilePath = __DIR__ . '/test_openapi.yaml';

        $appUrl = $this->app['config']['app.url'];

        // Update test OpenAPI schema content and set it as real OpenAPI schema.
        $newSchemaFileContent = file_get_contents($this->testSchemaFilePath);
        $newSchemaFileContent = str_replace("url: 'http://localhost'", "url: '$appUrl'", $newSchemaFileContent);
        copy($this->originalSchemaFilePath, $this->backupSchemaFilePath);
        file_put_contents($this->originalSchemaFilePath, $newSchemaFileContent);
    }

    public function teardown() : void
    {
        // Restore original OpenAPI schema.
        unlink($this->originalSchemaFilePath);
        rename($this->backupSchemaFilePath, $this->originalSchemaFilePath);

        parent::teardown();
    }

    public function testSuccess()
    {
        Artisan::call('openapi:configure');

        $originalFileContent = file_get_contents($this->backupSchemaFilePath);
        $newFileContent = file_get_contents($this->originalSchemaFilePath);

        $this->assertEquals($originalFileContent, $newFileContent);
    }

}