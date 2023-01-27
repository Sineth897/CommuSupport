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

        $this->ifLoggedIn($response);

        $user = new userModel();
        if ($request->isPost()) {
            $user->getData($request->getBody());
            if ($user->validate($request->getBody()) && $user->login()) {
                if($this->isRememberMeClicked($request)) {
                    $this->rememberMe($user);
                }
                $response->redirect('/');
                return;
            }
        }

        $this->render("login/user", "User Login", [
            'user' => $user
        ]);
    }

    protected function employeeLogin(Request $request, Response $response)
    {

        $this->ifLoggedIn($response);

        $user = new userModel();
        if ($request->isPost()) {
            $user->getData($request->getBody());
            if ($user->validate($request->getBody()) && $user->login(true)) {
                if($this->isRememberMeClicked($request)) {
                     $this->rememberMe($user);
                }
                $response->redirect('/');
                return;
            }
        }

        $this->render("login/employee", "Employee Login",[
            'user' => $user
        ]);
    }

    protected function logout(Request $request, Response $response)
    {
        $this->forgetMe();
        $user = new userModel();
        $user->logout();
        $response->redirect('/');
    }

    protected function lockedAccount(userModel $model)
    {
        $username = $model->username;
        echo $username . " is locked";
    }

    private function ifLoggedIn(Response $response) {

        if($this->getUserType() !== 'guest') {
            $this->setFlash('loggedStatus', 'You are already logged in');
            $response->redirect('/');
        }
    }

    private function isRememberMeClicked(Request $request):bool {
        return !empty($request->getBody()['rememberMe']);
    }

    private function rememberMe(userModel $user, $days = 30):void {
        $user = $user->findOne(['username' => $user->username]);
        [$selector, $validator, $token] = ['', '', ''];
        while(!($selector && $validator && $token)) {
            [$selector, $validator, $token] = $this->generateSelectorNValidator();
        }
        if($user->setRememberMe($selector, $validator,$days)) {
            $this->setFlash('rememberMe', 'Remember me is set');
            $this->setCookie('rememberMe', $token,);
        }
        else {
            $this->setFlash('rememberMe', 'Remember me is not set');
        }
    }

    private function forgetMe() {
        $user = new userModel();
        if($user->unsetRememberMe(Application::session()->get('user'))) {
            $this->setFlash('rememberMe', 'Remember me is unset');
            $this->unsetCookie('rememberMe');
        }
        else {
            $this->setFlash('rememberMe', 'Remember me is not unset');
        }
    }

    private function generateSelectorNValidator():array {
        try {
            $selector = bin2hex(random_bytes(16));
            $validator = bin2hex(random_bytes(32));
            return [$selector, $validator, $selector . ":" . $validator];
        } catch (\Exception $e) {
            return [];
        }
    }

    private function getSelectorNValidator():array
    {
        $selectorNValidator =Application::cookie()->getCookie('rememberMe');
        if(!$selectorNValidator) {
            return [];
        }
        return explode(":", $selectorNValidator);
    }

}

