<?php

namespace app\models;

use app\core\DbModel;

class deliveryModel extends DbModel
{

    public string $deliveryID = '';
    public string $deliveredBy = '';
    public string $createdDate = '';
    public string $status = '';
    public int $subdeliveryCount = 0;
    public string $start = '';
    public string $end = '';
    public string $completedDate = '';
    public string $completedTime = '';



    public function rules(): array
    {
        return [

        ];
    }

    public function table(): string
    {
        return 'delivery';
    }

    public function attributes(): array
    {
        return ['deliveryID', 'subdeliveryCount', 'start', 'end',];
    }

    public function primaryKey(): string
    {
        return 'deliveryID';
    }
}