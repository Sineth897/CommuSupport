<?php

namespace app\models;

use app\core\Application;
use app\core\DbModel;

class logisticModel extends DbModel
{
    public string $employeeID = '';
    public string $name = '';
    public int $age = 0;
    public string $NIC = '';
    public string $gender = '';
    public string $address = '';
    public string $contactNumber = '';
    public string $ccID = '';

    public function __construct(userModel $user =null)
    {
       //$this->user = $user;

    }
    public function table(): string
    {
        return "logisticofficer";
    }

    public function attributes(): array
    {
        return ["employeeID","name","age","NIC","gender","address","contactNumber","ccID"];
    }

    public function primaryKey(): string
    {
        return "employeeID";
    }

    public function rules(): array
    {
        return [
            "name" => [self::$REQUIRED],
            "age" => [self::$REQUIRED],
            "NIC" => [self::$REQUIRED,self::$nic,[self::$UNIQUE, "class" => self::class]],
            "gender" => [self::$REQUIRED],
            "address" => [self::$REQUIRED, [self::$UNIQUE, "class" => self::class]],
            "contactNumber" => [self::$REQUIRED,self::$CONTACT,[self::$UNIQUE, "class" => self::class]],
            'ccID' =>[self::$REQUIRED],
        ];
    }

    public function save(): bool
    {
        $this->employeeID = substr(uniqid('logistic',true),0,23);
        return parent::save();
    }

    public function userType():string
    {
        return 'logistic';

    }

    public function getPendingDeliveries() : array {
        $logisticOfficer = $this->findOne(['employeeID' => Application::$app->session->get('user')]);
//        return subdeliveryModel::getAllData();
        return $deliveries = [
            "directDonations" => $this->getDirectDonations($logisticOfficer->ccID),
            "acceptedRequests" => $this->getAcceptedRequests($logisticOfficer->ccID),
            "ccDonations" => $this->getCCDonations($logisticOfficer->ccID)
        ];
    }

    private function getDirectDonations(string $ccID): array {
        $sql = "SELECT * FROM subdelivery sd LEFT JOIN donation d ON d.deliveryID = sd.deliveryID WHERE d.donateTo = :ccID AND sd.status IN ('Not Assigned','Reassign Requested')";
        $stmt = self::prepare($sql);
        $stmt->bindValue(':ccID', $ccID);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

//    Get data of the accepted requests from the relevant tables.
    private function getAcceptedRequests(string $ccID): array {

        $sql = "SELECT * FROM subdelivery s LEFT JOIN acceptedrequest a on s.deliveryID = a.deliveryID  WHERE (a.acceptedBy IN (SELECT donorID FROM donor WHERE ccID = '$ccID') OR a.acceptedBy = '$ccID') AND s.status IN ('Not Assigned','Reassign Requested')";

        $stmt = self::prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    private function getCCDonations(string $ccID): array {
        $sql = "SELECT * FROM subdelivery s LEFT JOIN ccdonation c on s.deliveryID = c.deliveryID WHERE c.fromCC = :ccID AND s.status IN ('Not Assigned','Reassign Requested')";
        $stmt = self::prepare($sql);
        $stmt->bindValue(':ccID', $ccID);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}