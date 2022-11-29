<?php

namespace app\models;

use app\core\Application;
use app\core\userModel;

class loginForm extends userModel
{
    public string $email = '';
    public string $password = '';

    public function rules(): array
    {
        return [
            'email' => [self::$REQUIRED, self::$EMAIL],
            'password' => [self::$REQUIRED]
        ];
    }

    public function labels(): array
    {
        return [
            'email' => 'Email',
            'password' => 'Password'
        ];
    }

    public function table() : string
    {
        return 'users';
    }

    public function attributes() : array
    {
        return ['email', 'password'];
    }

    public function primaryKey(): string
    {
        return 'name';
    }

    public function getDisplayName(): string
    {
        return "Display Name";
    }

    public function userType() : string
    {
        return "managerModel";
    }

    public function login(): bool
    {
        $user = loginForm::findOne(['email' => $this->email]);
        if( !$user ) {
            $this->addError('email', 'User does not exist with this email');
            return false;
        }
        if( !password_verify($this->password, $user->password) ) {
            $this->addError('password', 'Password is incorrect');
            return false;
        }
        return Application::$app->login($user);
    }

}

