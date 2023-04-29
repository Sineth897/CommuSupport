<?php

namespace app\models;

use app\core\DbModel;
use app\core\Model;

class complaintModel extends DbModel
{
    public string $complaintID = "";

    public string $complaint = "";
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
        return ["complaintID", "complaint", "filedBy", "subject", "choID"];

    }

    public function primaryKey(): string
    {
        return "complaintID";
    }

    public function rules(): array
    {
        return [
            "complaint" => [self::$REQUIRED],
        ];

    }

    public function save(): bool
    {
        $this->complaintID = substr(uniqid('complaint', true), 0, 23);
        $this->filedBy = $_SESSION['user'];


        $this->choID = $this->getchoIDofDonor($_SESSION['user'])['cho'];


        return parent::save();


    }

    private function getchoIDofDonor($donorID)
    {
        $statement = self::prepare("SELECT c.cho from communitycenter c INNER JOIN donor d ON c.ccID = d.ccID WHERE d.donorID=:donorID ");
        $statement->bindValue(':donorID', $donorID);
        $statement->execute();
        return $statement->fetch(\PDO::FETCH_ASSOC);


    }

    public function getAllComplaints(string $choID)
    {
        $statement = self::prepare("SELECT u.username,c.filedDate,s.subcategoryName,c.status,c.solution,c.reviewedDate FROM complaint c INNER JOIN users u ON c.filedBy=u.userID INNER JOIN donation d ON c.subject=d.donationID INNER JOIN subcategory s ON d.item=s.subcategoryID where choID=:choID");
        $statement->bindValue(':choID', $choID);
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_ASSOC);

    }

    public function getOwnComplaints(string $userID)
    {
        $statement = self::prepare("SELECT c.complaint,c.filedDate,c.status,c.solution,c.reviewedDate,s.subcategoryName from complaint c INNER JOIN donation d ON c.subject=d.donationID INNER JOIN subcategory s ON d.item=s.subcategoryID where filedBy=:userID");
        $statement->bindValue(':userID', $userID);
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

       public function submitSolution(string $solution,string $complaintID)
   {
       $updateDate=date('Y-m-d');
       $updateStatus="completed";
       $statement = self::prepare("UPDATE complaint SET solution=:solution,reviewedDate=:updateDate,status=:updateStatus WHERE complaintID=:complaintID");
       $statement->bindValue(':solution',$solution);
       $statement->bindValue(':updateDate',$updateDate);
       $statement->bindValue(':updateStatus',$updateStatus);
       $statement->bindValue(':complaintID',$complaintID);
       $statement->execute();
       return $statement->fetchAll(\PDO::FETCH_ASSOC);

   }


}