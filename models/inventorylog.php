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

    /**
     * @param string $acceptedID
     * @param string $ccID
     * @return void
     */
    public static function logCollectionFromDonor(string $acceptedID, string $ccID) : void {

        // select related data from the accepted request table and insert it into the inventory log table
        // once request item is get collected from the donor
        $sql =  "INSERT INTO inventorylog(processID, amount, item, ccID, remark)  
                                SELECT acceptedID,amount,item,'$ccID',
                                CONCAT('Delivery was collected from donor with id ',acceptedBy,' on ',CURRENT_DATE) 
                                FROM acceptedrequest  
                                WHERE acceptedID = '$acceptedID'";

        $statement = self::prepare($sql);
        $statement->execute();
    }

    /**
     * @param string $acceptedID
     * @param string $ccID
     * @return void
     */
    public static function logPickupFromCC(string $acceptedID, string $ccID) : void {

        // select related data from the accepted request table and insert it into the inventory log table
        // once request item is get pickep up from the cc
        $sql = "UPDATE inventorylog SET remark = CONCAT(remark,CONCAT('Delivery was picked up from CC with id ', '$ccID',' on ',CURRENT_DATE)) 
                                     AND datePicked = CURRENT_DATE WHERE processID = '$acceptedID' AND ccID = '$ccID'";

        $statement = self::prepare($sql);
        $statement->execute();

    }

    /**
     * @param string $donationID
     * @param string $ccID
     * @return void
     */
    public static function logCollectionOfDonationFromDonor(string $donationID, string $ccID) : void {

        // select related data from the donation table and insert it into the inventory log table
        // once donation is get collected from the donor
        $sql =  "INSERT INTO inventorylog(processID, amount, item, ccID, remark)  
                        SELECT donationID,amount,item,donateTo,CONCAT('Donation was collected from donor with id ',createdBy,' on ',CURRENT_DATE) 
                        FROM donation  WHERE donationID = '$donationID'";

        $statement = self::prepare($sql);
        $statement->execute();

    }

    /**
     * @param string $ccdonationID
     * @param string $fromCC
     * @param string $toCC
     * @return void
     */
    public static function logCCdonation(string $ccdonationID, string $fromCC, string $toCC) : void {

        // select related data from the ccdonation table and insert it into the inventory log table
        // once cc donation is get accepted
        $sql = "INSERT INTO inventorylog(processID, amount, item, ccID, remark)  
                        SELECT ccdonationID,amount,item,toCC,CONCAT('Donation was collected from  ', c.city,' CC on ',CURRENT_DATE) 
                        FROM ccdonation cc INNER JOIN communitycenter c on cc.fromCC = c.ccID 
                        WHERE cc.ccdonationID = '$ccdonationID'";

        $statement = self::prepare($sql);
        $statement->execute();
    }

    /**
     * @param string $deliveryID
     * @return void
     */
    public static function logDeliveryBetween2CCs(string $deliveryID) : void {

        // select related data from the ccdonation table and insert it into the inventory log table
        // once ccdonation item is get delivered from one cc to another cc
        $sql = "INSERT INTO inventorylog(processID, amount, item, ccID, remark)  
                SELECT a.acceptedID,amount,item,c.ccID,CONCAT('Donation was dispatched to ', c.city,' CC on ',CURRENT_DATE) 
                FROM  acceptedrequest a INNER JOIN subdelivery s ON a.deliveryID = s.deliveryID 
                INNER JOIN communitycenter c ON s.start = c.ccID 
                WHERE a.deliveryID = '$deliveryID'";

        $statement = self::prepare($sql);
        $statement->execute();

        // select related data from the ccdonation table and insert it into the inventory log table
        // once ccdonation item is get delivered from one cc to another cc
        $sql = "INSERT INTO inventorylog(processID, amount, item, ccID, remark)  
                SELECT a.acceptedID,amount,item,c.ccID,CONCAT('Donation was collected from  ', c.city,' CC on ',CURRENT_DATE) 
                FROM  acceptedrequest a INNER JOIN subdelivery s ON a.deliveryID = s.deliveryID 
                INNER JOIN communitycenter c ON s.end = c.ccID 
                WHERE a.deliveryID = '$deliveryID'";

        $statement = self::prepare($sql);
        $statement->execute();

    }

    /**
     * @param string $acceptedID
     * @return void
     */
    public static function logInventoryAcceptingRequest(string $acceptedID) : void {

        // select related data from the accepted request table and insert it into the inventory log table
        // once request item is get accepted by the cc
        $sql = "INSERT INTO inventorylog(processID, amount, item, ccID, remark) 
                    SELECT acceptedID,amount,item,acceptedBy,CONCAT('Donation was accepted by ', c.city,' CC on ',CURRENT_DATE) 
                    FROM acceptedrequest a INNER JOIN communitycenter c ON a.acceptedBy = c.ccID 
                    WHERE a.acceptedID = '$acceptedID'";

        $statement = self::prepare($sql);
        $statement->execute();

    }

    /**
     * @param string $suncateogoryID
     * @param Int $amount
     * @param string $remark
     * @return void
     */
    public static function logLogisticAddingInventoryManually(string $suncateogoryID, Int $amount, string $remark) : void {

        // get the logistic model fromt the database
        $logistic = logisticModel::getModel(['employeeID' => $_SESSION['user']]);

        // generate a unique process id , for manually adding items to the inventory
        $processID = substr(uniqid('logisticAdd',true),0,23);

        // insert the data into the inventory log table
        $sql = "INSERT INTO inventorylog(processID,amount,item,ccID,remark) 
                    VALUES ('$processID',$amount,'$suncateogoryID','$logistic->ccID',:remark)";

        $statement = self::prepare($sql);
        $statement->bindParam(':remark',$remark);
        $statement->execute();

    }

    /**
     * @param string $ccdonationID
     * @return void
     */
    public static function logAcceptingCCDonation(string $ccdonationID) : void {

        // select related data from the ccdonation table and insert it into the inventory log table
        // once cc donation is get accepted
        $sql = "INSERT INTO inventorylog(processID, amount, item, ccID, remark)  
                    SELECT ccdonationID,amount,item,fromCC,CONCAT('Donation was dispatched to ', c.city,' CC on ',CURRENT_DATE) 
                    FROM ccdonation cc INNER JOIN communitycenter c on cc.toCC = c.ccID 
                    WHERE cc.ccdonationID = '$ccdonationID'";

        $statement = self::prepare($sql);
        $statement->execute();

    }

    /**
     * @return array
     */
    public static function getInventoryLog() : array {

        // get the inventory log from the database for admin
        $sql = "SELECT *,CONCAT(i.amount,' ',s.scale) AS amount FROM inventorylog i 
                            INNER JOIN subcategory s ON i.item = s.subcategoryID 
                            INNER JOIN communitycenter c ON i.ccID = c.ccID ORDER BY i.dateReceived DESC";

        $statement = self::prepare($sql);
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_ASSOC);

    }

    /**
     * @param string $ccID
     * @return array
     */
    public static function getInventoryLogsOfCCMonthBack(string $ccID) : array  {

        // get the inventory log from the database for a given ccID
        $sql = "SELECT *,CONCAT(i.amount,' ',s.scale) AS amount FROM inventorylog i 
                            INNER JOIN subcategory s ON i.item = s.subcategoryID 
                            INNER JOIN communitycenter c ON i.ccID = c.ccID 
                            WHERE c.ccID = :ccID AND i.dateReceived >= DATE_SUB(CURRENT_DATE, INTERVAL 1 MONTH)
                            ORDER BY i.dateReceived DESC";

        $statement = self::prepare($sql);

        $statement->bindParam(':ccID',$ccID);

        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_ASSOC);

    }

    public static function getInventoryLogsOfMonthBackGroupByDate(string $ccID) : array  {

        // get the inventory log from the database for a given ccID
        $sql = "SELECT i.dateReceived,count(*) FROM inventorylog i 
                            INNER JOIN subcategory s ON i.item = s.subcategoryID 
                            INNER JOIN communitycenter c ON i.ccID = c.ccID 
                            WHERE c.ccID = :ccID AND i.dateReceived >= DATE_SUB(CURRENT_DATE, INTERVAL 1 MONTH)
                            GROUP BY i.dateReceived
                            ORDER BY i.dateReceived DESC";

        $statement = self::prepare($sql);

        $statement->bindParam(':ccID',$ccID);

        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_KEY_PAIR);

    }

}