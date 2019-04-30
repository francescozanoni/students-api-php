<?php
declare(strict_types = 1);

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Handler extends ExceptionHandler
{

    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception $exception
     *
     * @throws Exception
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Exception $exception
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {

        foreach ($this->dontReport as $exceptionClassNotToReport) {
            if ($exception instanceof $exceptionClassNotToReport) {
                return parent::render($request, $exception);
            }
        }

        $content = [
            'class' => get_class($exception),
            'message' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
        ];

        $response = new \Illuminate\Http\JsonResponse(null, 500);

        // Since AddResponseMetadata and PrettyPrint middlewares are not executed,
        // their logic is here re-applied manually on the error response.
        $metadata = app('App\Services\OpenApi\MetadataManager')->getMetadata($request, $response);
        if (empty($metadata) === true) {
            // @todo retrieve metadata from OpenAPI schema (components -> responses -> InternalServerError)
            $metadata = [
                'status_code' => 500,
                'status' => 'Internal Server Error',
                'message' => 'An internal server error occurred',
            ];
        }
        $fullData = array_merge($metadata, ['data' => $content]);
        $response->setData($fullData);
        // https://www.aaronsaray.com/2017/laravel-pretty-print-middleware
        $response->setEncodingOptions(config('app.json_encode_options'));

        $response->exception = $exception;

        return $response;
    }

}
