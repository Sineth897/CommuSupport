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

    public function table(): string
    {
        return "logisticOfficer";
    }

    public function attributes(): array
    {
        return ["employeeID","name","age","NIC","gender","address","contactNumber","email","ccID"];
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
        ];
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
        $sql = "SELECT * FROM subdelivery sd INNER JOIN donation d ON d.deliveryID = sd.deliveryID INNER JOIN subcategory s ON d.item = s.subcategoryID WHERE d.donateTo = :ccID";
        $stmt = self::prepare($sql);
        $stmt->bindValue(':ccID', $ccID);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

//    Get data of the accepted requests from the relevant tables.
    private function getAcceptedRequests(string $ccID): array {

        $sql = "SELECT acceptedrequest.*, subcategory.subcategoryName FROM acceptedrequest INNER JOIN subcategory ON acceptedrequest.item = subcategory.subcategoryID WHERE acceptedBy IN (SELECT donorID FROM donor WHERE ccID = '$ccID') AND status = 'accepted'";

        $stmt = self::prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    private function getCCDonations(string $ccID): array {
        return ccDonationModel::getAllData(['fromCC' => $ccID]);
    }
}