<?php

namespace app\models;

use app\core\Application;
use app\core\DbModel;

class managerModel extends DbModel
{
    public userModel $user;
    public string $employeeID = '';
    public string $name = '';
    public int $age = 0;
    public string $gender = '';
    public string $NIC = '';
    public string $address = '';
    public string $contactNumber = '';
    public string $ccID = '';


    public function __construct(userModel $user = null)
    {
        // $this->user = $user;
    }

    public function table() : string
    {
        return 'manager';
    }

    public function attributes() : array
    {
        return ['employeeID', 'name', 'age', 'gender', 'NIC', 'address', 'contactNumber', 'ccID'];
    }

    public function primaryKey(): string
    {
        return 'employeeID';
    }

    public function rules(): array
    {
        return [
            'name' => [self::$REQUIRED],
            'age' => [self::$REQUIRED],
            'gender' => [self::$REQUIRED],
            'NIC' => [self::$REQUIRED, self::$nic, [self::$UNIQUE, 'class' => self::class]],
            'address' => [self::$REQUIRED, [self::$UNIQUE, 'class' => self::class]],
            'contactNumber' => [self::$REQUIRED, [self::$UNIQUE, 'class' => self::class]],
            'ccID' =>[self::$REQUIRED],
            
        ];
    }


    public function save(): bool
    {

        return parent::save();
    }

    public function userType(): string
    {
        return 'manager';
    }

    /**
     * @return array
     */
    public function getManagerInformationForProfile() : array {
        return [
            $this->getPersonalInfo()[0],
            $this->getManagerStatistics(),
        ];
    }

    /**
     * @return array
     */
    private function getPersonalInfo() : array {

        $sql = "SELECT *,m.contactNumber,m.address FROM users u 
                    INNER JOIN manager m ON u.userID = m.employeeID
                    INNER JOIN communitycenter c on m.ccID = c.ccID
                    INNER JOIN communityheadoffice c2 on c.cho = c2.choID
                    WHERE userID = '{$_SESSION['user']}'";

        $statement = self::prepare($sql);
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * @return array
     */
    private function getManagerStatistics() : array {

        $arrayOfSql = [
            $sqlDonationsReceived = "SELECT 'Active Events',COUNT(*) FROM event e 
                                            INNER JOIN manager m ON e.ccID = m.ccID 
                                            WHERE m.employeeID = '{$_SESSION['user']}'",

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