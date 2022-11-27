<?php

namespace app\models;

use app\core\DbModel;
use app\core\Model;

class regModel extends DbModel
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $confirmPassword = '';

    public function table() : string
    {
        return 'users';
    }

    public function attributes() : array
    {
        return ['name', 'email', 'password'];
    }

    public function primaryKey(): string
    {
        return 'name';
    }

    public function rules(): array
    {
        return [
            'name' => [self::$REQUIRED, [self::$UNIQUE, "class" => self::class]],
            'email' => [self::$REQUIRED, self::$EMAIL, [self::$UNIQUE, 'class' => self::class]],
            'password' => [self::$REQUIRED, [self::$MIN, 'min' => 8], [self::$MAX, 'max' => 12]],
            'confirmPassword' => [self::$REQUIRED, [self::$MATCH, 'match' => 'password']]
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
        return parent::save();
    }


}
