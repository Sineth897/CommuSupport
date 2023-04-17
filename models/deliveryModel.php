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

    public function getAssignedDeliveries(string $driverID) : array {
        $cols = "s.subdeliveryID,s.start,s.end,s.createdDate,t.item";
        $sql1 = "SELECT $cols from subdelivery s INNER JOIN acceptedrequest t on s.deliveryID = t.deliveryID WHERE s.deliveredBy = '$driverID' AND s.status = 'Ongoing'";
        $sql2 = "SELECT $cols from subdelivery s INNER JOIN donation t on s.deliveryID = t.deliveryID WHERE s.deliveredBy = '$driverID' AND s.status = 'Ongoing'";
        $sql3 = "SELECT $cols from subdelivery s INNER JOIN ccdonation t on s.deliveryID = t.deliveryID WHERE s.deliveredBy = '$driverID' AND s.status = 'Ongoing'";
        $stmt1 = self::prepare($sql1 . " UNION " . $sql2 . " UNION " . $sql3);
        $stmt1->execute();
        return $stmt1->fetchAll(\PDO::FETCH_ASSOC);
    }
}