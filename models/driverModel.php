<?php

namespace app\models;

use app\core\Application;
use app\core\DbModel;

class driverModel extends DbModel
{
    protected userModel $user;
    public string $employeeID = '';
    public string $name = '';
    public int $age = 0;
    public string $NIC = '';
    public string $gender = '';
    public string $address = '';
    public string $contactNumber = '';
    public string $ccID = '';
    public string $licenseNo = '';
    public string $vehicleNo = '';
    public string $vehicleType = '';
    public string $preference = '';


    public function table(): string
    {
        return 'driver';
    }

    public function attributes(): array
    {
        return ['employeeID', 'name', 'age', 'NIC','gender', 'address', 'contactNumber', 'ccID', 'licenseNo', 'vehicleNo', 'vehicleType', 'preference'];
    }

    public function primaryKey(): string
    {
        return 'employeeID';
    }

    public function rules(): array
    {
        return [
            'name'  => [self::$REQUIRED],
            'age' => [self::$REQUIRED],
            'NIC' => [self::$REQUIRED, self::$nic, [self::$UNIQUE, 'class' => self::class]],
            'gender' => [self::$REQUIRED],
            'address' => [self::$REQUIRED, [self::$UNIQUE, 'class' => self::class]],
            'contactNumber' => [self::$REQUIRED, [self::$UNIQUE, 'class' => self::class]],
            'licenseNo' => [self::$REQUIRED, [self::$UNIQUE, 'class' => self::class]],
            'vehicleNo' => [self::$REQUIRED, [self::$UNIQUE, 'class' => self::class]],
            'vehicleType' => [self::$REQUIRED],
            'preference' => [self::$REQUIRED],
        ];
    }

    public function save(): bool
    {
        $this->employeeID = uniqid('driver',true);
        $manager = managerModel::getModel(['employeeID' => Application::session()->get('user')]);
        $this->ccID = $manager->ccID;

        if(parent::save()) {
            if($this->user->save()) {
                return true;
            }
            else {
                $this->delete(['employeeID' => $this->employeeID]);
                return false;
            }
        }
        return false;
    }

    public function getVehicleTypes(): array
    {
        return [
            'Bike' => 'Bike',
            'Three-wheeler' => 'Three-wheeler',
            'Car'=> 'Car',
            'Van' => 'Van',
        ];
    }

    public function getPreferences(): array {
        return [
            '< 10km' => 'Less than 10km',
            '> 10km' => 'More than 10km',
        ];
    }

    public function setUser(userModel $user) {
        $this->user = $user;
        $this->user->userType = "driver";
        $this->user->userID = $this->employeeID;
    }

    public static function getDriverDetails(string $employeeID) : array {
        $sql = "SELECT status,COUNT(deliveredBy) FROM subdelivery WHERE deliveredBy = '$employeeID' GROUP BY status";
        $stmt = driverModel::prepare($sql);
        $stmt->execute();
        return ['driver' => driverModel::getModel(['employeeID' => $employeeID]),'deliveryInfo' => $stmt->fetchALL(\PDO::FETCH_KEY_PAIR)];
    }


    public function getDriverbyVehicle() {
//         get the count of donees and group by type

        $sql = "SELECT COUNT(*) as count,vehicleType FROM driver GROUP BY vehicleType";
        $statement = self::prepare($sql);
        $statement->execute();
        $result = $statement->fetchAll(\PDO::FETCH_KEY_PAIR);

        $chartData = array();
        // Loop through the result and update the corresponding value in the new array
        foreach ($result as $row) {
            $chartData[$row['vehicleType']] = $row['count'];
        }
        return $chartData;
    }

    public function getDriverLengths(): array
    {
        // get the counts of drivers who do "< 10km>" or "> 10km" and group by preference
        $sql = "SELECT COUNT(*) as count,preference FROM driver GROUP BY preference";
        $statement = self::prepare($sql);
        $statement->execute();
        $result = $statement->fetchAll(\PDO::FETCH_KEY_PAIR);

        $chartData = array();
        foreach ($result as $row) {
            $chartData[$row['preference']] = $row['count'];
        }
        return $chartData;

    }

    /**
     * @return array
     */
    public function getDriverInformationForProfile() : array {
        return [
            $this->getPersonalInfo()[0],
            $this->getDriverStatistics(),
        ];
    }

    /**
     * @return array
     */
    private function getPersonalInfo() : array {

        $sql = "SELECT *,d.contactNumber,d.address FROM users u 
                    INNER JOIN driver d ON u.userID = d.employeeID
                    INNER JOIN communitycenter c on d.ccID = c.ccID
                    INNER JOIN communityheadoffice c2 on c.cho = c2.choID
                    WHERE userID = '{$_SESSION['user']}'";

        $statement = self::prepare($sql);
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * @return array
     */
    private function getDriverStatistics() : array {

        $arrayOfSql = [
            $sqlAssignedDeliveries = "SELECT 'Assigned Deliveries',COUNT(*) FROM subdelivery s
                                            WHERE s.status = 'Ongoing' AND s.deliveredBy = '{$_SESSION['user']}'",

            $sqlCompletedDeliveries = "SELECT 'Completed Deliveries',COUNT(*) FROM subdelivery s
                                            WHERE s.status = 'Completed' AND s.deliveredBy = '{$_SESSION['user']}'",

            $sqlTotalDistance = "SELECT 'Distance Covered',CONCAT(ROUND(SUM(s.distance),2),' km') FROM subdelivery s
                                            WHERE s.status = 'Completed' AND s.deliveredBy = '{$_SESSION['user']}'",
        ];

        $statement = self::prepare(implode(" UNION ",$arrayOfSql));
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_KEY_PAIR);
    }

}