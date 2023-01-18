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
        $this->middleware = new loginMiddleware();
        parent::__construct($func, $request, $response);

    }

    protected function userLogin($request, $response)
    {
        $model = new userModel();
        if ($request->isPost()) {
            $model->getData($request->getBody());
            if ($model->validate($request->getBody()) && $model->login()) {
                $response->redirect('/');
                return;
            }
        }

        $this->render("login/user", "User Login", [
            'model' => $model
        ]);
    }

    protected function employeeLogin(Request $request, Response $response)
    {
        $model = new userModel();
        if ($request->isPost()) {
            $model->getData($request->getBody());
            if ($model->validate($request->getBody()) && $model->login(true)) {
                $response->redirect('/');
                return;
            }
        }

        $this->render("login/employee", "Employee Login",[
            'model' => $model
        ]);
    }

    protected function logout(Request $request, Response $response)
    {
        $model = new userModel();
        $model->logout();
        $response->redirect('/');
    }

    private function getModel(userModel $model)
    {
        $username = $model->username;
    }

    protected function lockedAccount(userModel $model)
    {
        $username = $model->username;
        echo $username . " is locked";
    }

}
