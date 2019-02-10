<?php
declare(strict_types = 1);

namespace App\Exceptions;

use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\JsonResponse
     */
    public function render($request, Exception $exception)
    {
    
        foreach ($this->dontReport as $exceptionClassNotToReport) {
            if ($exception instanceof $exceptionClassNotToReport) {
                return parent::render($request, $exception);
            }
        }
        
        $content = 
                    [
                        'class' => get_class($exception),
                        'message' => $exception->getMessage(),
                        'file' => $exception->getFile(),
                        'line' => $exception->getLine(),
                        //'trace' => $exception->getTrace(),
                    ];
            
        $response = new \Illuminate\Http\JsonResponse(null, 500);

        // Since AddResponseMetadata and PrettyPrint middlewares are not executed,
        // their logic is here re-applied manually on the error response.
        // @todo improve design of this
        $metadata = app('App\Http\Middleware\AddResponseMetadata')->getMetadata($request, $response);
        $fullData = array_merge($metadata, ['data' => $content]);
        $response->setContent(json_encode($fullData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
         
        $response->exception = $exception;
        
        return $response;
    }
    
}
