<?php

namespace app\models;

use app\core\DbModel;

class doneeOrganizationModel extends DbModel
{
    public string $doneeID = '';
    public string $organizationName = '';
    public string $regNo = '';
    public string $representative = '';
    public string $representativeContact = '';

    public int $capacity = 0;

    public function table(): string
    {
        return 'doneeorganization';
    }

    public function attributes(): array
    {
        return ['doneeID', 'organizationName', 'regNo','representative','representativeContact','capacity'];
    }

    public function primaryKey(): string
    {
        return 'doneeID';
    }

    public function rules(): array
    {
        return [
            'organizationName' => [self::$REQUIRED,[self::$UNIQUE,'class'=>self::class]],
            'regNo' => [self::$REQUIRED,[self::$UNIQUE,'class'=>self::class]],
            'representative' => [self::$REQUIRED,[self::$UNIQUE,'class'=>self::class]],
            'representativeContact' => [self::$REQUIRED,[self::$UNIQUE,'class'=>self::class]],
        ];
    }

}