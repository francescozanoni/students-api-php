<?php
declare(strict_types = 1);

namespace App\Http\Middleware;

use HKarlstrom\OpenApiReader\OpenApiReader;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class AddResponseMetadata
 * @package App\Http\Middleware
 */
class AddResponseMetadata
{

    /**
     * @var array
     */
    private $metadataKeys = ['status_code', 'status', 'message'];

    /**
     * @var OpenApiReader
     */
    private $openApiReader;

    /**
     * AddResponseMetadata constructor.
     *
     * @param OpenApiReader $openApiReader
     */
    public function __construct(OpenApiReader $openApiReader)
    {
        $this->openApiReader = $openApiReader;
    }

    /**
     * Add response metadata
     *
     * @param $request
     * @param \Closure $next
     *
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {

        $response = $next($request);

        $metadata = $this->getMetadata($request, $response);

        if (empty($metadata) === false) {
            $data = json_decode($response->getContent(), true);
            $fullData = $metadata;
            if (empty($data) === false) {
                $fullData['data'] = $data;
            }
            if ($response instanceof JsonResponse) {
                $response->setData($fullData);
            } else {
                if (is_string($fullData) === false &&
                    (is_object($fullData) === true && method_exists($fullData, '__toString') === true) === false) {
                    $fullData = json_encode($fullData);
                }
                $response->setContent($fullData);
            }
        }

        return $response;

    }

    /**
     * Given the current request and response, get the related response metadata.
     *
     * @param Request $request
     * @param $response
     *
     * @return array e.g. Array (
     *                      [status_code] => 200
     *                      [status] => OK
     *                      [message] => Resource(s) found
     *                    )
     *
     * Currently this method is public because App\Exceptions\Handler uses it.
     * @todo find a better way to share this logic
     */
    public function getMetadata(Request $request, $response) : array
    {

        // Step 1: create the OpenAPI reader, which then searches the schema for the requested response.
        $path = (string)app('current_route_path');
        $httpMethod = strtolower($request->method());
        $httpCode = $response->getStatusCode();

        // Step 2: search for the requested response.
        $responseSchema = $this->openApiReader->getOperationResponse($path, $httpMethod, $httpCode);

        if (empty($responseSchema) === true) {
            return [];
        }

        // Step 3: the actual response schema item is extracted from response.
        $responseSchema = $responseSchema->getContent()->schema;

        // Step 4: since metadata are extracted from response example, example must be available.
        if (isset($responseSchema['example']) === false ||
            is_array($responseSchema['example']) === false) {
            return [];
        }

        // Step 5: metadata are extracted from response example.
        // https://stackoverflow.com/questions/4260086/php-how-to-use-array-filter-to-filter-array-keys
        $metadata = array_filter(
            $responseSchema['example'],
            function ($key) {
                return in_array($key, $this->metadataKeys);
            },
            ARRAY_FILTER_USE_KEY
        );

        return $metadata;

    }

}