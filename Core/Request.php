<?php

declare(strict_types=1);

namespace Core;

class Request
{
    public function get($key, $default = null, $prefix = null)
    {
        return isset($_GET[$key])
            ? ($prefix ?: null) . $_GET[$key]
            : $default;
    }

    public function post($key, $default = null, $prefix = null)
    {
        return isset($_POST[$key])
            ? ($prefix ?: null) . $_POST[$key]
            : $default;
    }

    public function all()
    {
        return $_POST;
    }

    public function isAjax(): bool
    {
        return (
            !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest'
        );
    }
}
