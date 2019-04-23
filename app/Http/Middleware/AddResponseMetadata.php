<?php
declare(strict_types = 1);

namespace App\Http\Middleware;

use App\Services\OpenApi\MetadataManager;
use Illuminate\Http\JsonResponse;

/**
 * Class AddResponseMetadata
 * @package App\Http\Middleware
 */
class AddResponseMetadata
{

    /**
     * @var MetadataManager
     */
    private $metadataManager;

    /**
     * AddResponseMetadata constructor.
     *
     * @param MetadataManager $metadataManager
     */
    public function __construct(MetadataManager $metadataManager)
    {
        $this->metadataManager = $metadataManager;
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

        $metadata = $this->metadataManager->getMetadata($request, $response);

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

}