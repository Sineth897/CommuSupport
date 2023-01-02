<?php

namespace app\models;



use app\core\Application;
use app\core\DbModel;

class userModel extends  DbModel
{
    protected string $GUEST = 'guest';
    protected string $DONEE = 'donee';
    protected string $DONOR = 'donor';
    protected string $DRIVER = 'driver';
    protected string $LOGISTIC = 'logistic';
    protected string $MANAGER = 'managerModel';
    protected string $CHO = 'cho';
    protected string $ADMIN = 'admin';


    public string $userID = '';
    public string $username = '';
    public string $password = '';
    public string $userType = '';
    public int $invalidAttempts = 0;
    public int $lockedStatus = 0;

    public function table() : string
    {
        return 'users';
    }

    public function attributes() : array
    {
        return ['userID', 'username', 'password','userType','invalidAttempts','lockedStatus'];
    }

    public function rules(): array
    {
        return [
            'username' => [self::$REQUIRED],
            'password' => [self::$REQUIRED,self::$PASSWORD],
        ];
    }

    public function primaryKey(): string
    {
        return 'userID';
    }

    public function getDisplayName(): string {
        return $this->username;
    }

    public function userType() : string {
        return $this->userType;
    }

    public function login(bool $employee = false) {

        try {

            if($this->isRoot()) {

                if(!$this->passCheck()) {
                    $this->addError('password', 'Password is incorrect');
                    return false;
                }
                $this->userType= $this->ADMIN;
                return Application::$app->login($this);
            }


            $user = userModel::findOne(['username' => $this->username]);

            if($user->lockedStatus == 1) {
                Application::$app->response->redirect('/login/locked');
                return false;
            }

            if (!$user) {
                $this->addError('username', 'User does not exist with this username');
                return false;
            }
            if($employee && $this->isUser($user->userType)) {
                $this->addError('username', 'User does not exist with this username');
                return false;
            }
            if(!$employee && $this->isEmployee($user->userType)) {
                $this->addError('username', 'User does not exist with this username');
                return false;
            }

            if (!password_verify($this->password, $user->password)) {
                $user->invalidLogin();
                $this->addError('password', 'Password is incorrect');
                return false;
            }
            $user->userType = $user->userType();

            return Application::$app->login($user);
        } catch (\Exception $e) {
            echo $e->getMessage();
            exit;
        }
    }

    public function logout() {
        Application::$app->logout();
    }

    public function isEmployee(string $userType):bool {
        return in_array($userType, ['admin', 'cho', 'manager', 'logistic', 'driver']);
    }

    public function isUser(string $userType):bool {
        return in_array($userType, ['donor', 'donee']);
    }

    private function isRoot():bool {
        return Application::$app->isRoot($this->username);
    }

    private function passCheck():bool {
        return Application::$app->isRootPassword($this->password);
    }

    public function getGenders(): array {
        return [
            "Male" => "Male",
            "Female" => "Female"
        ];
    }

    public function invalidLogin() {
        if($this->invalidAttempts >= 5) {
            $this->updateOne(['username' => $this->username],"lockedStatus = 1");
            Application::$app->response->redirect('/login/locked');
        }

        $newAttemptValue = $this->invalidAttempts + 1;
        echo $newAttemptValue;
        $this->updateOne( ['username' => $this->username],"invalidAttempts = $newAttemptValue");
    }


}