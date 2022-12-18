<?php

namespace app\core;

use app\controller\guestController;
use app\core\exceptions\notFoundException;
use function Composer\Autoload\includeFile;

class Router
{
    public Request $request;
    public Response $response;
    protected array $routes = [];

    //constructor function to initialize the request and response
    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    //function to add the get route to the routes array
    public function get($path, $callback)
    {
        $this->routes['get'][$path] = $callback;
    }

    //function to add the post route to the routes array
    public function post($path, $callback)
    {
        $this->routes['post'][$path] = $callback;
    }

    public function resolve()
    {
        $path = $this->request->getPath();
        $method = $this->request->method();
        $callback = $this->routes[$method][$path] ?? false;

        if ($callback === false) {
            throw new notFoundException($path);
        }

        return call_user_func($callback,$this->request,$this->response);
    }

    public function renderView($view, $params = []): void
    {
        foreach ($params as $key => $value) {
            $$key = $value;
        }
        include_once Application::$ROOT_DIR . "/views/layouts/header.php";
        include_once Application::$ROOT_DIR . "/views/$view.php";
        include_once Application::$ROOT_DIR . "/views/layouts/footer.php";
    }



    public function sendData($data, $status = 200): void
    {
        $this->response->setStatusCode($status);
        $this->response->setJsonData($data);
        $this->response->send();
    }


}
