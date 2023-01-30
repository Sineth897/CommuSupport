<?php

namespace app\models;

use app\core\DbModel;
use app\core\Model;

class complaintModel extends DbModel
{
    public string $complaintID = "";
    public string $filledBy = "";
    public string $filedDate ="";
    public string $subject ="";
    public string $status = "";
    public string $solution = "";
    public string $reviewedDate = "";

    public function table(): string
    {
        return "complaint";
    }

    public function attributes(): array
    {
        return ["complaintID","filledBy","filedDate","subject","status","solution","reviewedDate"];
    }

    public function primaryKey(): string
    {
        return "complaintID";
    }

    public function rules(): array
    {
        return [
            "filledBy" => [self::$REQUIRED],
            "filedDate" => [self::$REQUIRED],
            "subject" => [self::$REQUIRED],
            "status"=> [self::$REQUIRED],
            "solution"=> [self::$REQUIRED],
            "reviewedDate" => [self::$REQUIRED]

        ];
    }
}