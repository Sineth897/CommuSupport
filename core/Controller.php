<?php

namespace app\core;

use app\core\middlewares\Middleware;
use app\models\adminModel;
use app\models\choModel;
use app\models\doneeModel;
use app\models\donorModel;
use app\models\driverModel;
use app\models\logisticModel;
use app\models\managerModel;

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

    public function render($view, $title, $params = []): void
    {
        echo Application::$app->router->render($view, $title, $params);
    }

    public function sendJson($data): void
    {
        Application::$app->router->sendData($data);
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

    protected function getUserModel()
    {
        $user = $this->getUserType();
        switch ($user) {
            case 'donor':
                return new donorModel();
            case 'donee':
                return new doneeModel();
            case 'admin':
                return new adminModel();
            case 'manager':
                return new managerModel();
            case 'logistic':
                return new logisticModel();
            case 'driver':
                return new driverModel();
            case 'cho':
                return new choModel();
            default:
                return null;
        }
    }

    protected function setCookie($key, $value, $days = 30): void
    {
        Application::$app->cookie->setCookie($key, $value, $days);
    }

    protected function getCookie($key)
    {
        return Application::$app->cookie->getCookie($key);
    }

    protected function deleteCookie($key): void
    {
        Application::$app->cookie->unsetCookie($key);
    }
}
