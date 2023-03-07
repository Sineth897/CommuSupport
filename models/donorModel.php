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

    private userModel $user;
    private donorIndividualModel $donorIndividual;
    private donorOrganizationModel $donorOrganization;

    public function table(): string
    {
        return 'donor';
    }

    public function attributes(): array
    {
        return ['donorID', 'ccID', 'registeredDate', 'email', 'address', 'contactNumber', 'type','mobileVerification'];
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
        ];
    }

    public function getDonorIndividuals(string $ccID = "") {
        if($ccID == "") {
            return $this->retrieveWithJoin('donorindividual','donorID');
        }
        return $this->retrieveWithJoin('donorindividual','donorID',['donor.ccID' => $ccID]);
    }

    public function getDonorOrganizations(string $ccID = "") {
        if($ccID == "") {
            return $this->retrieveWithJoin('donororganization','donorID');
        }
        return $this->retrieveWithJoin('donororganization','donorID',['donor.ccID' => $ccID]);
    }

    public function getAllDonors(string $ccID = ''): array
    {
        $individuals = $this->getDonorIndividuals($ccID);
        $organizations = $this->getDonorOrganizations($ccID);
        return [ 'individuals' => $individuals, 'organizations' => $organizations];
    }

    public function getDonorTypes() {
        return [
            'Individual' => 'Individual',
            'Organization' => 'Organization'
        ];
    }

    public function setUser(userModel $user) {
        $this->user = $user;
        $this->user->userType = "donor";
        $this->user->userID = $this->donorID;
    }

    public function setDonorIndividual(donorIndividualModel $donorIndividual) {
        $this->donorIndividual = $donorIndividual;
        $this->donorIndividual->donorID = $this->donorID;
    }

    public function setDonorOrganization(donorOrganizationModel $donorOrganization) {
        $this->donorOrganization = $donorOrganization;
        $this->donorOrganization->donorID = $this->donorID;
    }

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

    private function saveIndividualDonor($data): bool {
        try {
            $cols = ['donorID', 'ccID', 'registeredDate', 'email', 'address', 'contactNumber', 'type','fname','lname','nic','age','username','password'];
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

    private function saveOrganizationDonor($data): bool {
        try {
            $cols = ['donorID', 'ccID', 'registeredDate', 'email', 'address', 'contactNumber', 'type','organizationName','regNo','representative','representativeContact','username','password'];
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

    public function createDonation($data): bool
    {
        $cols = ['donationID', 'createdBy', 'item', 'amount', 'address', 'donateTo'];
        $data['donationID'] = substr(uniqid('donation',true),0,23);
        $data['createdBy'] = $this->donorID;
        if(empty($data['address'])) {
            $data['address'] = $this->address;
        }
        if(empty($data['donateTo'])) {
            $data['donateTo'] = $this->ccID;
        }
        try {
            $sql = "INSERT INTO donation (donationID,createdBy,item,amount,address,donateTo) VALUES (:donationID,:createdBy,:item,:amount,:address,:donateTo)";
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

    public function filterRequests(array $requests): array {
        $sql = acceptedModel::prepare("SELECT requestID FROM acceptedrequest WHERE acceptedBy = :donorID");
        $sql->bindValue(":donorID",$this->donorID);
        $sql->execute();
        $acceptedRequests = $sql->fetchAll(\PDO::FETCH_COLUMN);
        return array_filter($requests,function($request) use ($acceptedRequests) {
            return !in_array($request['requestID'],$acceptedRequests);
        });
    }

}