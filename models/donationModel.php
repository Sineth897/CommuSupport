<?php

namespace app\models;

use app\core\DbModel;					

class donationModel extends DbModel
{
    public string $donationID = "";
    public string $createdBy ="";
    public string $item ="";
    public string $amount = "";
    public string $date = "";
    public string $address = "";
    public string $donateTo = "";
    public string $deliveryID = "";
    public string $deliveryStatus= "";
    public function table(): string
    {
        return "donation";
    }

    public function attributes(): array
    {
        return ["donationID","createdBy","item","amount","date","address","donateTo","deliveryID","deliveryStatus"];
    }

    public function primaryKey(): string
    {
        return "donationID";
    }

    public function rules(): array
    {
        return [

            "address" => [self::$REQUIRED, [self::$UNIQUE, "class" => self::class]],
            "createdBy" => [self::$REQUIRED, [self::$UNIQUE, 'class' => self::class]],
            "item" => [self::$REQUIRED, self::$EMAIL, [self::$UNIQUE,"class" => self::class]],
            "date" => [self::$REQUIRED, [self::$UNIQUE, 'class' => self::class]],
            "donateTo" => [self::$REQUIRED, [self::$UNIQUE, 'class' => self::class]],
            "deliveryID" => [self::$REQUIRED],
            "deliveryStatus" => [self::$REQUIRED]

        ];
    }
}