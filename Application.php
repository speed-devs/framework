<?php

namespace speedweb\core;

use speedweb\core\form\csrf\CsrfManager;
use speedweb\core\http\Request;
use speedweb\core\http\Response;
use speedweb\core\session\SessionManager;

class Application
{

    const EVENT_BEFORE_REQUEST = 'beforeRequest';
    const EVENT_AFTER_REQUEST = 'afterRequest';
    const CSRF_SESSION_NAMESPACE = 'csrf';

    protected array $eventListeners = [];

    public static string $ROOT_DIR;
    public Router $router;
    public Request $request;
    public Response $response;
    public Database $db;
    public SessionManager $sessionManager;
    public ?Controller $controller = null;
    public static Application $application;

    public function __construct($rootPath, array $config)
    {
        self::$ROOT_DIR = $rootPath;
        self::$application = $this;

        $this->db = new Database($config['db']);
        $this->sessionManager = new SessionManager();

        if($this->sessionManager->get(Application::CSRF_SESSION_NAMESPACE) === false) {
            $this->sessionManager->set(self::CSRF_SESSION_NAMESPACE, CsrfManager::generateToken());
        }

        $this->request = new Request($this->sessionManager);
        $this->response = new Response();
        $this->router = new Router($this->request, $this->response);
    }

    public function run()
    {
        $this->triggerEvent(self::EVENT_BEFORE_REQUEST);
        try {
            echo $this->router->resolve();
        }catch (\Exception $exception) {
            //@todo: create modern error page
        }
        $this->triggerEvent(self::EVENT_AFTER_REQUEST);
    }

    public function on($eventName, $callback)
    {
        $this->eventListeners[$eventName][] = $callback;
    }

    public function triggerEvent($eventName)
    {
        $callbacks = $this->eventListeners[$eventName] ?? [];
        foreach ($callbacks as $callback) {
            call_user_func($callbacks);
        }
    }
}