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
        return ["complaintID","complaint","filedBy", "subject", "choID"];

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


        $this->choID = $this->getchoIDofDonor($_SESSION['user'])['cho'];

//        echo "<pre>";
//        print_r($this->getchoIDofDonor($_SESSION['user']));
//        echo $_SESSION['user'];
//        echo "</pre>";

        return parent::save();
//        return 0;

    }

    private function getchoIDofDonor($donorID)
    {
        $statement = self ::prepare("SELECT c.cho from communitycenter c INNER JOIN donor d ON c.ccID = d.ccID WHERE d.donorID=:donorID ");
        $statement -> bindValue(':donorID',$donorID);
        $statement->execute();
        return $statement->fetch(\PDO::FETCH_ASSOC);


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