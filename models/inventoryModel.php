<?php

namespace app\models;

use app\core\Application;
use app\core\DbModel;

class inventoryModel extends DbModel
{
    public string $ccID = '';
    public string $subcategoryID = '';
    public int $amount = 0;
    public string $updatedDate = '';

    public function table(): string
    {
        return 'inventory';
    }

    public function attributes(): array
    {
        return ['ccID','subcategoryID','amount'];
    }

    public function primaryKey(): string
    {
        return 'itemID';
    }

    public function rules(): array
    {
        return [
            'subcategoryID' => [self::$REQUIRED],
            'amount' => [self::$REQUIRED],
        ];
    }

    public function save(): bool
    {
        $logisticOfficer = logisticModel::getModel(['employeeID' => Application::session()->get('user')]);
        $this->ccID = $logisticOfficer->ccID;
        $this->updatedDate = date('Y-m-d H:i:s');
        return parent::save();
    }

    public function getCategories(): bool|array
    {
        $sql = "SELECT * FROM category";
        $stmt = self::prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_KEY_PAIR);
    }

    public function getsubcategories($categoryID = ""): bool|array
    {
        if($categoryID == "") {
            $sql = "SELECT subcategoryID,subcategoryName FROM subcategory";
        }
        else {
            $sql = "SELECT subcategoryID,subcategoryName FROM subcategory WHERE categoryID = '$categoryID'";
        }
        $stmt = self::prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_KEY_PAIR);
    }

    public static function updateInventoryAfterDonation(array $data) {

        $sql = "UPDATE inventory SET amount = amount + :amount, updatedTime = CURRENT_TIMESTAMP WHERE ccID = :ccID AND subcategoryID = :subcategoryID";
        $stmt = self::prepare($sql);
        $stmt->bindValue(':amount', $data['amount']);
        $stmt->bindValue(':ccID', $data['donateTo']);
        $stmt->bindValue(':subcategoryID', $data['item']);
        $stmt->execute();


        if($stmt->rowCount() === 0) {
            $sql = "INSERT INTO inventory (ccID,subcategoryID,amount,updatedTime) VALUES (:ccID,:subcategoryID,:amount,CURRENT_TIMESTAMP)";
            $stmt = self::prepare($sql);
            $stmt->bindValue(':amount', $data['amount']);
            $stmt->bindValue(':ccID', $data['donateTo']);
            $stmt->bindValue(':subcategoryID', $data['item']);
            $stmt->execute();
        }

    }

    public static function updateInventoryAfterAcceptingCCDontion(string $ccID,string $subcategoryID,int $amount) : void {
        $stmnt = self::prepare("UPDATE inventory SET amount = amount - :amount, updatedTime = CURRENT_TIMESTAMP WHERE ccID = :ccID AND subcategoryID = :subcategoryID");
        $stmnt->bindValue(':amount', $amount);
        $stmnt->bindValue(':ccID', $ccID);
        $stmnt->bindValue(':subcategoryID', $subcategoryID);
        $stmnt->execute();
    }
}