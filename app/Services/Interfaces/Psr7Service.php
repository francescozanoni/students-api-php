<?php
declare(strict_types = 1);

namespace App\Services\Interfaces;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Interface Psr7Service
 * @package App\Services\Interfaces
 *
 * This interface describes classes transforming requests/responses to PSR-7 requests/responses.
 */
interface Psr7Service
{

    /**
     * Convert request to PSR-7 request.
     *
     * @param $request
     *
     * @return ServerRequestInterface
     */
    public function getRequest($request) : ServerRequestInterface;

    /**
     * Convert response to PSR-7 response.
     *
     * @param $response
     *
     * @return ResponseInterface
     */
    public function getResponse($response) : ResponseInterface;

}