<?php

namespace speedweb\core\http;

use speedweb\core\Application;
use speedweb\core\exception\CsrfNotFoundException;
use speedweb\core\form\csrf\CsrfManager;
use speedweb\core\session\SessionManager;

class Request
{

    public SessionManager $sessionManager;

    public function __construct(SessionManager $sessionManager)
    {
        $this->sessionManager = $sessionManager;

        if($this->method() === 'post') {
            $searchCsrf = false;
            foreach ($_POST as $key => $value) {
                if($key === Application::CSRF_SESSION_NAMESPACE) {
                    $searchCsrf = true;
                }
            }

            if(!$searchCsrf) {
                exit('csrf token not found. try add csrf token to form');
            }

            if($this->sessionManager->get(Application::CSRF_SESSION_NAMESPACE) !== $_POST[Application::CSRF_SESSION_NAMESPACE]) {
                exit('csrf token not match.');
            }

            $this->sessionManager->remove(Application::CSRF_SESSION_NAMESPACE);
        }
    }

    public function getPath()
    {
        $path = $_SERVER['REQUEST_URI'] ?? '/';
        $position = strpos($path, '?');
        if ($position === false) {
            return $path;
        }
        return substr($path, 0, $position);
    }

    public function method()
    {
        return strtolower($_SERVER["REQUEST_METHOD"]);
    }

    public function getBody()
    {
        $data = [];
        if ($this->isGet()) {
            foreach ($_GET as $key => $value) {
                $data[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }
        if ($this->isPost()) {
            foreach ($_POST as $key => $value) {
                $data[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }
        return $data;
    }

    /**
     * @return bool
     */
    public function isGet(): bool
    {
        return $this->method() === 'get';
    }

    /**
     * @return bool
     */
    public function isPost(): bool
    {
        return $this->method() === 'post';
    }
}