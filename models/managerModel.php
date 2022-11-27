<?php

namespace app\models;

use app\core\Application;
use app\core\DbModel;
use app\core\UserModel;

class managerModel extends DbModel
{
    public UserModel $user;
    public string $username = '';
    public string $employeeID = '';
    public string $password = '';


    public function __construct(UserModel $user = null)
    {
        $this->user = $user;
    }

    public function table() : string
    {
        return 'manager';
    }

    public function attributes() : array
    {
        return ['ID', 'username', 'password'];
    }

    public function primaryKey(): string
    {
        return 'ID';
    }

    public function rules(): array
    {
        return [
            'username' => [self::$REQUIRED, [self::$UNIQUE, "class" => self::class]],
            'password' => [self::$REQUIRED, [self::$MIN, 'min' => 8], [self::$MAX, 'max' => 12]],
        ];
    }

    public function labels(): array
    {
        return [
            'name' => 'Name',
            'email' => 'Email',
            'password' => 'Password',
            'confirmPassword' => 'Confirm Password'
        ];
    }

    public function save(): bool
    {
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);
        $this->ID = uniqid("manager", true);
        return parent::save();
    }

    public function getDisplayName(): string
    {
        return $this->username;
    }

    public function userType(): string
    {
        return 'manager';
    }

    public function login(): bool
    {
        $user = managerModel::findOne(['username' => $this->username]);
        if( !$user ) {
            $this->addError('username', 'User does not exist with this username');
            return false;
        }
        if( !password_verify($this->password, $user->password) ) {
            $this->addError('password', 'Password is incorrect');
            return false;
        }
        return Application::$app->login($user);
    }
}