<?php
declare(strict_types = 1);

namespace App\Services;

use HKarlstrom\Middleware\OpenApiValidation;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

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
    private $realValidator;

    /**
     * @var Psr7Service
     */
    private $psr7Service;

    /**
     * OpenApiValidator constructor.
     *
     * @param string $openApiSchemaFilePath
     * @param Psr7Service $psr7Service class that converts Laravel/Lumen requests/responses to PSR-7 requests/responses
     */
    public function __construct(string $openApiSchemaFilePath, Psr7Service $psr7Service)
    {
        $this->realValidator = new OpenApiValidation($openApiSchemaFilePath);
        $this->psr7Service = $psr7Service;
    }

    /**
     * Validate a request against OpenAPI schema.
     *
     * @param Request|RequestInterface $request
     * @param string $path
     * @param string $method
     * @param array $pathParameters
     *
     * @ throws ValidationException --> "@ throws" is disabled because ValidationException::withMessages() method's
     *                                  return type is not explicit within source code, therefore IDEs could complain
     *                                  with the following warning:
     *                                  "Exception 'ValidationException' is never thrown in the function"
     */
    public function validateRequest($request, string $path, string $method, array $pathParameters)
    {

        // @todo remove $path, $method and $pathParameters from input (if possible)

        if (($request instanceof RequestInterface) === false) {
            $request = $this->psr7Service->getRequest($request);
        }

        // @todo fix situation of URL http://localhost/students/:
        //  - considered as students path by Laravel/Lumen routing
        //  - considered as /students/ path by PSR-7 requests <-- THE TRAILING SLASH MAKES ROUTE RESOLVING DIFFERENT
        // This makes Laravel/Lumen and HKarlstrom\OpenApiReader route resolving incompatible.
        // @see class Illuminate\Http\Request, method path()
        // THIS IS LIKELY RELATED ONLY TO PHP BUILT-IN WEB SERVER,
        // BECAUSE .htaccess (WHEN ENABLED) REDIRECTS students/ TO students

        $errors = $this->realValidator->validateRequest($request, $path, $method, $pathParameters);

        if (empty($errors) === false) {
            throw ValidationException::withMessages($this->getFormattedErrors($errors));
        }

    }

    /**
     * Validate a response against OpenAPI schema.
     *
     * @param mixed $response
     * @param string $path
     * @param string $method
     *
     * @ throws ValidationException --> "@ throws" is disabled because ValidationException::withMessages() method's
     *                                  return type is not explicit within source code, therefore IDEs could complain
     *                                  with the following warning:
     *                                  "Exception 'ValidationException' is never thrown in the function"
     */
    public function validateResponse($response, string $path, string $method)
    {

        // @todo remove $path and $method from input (if possible)

        // Response object is cloned because validator methods use it by reference.
        $_response_1 = clone $response;
        $_response_2 = clone $response;

        if (($response instanceof ResponseInterface) === false) {
            $_response_1 = $this->psr7Service->getResponse($response);
            $_response_2 = $this->psr7Service->getResponse($response);
        }

        $errors = array_merge(
            $this->realValidator->validateResponseBody($_response_1, $path, $method),
            $this->realValidator->validateResponseHeaders($_response_2, $path, $method)
        );

        if (empty($errors) === false) {
            throw ValidationException::withMessages($this->getFormattedErrors($errors));
        }

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