<?php
declare(strict_types = 1);

namespace App\Http\Middleware\Traits;

use App\Services\OpenApi\Validator;

/**
 * Trait UsesOpenApiValidator
 *
 * @package App\Http\Middleware\Traits
 */
trait UsesOpenApiValidator
{

    /**
     * @var Validator
     */
    protected $openApiValidator;

    /**
     * UsesOpenApiValidator constructor.
     *
     * @param Validator $openApiValidator
     */
    public function __construct(Validator $openApiValidator)
    {
        $this->openApiValidator = $openApiValidator;
    }

}
