<?php

namespace app\models;
															

use app\core\DbModel;					
class acceptedModel extends DbModel
{
    public string $requestID = "";
    public string $acceptedBy ="";
    public string $acceptedDate = "";
    public string $status = "";
    public string $postedBy = "";
    public string $approval = "";
    public string $approvedDate = "";
    public string $item= "";
    public string $amount= "";
    public string $address= "";
    public string $urgency= "";
    public string $postedDate= "";
    public string $notes= "";
    public string $deliveryID= "";
    public string $deliveryStatus= "";

    public function table(): string
    {
        return "acceptedrequest";
    }

    public function attributes(): array
    {
        return ["requestID","acceptedBy","acceptedDate","status","postedBy","approval","approvedDate","item","amount","address","urgency","postedDate","notes","deliveryID","deliveryStatus"];
    }

    public function primaryKey(): string
    {
        return "requestID";
    }

    public function rules(): array
    {
        return [

            "acceptedBy" => [self::$REQUIRED, [self::$UNIQUE, "class" => self::class]],
            "acceptedDate" => [self::$REQUIRED, [self::$UNIQUE, 'class' => self::class]],
            "approvedDate" => [self::$REQUIRED, self::$EMAIL, [self::$UNIQUE,"class" => self::class]],
            "status" => [self::$REQUIRED, [self::$UNIQUE, 'class' => self::class]],
            "postedBy" => [self::$REQUIRED, [self::$UNIQUE, 'class' => self::class]],
            "approval" => [self::$REQUIRED],
            "item" => [self::$REQUIRED],
            "amount" => [self::$REQUIRED],
            "address" => [self::$REQUIRED],
            "urgency" => [self::$REQUIRED],
            "postedDate" => [self::$REQUIRED],
            "notes" => [self::$REQUIRED],
            "deliveryID" => [self::$REQUIRED],
            "deliveryStatus" => [self::$REQUIRED],
            



        ];
    }
}