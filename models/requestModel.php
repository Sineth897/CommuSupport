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
        return ["requestID","postedBy","item","amount","address","urgency", "notes"];
    }

    public function primaryKey(): string
    {
        return "requestID";
    }

    public function rules(): array
    {
        return [
            "item" => [self::$REQUIRED],
            "amount" => [self::$REQUIRED],
            "urgency" => [self::$REQUIRED],
//            "notes" => [self::$REQUIRED],
        ];
    }

    public function getCategories():array {
        $stmnt = self::prepare('SELECT * FROM category');
        $stmnt->execute();
        return $stmnt->fetchAll(\PDO::FETCH_KEY_PAIR);
    }

    public function getSubcategories($category) {
        $stmnt = self::prepare('SELECT subcategoryID,subcategoryName FROM subcategory WHERE categoryID = :category');
        $stmnt->bindValue(':category',$category);
        $stmnt->execute();
        return $stmnt->fetchAll(\PDO::FETCH_KEY_PAIR);
    }

    public function getUrgency():array {
        return [
            'Urgent' => 'Urgent',
            'Not Urgent' => 'Not Urgent',
        ];
    }

    public function save(): bool
    {
        $this->requestID = substr(uniqid('request', true), 0, 23);
        $this->postedBy = $_SESSION['user'];

        if ($this->address === "") {
            $user = doneeModel::getModel(['doneeID' => $_SESSION['user']]);
            $this->address = $user->address;
        }

        return parent::save();
    }

    public function getRequestsUnderCC(string $ccID) {
        $stmnt = self::prepare('SELECT * FROM request r INNER JOIN subcategory s ON r.item = s.subcategoryID WHERE r.postedBy IN (SELECT doneeID FROM donee WHERE ccID = :ccID)');
        $stmnt->bindValue(':ccID',$ccID);
        $stmnt->execute();
        return $stmnt->fetchAll(\PDO::FETCH_ASSOC);
    }
}