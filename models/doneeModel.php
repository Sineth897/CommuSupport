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
    public string $type = '';

    public function table(): string
    {
        return 'donee';
    }

    public function attributes(): array
    {
        return ['doneeID', 'ccID', 'registeredDate', 'verificationStatus', 'email', 'address', 'contactNumber', 'type'];
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
        ];
    }

    public function getDoneeIndividuals(string $ccID = "") {
        if($ccID == "") {
            return $this->retrieveWithJoin('doneeindividual','doneeID');
        }
        return $this->retrieveWithJoin('doneeindividual','doneeID',['donee.ccID' => $ccID]);
    }

    public function getDoneeOrganizations(string $ccID = "") {
        if($ccID == "") {
            return $this->retrieveWithJoin('doneeorganization','doneeID');
        }
        return $this->retrieveWithJoin('doneeorganization','doneeID',['donee.ccID' => $ccID]);
    }

    public function getAllDonees(string $ccID = ''): array
    {
        $individuals = $this->getDoneeIndividuals($ccID);
        $organizations = $this->getDoneeOrganizations($ccID);
        return [ 'individuals' => $individuals, 'organizations' => $organizations];
    }

    public function saveOnALL(array $data) {
        $data['doneeID'] = substr(uniqid('donee',true),0,23);
        $data['registeredDate'] = date('Y-m-d');
        $data['password'] = password_hash($data['password'],PASSWORD_DEFAULT);
        if($data['type'] === 'Individual') {
            return $this->saveOnDoneeIndividual($data);
        } else {
            return $this->saveOnDoneeOrganization($data);
        }
    }

    private function saveOnDoneeIndividual(array $data): bool {
        try {
            $nicFront = Application::file()->saveDonee('nicFront',$data['doneeID']);
            $nicBack = Application::file()->saveDonee('nicBack',$data['doneeID'],'back');
            if(!($nicFront || $nicBack)) {
                $this->addError('nicFront',$nicFront);
                $this->addError('nicBack',$nicBack);
                return false;
            }
            $cols = ['doneeID','ccID','registeredDate','email','address','contactNumber','type','fname','lname','nic','age','username','password'];
            $sql = 'CALL insertDoneeIndividual(' . implode(',', array_map((fn($attr) => ":$attr"), $cols)) . ')';
            $stmt = self::prepare($sql);
            foreach($cols as $key) {
                $stmt->bindValue(":$key",$data[$key]);
            }
            echo $sql;
            return $stmt->execute();
        }
        catch(\PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    private function saveOnDoneeOrganization(array $data): bool {
        try {
            $certificateFront = Application::file()->saveDonee('certificateFront',$data['doneeID']);
            $certificateBack = Application::file()->saveDonee('certificateBack',$data['doneeID'],'back');
            if(!( $certificateFront || $certificateBack)) {
                return false;
            }
            $cols = ['doneeID','ccID','registeredDate','email','address','contactNumber','type','organizationName','regNo','representative','representativeContact','capacity','username','password'];
            $sql = 'CALL insertDoneeOrganization(' . implode(',', array_map((fn($attr) => ":$attr"), $cols)) . ')';
            $stmt = self::prepare($sql);
            foreach($cols as $key) {
                $stmt->bindValue(":$key",$data[$key]);
            }
            return $stmt->execute();
        }
        catch(\PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }
}