<?php
/**
 * Created by PhpStorm.
 * User: Francesco.Zanoni
 * Date: 17/01/2019
 * Time: 10:46
 */
declare(strict_types = 1);

namespace App\Services;

use HKarlstrom\Middleware\OpenApiValidation;
use Illuminate\Http\Request;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Symfony\Bridge\PsrHttpMessage\Factory\PsrHttpFactory;
use Symfony\Component\Yaml\Yaml;
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
     * @var RequestHandlerInterface anonymous class used by OpenApiValidation->process()
     */
    private $requestHandler;

    /**
     * OpenApiValidator constructor.
     *
     * @param string $openApiSchemaFilePath
     *
     * @throws \Exception if OpenAPI schema file path does not exist
     */
    public function __construct(string $openApiSchemaFilePath)
    {

        $tmpSchemaFilePath = sys_get_temp_dir() . '/' . date('YmdHis') . '.json';

        // @todo improve conversion of OpenAPI schema to JSON format
        $schema = Yaml::parseFile($openApiSchemaFilePath);
        $schema = json_encode($schema, JSON_PARTIAL_OUTPUT_ON_ERROR);
        file_put_contents($tmpSchemaFilePath, $schema);
        $this->validator = new OpenApiValidation($tmpSchemaFilePath, ['validateResponse' => false]);
        unlink($tmpSchemaFilePath);

        $this->requestHandler = new class implements RequestHandlerInterface
        {
            public function handle(ServerRequestInterface $request) : ResponseInterface
            {
                return new Response();
            }
        };

    }

    /**
     * Validate a request against OpenAPI schema.
     *
     * @param mixed $request
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
     * @throws \Exception if a combination of HTTP method and path is not found within OpenAPI schema
     */
    public function validateRequest($request) : array
    {

        if (($request instanceof ServerRequestInterface) === false) {
            $request = $this->getPsr7Request($request);
        }

        $errors = [];

        $response = $this->validator->process($request, $this->requestHandler);

        if ($response->getStatusCode() === 400) {

            /* Example of $r->getBody()->__toString() content,
             * related to request with URL http://localhost/students/a:
             *
             * {
             *   "message": "Request validation failed",
             *   "errors": [
             *     {
             *       "name": "id",
             *       "code": "error_type",
             *       "value": "a",
             *       "expected": "integer",
             *       "used": "string"
             *     }
             *   ]
             * }
             */

            $errors = json_decode($response->getBody()->__toString(), true)['errors'];

        }

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
        $_response = clone $response;

        if (($_response instanceof ResponseInterface) === false) {
            $_response = $this->getPsr7Response($_response);
        }

        $errors = array_merge(
            $this->validator->validateResponseBody($_response, $path, $method),
            $this->validator->validateResponseHeaders($_response, $path, $method)
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
     *                        [code] => error_type
     *                        [value] =>
     *                        [in] => body
     *                        [expected] => string
     *                        [used] => null
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
                $errors[$name][] = $key . ' ' . $value;
            }
        }

        return $errors;

    }

}