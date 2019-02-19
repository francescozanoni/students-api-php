<?php
declare(strict_types = 1);

namespace App\Http\Middleware\Traits;

use App\Services\OpenApiValidator;

/**
 * Trait UsesOpenApiValidator
 *
 * @package App\Http\Middleware\Traits
 */
trait UsesOpenApiValidator
{

    /**
     * @var OpenApiValidator
     */
    protected $openApiValidator;

    /**
     * UsesOpenApiValidator constructor.
     *
     * @param OpenApiValidator $openApiValidator
     */
    public function __construct(OpenApiValidator $openApiValidator)
    {
        $this->openApiValidator = $openApiValidator;
    }

}
