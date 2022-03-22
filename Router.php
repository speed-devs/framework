<?php

namespace speedweb\core;

use Exception;
use speedweb\core\exception\NotFoundException;
use speedweb\core\http\Request;
use speedweb\core\http\Response;
use Jenssegers\Blade\Blade;

class Router
{

    public Request $request;
    public Response $response;
    protected array $routes = [];

    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    public function get($path, $callback)
    {
        $this->routes['get'][$path] = $callback;
    }

    public function post($path, $callback)
    {
        $this->routes['post'][$path] = $callback;
    }

    /**
     * @throws NotFoundException
     * @throws Exception
     */
    public function resolve()
    {
        $path = $this->request->getPath();
        $method = $this->request->method();
        $callback = $this->routes[$method][$path] ?? false;
        if (!$callback) {
            throw new Exception("Page not found. Try create new router.");
        }

        if(is_string($callback)) {
            return view($callback);
        }

        if(is_array($callback)) {
            $controller = new $callback[0];
            $controller->action = $callback[1];
            Application::$application->controller = $controller;
            $middlewares = $controller->getMiddlewares();
            foreach ($middlewares as $middleware) {
                $middleware->execute();
            }

            $callback[0] = $controller;
        }

        return call_user_func($callback, $this->request, $this->response);
    }

    public static function renderView($view, array $data = [])
    {
        $blade = new Blade(Application::$ROOT_DIR . '/resources/views', Application::$ROOT_DIR . '/resources/cache');
        return $blade->render($view, $data);
    }
}