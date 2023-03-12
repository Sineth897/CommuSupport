<?php

namespace app\models;

use app\core\Application;
use app\core\DbModel;

class ccModel extends DbModel
{
    public string $ccID = "";
    public string $address = "";
    public string $city = "";
    public float $longitude = 0.0;
    public float $latitude = 0.0;
    public string $email = "";
    public string $fax = "";
    public string $contactNumber = "";
    public string $cho = "";

    public function table(): string
    {
        return "communityCenter";
    }

    public function attributes(): array
    {
        return ["ccID", "address", "city","longitude","latitude", "email", "fax", "contactNumber", "cho"];
    }

    public function primaryKey(): string
    {
        return "ccID";
    }

    public function rules(): array
    {
        return [

            "address" => [self::$REQUIRED, [self::$UNIQUE, "class" => self::class]],
            "city" => [self::$REQUIRED, [self::$UNIQUE, 'class' => self::class]],
            "email" => [self::$REQUIRED, self::$EMAIL, [self::$UNIQUE, "class" => self::class]],
            "fax" => [self::$REQUIRED, [self::$UNIQUE, 'class' => self::class]],
            "contactNumber" => [self::$REQUIRED, [self::$UNIQUE, 'class' => self::class]],
            'longitude' => [self::$REQUIRED, self::$LONGITUDE],
            'latitude' => [self::$REQUIRED, self::$LATITUDE],

        ];
    }


    public function getCoordinates()
    {
        $stmnt = self::prepare("SELECT ccID,longitude,latitude FROM communitycenter");
        $stmnt->execute();
        return $stmnt->fetchALL(\PDO::FETCH_ASSOC);
    }

    public function getAll(string $choID)
    {
        $stmnt = self::prepare("SELECT cc.*,m.name AS manager,l.name AS logistic FROM manager m INNER JOIN communitycenter cc ON m.ccID = cc.ccID INNER JOIN logisticofficer l ON l.ccID=cc.ccID WHERE cc.cho = :choID; ");
        $stmnt->bindValue(':choID',$choID);
        $stmnt ->execute();
        return $stmnt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function save(): bool
    {
        $this->ccID = substr(uniqid('cc', true),0,23);
        $this->cho = $_SESSION['user'];
        return parent::save();
    }

}