<?php

namespace app\models;

use app\core\DbModel;

class subdeliveryModel extends DbModel
{
    public string $subdeliveryID = '';
    public string $deliveryID = '';
    public int $deliveryStage = 0;
    public string $status = '';
    public string $createdDate = '';
    public string $start = '';
    public string $end = '';
    public float $fromLongitude = 0.0;
    public float $fromLatitude = 0.0;
    public float $toLongitude = 0.0;
    public float $toLatitude = 0.0;
    public string $completedDate = '';
    public string $completedTime = '';

    public function table(): string
    {
        return 'subdelivery';
    }

    public function attributes(): array
    {
        return ['subdeliveryID', 'deliveryID', 'deliveryStage','start', 'end', 'fromLongitude', 'fromLatitude', 'toLongitude', 'toLatitude',];
    }

    public function primaryKey(): string
    {
        return 'subdeliveryID';
    }

    public function rules(): array
    {
        return [];
    }

    public function save(): bool
    {
        $this->subdeliveryID = substr(uniqid('sub',true),0,23);
        return parent::save();
    }
}