<?php

namespace app\models;

use app\core\DbModel;

class ccDonationModel extends DbModel
{
    public string $ccDonationID = '';
    public ?string $fromCC = '';
    public string $toCC = '';
    public string $createdDate = '';
    public ?string $completedDate = '';
    public ?string $deliveryID = '';
    public ?string $deliveryStatus = '';
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

    public function getDonations() : array
    {
        $stmnt = self::prepare("SELECT *,CONCAT(c.amount,' ',s.scale) AS amount FROM ccdonation c LEFT JOIN subcategory s ON c.item = s.subcategoryID");
        $stmnt->execute();

        return [
            'donations' => $stmnt->fetchAll(\PDO::FETCH_ASSOC),
            'CCs' => ccModel::getCCs(),
        ];
    }

    public static function getAllSubcategories() : array {

        $stmnt = self::prepare('SELECT subcategoryID,subcategoryName FROM subcategory');
        $stmnt->execute();
        return $stmnt->fetchAll(\PDO::FETCH_KEY_PAIR);
    }

    public static function acceptCCDonation(string $ccDonationID,string $fromCC,string $deliveryID) : void {
        $sql = "UPDATE ccdonation SET deliveryID = :deliveryID, deliveryStatus = 'Ongoing', fromCC = :fromCC WHERE ccDonationID = :ccDonationID";
        $stmt = self::prepare($sql);
        $stmt->bindValue(':deliveryID', $deliveryID);
        $stmt->bindValue(':fromCC', $fromCC);
        $stmt->bindValue(':ccDonationID', $ccDonationID);
        $stmt->execute();
    }

    public static function getCCDonationInfoWithItemInfo(string $ccDonationID) : array {
        $sql = "SELECT ccd.*,s.subcategoryName,CONCAT(ccd.amount,' ',s.scale) AS amount FROM ccdonation ccd LEFT JOIN subcategory s ON ccd.item = s.subcategoryID  WHERE ccd.ccDonationID = :ccDonationID";
        $stmt = self::prepare($sql);
        $stmt->bindValue(':ccDonationID', $ccDonationID);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public static function getCCDonationsBelongsToLogisticOfficerFilteredAndSorted(array $filters, array $sort) : array {

        //query to get all CCdonations
        $sql = "SELECT *,CONCAT(c.amount,' ',s.scale) AS amount FROM ccdonation c LEFT JOIN subcategory s ON c.item = s.subcategoryID";

        // use static method to run custom query to get the result
        return self::runCustomQuery($sql,$filters,$sort);
    }

}