<?php
declare(strict_types = 1);

namespace App\Http\Middleware;

use App\Services\OpenApi\MetadataManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
     * @param Request $request
     * @param \Closure $next
     *
     * @return mixed
     */
    public function handle(Request $request, \Closure $next)
    {

        $response = $next($request);

        $metadata = $this->metadataManager->getMetadata($request, $response);

        if (empty($metadata) === true) {
            return $response;
        }
        
        $data = json_decode($response->getContent(), true);
        $fullData = $metadata;
        
        if (empty($data) === false) {
            $fullData['data'] = $data;
        }
        
        if ($response instanceof JsonResponse) {
            $response->setData($fullData);
        } else {
            $response->setContent($this->isNotStringifable($fullData) === true ? json_encode($fullData) : $fullData);
        }

        return $response;

    }
    
    private function isNotStringifable($value) : bool
    {
        return is_string($value) === false &&
                (is_object($value) === true && method_exists($value, '__toString') === true) === false;
    }

}