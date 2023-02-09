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

    public function table(): string
    {
        return 'doneeorganization';
    }

    public function attributes(): array
    {
        return ['doneeID', 'organizationName', 'regNo','representative','representativeContact'];
    }

    public function primaryKey(): string
    {
        return 'doneeID';
    }

    public function rules(): array
    {
        return [
            'organizationName' => [self::$REQUIRED],
            'regNo' => [self::$REQUIRED],
            'representative' => [self::$REQUIRED,[self::$UNIQUE,'class'=>self::class]],
            'representativeContact' => [self::$REQUIRED,[self::$UNIQUE,'class'=>self::class]],
        ];
    }

}