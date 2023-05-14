<?php

namespace app\models;

use app\core\DbModel;

class donorIndividualModel extends DbModel
{
    public string $donorID = '';
    public string $fname = '';
    public string $lname = '';
    public string $NIC = '';
    public int $age = 0;

    public function table(): string
    {
        return "donorindividual";
    }

    public function attributes(): array
    {
        return [
            'donorID','fname','lname','nic','age'
        ];
    }

    public function primaryKey(): string
    {
        return 'donorID';
    }

    public function rules(): array
    {
        return [
            'fname' => [self::$REQUIRED],
            'lname' => [self::$REQUIRED],
            'NIC' => [self::$REQUIRED,self::$nic,[self::$UNIQUE, 'class' => self::class]],
            'age' => [self::$REQUIRED],
        ];
    }
}