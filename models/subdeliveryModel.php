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

    public string $deliveredBy = '';

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

    public static function getDestinations() :  array {
        $sql = "SELECT donorID,address FROM donor UNION SELECT ccID,CONCAT(city,' (CC)') as address FROM communitycenter UNION SELECT doneeID,address FROM donee";
        $stmt = self::prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_KEY_PAIR);
    }
}