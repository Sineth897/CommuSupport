<?php

namespace app\models;

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
            'email' => [self::$REQUIRED, self::$EMAIL],
            'address' => [self::$REQUIRED],
            'contactNumber' => [self::$REQUIRED, self::$CONTACT],
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
}