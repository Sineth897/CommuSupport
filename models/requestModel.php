<?php

namespace app\models;

use app\core\DbModel;					
class requestModel extends DbModel
{
    public string $requestID = "";
    public string $postedBy ="";
    public string $approval = "";
    public string $approvedDate = "";
    public string $item = "";
    public string $amount = "";
    public string $address = "";
    public string $urgency= "";
    public string $postedDate= "";
    public string $notes= "";
    public function table(): string
    {
        return "request";
    }

    public function attributes(): array
    {
        return ["requestID","postedBy","approval","approvedDate","item","amount","address","urgency","postedDate", "notes"];
    }

    public function primaryKey(): string
    {
        return "requestID";
    }

    public function rules(): array
    {
        return [

            "postedBy" => [self::$REQUIRED, [self::$UNIQUE, "class" => self::class]],
            "approval" => [self::$REQUIRED, [self::$UNIQUE, 'class' => self::class]],
            "approvedDate" => [self::$REQUIRED, self::$EMAIL, [self::$UNIQUE,"class" => self::class]],
            "item" => [self::$REQUIRED, [self::$UNIQUE, 'class' => self::class]],
            "amount" => [self::$REQUIRED, [self::$UNIQUE, 'class' => self::class]],
            "address" => [self::$REQUIRED],
            "urgency" => [self::$REQUIRED],
            "postedDate" => [self::$REQUIRED],
            "notes" => [self::$REQUIRED],

        ];
    }
}