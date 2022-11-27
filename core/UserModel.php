<?php

namespace app\core;



class UserModel extends  DbModel
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

    public function table() : string
    {
        return 'users';
    }

    public function attributes() : array
    {
        return ['userID', 'username', 'password','userType'];
    }

    public function rules(): array
    {
        return [
            'username' => [self::$REQUIRED],
            'password' => [self::$REQUIRED],
        ];
    }

    public function primaryKey(): string
    {
        return '';
    }

    public function getDisplayName(): string {
        return 'guest';
    }

    public function userType() : string {
        return 'guest';
    }

    public function login() {
        try {
            $user = UserModel::findOne(['username' => $this->username]);
            if (!$user) {
                $this->addError('username', 'User does not exist with this username');
                return false;
            }
            if (!password_verify($this->password, $user->password)) {
                $this->addError('password', 'Password is incorrect');
                return false;
            }

            echo $user->userType;

            return Application::$app->login($user);
        } catch (\Exception $e) {
            echo $e->getMessage();
            exit;
        }
    }

}