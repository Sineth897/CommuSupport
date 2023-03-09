<?php

namespace app\models;

use app\core\DbModel;					
class requestModel extends DbModel
{
    public string $requestID = "";
    public string $postedBy ="";
    public string $approval = "";
    public ?string $approvedDate = "";
    public string $item = "";
    public string $amount = "";
    public string $address = "";
    public string $urgency= "";
    public string $postedDate= "";
    public string $expDate="";
    public string $notes= "";
    public function table(): string
    {
        return "request";
    }

    public function attributes(): array
    {
        return ["requestID","postedBy","item","amount","address","urgency","postedDate","expDate", "notes"];
    }

    public function primaryKey(): string
    {
        return "requestID";
    }

    public function rules(): array
    {
        return [
            "item" => [self::$REQUIRED,],
            "amount" => [self::$REQUIRED,self::$POSITIVE],
            "urgency" => [self::$REQUIRED],
//            "notes" => [self::$REQUIRED],
        ];
    }

    public function getCategories():array {
        $stmnt = self::prepare('SELECT * FROM category');
        $stmnt->execute();
        return $stmnt->fetchAll(\PDO::FETCH_KEY_PAIR);
    }

    public function getSubC($item){
        $stmnt = self::prepare('SELECT subcategoryName, scale FROM subcategory WHERE subcategoryID = :item');
        $stmnt->bindValue(':item',$item);
        $stmnt->execute();
        return $stmnt->fetch(\PDO::FETCH_ASSOC);
    }

    public function getPostedBy($userID){
        $stmnt = self::prepare('SELECT username FROM users WHERE userID = :userID');
        $stmnt->bindValue(':userID',$userID);
        $stmnt->execute();
        return $stmnt->fetch(\PDO::FETCH_ASSOC);
    }

    public function getSubcategories($category) {
        $stmnt = self::prepare('SELECT subcategoryID,subcategoryName FROM subcategory WHERE categoryID = :category');
        $stmnt->bindValue(':category',$category);
        $stmnt->execute();
        return $stmnt->fetchAll(\PDO::FETCH_KEY_PAIR);
    }

    public function getUrgency():array {
        return [
            'Within 7 days' => 'Within 7 days',
            'Within a month' => 'Within a month',
            'Within 3 month' => 'Within 3 month'
        ];
    }

    public function save(): bool
    {
        $this->requestID = substr(uniqid('request', true), 0, 23);
        $this->postedBy = $_SESSION['user'];
        $this->postedDate = date('Y-m-d');
        $this->expDate = date('Y-m-d', strtotime("+" . $this->getDays() . " days"));

        if ($this->address === "") {
            $user = doneeModel::getModel(['doneeID' => $_SESSION['user']]);
            $this->address = $user->address;
        }

        return parent::save();
    }

    private function getDays():int {
        return match ($this->urgency) {
            'Within 7 days' => 7,
            'Within a month' => 30,
            'Within 3 month' => 90,
            default => 0,
        };
    }

    public function getRequestsUnderCC(string $ccID) {
        $stmnt = self::prepare('SELECT * FROM request r INNER JOIN subcategory s ON r.item = s.subcategoryID INNER JOIN category c ON s.categoryID = c.categoryID WHERE r.postedBy IN (SELECT doneeID FROM donee WHERE ccID = :ccID)');
        $stmnt->bindValue(':ccID',$ccID);
        $stmnt->execute();
        return $stmnt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function rejectRequest(string $rejectedReason) {
        $cols = ['requestID','postedBy','item','amount','address','urgency','postedDate','notes'];
        $colstring = implode(',', array_map(fn($col) => ":$col", $cols));
        $sql = 'INSERT INTO rejectedrequest VALUES (' . $colstring . ',:rejectedDate,:rejectedReason)';
        $stmnt = self::prepare($sql);
        foreach ($cols as $attribute) {
            $stmnt->bindValue(":$attribute", $this->{$attribute});
        }
        $stmnt->bindValue(':rejectedDate',date('Y-m-d'));
        $stmnt->bindValue(':rejectedReason',$rejectedReason);
        $stmnt->execute();
        $this->delete(['requestID' => $this->requestID]);
    }
}