<?php
declare(strict_types = 1);

namespace App\Console\Commands;

use App;
use Illuminate\Console\Command;
use Symfony\Component\Yaml\Yaml;

class ConfigureOpenApiSchema extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'openapi:configure';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Configure OpenAPI schema with application settings (base URL and internship evaluation items)';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        if (file_exists(config('openapi.example_schema_file_path')) === false ||
            is_readable(config('openapi.example_schema_file_path')) === false) {
            $this->error('OpenAPI schema example file does not exist or is not readable');
            return;
        }
        if (file_exists(config('openapi.schema_file_path')) === true &&
            is_writable(config('openapi.schema_file_path')) === false) {
            $this->error('OpenAPI schema file is not writable');
            return;
        }

        $schemaAsArray = Yaml::parseFile(config('openapi.example_schema_file_path'));
        
        $schemaAsArray = $this->setBaseUrl($schemaAsArray);
        $schemaAsArray = $this->setInternshipEvaluationItems($schemaAsArray);
        
        $schemaAsString = Yaml::dump($schemaAsArray, 999, 2);
        
        file_put_contents(config('openapi.schema_file_path'), $schemaAsString);

        $this->info('OpenAPI schema configured successfully');

    }
    
    /**
     * Base URL configuration.
     *
     * @param array $schema
     *
     * @return array
     */
    private function setBaseUrl(array $schema) : array
    {
        $schema['servers'][0]['url'] = config('app.url');
        return $schema;
    }
    
    /**
     * Internship evaluation item configuration.
     *
     * @param array $schema
     *
     * @return array
     */
    private function setInternshipEvaluationItems(array $schema) : array
    {
        $itemDefinitions = config('internships.evaluations.items');

        if (empty($itemDefinitions) === true) {
            return $schema;
        }
        
        $itemExamples = \EvaluationsTableSeeder::generateItemValues($itemDefinitions);

        // Evaluation model is updated
        foreach ($itemDefinitions as $index => $item) {
            $schema['components']['schemas']['NewEvaluation']['properties'][$item['name']] = ['type' => 'string', 'enum' => $item['values']];
            if ($item['required'] === true) {
                $schema['components']['schemas']['NewEvaluation']['required'][] = $item['name'];
            }
        }

        // Evaluation examples are updated
        foreach ($itemExamples as $name => $value) {
            $schema['components']['responses']['Evaluations']['content']['application/json']['schema']['example']['data'][0][$name] = $value;
            $schema['components']['responses']['Evaluations']['content']['application/json']['schema']['example']['data'][1][$name] = $value;
            $schema['components']['responses']['Evaluation']['content']['application/json']['schema']['example']['data'][$name] = $value;
            $schema['components']['schemas']['NewEvaluation']['example'][$name] = $value;
            $schema['components']['schemas']['Evaluation']['example'][$name] = $value;
        }
        
        return $schema;
    }

}
