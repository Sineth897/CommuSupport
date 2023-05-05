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
    public ?string $completedDate = '';
    public ?string $completedTime = '';

    public ?string $deliveredBy = '';
    //distance respect to each subdelivery in kilometers
    public ?float $distance = 0.0;

    /**
     * @return string
     */
    public function table(): string
    {
        return 'subdelivery';
    }

    /**
     * @return string[]
     */
    public function attributes(): array
    {
        return ['subdeliveryID', 'deliveryID', 'deliveryStage','start', 'end', 'fromLongitude', 'fromLatitude', 'toLongitude', 'toLatitude',];
    }

    /**
     * @return string
     */
    public function primaryKey(): string
    {
        return 'subdeliveryID';
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [];
    }

    /**
     * @return bool
     */
    public function save(): bool
    {
        $this->subdeliveryID = substr(uniqid('sub',true),0,23);
        return parent::save();
    }

    /**
     * @return array
     */
    public static function getDestinations() :  array {

        // get all the donors, donees and community centers with their IDs as keys and addresses as values
        $sql = "SELECT donorID,address FROM donor UNION SELECT ccID,CONCAT(city,' (CC)') as address FROM communitycenter UNION SELECT doneeID,address FROM donee";
        $stmt = self::prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_KEY_PAIR);
    }

    /**
     * @param subdeliveryModel $subdelivery
     * @return void
     */
    public function saveFinalStagedetails(subdeliveryModel $subdelivery) : void {
        $this->start = $subdelivery->end;
        $this->fromLongitude = $subdelivery->toLongitude;
        $this->fromLatitude = $subdelivery->toLatitude;
        $this->deliveryID = $subdelivery->deliveryID;

        $sql = "SELECT a.acceptedID.* FROM acceptedrequest a INNER JOIN donee d ON a.postedBy = d.doneeID WHERE deliveryID = :deliveryID";
        $stmt = self::prepare($sql);
        $stmt->bindValue(":deliveryID",$this->deliveryID);
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        $this->toLongitude = $result['longitude'];
        $this->toLatitude = $result['latitude'];
        $this->end = $result['doneeID'];
        $this->deliveryStage = 1;

        $this->save();
        inventorylog::logPickupFromCC($result['acceptedID'],$result['doneeID']);

    }

    /**
     * @param subdeliveryModel $subdelivery
     * @return void
     */
    public function save2ndStagedetails(subdeliveryModel $subdelivery) : void {
        $this->start = $subdelivery->end;
        $this->fromLongitude = $subdelivery->toLongitude;
        $this->fromLatitude = $subdelivery->toLatitude;
        $this->deliveryID = $subdelivery->deliveryID;

        $sql = "SELECT a.acceptedID,c.* FROM acceptedrequest a INNER JOIN donee d ON a.postedBy = d.doneeID INNER JOIN communitycenter c ON d.ccID = c.ccID WHERE deliveryID = :deliveryID";
        $stmt = self::prepare($sql);
        $stmt->bindValue(":deliveryID",$this->deliveryID);
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        $this->toLongitude = $result['longitude'];
        $this->toLatitude = $result['latitude'];
        $this->end = $result['ccID'];
        $this->deliveryStage = 2;

        $this->save();
        inventorylog::logCollectionFromDonor($result['acceptedID'],$result['ccID']);
    }

    /**
     * @param string $subdeliveryID
     * @param string $completed
     * @return void
     */
    public static function updateAsCompleted(string $subdeliveryID, string $completed) : void {
        $sql = "UPDATE subdelivery SET status = 'Completed', completedDate = :completedDate, completedTime = :completedTime WHERE subdeliveryID = :subdeliveryID";
        $stmt = self::prepare($sql);
        $stmt->bindValue(":completedDate",$completed);
        $stmt->bindValue(":completedTime",$completed);
        $stmt->bindValue(":subdeliveryID",$subdeliveryID);
        $stmt->execute();
    }

    /**
     * @param string $subdeliveryID
     * @return array
     */
    public static function getDeliveryDetailsByID(string $subdeliveryID) : array {
        return [];
    }
}