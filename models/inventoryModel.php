<?php

namespace app\models;

use app\core\DbModel;

class inventoryModel extends DbModel
{
    public string $ccID = '';
    public string $itemID = '';
    public int $amount = 0;
    public string $updatedDate = '';

    public function table(): string
    {
        return 'inventory';
    }

    public function attributes(): array
    {
        return ['ccID','itemID','amount','updatedDate'];
    }

    public function primaryKey(): string
    {
        return 'itemID';
    }

    public function rules(): array
    {
        return [
            'itemID' => [self::$REQUIRED],
            'amount' => [self::$REQUIRED],
        ];
    }
}