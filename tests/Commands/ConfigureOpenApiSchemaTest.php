<?php
declare(strict_types = 1);

use Illuminate\Support\Facades\Artisan;

class ConfigureOpenApiSchemaTest extends TestCase
{

    /**
     * @var string real OpenAPI schema file path
     */
    private $schemaFilePath;

    /**
     * @var string example OpenAPI schema file path
     */
    private $exampleSchemaFilePath;

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

        $this->schemaFilePath = $this->app['config']['openapi.schema_file_path'];
        $this->exampleSchemaFilePath = $this->app['config']['openapi.example_schema_file_path'];
        $this->backupSchemaFilePath = $this->app['config']['openapi.schema_file_path'] . '.backup';
        $this->testSchemaFilePath = __DIR__ . '/test_openapi.yaml';

        $appUrl = $this->app['config']['app.url'];

        // Update test OpenAPI schema content and set it as real OpenAPI schema.
        $newSchemaFileContent = file_get_contents($this->testSchemaFilePath);
        $newSchemaFileContent = str_replace("url: 'http://localhost'", "url: '$appUrl'", $newSchemaFileContent);
        copy($this->schemaFilePath, $this->backupSchemaFilePath);
        file_put_contents($this->schemaFilePath, $newSchemaFileContent);
    }

    public function teardown() : void
    {
        // Restore original OpenAPI schema.
        unlink($this->schemaFilePath);
        rename($this->backupSchemaFilePath, $this->schemaFilePath);

        parent::teardown();
    }

    public function testSuccess()
    {
        Artisan::call('openapi:configure');

        $schemaFileContent = file_get_contents($this->backupSchemaFilePath);
        $newSchemaFileContent = file_get_contents($this->schemaFilePath);

        $this->assertEquals($schemaFileContent, $newSchemaFileContent);
    }

    public function testFailureMissingExampleSchema()
    {
        rename($this->exampleSchemaFilePath, $this->exampleSchemaFilePath . '_123456');

        Artisan::call('openapi:configure');

        $schemaFileContent = file_get_contents($this->backupSchemaFilePath);
        $newSchemaFileContent = file_get_contents($this->schemaFilePath);

        rename($this->exampleSchemaFilePath . '_123456', $this->exampleSchemaFilePath);

        $this->assertNotEquals($schemaFileContent, $newSchemaFileContent);
    }

    public function testFailureMissingSchema()
    {
        rename($this->schemaFilePath, $this->schemaFilePath . '_123456');

        Artisan::call('openapi:configure');

        $schemaFileContent = file_get_contents($this->backupSchemaFilePath);
        $newSchemaFileContent = file_get_contents($this->schemaFilePath . '_123456');

        rename($this->schemaFilePath . '_123456', $this->schemaFilePath);

        $this->assertNotEquals($schemaFileContent, $newSchemaFileContent);
    }

}