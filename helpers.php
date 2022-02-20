<?php

/**
 * @param string $name
 * @param array $data
 * @return string
 */
function view(string $name, array $data = []): string
{
    return \speedweb\core\Router::renderView($name, $data);
}

/**
 * @return array
 */
function get_config(): array
{
    return [
        'db' => [
            'HOST' => env('HOST', 'localhost'),
            'DB_NAME' => env('DB_NAME', 'test'),
            'PORT' => env('PORT', 3306),
            'DB_USERNAME' => env('DB_USERNAME', 'root'),
            'DB_PASSWORD' => env('DB_PASSWORD', '')
        ]
    ];
}

/**
 * @param $key
 * @param $value
 * @return void
 */
function session_create($key, $value)
{
    $session = new \speedweb\core\session\SessionManager();
    $session->set($key, $value);
}

/**
 * @param $key
 * @return false|mixed
 */
function session_get($key)
{
    $session = new \speedweb\core\session\SessionManager();
    return $session->get($key);
}

/**
 * @param $key
 * @return void
 */
function session_remove($key)
{
    $session = new \speedweb\core\session\SessionManager();
    $session->remove($key);
}

/**
 * @param $key
 * @param $value
 * @return void
 */
function session_create_flash($key, $value)
{
    $session = new \speedweb\core\session\SessionManager();
    $session->setFlash($key, $value);
}

/**
 * @param $key
 * @return void
 */
function session_get_flash($key)
{
    $session = new \speedweb\core\session\SessionManager();
    $session->getFlash($key);
}

/**
 * @return string
 */
function csrf_token(): string
{
    $session = new \speedweb\core\session\SessionManager();
    return $session->get(\speedweb\core\Application::CSRF_SESSION_NAMESPACE);
}

/**
 * @param $action
 * @param $method
 * @param array $options
 * @return \speedweb\core\form\Form
 */
function form_start($action, $method, $options = []): \speedweb\core\form\Form
{
    return \speedweb\core\form\Form::begin($action, $method, $options);
}

/**
 * @return void
 */
function form_end(): void
{
    \speedweb\core\form\Form::end();
}

/**
 * @param string $path
 * @return string
 */
function url(string $path): string
{
    return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . '/' . $path;
}