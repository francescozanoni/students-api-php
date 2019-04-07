<?php

require_once __DIR__ . '/../vendor/autoload.php';

(new Laravel\Lumen\Bootstrap\LoadEnvironmentVariables(
    dirname(__DIR__)
))->bootstrap();

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| Here we will load the environment and create the application instance
| that serves as the central piece of this framework. We'll use this
| application as an "IoC" container and router for this framework.
|
*/

$app = new Laravel\Lumen\Application(
    dirname(__DIR__)
);

$app->withFacades();

$app->withEloquent();

$app->configure('app');
$app->configure('openapi');
$app->configure('database');
$app->configure('internships');
$app->configure('erd-generator');
$app->configure('audit');

/*
|--------------------------------------------------------------------------
| Register Container Bindings
|--------------------------------------------------------------------------
|
| Now we will register a few bindings in the service container. We will
| register the exception handler and the console kernel. You may add
| your own bindings here if you like or you can make another file.
|
*/

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

/*
|--------------------------------------------------------------------------
| Register Middleware
|--------------------------------------------------------------------------
|
| Next, we will register the middleware with the application. These can
| be global middleware that run before and after each request into a
| route or middleware that'll be assigned to some specific routes.
|
*/

$app->middleware([
    App\Http\Middleware\ValidateResponse::class,
    App\Http\Middleware\PrettyPrint::class,
    App\Http\Middleware\AddResponseMetadata::class,
    App\Http\Middleware\HandleNegativeResponseContent::class,
    App\Http\Middleware\RemoveResponseEmptyItems::class,
    App\Http\Middleware\ApplyResourceTransformers::class,
]);

$app->routeMiddleware([
    // 'auth' => App\Http\Middleware\Authenticate::class,

    // This middleware cannot be global because, during execution of global before-middlewares,
    // current route is not available yet and it is required to find the right validation rules.
    'validate_request' => App\Http\Middleware\ValidateRequest::class,
]);

/*
|--------------------------------------------------------------------------
| Register Service Providers
|--------------------------------------------------------------------------
|
| Here we will register all of the application's service providers which
| are used to bind services into the container. Service providers are
| totally optional, so you are not required to uncomment this line.
|
*/

$app->register(App\Providers\AppServiceProvider::class);
$app->register(App\Providers\AuthServiceProvider::class);
// $app->register(App\Providers\EventServiceProvider::class);
$app->register(App\Providers\RouteServiceProvider::class);
$app->register(App\Providers\ValidationServiceProvider::class);
$app->register(BeyondCode\ErdGenerator\ErdGeneratorServiceProvider::class);
$app->register(OwenIt\Auditing\AuditingServiceProvider::class);

/*
|--------------------------------------------------------------------------
| Load The Application Routes
|--------------------------------------------------------------------------
|
| Next we will include the routes file so that they can all be added to
| the application. This will provide all of the URLs the application
| can respond to, as well as the controllers that may handle them.
|
*/

$app->router->group([
    'namespace' => 'App\Http\Controllers',
], function ($router) {
    require __DIR__ . '/../routes/web.php';
});

return $app;
