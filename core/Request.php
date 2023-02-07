<?php

namespace app\core;

class Request
{
    private static $REPLACE_START;


    public function __construct()
    {
        self::$REPLACE_START = ['/CommuSupport/' => '/'];
    }

    //function to get the path of the request
    public function getPath(): string
    {
        $path = strtr($_SERVER['REQUEST_URI'], self::$REPLACE_START);
        $position = strpos($path, '?');
        if($path === '/') {
            return $path;
        }
        if($path[-1] === '/') {
            $path = substr($path, 0, -1);
        }
        if ($position === false) {
            return $path;
        }
        return substr($path, 0, $position);
    }

    //function to get the request method
    public function method(): string
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    //function to verify whether the method is get or not
    public function isGet(): bool
    {
        return $this->method() === 'get';
    }

    //function to verify whether the method is post or not
    public function isPost(): bool
    {
        return $this->method() === 'post';
    }

    //function to get the body of the request
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

    public function getUser(): string {
        $url = $this->getPath();
        $url = explode('/', $url);
        return $url[1];
    }

    public function getJsonData() {
        return json_decode(file_get_contents('php://input'), true);
    }
}
