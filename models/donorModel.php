<?php

namespace app\models;

use app\core\DbModel;

class donorModel extends DbModel
{
    public string $doneeID = '';
    public string $ccID = '';
    public string $registeredDate = '';
    public string $email = '';
    public string $address = '';
    public string $contactNumber = '';
    public string $type = '';

    public function table(): string
    {
        return 'donor';
    }

    public function attributes(): array
    {
        return ['donorID', 'ccID', 'registeredDate', 'email', 'address', 'contactNumber', 'type'];
    }

    public function primaryKey(): string
    {
        return 'donorID';
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

}