<?php

class App
{

    protected $controller;
    protected $method;
    protected $params = [];

    public function __construct()
    {
        $url = $this->parseURL();

        $this->controller = $url[1] ?? 'todos';
        $this->method = $url[2] ?? 'index';

        require_once __DIR__.'/controllers/' . $this->controller . '.php';
        $this->controller = new $this->controller;

        $this->params = array_slice($url, 3);


        //jalankan controller
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    public function parseURL()
    {
        if (isset($_SERVER['REQUEST_URI'])) {
            $url = rtrim($_SERVER['REQUEST_URI'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);
            return $url;
        }
    }
}
