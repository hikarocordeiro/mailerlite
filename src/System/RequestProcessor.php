<?php

namespace Src\System;

class RequestProcessor
{

    public function __construct()
    {
        if (isset($_SERVER['HTTP_ORIGIN'])) {
            header('Access-Control-Allow-Origin: *');
            header('Access-Control-Allow-Credentials: true');
            header('Access-Control-Max-Age: 1000');
        }
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])) {
                header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE");
            }

            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])) {
                header(
                    "Access-Control-Allow-Headers: Accept, Content-Type, Content-Length, Accept-Encoding, X-CSRF-Token, Authorization"
                );
            }
            exit(0);
        }
    }

    public function getURI($index = null)
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uri = explode('/', $uri);

        return ($index && array_key_exists($index, $uri)) ? $uri[$index] : $uri;
    }

    public function getRoute()
    {
        return $this->getURI(1);
    }

    public function getParam()
    {
        $param = $this->getURI(2);
        return (isset($param) && is_numeric($param)) ? (int)$param : null;
    }

    public function getRequestMethod()
    {
        return $_SERVER["REQUEST_METHOD"];
    }
}
