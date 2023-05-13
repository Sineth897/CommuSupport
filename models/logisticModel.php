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
        $sql = "SELECT * FROM subdelivery sd LEFT JOIN donation d ON d.deliveryID = sd.deliveryID WHERE d.donateTo = :ccID";
        $stmt = self::prepare($sql);
        $stmt->bindValue(':ccID', $ccID);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

//    Get data of the accepted requests from the relevant tables.
    private function getAcceptedRequests(string $ccID): array {

        $sql = "SELECT *,s.status FROM subdelivery s LEFT JOIN acceptedrequest a on s.deliveryID = a.deliveryID  WHERE (a.acceptedBy IN (SELECT donorID FROM donor WHERE ccID = '$ccID') OR a.acceptedBy = '$ccID')";

        $stmt = self::prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    private function getCCDonations(string $ccID): array {
        $sql = "SELECT *,c.createdDate AS date,s.status AS deliveryStatus FROM subdelivery s LEFT JOIN ccdonation c on s.deliveryID = c.deliveryID WHERE c.fromCC = :ccID";
        $stmt = self::prepare($sql);
        $stmt->bindValue(':ccID', $ccID);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    // get deliveries filtered and sorted realted to specific process or all processes
    public static function getDeliveriesOfLogisticOfficerFilteredAndSorted(string $process,array $filter,array $sort) : array {

        // get the model of the logistic offcer
        $logistic = logisticModel::getModel(['employeeID' => $_SESSION['user']]);

        // sql queries to retrieve subdelivery relating to each process
        $directDonationSql = "SELECT * FROM subdelivery sd LEFT JOIN donation d ON d.deliveryID = sd.deliveryID WHERE d.donateTo = '{$logistic->ccID}'";
        $ccDonationSql = "SELECT *,c.createdDate AS date,s.status AS deliveryStatus FROM subdelivery s LEFT JOIN ccdonation c on s.deliveryID = c.deliveryID WHERE c.fromCC = '{$logistic->ccID}'";
        $acceptedRequestSql = "SELECT *,s.status FROM subdelivery s LEFT JOIN acceptedrequest a on s.deliveryID = a.deliveryID  WHERE (a.acceptedBy IN (SELECT donorID FROM donor WHERE ccID = '{$logistic->ccID}') OR a.acceptedBy = '{$logistic->ccID}') ";

        // if filter is provided
        // since here filtering is done only by item we can directly add it
        if(!empty($filter)) {
            $directDonationSql .= " AND d.item = '{$filter['item']}'";
            $ccDonationSql .= " AND c.item = '{$filter['item']}'";
            $acceptedRequestSql .= " AND a.item = '{$filter['item']}'";
        }

        // if sort is provided
        // since here sorting is done only by date we can directly add it
        if(!empty($sort['DESC'])) {
            $directDonationSql .= " ORDER BY s.createdDate DESC";
            $ccDonationSql .= " ORDER BY s.createdDate DESC";
            $acceptedRequestSql .= " ORDER BY s.createdDate DESC";
        }

        // prepare and execute the queries
        $directDonationStmnt = self::prepare($directDonationSql);
        $ccDonationStmnt = self::prepare($ccDonationSql);
        $acceptedRequestStmnt = self::prepare($acceptedRequestSql);
        $directDonationStmnt->execute();
        $ccDonationStmnt->execute();
        $acceptedRequestStmnt->execute();

        //match expression to return the relevant data matching the process
        return match ($process) {
            'donation' => [
                'status' =>  1,
                'directDonations' => $directDonationStmnt->fetchAll(\PDO::FETCH_ASSOC),
            ],
            'acceptedRequest' => [
                'status' =>  1,
                'acceptedRequests' => $acceptedRequestStmnt->fetchAll(\PDO::FETCH_ASSOC),
            ],
            'ccDonation' => [
                'status' =>  1,
                'ccDonations' => $ccDonationStmnt->fetchAll(\PDO::FETCH_ASSOC),
            ],
            default => [
                'status' =>  1,
                'directDonations' => $directDonationStmnt->fetchAll(\PDO::FETCH_ASSOC),
                'acceptedRequests' => $acceptedRequestStmnt->fetchAll(\PDO::FETCH_ASSOC),
                'ccDonations' => $ccDonationStmnt->fetchAll(\PDO::FETCH_ASSOC),
            ],
        };

    }

    /**
     * @return array
     */
    public function getLogisticInformationForProfile() : array {
        return [
            $this->getPersonalInfo()[0],
            $this->getLogisticStatistics(),
        ];
    }

    /**
     * @return array
     */
    private function getPersonalInfo() : array {

        $sql = "SELECT *,l.contactNumber,l.address FROM users u 
                    INNER JOIN logisticofficer l ON u.userID = l.employeeID
                    INNER JOIN communitycenter c on l.ccID = c.ccID
                    INNER JOIN communityheadoffice c2 on c.cho = c2.choID
                    WHERE userID = '{$_SESSION['user']}'";

        $statement = self::prepare($sql);
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * @return array
     */
    private function getLogisticStatistics() : array {

        $arrayOfSql = [
            $sqlDonationsReceived = "SELECT 'Donations Received',COUNT(*) FROM donation d 
                                            INNER JOIN logisticofficer l ON d.donateTo = l.ccID 
                                            WHERE l.employeeID = '{$_SESSION['user']}'",

            $sqlAcceptedRequesrts = "SELECT 'Requests Accepted',COUNT(*) FROM acceptedrequest a 
                                            INNER JOIN logisticofficer l ON a.acceptedBy = l.ccID 
                                            WHERE l.employeeID = '{$_SESSION['user']}' 
                                            GROUP BY a.requestID",

            $sqlccDonationsRequested = "SELECT 'CCDonations Requested',COUNT(*) FROM ccdonation c 
                                            INNER JOIN logisticofficer l ON c.toCC = l.ccID 
                                            WHERE l.employeeID = '{$_SESSION['user']}' ",

            $sqlccDonationsAccepted = "SELECT 'CCDonations Donated',COUNT(*) FROM ccdonation c 
                                            INNER JOIN logisticofficer l ON c.fromCC = l.ccID 
                                            WHERE l.employeeID = '{$_SESSION['user']}' ",

            $sqlDriversAvailable = "SELECT 'Available Drivers',COUNT(*) FROM driver d 
                                            INNER JOIN logisticofficer l ON l.ccID = d.ccID 
                                            WHERE l.employeeID = '{$_SESSION['user']}'"


        ];

        $statement = self::prepare(implode(" UNION ",$arrayOfSql));
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_KEY_PAIR);
    }


}