<?php namespace Hpolthof\LaravelAPI;

use Hpolthof\LaravelAPI\Middleware\APIErrors;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;

class APIServiceProvider extends ServiceProvider
{
    protected $middleware = [
        'api.errors' => APIErrors::class,
    ];

    public function boot(Router $router)
    {
        Response::macro('api', function($value, $message = null, $http_status = 200, $headers = []) {
            $layout = new Layout($value, $http_status, $message);
            return $layout->getResponse();
        });

        $this->registerMiddleware($router);

    }

    /**
     * @param Router $router
     */
    protected function registerMiddleware(Router $router)
    {
        foreach ($this->middleware as $name => $class) {
            $router->middleware($name, $class);
        }

        // Laravel 5.3 - Allows for middlewareGroup support
        // so we'll add some middleware to the 'api' group.
        if (method_exists($router, 'middlewareGroup')) {
            $router->pushMiddlewareToGroup('api', 'api.errors');
        }
    }
}