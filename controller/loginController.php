<?php

namespace app\controller;

use app\core\Application;
use app\core\Controller;
use app\core\exceptions\forbiddenException;
use app\core\exceptions\methodNotFound;
use app\core\middlewares\loginMiddleware;
use app\core\Request;
use app\core\Response;
use app\models\doneeModel;
use app\models\donorModel;
use app\models\userModel;
use http\Exception;

class loginController extends  Controller
{


    /**
     * @param string $func
     * @param Request $request
     * @param Response $response
     * @throws forbiddenException
     * @throws methodNotFound
     */
    public function __construct(string $func, Request $request, Response $response)
    {
        $this->middleware = new loginMiddleware();
        parent::__construct($func, $request, $response);

    }

    /**
     * @param $request
     * @param $response
     * @return void
     */
    protected function userLogin($request, $response) : void
    {

        // if user is already logged in, redirect to home page
        $this->ifLoggedIn($response);


        $user = new userModel();

        // if request is a post request, get the data from the request body and try to log in
        if ($request->isPost()) {

            // load data from the login form
            $user->getData($request->getBody());

            // if login is successful, redirect to home page
            if ($user->login()) {

                // if remember me is clicked, set the remember me cookie
                if($this->isRememberMeClicked($request)) {

                    // set the remember me cookie
                    $this->rememberMe($user);
                }

                // redirect to home page
                $response->redirect('/');
                return;
            }
        }

        // render the login page
        $this->render("login/user", "User Login", [
            'user' => $user
        ]);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return void
     */
    protected function employeeLogin(Request $request, Response $response) :void
    {

        // if user is already logged in, redirect to home page
        $this->ifLoggedIn($response);

        $user = new userModel();

        // if request is a post request, get the data from the request body and try to log in
        if ($request->isPost()) {

            // load data from the login form
            $user->getData($request->getBody());

            // if login is successful, redirect to home page
            if ($user->login(true)) {

                // if remember me is clicked, set the remember me cookie
                if($this->isRememberMeClicked($request)) {

                    // set the remember me cookie
                     $this->rememberMe($user);
                }

                // redirect to home page
                $response->redirect('/');
                return;

            }
        }

        // render the login page
        $this->render("login/employee", "Employee Login",[
            'user' => $user
        ]);

    }

    /**
     * @param Request $request
     * @param Response $response
     * @return void
     */
    protected function logout(Request $request, Response $response) : void
    {

        // remove the remember me cookie
        $this->forgetMe();

        $user = new userModel();

        // logout the user
        $user->logout();

        // set the flash message and redirect to home page
        $this->setFlash('success','Logout Successful');
        $response->redirect('/');

    }

    /**
     * @param Request $request
     * @param Response $response
     * @return void
     */
    protected function forgetPassword(Request $request, Response $response) : void {
        try{

            // if the request is a post request, get the data from the request body and send the OTP
            if($request->isPost()) {

                // get the data from the request JSON body
                $data = $request->getJsonData();

                // get the function name from the request body unset it from the data
                $func = $data['do'];
                unset($data['do']);

                // call the function
                $result = $this->$func($data);

                // send the result as JSON
                $this->sendJson($result);
            }
        }
        catch(\Exception $e) {

            // if any exception occurs, send the exception message as JSON
            $this->sendJson(['success' => 0, 'message' => $e->getMessage()]);
        }

        // if the request is a get request, render the forget password page
        if($request->isGet()) {
            $this->render("login/forgetPassword/forgetPassword", "Forget Password");
        }

    }

    protected function lockedAccount(userModel $model) : void
    {
//        $username = $model->username;
        echo " is locked";
    }

    /**
     * @param $data
     * @return array
     */
    private function changePassword($data) : array {

        // get the user model from the database
        $user = userModel::getModel(['username' => $data['username']]);

        // change password using the model
        if($user->changePassword($data['newPassword'])) {
            return ['success' => 1, 'message' => 'Password changed successfully'];
        }

        // if password change fails, return error message
        return ['success' => 0, 'message' => 'Password change failed'];

    }

    /**
     * @param $data
     * @return array
     */
    private function requestOTP($data) : array {
        try {

            // generate random number for OTP
            $OTP = rand(100000,999999);

            // created time
            $createdTime = time();

            // valid for 10 mins
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

        // set the OTP in session
        $this->setSessionMsg('OTP', $OTP);

        // get the user model from the database
        $user = userModel::getModel(['username' => $data['username']]);

        // send the OTP to the user
        if($this->sendOTP($OTP['OTP'], $user)) {
            return ['success' => 1, 'message' => 'OTP sent'];
        }

        // if OTP sending fails, return error message
        return ['success' => 0, 'message' => 'Unable to send OTP'];

    }

    /**
     * @param $data
     * @return array
     */
    private function checkOTP($data) : array {

        // get the OTP from session
        $OTP = $this->getSessionMsg('OTP');

        // if OTP is not found, return error message
        if(!$OTP) {
            return ['success' => 0, 'message' => 'OTP not found. '];
        }

        // if OTP is expired, return error message
        if($OTP['validTime'] < time()) {
            return ['success' => 0, 'message' => 'OTP is expired. Please request a new OTP'];
        }

        // if OTP does not match, return error message
        if($OTP['OTP'] != $data['OTP']) {
            return ['success' => 0, 'message' => 'OTP does not match'];
        }


        $user = userModel::getModel(['username' => $data['username']]);

        // if OTP is valid, return success message
        // if user is employee, return isEmployee = true
        $isEmployee = $user->isEmployee($user->userType);

        // unset the OTP from session
        $this->unsetCookie('OTP');

        // return success message
        return ['success' => 1, 'message' => 'OTP is valid', 'isEmployee' => $isEmployee];

    }

    /**
     * @param $data
     * @return array
     */
    private function checkUsername($data) : array {
        $username = $data['username'];
        $user = userModel::getModel(['username' => $username]);
        if(!$user) {
            return ['success' => 0,'message' => 'User account with given username does not exist'];
        }
        return ['success' => 1, 'message' => 'User account found'];

    }


    /**
     * @param Response $response
     * @return void
     */
    private function ifLoggedIn(Response $response) : void {

        // if already logged in redirect to user's home page
        if($this->getUserType() !== 'guest') {

            // set the flash message and redirect to home page
            $this->setFlash('loggedStatus', 'You are already logged in');
            $response->redirect('/');

        }

    }

    /**
     * @param Request $request
     * @return bool
     */
    private function isRememberMeClicked(Request $request) : bool {

        // if remember me is clicked, return true
        return !empty($request->getBody()['rememberMe']);

    }

    /**
     * @param userModel $user
     * @param int $days
     * @return void
     */
    private function rememberMe(userModel $user, $days = 30) : void {

        // get the user model from the database
        $user = $user->findOne(['username' => $user->username]);

        // generate selector, validator and token
        [$selector, $validator, $token] = ['', '', ''];

        // generate selector, validator and token until all of them are generated
        // this is to make sure that all of them are generated, because generating built-in function is not 100% secure
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

    protected function changePasswordFromProfile(Request $request,Response $response) {

        $data = $request->getJsonData();

        $newPassword = $data['newPassword'];
        $currentPassword = $data['currentPassword'];

        $user = userModel::getModel(['username' => Application::session()->get('username')]);

        if(!password_verify($currentPassword,$user->password)) {

            $this->sendJson(['status' => 0 , 'message' => 'Provided password is incorrect']);
            return;
        }

        try {

            $this->startTransaction();

            $user->update(['userID' => $_SESSION['user']],['password' => password_hash($newPassword,PASSWORD_DEFAULT)]);

            $this->commitTransaction();

            $this->sendJson(['status' => 1 , 'message' => 'Password changed successfully']);

        }
        catch(\Exception $e) {
            $this->rollbackTransaction();
            $this->sendJson(['status' => 0 , 'message' => $e->getMessage()]);
            return;
        }

    }


}

