<?php

namespace app\models;

use app\core\DbModel;

class donorModel extends DbModel
{
    public string $donorID = '';
    public string $ccID = '';
    public string $registeredDate = '';
    public string $email = '';
    public string $address = '';
    public string $contactNumber = '';
    public string $type = '';
    public int $mobileVerification = 0;
    public string $longitude = '0';
    public string $latitude = '0';

    public function table(): string
    {
        return 'donor';
    }

    public function attributes(): array
    {
        return ['donorID', 'ccID', 'registeredDate', 'email', 'address', 'contactNumber', 'type','mobileVerification','longitude','latitude'];
    }

    public function primaryKey(): string
    {
        return 'donorID';
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
    public function getDonorIndividuals(string $ccID = "") : array {
        if($ccID == "") {
            return $this->retrieveWithJoin('donorindividual','donorID');
        }
        return $this->retrieveWithJoin('donorindividual','donorID',['donor.ccID' => $ccID]);
    }

    /**
     * @param string $ccID
     * @return array
     */
    public function getDonorOrganizations(string $ccID = "") : array {
        if($ccID == "") {
            return $this->retrieveWithJoin('donororganization','donorID');
        }
        return $this->retrieveWithJoin('donororganization','donorID',['donor.ccID' => $ccID]);
    }

    /**
     * @param string $ccID
     * @return array
     */
    public function getAllDonors(string $ccID = ''): array
    {
        $individuals = $this->getDonorIndividuals($ccID);
        $organizations = $this->getDonorOrganizations($ccID);
        return [ 'individuals' => $individuals, 'organizations' => $organizations];
    }

    /**
     * @param $data
     * @return bool
     */
    public function saveOnALL($data): bool
    {
        $data["donorID"] = substr(uniqid('donor',true),0,23);
        $data["registeredDate"] = date("Y-m-d");
        $data["password"] = password_hash($data["password"],PASSWORD_DEFAULT);
        if($data['type'] === "Individual") {
            return $this->saveIndividualDonor($data);
        } else {
            return $this->saveOrganizationDonor($data);
        }
    }

    /**
     * @param $data
     * @return bool
     */
    private function saveIndividualDonor($data): bool {
        try {
            $cols = ['donorID', 'ccID', 'registeredDate', 'email', 'address', 'contactNumber', 'type','fname','lname','nic','age','username','password','longitude','latitude'];
            $params = array_map((fn($attr) => ":$attr"), $cols);
            $sql = "CALL insertDonorIndividual(" . implode(',', $params) . ")";
            echo $sql;
            $statement = self::prepare($sql);
            foreach ($cols as $attr) {
                $statement->bindValue(":$attr", $data[$attr]);
            }
            $statement->execute();
            return true;
        }
        catch (\PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    /**
     * @param $data
     * @return bool
     */
    private function saveOrganizationDonor($data): bool {
        try {
            $cols = ['donorID', 'ccID', 'registeredDate', 'email', 'address', 'contactNumber', 'type','organizationName','regNo','representative','representativeContact','username','password','longitude','latitude'];
            $params = array_map((fn($attr) => ":$attr"), $cols);
            $sql = "CALL insertDonorOrganization(" . implode(',', $params) . ")";
            $statement = self::prepare($sql);
            foreach ($cols as $attr) {
                $statement->bindValue(":$attr", $data[$attr]);
            }
            $statement->execute();
            return true;
        }
        catch (\PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    /**
     * @param $data
     * @return bool
     */
    public function createDonation($data): bool
    {
        $cols = ['donationID', 'createdBy', 'item', 'amount', 'donateTo'];
        $data['donationID'] = substr(uniqid('donation',true),0,23);
        $data['createdBy'] = $this->donorID;
        if(empty($data['donateTo'])) {
            $data['donateTo'] = $this->ccID;
        }
        try {
            $sql = "INSERT INTO donation (donationID,createdBy,item,amount,donateTo) VALUES (:donationID,:createdBy,:item,:amount,:donateTo)";
            $statement = self::prepare($sql);
            foreach ($cols as $attr) {
                $statement->bindValue(":$attr", $data[$attr]);
            }
            return $statement->execute();
        }
        catch (\PDOException $e) {
            echo $e->getMessage();
            return false;
        }

    }

    /**
     * @param array $requests
     * @return array
     */
    public function filterRequests(array $requests): array {
        $sql = acceptedModel::prepare("SELECT requestID FROM acceptedrequest WHERE acceptedBy = :donorID");
        $sql->bindValue(":donorID",$this->donorID);
        $sql->execute();
        $acceptedRequests = $sql->fetchAll(\PDO::FETCH_COLUMN);
        return array_filter($requests,function($request) use ($acceptedRequests) {
            return !in_array($request['requestID'],$acceptedRequests);
        });
    }

    /**
     * @param string $ccID
     * @return array
     */
    public static function getDonorIDs(string $ccID): array {
        $sql = self::prepare("SELECT donorID FROM donor WHERE ccID = :ccID");
        $sql->bindValue(":ccID",$ccID);
        $sql->execute();
        return $sql->fetchAll(\PDO::FETCH_COLUMN);
    }


    public function getDonorbyCategory() {
//         get the count of donors and group by type

        $sql = "SELECT COUNT(donorID) as count,type FROM donor GROUP BY type";
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

    public function getDonorRegMonthly(): array
    {
        $chartData = array();
        // Create an array with all 12 months of the year
        $monthsOfYear = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
        // Get the count of requests published on each month for urgency = "Within 7 days"
        $sql = "SELECT COUNT(*) as count, MONTHNAME(registeredDate) as month FROM donor GROUP BY MONTH(registeredDate)";
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

    public function getDonorInformationForProfile() : array {
        return [
            $this->getPersonalInfo()[0],
            $this->getDonorStatistics(),
        ];
    }

    /**
     * @return array
     */
    private function getPersonalInfo() : array {

        $colsIndividual = "u.*,d.*,c.city,c2.district,di.fname,di.lname,di.NIC,di.age";
        $colsOrganization = "u.*,d.*,c.city,c2.district,do.organizationName,do.representative,do.representativeContact,c.fax";

        $sqlIndividual = "SELECT {$colsIndividual} FROM users u 
                            INNER JOIN donor d ON u.userID = d.donorID
                            INNER JOIN donorindividual di ON d.donorID = di.donorID
                            INNER JOIN communitycenter c on d.ccID = c.ccID
                            INNER JOIN communityheadoffice c2 on c.cho = c2.choID
                            WHERE u.userID = '{$_SESSION['user']}'";

        $sqlOrganization = "SELECT {$colsOrganization} FROM users u
                            INNER JOIN donor d ON u.userID = d.donorID
                            INNER JOIN donororganization do ON d.donorID = do.donorID
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
    private function getDonorStatistics() : array {

        $arrayOfSql = [
            $sqlEventParticipartion = "SELECT 'Event Participation',COUNT(*) FROM eventparticipation 
                                            WHERE userID = '{$_SESSION['user']}'",

            $sqlCompletedDonations = "SELECT 'Completed Donations',COUNT(*) FROM donation 
                                            WHERE createdBy = '{$_SESSION['user']}' AND deliveryStatus = 'Completed'",

            $sqlActiveDonations = "SELECT 'Active Donations',COUNT(*) FROM donation 
                                            WHERE createdBy = '{$_SESSION['user']}' AND deliveryStatus != 'Completed'",

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