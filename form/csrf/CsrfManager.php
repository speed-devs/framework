<?php

namespace speedweb\core\form\csrf;

class CsrfManager implements TokenGeneratorInterface
{

    /**
     * @return string
     * @throws \Exception
     */
    public static function generateToken(): string
    {
        return md5(uniqid(mt_rand(), true));
    }
}