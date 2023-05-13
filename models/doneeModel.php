<?php

namespace app\models;

use app\core\Application;
use app\core\DbModel;

class doneeModel extends DbModel
{
    public string $doneeID = '';
    public string $ccID = '';
    public string $registeredDate = '';
    public int $verificationStatus = 0;
    public string $email = '';
    public string $address = '';
    public string $contactNumber = '';
    public string $type = 'Individual';
    public int $mobileVerification = 0;
    public string $longitude = '0';
    public string $latitude = '0';

    public function table(): string
    {
        return 'donee';
    }

    public function attributes(): array
    {
        return ['doneeID', 'ccID', 'registeredDate', 'verificationStatus', 'email', 'address', 'contactNumber', 'type', 'mobileVerification', 'longitude', 'latitude'];
    }

    public function primaryKey(): string
    {
        return 'doneeID';
    }

    public function rules(): array
    {
        return [
            'ccID' => [self::$REQUIRED],
            'email' => [self::$REQUIRED, self::$EMAIL, [self::$UNIQUE, 'class' => self::class]],
            'address' => [self::$REQUIRED, [self::$UNIQUE, 'class' => self::class]],
            'contactNumber' => [self::$REQUIRED, self::$CONTACT, [self::$UNIQUE, 'class' => self::class]],
            'type' => [self::$REQUIRED],
            'longitude' => [self::$REQUIRED, self::$LONGITUDE],
            'latitude' => [self::$REQUIRED, self::$LATITUDE],
        ];
    }

    /**
     * @param string $ccID
     * @return array
     */
    public function getDoneeIndividuals(string $ccID = "") : array {
        if($ccID == "") {
            return $this->retrieveWithJoin('doneeindividual','doneeID');
        }
        return $this->retrieveWithJoin('doneeindividual', 'doneeID', ['donee.ccID' => $ccID]);
    }

    /**
     * @param string $ccID
     * @return array
     */
    public function getDoneeOrganizations(string $ccID = "") : array {
        if($ccID == "") {
            return $this->retrieveWithJoin('doneeorganization','doneeID');
        }
        return $this->retrieveWithJoin('doneeorganization', 'doneeID', ['donee.ccID' => $ccID]);
    }

    /**
     * @param string $ccID
     * @return array
     */
    public function getAllDonees(string $ccID = '') : array
    {
        $individuals = $this->getDoneeIndividuals($ccID);
        $organizations = $this->getDoneeOrganizations($ccID);
        return ['individuals' => $individuals, 'organizations' => $organizations];
    }

    /**
     * @param array $data
     * @return bool
     */
    public function saveOnALL(array $data) : bool {
        $data['doneeID'] = substr(uniqid('donee',true),0,23);
        $data['registeredDate'] = date('Y-m-d');
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        if ($data['type'] === 'Individual') {
            return $this->saveOnDoneeIndividual($data);
        } else {
            return $this->saveOnDoneeOrganization($data);
        }
    }

