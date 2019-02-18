<?php
declare(strict_types = 1);

namespace App\Services;

use Illuminate\Http\Request;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Bridge\PsrHttpMessage\Factory\PsrHttpFactory;
use Symfony\Component\HttpFoundation\Response;
use Tuupola\Http\Factory\ResponseFactory;
use Tuupola\Http\Factory\ServerRequestFactory;
use Tuupola\Http\Factory\StreamFactory;
use Tuupola\Http\Factory\UploadedFileFactory;

/**
 * Class Psr7Service
 * @package App\Services
 *
 * This class transforms Laravel/Lumen requests/responses to PSR-7 requests/responses.
 */
class Psr7Service
{

    /**
     * @var PsrHttpFactory
     */
    protected $factory;

    public function __construct()
    {

        // Shamelessly taken from
        // https://github.com/symfony/psr-http-message-bridge/blob/master/Tests/Factory/PsrHttpFactoryTest.php
        $this->factory =
            new PsrHttpFactory (
                new ServerRequestFactory(),
                new StreamFactory(),
                new UploadedFileFactory(),
                new ResponseFactory()
            );

    }

    /**
     * Convert Laravel/Lumen request to PSR-7 request.
     *
     * @param Request $request
     *
     * @return ServerRequestInterface
     */
    public function getRequest(Request $request) : ServerRequestInterface
    {
        return $this->factory->createRequest($request);
    }

    /**
     * Convert Laravel/Lumen response to PSR-7 response.
     *
     * @param Response $response
     *
     * @return ResponseInterface
     */
    public function getResponse(Response $response) : ResponseInterface
    {
        return $this->factory->createResponse($response);
    }

}
