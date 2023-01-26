<?php

namespace app\models;

use app\core\DbModel;

class userTokenModel extends DbModel
{
    public int $ID = 0;
    public string $selector = '';
    public string $validator = '';
    public string $userID = '';
    public string $expiryDate = '';

    public function table(): string
    {
        return 'usertoken';
    }

    public function attributes(): array
    {
        return ['selector', 'token', 'userID', 'expiryDate'];
    }

    public function primaryKey(): string
    {
        return 'ID';
    }

    public function rules(): array
    {
        return [];
    }
}