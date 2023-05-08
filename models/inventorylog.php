<?php

namespace app\models;

use app\core\DbModel;

class inventorylog extends DbModel
{

    public string $remark = '';

    public function table(): string
    {
        return 'inventorylog';
    }

    public function attributes(): array
    {
        return ['processID', 'ccID','amount','item','dateReceived','remark'];
    }

    public function primaryKey(): string
    {
        return 'processID';
    }

    public function rules(): array
    {
        return [];
    }

    public static function logCollectionFromDonor(string $acceptedID,string $ccID) {
        $sql =  "INSERT INTO inventorylog(processID, amount, item, ccID, remark)  SELECT acceptedID,amount,item,'$ccID',CONCAT('Delivery was collected from donor with id ',acceptedBy,' on ',CURRENT_DATE) FROM acceptedrequest  WHERE acceptedID = '$acceptedID'";
        $statement = self::prepare($sql);
        $statement->execute();
    }

    public static function logPickupFromCC(string $acceptedID,string $ccID) {
        $sql = "UPDATE inventorylog SET remark = CONCAT(remark,CONCAT('Delivery was picked up from CC with id ', '$ccID',' on ',CURRENT_DATE)) AND datePicked = CURRENT_DATE WHERE processID = '$acceptedID' AND ccID = '$ccID'";
        $statement = self::prepare($sql);
        $statement->execute();
    }

    public static function logCollectionOfDonationFromDonor(string $donationID,string $ccID) {
        $sql =  "INSERT INTO inventorylog(processID, amount, item, ccID, remark)  SELECT donationID,amount,item,donateTo,CONCAT('Donation was collected from donor with id ',createdBy,' on ',CURRENT_DATE) FROM donation  WHERE donationID = '$donationID'";
        $statement = self::prepare($sql);
        $statement->execute();
    }

    public static function logCCdonation(string $ccdonationID,string $fromCC,string $toCC) {

        $sql = "INSERT INTO inventorylog(processID, amount, item, ccID, remark)  SELECT ccdonationID,amount,item,toCC,CONCAT('Donation was collected from  ', c.city,' CC on ',CURRENT_DATE) FROM ccdonation cc INNER JOIN communitycenter c on cc.fromCC = c.ccID WHERE cc.ccdonationID = '$ccdonationID'";
        $statement = self::prepare($sql);
        $statement->execute();
    }

    public static function logDeliveryBetween2CCs(string $deliveryID) {
        $sql = "INSERT INTO inventorylog(processID, amount, item, ccID, remark)  SELECT a.acceptedID,amount,item,c.ccID,CONCAT('Donation was dispatched to ', c.city,' CC on ',CURRENT_DATE) FROM  acceptedrequest a INNER JOIN subdelivery s ON a.deliveryID = s.deliveryID INNER JOIN communitycenter c ON s.start = c.ccID WHERE a.deliveryID = '$deliveryID'";
        $statement = self::prepare($sql);
        $statement->execute();
        $sql = "INSERT INTO inventorylog(processID, amount, item, ccID, remark)  SELECT a.acceptedID,amount,item,c.ccID,CONCAT('Donation was collected from  ', c.city,' CC on ',CURRENT_DATE) FROM  acceptedrequest a INNER JOIN subdelivery s ON a.deliveryID = s.deliveryID INNER JOIN communitycenter c ON s.end = c.ccID WHERE a.deliveryID = '$deliveryID'";
        $statement = self::prepare($sql);
        $statement->execute();
    }

    public static function logInventoryAcceptingRequest(string $acceptedID) {
        $sql = "INSERT INTO inventorylog(processID, amount, item, ccID, remark) SELECT acceptedID,amount,item,acceptedBy,CONCAT('Donation was accepted by ', c.city,' CC on ',CURRENT_DATE) FROM acceptedrequest a INNER JOIN communitycenter c ON a.acceptedBy = c.ccID WHERE a.acceptedID = '$acceptedID'";
        $statement = self::prepare($sql);
        $statement->execute();
    }

    public static function logLogisticAddingInventoryManually(string $suncateogoryID,Int $amount,string $remark) {
        $logistic = logisticModel::getModel(['employeeID' => $_SESSION['user']]);
        $processID = substr(uniqid('logisticAdd',true),0,23);
        $sql = "INSERT INTO inventorylog(processID,amount,item,ccID,remark) VALUES ('$processID',$amount,'$suncateogoryID','$logistic->ccID',:remark)";
        $statement = self::prepare($sql);
        $statement->bindParam(':remark',$remark);
        $statement->execute();
    }

    public static function logAcceptingCCDonation(string $ccdonationID) : void {
        $sql = "INSERT INTO inventorylog(processID, amount, item, ccID, remark)  SELECT ccdonationID,amount,item,fromCC,CONCAT('Donation was dispatched to ', c.city,' CC on ',CURRENT_DATE) FROM ccdonation cc INNER JOIN communitycenter c on cc.toCC = c.ccID WHERE cc.ccdonationID = '$ccdonationID'";
        $statement = self::prepare($sql);
        $statement->execute();
    }

}