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
    protected $description = 'Configure OpenAPI schema file according to application settings';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        // @todo add file existence check and writability

        $schemaAsArray = Yaml::parseFile(config('openapi.schema_file_path'));

        // @todoadd URL configuration (currently within scripts/setup.php)

        $internshipEvaluationItemDefinitions = config('internships.evaluations.items');
        $internshipEvaluationItemExamples = \EvaluationsTableSeeder::generateItemValues($internshipEvaluationItemDefinitions);

        if (empty($internshipEvaluationItemDefinitions) === true) {
            return;
        }

        # Evaluation model is updated
        foreach ($internshipEvaluationItemDefinitions as $index => $item) {
            $schemaAsArray['components']['schemas']['NewEvaluation']['properties'][$item['name']] = ['type' => 'string', 'enum' => $item['values']];
            if ($item['required'] === true) {
                $schemaAsArray['components']['schemas']['NewEvaluation']['required'][] = $item['name'];
            }
        }

        # Evaluation examples are updated
        foreach ($internshipEvaluationItemExamples as $name => $value) {
            $schemaAsArray['components']['responses']['Evaluations']['content']['application/json']['schema']['example']['data'][0][$name] = $value;
            $schemaAsArray['components']['responses']['Evaluations']['content']['application/json']['schema']['example']['data'][1][$name] = $value;
            $schemaAsArray['components']['responses']['Evaluation']['content']['application/json']['schema']['example']['data'][$name] = $value;
            $schemaAsArray['components']['schemas']['NewEvaluation']['example'][$name] = $value;
            $schemaAsArray['components']['schemas']['Evaluation']['example'][$name] = $value;
        }

        file_put_contents(config('openapi.schema_file_path'), Yaml::dump($schemaAsArray, 999, 2));

        // $this->error($error);
        // $this->info('Wards imported successfully');

    }

}
