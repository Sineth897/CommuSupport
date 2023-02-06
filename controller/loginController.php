<?php

namespace app\controller;

use app\core\Application;
use app\core\Controller;
use app\core\middlewares\loginMiddleware;
use app\core\Request;
use app\core\Response;
use app\models\userModel;
use http\Exception;

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

    protected function forgetPassword(Request $request,Response $response) {
        try{
            if($request->isPost()) {
                $data = $request->getJsonData();
                $func = $data['do'];
                switch ($func) {
                    case 'requestOTP':
                        $this->sendJson($this->requestOTP($data));
                        break;
                    case 'checkOTP':
                        $this->sendJson($this->checkOTP($data));
                        break;
                    case 'checkUsername':
                        $this->sendJson($this->checkUsername($data));
                        break;
                    default:
                        $this->sendJson(['success' => 0, 'message' => 'Invalid request', 'data' => $data]);
                }
            }
        }
        catch(\Exception $e) {
            $this->sendJson(['success' => 0, 'message' => $e->getMessage()]);
        }

        if($request->isGet()) {
            $this->render("login/forgetPassword/forgetPassword", "Forget Password");
        }

    }

    protected function lockedAccount(userModel $model)
    {
//        $username = $model->username;
        echo " is locked";
    }

    private function requestOTP($data):array {
        try {
            $OTP = rand(100000,999999);
            $createdTime = time();
            $validTime = $createdTime + 600;
        }
        catch (\Exception $e) {
            return ['success' => 0, 'message' => $e->getMessage()];
        }

        $OTP = [
            'OTP' => $OTP,
            'createdTime' => $createdTime,
            'validTime' => $validTime
        ];
        $this->setSession('OTP', $OTP);
        //$this->sendOTP($OTP['OTP']);
        return ['success' => 1, 'message' => 'OTP sent', 'OTP' => $OTP];
    }

    private function checkOTP($data) {
        $this->sendJson("Hello");
    }

    private function checkUsername($data):array {
        $username = $data['username'];
        $user = userModel::getModel(['username' => $username]);
        if(!$user) {
            return ['success' => 0,'message' => 'User account with given username does not exist'];
        }
        return ['success' => 1, 'message' => 'User account found'];
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


}

