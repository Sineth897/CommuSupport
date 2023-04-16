<?php

namespace app\models;

use app\core\Application;
use app\core\DbModel;

class managerModel extends DbModel
{
    public userModel $user;
    public string $employeeID = '';
    public string $name = '';
    public int $age = 0;
    public string $gender = '';
    public string $NIC = '';
    public string $address = '';
    public string $contactNumber = '';
    public string $ccID = '';


    public function __construct(userModel $user = null)
    {
        // $this->user = $user;
    }

    public function table() : string
    {
        return 'manager';
    }

    public function attributes() : array
    {
        return ['employeeID', 'name', 'age', 'gender', 'NIC', 'address', 'contactNumber', 'ccID'];
    }

    public function primaryKey(): string
    {
        return 'employeeID';
    }

    public function rules(): array
    {
        return [
            'name' => [self::$REQUIRED],
            'age' => [self::$REQUIRED],
            'gender' => [self::$REQUIRED],
            'NIC' => [self::$REQUIRED, self::$nic, [self::$UNIQUE, 'class' => self::class]],
            'address' => [self::$REQUIRED, [self::$UNIQUE, 'class' => self::class]],
            'contactNumber' => [self::$REQUIRED, [self::$UNIQUE, 'class' => self::class]],
            'ccID' =>[self::$REQUIRED],
            
        ];
    }


    public function save(): bool
    {

        return parent::save();
    }

    public function userType(): string
    {
        return 'manager';
    }

}