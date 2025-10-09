<?php

declare(strict_types = 1);

namespace Core;

class Session
{
    public function get($key, $default = null)
    {
        return $_SESSION[$key] ?? $default;
    }

    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public function forget($key)
    {
        unset($_SESSION[$key]);
    }
}
