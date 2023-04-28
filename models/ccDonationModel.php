<?php

namespace app\models;

use app\core\DbModel;

class ccDonationModel extends DbModel
{
    public string $ccDonationID = '';
    public string $fromCC = '';
    public string $toCC = '';
    public string $createdDate = '';
    public string $completedDate = '';
    public string $deliveryID = '';
    public string $deliveryStatus = '';
    public string $item = '';
    public float $amount = 0.0;
    public string $notes = '';


    public function table(): string
    {
        return 'ccdonation';
    }

    public function attributes(): array
    {
        return ['ccDonationID', 'toCC','item', 'amount','notes'];
    }

    public function primaryKey(): string
    {
        return 'ccDonationID';
    }

    public function rules(): array
    {
        return [
            'item' => [self::$REQUIRED],
            'amount' => [self::$REQUIRED,self::$POSITIVE],
            'notes' => [self::$REQUIRED],
        ];
    }

    public function save(): bool
    {
        $this->ccDonationID = substr(uniqid('ccdonation',true),0,23);
        $user = logisticModel::getModel(['employeeID' => $_SESSION['user']]);
        $this->toCC = $user->ccID;
        return parent::save();
    }
    public static function getSubcategories(string $categoryId) : bool|array
    {
        $sql = "SELECT `subcategoryID`,`subcategoryName` FROM subcategory WHERE `categoryID` = :categoryID";
        $stmt = self::prepare($sql);
        $stmt->bindValue(':categoryID', $categoryId);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_KEY_PAIR);
    }

    public static function getCategories():array {
        $stmnt = self::prepare('SELECT * FROM category');
        $stmnt->execute();
        return $stmnt->fetchAll(\PDO::FETCH_KEY_PAIR);
    }

    public function getDonations(string $ccID) : array
    {
        $stmnt1 = self::prepare("SELECT *,CONCAT(cc.amount,' ',s.scale) AS amount FROM ccdonation cc INNER JOIN subcategory s ON cc.item = s.subcategoryID INNER JOIN communitycenter c on cc.fromCC = c.ccID WHERE cc.fromCC = :ccID");
        $stmnt1->bindValue(':ccID', $ccID);
        $stmnt1->execute();
        $stmnt2 = self::prepare("SELECT *,CONCAT(cc.amount,' ',s.scale) AS amount FROM ccdonation cc INNER JOIN subcategory s ON cc.item = s.subcategoryID INNER JOIN communitycenter c on cc.toCC = c.ccID WHERE cc.toCC = :ccID");
        $stmnt2->bindValue(':ccID', $ccID);
        $stmnt2->execute();

        return [
            'fromCC' => $stmnt1->fetchAll(\PDO::FETCH_ASSOC),
            'toCC' => $stmnt2->fetchAll(\PDO::FETCH_ASSOC)
        ];
    }

}