<?php

namespace app\core;

/**
 * Class Request
 *
 * @author Daishitie <daishitie@gmail.com>
 * @package app\core
 */
class Request
{
    /**
     * Request path getter.
     *
     * @return string|false
     */
    public function getPath(): string|false
    {
        $path = $_SERVER["REQUEST_URI"] ?? "/";
        $position = strpos($path, "?");

        if ($position === false) {
            return $path;
        }

        return substr($path, 0, $position);
    }

    /**
     * Request method.
     *
     * @return string
     */
    public function method(): string
    {
        return strtolower($_SERVER["REQUEST_METHOD"]);
    }

    /**
     * Request is get method.
     *
     * @return boolean
     */
    public function isGet(): bool
    {
        return $this->method() === 'get';
    }

    /**
     * Request is post method.
     *
     * @return boolean
     */
    public function isPost(): bool
    {
        return $this->method() === 'post';
    }

    /**
     * Request body getter.
     *
     * @return array
     */
    public function getBody(): array
    {
        $body = [];

        if ($this->isGet()) {
            foreach ($_GET as $key => $value) {
                $body[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }

        if ($this->isPost()) {
            foreach ($_POST as $key => $value) {
                $body[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }

        return $body;
    }
}
