<?php

namespace app\models;

use app\core\DbModel;
use app\core\Model;

class complaintModel extends DbModel
{
    public string $complaintID = "";

    public string $complaint="";
    public string $filedBy = "";
    public string $filedDate = "";
    public string $subject = "";
    public string $status = "";
    public string $solution = "";
    public string $reviewedDate = "";

    public string $choID = "";

    public function table(): string
    {
        return "complaint";

    }

    public function attributes(): array
    {
        return ["complaintID","complaint","filedBy", "filedDate", "subject", "choID"];

    }

    public function primaryKey(): string
    {
        return "complaintID";
    }

    public function rules(): array
    {
        return [
            "complaint"=>[self::$REQUIRED],
        ];

    }

    public function save(): bool
    {
        $this->complaintID = substr(uniqid('complaint', true), 0, 23);
        $this->filedBy=$_SESSION['user'];
//        protected function getchoIDforComplaints($donorID)
//    {
//
//        $statement= self::prepare("SELECT choID.cc from donor INNER JOIN communitycenter ON donor.ccID = communitycenter.ccID where donorID=:$donorID");
//        $statement->bindValue(':userID',$donorID);
//        $statement->execute();
//        return $statement->fetchAll(\PDO::FETCH_ASSOC);
//
//    }
//        $this->choID=$_SESSION['user'];

        return parent::save();

    }

    public function getComplaints(string $choID)
    {
        $statement= self::prepare("SELECT filedBy,filedDate,subject,status,solution,reviewedDate from complaint where choID=:choID");
        $statement->bindValue(':choID',$choID);
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_ASSOC);

    }

    public function getOwnComplaints(string $userID)
        {
            $statement = self::prepare("SELECT filedDate,subject,status,solution,reviewedDate from complaint where filedBy=:userID");
            $statement->bindValue(':userID',$userID);
            $statement->execute();
            return $statement->fetchAll(\PDO::FETCH_ASSOC);
        }


}