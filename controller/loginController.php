<?php

namespace app\controller;

use app\core\Application;
use app\core\Controller;
use app\core\middlewares\loginMiddleware;
use app\core\Request;
use app\core\Response;
use app\models\userModel;

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

    public function userLogin($request, $response)
    {
        $model = new userModel();
        if ($request->isPost()) {
            $model->getData($request->getBody());
            if ($model->validate($request->getBody()) && $model->login()) {
                $response->redirect('/');
                return;
            }
        }

        $this->render("login/user", [
            'model' => $model
        ]);
    }

    public function employeeLogin(Request $request, Response $response)
    {
        $model = new userModel();
        if ($request->isPost()) {
            $model->getData($request->getBody());
            if ($model->validate($request->getBody()) && $model->login(true)) {
                $response->redirect('/');
                return;
            }
        }

        $this->render("login/employee", [
            'model' => $model
        ]);
    }

    public function logout(Request $request, Response $response)
    {
        $model = new userModel();
        $model->logout();
        $response->redirect('/');
    }

    private function getModel(userModel $model)
    {
        $username = $model->username;
    }

}
