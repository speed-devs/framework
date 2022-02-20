<?php

namespace speedweb\core\exception;

class CsrfNotFoundException extends \Exception
{

    protected $message = 'Csrf token not found!';
    protected $code = 404;
}