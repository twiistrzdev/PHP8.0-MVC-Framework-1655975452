<?php

namespace app\core;

/**
 * Class Response
 *
 * @author Daishitie <daishitie@gmail.com>
 * @package app\core
 */
class Response
{
    /**
     * Response status code setter.
     *
     * @param integer $code
     */
    public function setStatusCode(int $code)
    {
        http_response_code($code);
    }

    /**
     * Response redirect method.
     *
     * @param string $location
     */
    public function redirect(string $location)
    {
        header("Location: $location");
    }
}
