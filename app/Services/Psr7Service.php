<?php
declare(strict_types = 1);

namespace App\Services;

use App\Services\Interfaces\Psr7Service as Psr7ServiceInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Bridge\PsrHttpMessage\Factory\PsrHttpFactory;
use Symfony\Bridge\PsrHttpMessage\HttpMessageFactoryInterface;
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
class Psr7Service implements Psr7ServiceInterface
{

    /**
     * @var HttpMessageFactoryInterface
     */
    protected $factory;

    /**
     * Psr7Service constructor.
     */
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
     * @param $request
     *
     * @return ServerRequestInterface
     */
    public function getRequest($request) : ServerRequestInterface
    {
        return $this->factory->createRequest($request);
    }

    /**
     * Convert Laravel/Lumen response to PSR-7 response.
     *
     * @param $response
     *
     * @return ResponseInterface
     */
    public function getResponse($response) : ResponseInterface
    {
        return $this->factory->createResponse($response);
    }

}
