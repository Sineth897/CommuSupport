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
            $sqlActiveEvents = "SELECT 'Active Events',COUNT(*) FROM event e 
                                            INNER JOIN manager m ON e.ccID = m.ccID 
                                            WHERE m.employeeID = '{$_SESSION['user']}' AND e.status IN ('Upcoming','Active')",

            $sqlCompletedEvents = "SELECT 'Finished Events',COUNT(*) FROM event e 
                                            INNER JOIN manager m ON e.ccID = m.ccID 
                                            WHERE m.employeeID = '{$_SESSION['user']}' AND e.status = 'Finished'",

            $sqlCancelledEvents = "SELECT 'Cancelled Events',COUNT(*) FROM event e 
                                            INNER JOIN manager m ON e.ccID = m.ccID 
                                            WHERE m.employeeID = '{$_SESSION['user']}' AND e.status = 'Cancelled'",

            $sqlccDonorsRegistered = "SELECT 'Registered Donors',COUNT(*) FROM donor d 
                                            INNER JOIN manager m ON m.ccID = d.ccID 
                                            WHERE m.employeeID = '{$_SESSION['user']}' ",

            $sqlccDoneesRegistered = "SELECT 'Registered Donees',COUNT(*) FROM donee d 
                                            INNER JOIN manager m ON m.ccID = d.ccID 
                                            WHERE m.employeeID = '{$_SESSION['user']}' ",

            $sqlccDoneesRegistered = "SELECT 'Donees Waiting For Verification',COUNT(*) FROM donee d 
                                            INNER JOIN manager m ON m.ccID = d.ccID 
                                            WHERE m.employeeID = '{$_SESSION['user']}' AND d.verificationStatus = 0 ",


        ];

        $statement = self::prepare(implode(" UNION ",$arrayOfSql));
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_KEY_PAIR);
    }

}