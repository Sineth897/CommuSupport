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

<<<<<<< HEAD
    public function rules(): array
    {
        // TODO: Implement rules() method.
    }

    public function table(): string
    {
        // TODO: Implement table() method.
=======
    public function table(): string
    {
        return "complaint";
>>>>>>> eff85e44986d3bc01499410308913423699b159b
    }

    public function attributes(): array
    {
<<<<<<< HEAD
        // TODO: Implement attributes() method.
=======
        return ["complaintID","filedBy","filedDate","subject","status","solution","reviewedDate"];
>>>>>>> eff85e44986d3bc01499410308913423699b159b
    }

    public function primaryKey(): string
    {
<<<<<<< HEAD
        // TODO: Implement primaryKey() method.
=======
        return "complaintID";
    }

    public function rules(): array
    {
        return [
            "filedBy" => [self::$REQUIRED],
            "filedDate" => [self::$REQUIRED],
            "subject" => [self::$REQUIRED],
            "status"=> [self::$REQUIRED],
            "solution"=> [self::$REQUIRED],
            "reviewedDate" => [self::$REQUIRED]

        ];
>>>>>>> eff85e44986d3bc01499410308913423699b159b
    }
}