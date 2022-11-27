<?php

namespace app\controller;

use app\core\Controller;
use app\core\middlewares\loginMiddleware;
use app\core\Request;
use app\core\Response;
use app\core\UserModel;
use http\Client\Curl\User;

class loginController extends  Controller
{

    public function __construct(string $func,Request $request,Response $response)
    {
        $this->getUserType();



        if(method_exists($this, $func)) {
            $this->middleware = new loginMiddleware();
            $this->middleware->execute($func, $this->userType);
            $this->$func($request,$response);
        } else {
            throw new \Exception('Method does not exist');
        }

    }

    public function userLogin()
    {
        $model = new UserModel();
        $this->render("login/user", [
            'model' => $model
        ]);
    }

    public function employeeLogin(Request $request, Response $response)
    {
        $model = new UserModel();
        echo $request->getUser();
        if ($request->isPost()) {

            $model->getData($request->getBody());
            if ($model->validate($request->getBody()) && $model->login()) {
                $response->redirect('/');
                return;
            }
        }


        $this->render("login/employee", [
            'model' => $model
        ]);
    }

    public function managerLogin()
    {
        $this->render("login/login");
    }

    public function handleLogin()
    {
        $this->render("login/login");
    }

    private function getModel(userModel $model)
    {
        $username = $model->username;
    }

}
