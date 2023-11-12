<?php
namespace App;

use Config;

class HTTPAuth
{

    protected $username;
    protected $password;

    public function __construct()
    {
        $config = new Config();
        $this->username = $config::HTTP_AUTH_USERNAME;
        $this->password = $config::HTTP_AUTH_PASSWORD;
    }

    public function authenticate()
    {
        if (!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW'])) {
            $this->requireLogin();
        } elseif ($_SERVER['PHP_AUTH_USER'] != $this->username || $_SERVER['PHP_AUTH_PW'] != $this->password) {
            $this->unauthorized();
        } else {
            return true;
        }
    }

    protected function requireLogin()
    {
        header('WWW-Authenticate: Basic realm="My Realm"');
        header('HTTP/1.0 401 Unauthorized');
        exit;
    }

    protected function unauthorized()
    {
        header('HTTP/1.0 403 Unauthorized');
        exit;
    }
}

