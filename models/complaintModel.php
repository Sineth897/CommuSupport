<?php

namespace app\models;

use app\core\DbModel;
use app\core\Model;
use app\core\notificationModel;
use app\core\donationModel;
use mysql_xdevapi\Exception;

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
            "complaint"=>[self::$REQUIRED],
        ];

    }

    public function save(): bool
    {

        try {
        $this->complaintID = substr(uniqid('complaint', true), 0, 23);
        $this->filedBy=$_SESSION['user'];

            $this->choID = $this->getchoIDofDonor($_SESSION['user']);
        }
        catch (Exception $e) {
            echo 'aul';
        }

        return parent::save();


    }

    private function getchoIDofDonor($donorID)
    {
        $statement = self::prepare("SELECT c.cho from communitycenter c INNER JOIN donor d ON c.ccID = d.ccID WHERE d.donorID=:donorID LIMIT 1");
        $statement->bindValue(':donorID', $donorID);
        $statement->execute();
        return $statement->fetch(\PDO::FETCH_COLUMN);
    }

    public function getAllComplaints(string $choID)
    {
        $statement = self::prepare("SELECT u.username,c.filedDate,c.filedBy,c.subject,c.complaintID,c.status,c.solution,c.reviewedDate FROM complaint c
        INNER JOIN users u ON c.filedBy=u.userID where choID=:choID");

        $statement->bindValue(':choID', $choID);
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_ASSOC);



    }


    public function getOwnComplaints(string $userID)
    {
        $statement = self::prepare("SELECT c.complaintID,c.complaint,c.filedDate,c.status,c.solution,c.reviewedDate,s.subcategoryName from complaint c INNER JOIN donation d ON c.subject=d.donationID INNER JOIN subcategory s ON d.item=s.subcategoryID where filedBy=:userID");
        $statement->bindValue(':userID', $userID);
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }


    //submitting the solution for each complaint
    //solution and related complaintID passing as query parameters
       public function submitSolution(string $solution,string $complaintID)
   {
       $updateDate=date('Y-m-d');
       $updateStatus="Completed";
       $statement = self::prepare("UPDATE complaint SET solution=:solution,reviewedDate=:updateDate,status=:updateStatus WHERE complaintID=:complaintID");
       $statement->bindValue(':solution',$solution);
       $statement->bindValue(':updateDate',$updateDate);
       $statement->bindValue(':updateStatus',$updateStatus);
       $statement->bindValue(':complaintID',$complaintID);
       $statement->execute();
       return $statement->fetchAll(\PDO::FETCH_ASSOC);

   }

    // select user types
    public function getUserType(): bool|array
    {
        $statement = self::prepare("SELECT userType FROM users");
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_KEY_PAIR);
    }

    // retrieve all complaints
    public function allComplaints()
    {
        $statement = self::prepare("SELECT * from complaint");
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function provideSolution(string $donorID,string $complaintID)
    {  $statement = self::prepare("SELECT u.username,c.complaint,c.filedDate,c.filedBy,SUBSTRING(REGEXP_REPLACE(c.subject, '[^a-zA-Z]', ''),1,8) AS sub,c.complaintID,c.status,c.solution,c.reviewedDate FROM complaint c
        INNER JOIN users u ON c.filedBy=u.userID where filedBy=:filedBy and complaintID=:complaintID");
//        $statement = self::prepare("SELECT * from complaint c INNER JOIN donation d ON c.subject=d.donationID INNER JOIN subcategory s ON d.item=s.subcategoryID where filedBy=:filedBy and complaintID=:complaintID");
        $statement->bindValue(':filedBy', $donorID);
        $statement->bindValue(':complaintID',$complaintID);

        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function requestComplaints($requestID)
    {
        $statement = self::prepare("SELECT r.approvedDate,s.subcategoryName,r.amount,r.urgency,
        r.postedDate,r.expDate,r.notes FROM acceptedrequest r INNER JOIN  subcategory s ON r.item = s.subcategoryID where acceptedID=:requestID");
        $statement->bindValue(':requestID',$requestID);
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_ASSOC);

    }


}