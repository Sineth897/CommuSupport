<?php

namespace app\models;

use app\core\DbModel;

class inventorylog extends DbModel
{

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
        $sql =  "INSERT INTO inventorylog(processID, amount, item, ccID, remark)  SELECT acceptedID,amount,item,$ccID,CONCAT('Delivery was collected from donor with id ',acceptedBy,' on ',CURRENT_DATE) FROM acceptedrequest  WHERE acceptedID = $acceptedID";
        $statement = self::prepare($sql);
        $statement->execute();
    }

    public static function logPickupFromCC(string $acceptedID,string $ccID) {
        $sql = "UPDATE inventorylog SET remark = CONCAT(remark,CONCAT('Delivery was picked up from CC with id ', $ccID,' on ',CURRENT_DATE)) AND datePicked = CURRENT_DATE WHERE processID = $acceptedID AND ccID = $ccID";
        $statement = self::prepare($sql);
        $statement->execute();
    }


}