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
        return "communitycenter";
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
            "fax" => [self::$REQUIRED, [self::$UNIQUE, 'class' => self::class],self::$CONTACT],
            "contactNumber" => [self::$REQUIRED, [self::$UNIQUE, 'class' => self::class],self::$CONTACT],
            'longitude' => [self::$REQUIRED, self::$LONGITUDE],
            'latitude' => [self::$REQUIRED, self::$LATITUDE],
            'cho' => [self::$REQUIRED],

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
        $stmnt = self::prepare("SELECT cc.*, m.name AS manager,l.name AS logistic FROM manager m RIGHT JOIN communitycenter cc ON m.ccID = cc.ccID LEFT JOIN logisticofficer l ON l.ccID=cc.ccID WHERE cc.cho = :choID; ");
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

    public static function getCCs()
    {
        $stmnt = self::prepare("SELECT ccID,city FROM communitycenter");
        $stmnt->execute();
        return $stmnt->fetchAll(\PDO::FETCH_KEY_PAIR);
    }

    public function getHighestPerformingCC()
    {
//         give out the community center with the highest number of registrations
        $sql = "SELECT communitycenter.city, COUNT(*) AS registration_count FROM communitycenter LEFT JOIN donor ON communitycenter.ccID = donor.ccID LEFT JOIN donee ON communitycenter.ccID = donee.ccID GROUP BY communitycenter.ccID ORDER BY registration_count DESC LIMIT 5;";
        $stmnt = self::prepare($sql);
        $stmnt->execute();
        $result = $stmnt->fetchAll(\PDO::FETCH_ASSOC);

        $chartData = array();
        // Loop through the result and update the corresponding value in the new array
        foreach ($result as $row) {
            $chartData[$row['city']] = $row['registration_count'];
        }
        return $chartData;
    }








}