<?php

namespace app\controller;

use app\core\Application;
use app\core\Controller;
use app\core\middlewares\loginMiddleware;
use app\core\Request;
use app\core\Response;
use app\models\doneeModel;
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
            if ($user->login()) {
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
            if ($user->login(true)) {
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
        $this->setFlash('success','Logout Successful');
        $response->redirect('/');
    }

    protected function forgetPassword(Request $request,Response $response) {
        try{
            if($request->isPost()) {
                $data = $request->getJsonData();
                $func = $data['do'];
                unset($data['do']);
                $result = $this->$func($data);
                $this->sendJson($result);
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

    private function changePassword($data):array {
        $user = userModel::getModel(['username' => $data['username']]);
        if($user->changePassword($data['newPassword'])) {
            return ['success' => 1, 'message' => 'Password changed successfully'];
        }
        return ['success' => 0, 'message' => 'Password change failed'];
    }

    private function requestOTP($data):array {
        try {
            $OTP = rand(100000,999999);
            $createdTime = time();
            $validTime = $createdTime + 60 * 10;
        }
        catch (\Exception $e) {
            return ['success' => 0, 'message' => $e->getMessage()];
        }

        $OTP = [
            'OTP' => $OTP,
            'createdTime' => $createdTime,
            'validTime' => $validTime
        ];
        $this->setSessionMsg('OTP', $OTP);
        $user = userModel::getModel(['username' => $data['username']]);
        if($this->sendOTP($OTP['OTP'], $user)) {
            return ['success' => 1, 'message' => 'OTP sent'];
        }
        return ['success' => 0, 'message' => 'Unable to send OTP'];
    }

    private function checkOTP($data):array {
        $OTP = $this->getSessionMsg('OTP');
        if(!$OTP) {
            return ['success' => 0, 'message' => 'OTP not found. '];
        }
        if($OTP['validTime'] < time()) {
            return ['success' => 0, 'message' => 'OTP is expired. Please request a new OTP'];
        }
        if($OTP['OTP'] != $data['OTP']) {
            return ['success' => 0, 'message' => 'OTP does not match'];
        }
        $user = userModel::getModel(['username' => $data['username']]);
        $isEmployee = $user->isEmployee($user->userType);
        $this->unsetCookie('OTP');
        return ['success' => 1, 'message' => 'OTP is valid', 'isEmployee' => $isEmployee];
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

    protected function verifyMobile(Request $request,Response $response) {
        $model = new userModel();

        if($request->isGet()) {
            $this->renderOnlyView('/mobileVerification/mobileVerification','Verify your mobile',[
                'user' => $model,
            ]);
        }
        else {
            try{
                    $data = $request->getJsonData();
                    $func = $data['do'];
                    unset($data['do']);
                    $data['username'] = Application::session()->get("username");
                    $result = $this->$func($data);
                    if($func === 'checkOTP' && $result['success'] === 1) {
                        $result['success'] = $this->updateVerification();
                    }
                    $this->sendJson($result);
            }
            catch(\Exception $e) {
                $this->sendJson(['success' => 0, 'message' => $e->getMessage()]);
            }

        }

    }

    private function updateVerification(): bool
    {
        $user = null;
        try {
            if($this->getUserType() === 'donee' ) {
                $user = doneeModel::getModel(['doneeID' => Application::session()->get('user')]);
                $user->update(['doneeID' => $user->doneeID],['mobileVerification' => 1]);
            }
            else {
                $user = donorModel::getModel(['donorID' => Application::session()->get('user')]);
                $user->update(['donorID' => $user->donorID],['mobileVerification' => 1]);
            }
            return 1;
        }
        catch(\Exception $e) {
            return 0;
        }


    }


}

