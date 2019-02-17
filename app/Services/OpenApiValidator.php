<?php
declare(strict_types = 1);

namespace App\Services;

use HKarlstrom\Middleware\OpenApiValidation;
use Illuminate\Http\Request;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Symfony\Bridge\PsrHttpMessage\Factory\PsrHttpFactory;
use Tuupola\Http\Factory\ResponseFactory;
use Tuupola\Http\Factory\ServerRequestFactory;
use Tuupola\Http\Factory\StreamFactory;
use Tuupola\Http\Factory\UploadedFileFactory;
use Zend\Diactoros\Response;

/**
 * Class OpenApiValidator
 * @package App\Services
 *
 * This class is a Laravel/Lumen-tailored wrapper of HKarlstrom\Middleware\OpenApiValidation class,
 * which handles only PSR-7 requests/responses.
 */
class OpenApiValidator
{

    /**
     * @var OpenApiValidation
     */
    private $validator;

    /**
     * OpenApiValidator constructor.
     *
     * @param string $openApiSchemaFilePath
     *
     * @throws \Exception if OpenAPI schema file path does not exist
     */
    public function __construct(string $openApiSchemaFilePath)
    {

        $this->validator = new OpenApiValidation($openApiSchemaFilePath);

    }

    /**
     * Validate a request against OpenAPI schema.
     *
     * @param mixed $request
     * @param string $path
     * @param string $method
     * @param array $pathParameters
     *
     * @return array validation errors, e.g. Array (
     *                                         [id] => Array (
     *                                           [in] => body
     *                                           [code] => error_type
     *                                           [value] => a
     *                                           [expected] => integer
     *                                           [used] => string
     *                                         )
     *                                       )
     *
     */
    public function validateRequest($request, string $path, string $method, array $pathParameters) : array
    {

        // @todo remove $path, $method and $pathParameters from input (if possible)

        if (($request instanceof ServerRequestInterface) === false) {
            $request = $this->getPsr7Request($request);
        }

        // @todo fix situation of URL http://localhost/students/:
        //  - considered as students path by Laravel/Lumen routing
        //  - considered as /students/ path by PSR-7 requests <-- THE TRAILING SLASH MAKES ROUTE RESOLVING DIFFERENT
        // This makes Laravel/Lumen and HKarlstrom\OpenApiReader route resolving incompatible.
        // @see class Illuminate\Http\Request, method path()
        // THIS IS LIKELY RELATED ONLY TO PHP BUILT-IN WEB SERVER,
        // BECAUSE .htaccess (WHEN ENABLED) REDIRECTS students/ TO students

        $errors = $this->validator->validateRequest($request, $path, $method, $pathParameters);

        return $this->getFormattedErrors($errors);

    }

    /**
     * Validate a response against OpenAPI schema.
     *
     * @param mixed $response
     * @param string $path
     * @param string $method
     *
     * @return array validation errors, e.g. Array (
     *                                         [id] => Array (
     *                                           [in] => body
     *                                           [code] => error_type
     *                                           [value] => a
     *                                           [expected] => integer
     *                                           [used] => string
     *                                         )
     *                                       )
     *
     */
    public function validateResponse($response, string $path, string $method) : array
    {

        // @todo remove $path and $method from input (if possible)

        // Response object is cloned because validator methods use it by reference.
        $_response_1 = clone $response;
        $_response_2 = clone $response;

        if (($response instanceof ResponseInterface) === false) {
            $_response_1 = $this->getPsr7Response($response);
            $_response_2 = $this->getPsr7Response($response);
        }

        $errors = array_merge(
            $this->validator->validateResponseBody($_response_1, $path, $method),
            $this->validator->validateResponseHeaders($_response_2, $path, $method)
        );

        return $this->getFormattedErrors($errors);

    }

    /**
     * Convert Lumen request to PSR-7 request.
     *
     * @param Request $request
     *
     * @return ServerRequestInterface
     */
    protected function getPsr7Request(Request $request) : ServerRequestInterface
    {

        // Shamelessly taken from
        // https://github.com/symfony/psr-http-message-bridge/blob/master/Tests/Factory/PsrHttpFactoryTest.php

        $factory = new PsrHttpFactory (
            new ServerRequestFactory(),
            new StreamFactory(),
            new UploadedFileFactory(),
            new ResponseFactory()
        );

        return $factory->createRequest($request);

    }

    /**
     * Convert Lumen response to PSR-7 response.
     *
     * @param $response
     *
     * @return ResponseInterface
     */
    protected function getPsr7Response($response) : ResponseInterface
    {

        // Shamelessly taken from
        // https://github.com/symfony/psr-http-message-bridge/blob/master/Tests/Factory/PsrHttpFactoryTest.php

        $factory = new PsrHttpFactory (
            new ServerRequestFactory(),
            new StreamFactory(),
            new UploadedFileFactory(),
            new ResponseFactory()
        );

        return $factory->createResponse($response);

    }

    /**
     * Convert OpenApiValidation errors to Laravel/Lumen-like validation errors.
     *
     * @param array $originalErrors e.g. Array (
     *                                     [0] => Array (
     *                                       [name] => data.1.phone
     *                                       [code] => error_type
     *                                       [value] =>
     *                                       [in] => body
     *                                       [expected] => string
     *                                       [used] => null
     *                                     )
     *                                   )
     *
     * @return array e.g. Array (
     *                      [data.1.phone] => Array (
     *                        [0] => code error_type
     *                        [1] => value
     *                        [2] => in body
     *                        [3] => expected string
     *                        [4] => used null
     *                      )
     *                    )
     */
    protected function getFormattedErrors(array $originalErrors) : array
    {

        $errors = [];

        foreach ($originalErrors as $originalError) {
            $name = $originalError['name'];
            unset($originalError['name']);
            $errors[$name] = [];
            foreach ($originalError as $key => $value) {
                // In case error value is not a string, it is converted to JSON.
                // It should not happen, anyway.
                if (is_string($value) === false) {
                    $value = json_encode($value, JSON_PRETTY_PRINT | JSON_PARTIAL_OUTPUT_ON_ERROR);
                }
                $errors[$name][] = $key . ' ' . $value;
            }
        }

        return $errors;

    }

}