<?php

namespace speedweb\core;

use speedweb\core\middlewares\BaseMiddleware;

class Controller
{

    public string $action = '';
    public array $middlewares = [];

    public function render($view, $params = []):string
    {
        return Application::$application->router->renderView($view, $params);
    }

    public function registerMiddleware(BaseMiddleware $middleware)
    {
        $this->middlewares[] = $middleware;
    }

    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }
}