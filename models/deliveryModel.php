<?php

namespace app\models;

use app\core\DbModel;

class deliveryModel extends DbModel
{

    public string $deliveryID = '';
    public string $deliveredBy = '';
    public string $createdDate = '';
    public string $createdTime = '';
    public string $status = '';
    public string $city = '';
    public string $completedDate = '';
    public string $completedTime = '';



    public function rules(): array
    {
        return [
            'city' => [self::$REQUIRED],
        ];
    }

    public function table(): string
    {
        return 'delivery';
    }

    public function attributes(): array
    {
        return ['deliveryID','city'];
    }

    public function primaryKey(): string
    {
        return 'deliveryID';
    }
}