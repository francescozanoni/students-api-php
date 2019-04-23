<?php
declare(strict_types = 1);

namespace App\Services\OpenApi;

use HKarlstrom\OpenApiReader\OpenApiReader;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class MetadataManager
 * @package App\Services\OpenApi
 *
 * Given the current request and response, find the suitable metadata to be appended to response body.
 */
class MetadataManager
{

    /**
     * @var OpenApiReader
     */
    private $openApiReader;

    /**
     * @var array
     */
    private $metadataKeys = ['status_code', 'status', 'message'];

    /**
     * MetadataManager constructor.
     *
     * @param OpenApiReader $openApiReader
     */
    public function __construct(OpenApiReader $openApiReader)
    {
        $this->openApiReader = $openApiReader;
    }

    /**
     * Given the current request and response, get the related response metadata.
     *
     * @param Request $request
     * @param Response $response
     *
     * @return array e.g. Array (
     *                      [status_code] => 200
     *                      [status] => OK
     *                      [message] => Resource(s) found
     *                    )
     */
    public function getMetadata(Request $request, Response $response) : array
    {

        // Step 0: extract from input required information to find metadata.
        // @todo extract method
        $path = (string)app('current_route_path');
        $httpMethod = strtolower($request->method());
        $httpCode = $response->getStatusCode();

        // Step 1: search for the requested response.
        $responseSchema = $this->openApiReader->getOperationResponse($path, $httpMethod, $httpCode);

        if (empty($responseSchema) === true) {
            return [];
        }

        // Step 2: the actual response schema item is extracted from response.
        $responseSchema = $responseSchema->getContent()->schema;

        // Step 3: since metadata are extracted from response example, example must be available.
        if (isset($responseSchema['example']) === false ||
            is_array($responseSchema['example']) === false) {
            return [];
        }

        // Step 4: metadata are extracted from response example.
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