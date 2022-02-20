<?php

namespace speedweb\core\form\csrf;

interface TokenGeneratorInterface
{

    public static function generateToken();
}