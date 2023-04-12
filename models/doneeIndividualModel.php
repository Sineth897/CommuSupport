<?php

namespace app\models;

use app\core\DbModel;

class doneeIndividualModel extends DbModel
{
    public string $doneeID = '';
    public string $fname = '';
    public string $lname = '';
    public string $NIC = '';
    public int $age = 0;

    public function table(): string
    {
        return "doneeindividual";
    }

    public function attributes(): array
    {
        return [
            'doneeID','fname','lname','nic','age'
        ];
    }

    public function primaryKey(): string
    {
        return 'doneeID';
    }

    public function rules(): array
    {
        return [
            'fname' => [self::$REQUIRED],
            'lname' => [self::$REQUIRED],
            'nic' => [self::$REQUIRED,self::$nic,[self::$UNIQUE, 'class' => self::class]],
            'age' => [self::$REQUIRED],
        ];
    }

}