<?php

namespace app\core;

use app\core\middlewares\Middleware;

class Controller
{
    protected string $userType  = 'guest';
    protected ?Middleware $middleware = null;

    public function __construct($func, Request $request, Response $response)
    {
        $this->getUserType();
        if (method_exists($this, $func)) {
            $this->middleware->execute($func, $this->getUserType());
            $this->$func($request, $response);
        } else {
            throw new \Exception('Method does not exist');
        }
    }



    //function to be called by the subclasses to render the view

    public function render($view, $params = []): void
    {
        Application::$app->router->renderView($view, $params);
    }

    //function to get the user type
    protected function getUserType()
    {
        return Application::$app->userType();
    }

    //function to set the user type
    public function setUserType(): void
    {
        if($this->getUserType()) {
            $this->userType = $this->getUserType();
        }
    }

    protected function haveAccess(array $users): void
    {
        if(!in_array($this->userType, $users)) {
            throw new \Exception('You do not have access to this page');
        }
    }

    protected function setFlash($key,$message): void
    {
        Application::$app->session->setFlash($key, $message);
    }

}