    /**
     * @param array $data
     * @return bool
     */
    private function saveOnDoneeIndividual(array $data): bool {
        try {
            $nicFront = Application::file()->saveDonee('nicFront', $data['doneeID']);
            $nicBack = Application::file()->saveDonee('nicBack', $data['doneeID'], 'back');
            if ($nicFront !== true || $nicBack !== true) {
                $this->addError('nicFront', $nicFront);
                $this->addError('nicBack', $nicBack);
                return false;
            }
            $cols = ['doneeID','ccID','registeredDate','email','address','contactNumber','type','fname','lname','NIC','age','username','password','longitude','latitude'];
            $sql = 'CALL insertDoneeIndividual(' . implode(',', array_map((fn($attr) => ":$attr"), $cols)) . ')';
            $stmt = self::prepare($sql);
            foreach ($cols as $key) {
                $stmt->bindValue(":$key", $data[$key]);
            }
            echo $sql;
            return $stmt->execute();
        } catch (\PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    /**
     * @param array $data
     * @return bool
     */
    private function saveOnDoneeOrganization(array $data): bool {
        try {
            $certificateFront = Application::file()->saveDonee('certificateFront', $data['doneeID']);
            $certificateBack = Application::file()->saveDonee('certificateBack', $data['doneeID'], 'back');
            if ($certificateFront !== true || $certificateBack !== true) {
                $this->addError('certificateFront', $certificateFront);
                $this->addError('certificateBack', $certificateBack);
                return false;
            }
            $cols = ['doneeID', 'ccID', 'registeredDate', 'email', 'address', 'contactNumber', 'type', 'organizationName', 'regNo', 'representative', 'representativeContact', 'capacity', 'username', 'password', 'longitude', 'latitude'];
            $sql = 'CALL insertDoneeOrganization(' . implode(',', array_map((fn($attr) => ":$attr"), $cols)) . ')';
            $stmt = self::prepare($sql);
            foreach ($cols as $key) {
                $stmt->bindValue(":$key", $data[$key]);
            }
            return $stmt->execute();
        } catch (\PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    private function ownComplaints($userID)
    {
        $statement = self::prepare("SELECT filedDate,subject,status,solution,reviewedDate from complaint where filedBy=:userID");
        $statement->bindValue(":userID", $userID);
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_KEY_PAIR);
    }

    public function getDoneebyCategory(): array
    {
//         get the count of donees and group by type

        $sql = "SELECT type, COUNT(doneeID) as count  FROM donee GROUP BY type";
        $statement = self::prepare($sql);
        $statement->execute();
        $result = $statement->fetchAll(\PDO::FETCH_ASSOC);

        $chartData = array();
        // Loop through the result and update the corresponding value in the new array
        foreach ($result as $row) {
            $chartData[$row['type']] = $row['count'];
        }
        return $chartData;
    }

    public function getDoneeRegMonthly(): array
    {
        $chartData = array();
        // Create an array with all 12 months of the year
        $monthsOfYear = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
        // Get the count of requests published on each month for urgency = "Within 7 days"
        $sql = "SELECT COUNT(*) as count, MONTHNAME(registeredDate) as month FROM donee GROUP BY MONTH(registeredDate)";
        $statement = requestModel::prepare($sql);
        $statement->execute();
        $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
        // Loop through the result and update the corresponding value in the new array
        $chartData = array_fill_keys($monthsOfYear, 0);
        foreach ($result as $row) {
            $chartData[$row['month']] = $row['count'];
        }
        return $chartData;
    }

    /**
     * @return array
     */
    public function getDoneeInformationForProfile() : array {
        return [
            $this->getPersonalInfo()[0],
            $this->getDoneeStatistics(),
        ];
    }

    /**
     * @return array
     */
    private function getPersonalInfo() : array {

        $colsIndividual = "u.*,d.*,di.fname,di.lname,di.NIC,di.age,c.city,c2.district";
        $colsOrganization = "u.*,d.*,do.organizationName,do.representative,do.representativeContact,do.capacity,c.city,c2.district";

        $sqlIndividual = "SELECT {$colsIndividual} FROM users u 
                            INNER JOIN donee d ON u.userID = d.doneeID
                            INNER JOIN doneeindividual di ON d.doneeID = di.doneeID
                            INNER JOIN communitycenter c on d.ccID = c.ccID
                            INNER JOIN communityheadoffice c2 on c.cho = c2.choID
                            WHERE u.userID = '{$_SESSION['user']}'";

        $sqlOrganization = "SELECT {$colsOrganization} FROM users u
                            INNER JOIN donee d ON u.userID = d.doneeID
                            INNER JOIN doneeorganization do ON d.doneeID = do.doneeID
                            INNER JOIN communitycenter c on d.ccID = c.ccID
                            INNER JOIN communityheadoffice c2 on c.cho = c2.choID
                            WHERE u.userID = '{$_SESSION['user']}'";

        $statement = self::prepare($sqlIndividual . " UNION " . $sqlOrganization);
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * @return array
     */
    private function getDoneeStatistics() : array {

        $arrayOfSql = [
            $sqlEventParticipartion = "SELECT 'Event Participation',COUNT(*) FROM eventparticipation 
                                            WHERE userID = '{$_SESSION['user']}'",

            $sqlNotApprovedRequests = "SELECT 'Requests waiting for Approval',COUNT(*) FROM request 
                                            WHERE postedBy = '{$_SESSION['user']}' AND approval = 'pending'",

            $sqlActiveRequests = "SELECT 'Active Requests',COUNT(*) FROM request 
                                            WHERE postedBy = '{$_SESSION['user']}' AND approval = 'Approved'",

            $sqlAwaitingDeliveries = "SELECT 'Awaiting Deliveries',COUNT(*) FROM delivery 
                                            WHERE end = '{$_SESSION['user']}' AND status != 'Completed'",

            $sqlActiveComplaints = "SELECT 'Active Complaints',COUNT(*) FROM complaint 
                                            WHERE filedBy = '{$_SESSION['user']}' AND status != 'Completed'",

            $sqlSolvedComplaints = "SELECT 'Solved Complaints',COUNT(*) FROM complaint 
                                            WHERE filedBy = '{$_SESSION['user']}' AND status = 'Completed'",
        ];

        $statement = self::prepare(implode(" UNION ",$arrayOfSql));
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_KEY_PAIR);

    }

}