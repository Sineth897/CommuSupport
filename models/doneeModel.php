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

    public function getAllDonees(string $ccID = ''): array
    {
        if($ccID) {
            $individuals = $this->retrieveWithJoin('doneeindividual','doneeID','ccID',$ccID);
            $organizations = $this->retrieveWithJoin('doneeorganization','doneeID','ccID',$ccID);
            return ['individuals' => $individuals, 'organizations' => $organizations];
        }
        $individuals = $this->retrieveWithJoin('doneeindividual','doneeID');
        $organizations = $this->retrieveWithJoin('doneeorganization','doneeID');
        return ['individuals' => $individuals, 'organizations' => $organizations];
    }
}