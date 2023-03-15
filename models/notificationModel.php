<?php

namespace app\models;

use app\core\DbModel;

class notificationModel extends DbModel
{
    public string $ID = '';
    public string $userID = '';
    public string $applicable = '';
    public string $category = '';
    public string $message = '';
    public string $type = '';
    public string $status = '';
    public string $dateCreated = '';

    public function table(): string
    {
        return 'notifications';
    }

    public function attributes(): array
    {
        return ['userID','applicable', 'category' ,'message', 'type', 'status', 'dateCreated'];
    }

    public function primaryKey(): string
    {
        return 'ID';
    }

    public function rules(): array
    {
        return [

        ];
    }
}