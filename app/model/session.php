<?php

namespace App\Model;

class Session
{
    protected $lifetime;

    public function __construct()
    {
        // Aktifkan session
        session_start();

        // Dapatkan lifetime dari cookie session
        $this->lifetime = ini_get('session.cookie_lifetime');
    }

    public function set($name, $value)
    {
        $_SESSION[$name] = $value;
        // Set cookie
        $this->setCookie($name);
    }

    public function get($name)
    {
        if (isset($_SESSION[$name])) {
            return $_SESSION[$name];
        }
    }

    public function clear()
    {
        // Hapus semua session dan cookie
        session_destroy();
        if (isset($_SERVER['HTTP_COOKIE'])) {
            $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
            foreach($cookies as $cookie) {
                $parts = explode('=', $cookie);
                $name = trim($parts[0]);
                setcookie($name, '', time()-1000);
                setcookie($name, '', time()-1000, '/');
            }
        }
    }

    protected function setCookie($name)
    {
        $params = session_get_cookie_params();
        $expiryTime = time() + $this->lifetime;
        setcookie(
            $name,
            $_SESSION[$name],
            $expiryTime,
            $params["path"],
            $params["domain"],
            $params["secure"],
            $params["httponly"]
        );
    }
}
