<?php

namespace app\models;

use app\core\DbModel;

class logisticModel extends DbModel
{
    public string $employeeID = '';
    public string $name = '';
    public int $age = 0;
    public string $NIC = '';
    public string $gender = '';
    public string $address = '';
    public string $contactNumber = '';
    public string $ccID = '';

    public function table(): string
    {
        return "logisticofficer";
    }

    public function attributes(): array
    {
        return ["employeeID","name","age","NIC","gender","address","contactNumber","email","ccID"];
    }

    public function primaryKey(): string
    {
        return "employeeID";
    }

    public function rules(): array
    {
        return [
            "name" => [self::$REQUIRED],
            "age" => [self::$REQUIRED],
            "NIC" => [self::$REQUIRED,self::$nic,[self::$UNIQUE, "class" => self::class]],
            "gender" => [self::$REQUIRED],
            "address" => [self::$REQUIRED, [self::$UNIQUE, "class" => self::class]],
            "contactNumber" => [self::$REQUIRED,self::$CONTACT,[self::$UNIQUE, "class" => self::class]],
        ];
    }
}