<?php

namespace app\models;

use app\core\DbModel;

class donorOrganizationModel extends DbModel
{
    public string $donorID = '';
    public string $organizationName = '';
    public string $regNo = '';
    public string $representative = '';
    public string $representativeContact = '';

    public function table(): string
    {
        return 'donororganization';
    }

    public function attributes(): array
    {
        return ['donorID', 'organizationName', 'regNo','representative','representativeContact'];
    }

    public function primaryKey(): string
    {
        return 'donorID';
    }

    public function rules(): array
    {
        return [
            'organizationName' => [self::$REQUIRED,[self::$UNIQUE,'class'=>self::class]],
            'regNo' => [self::$REQUIRED,[self::$UNIQUE,'class'=>self::class]],
            'representative' => [self::$REQUIRED,[self::$UNIQUE,'class'=>self::class]],
            'representativeContact' => [self::$REQUIRED,self::$CONTACT,[self::$UNIQUE,'class'=>self::class]],
        ];
    }
}