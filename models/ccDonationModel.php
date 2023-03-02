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
        return ['ccDonationID', 'fromCC','item', 'amount','notes'];
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
        $this->fromCC = $user->ccID;
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
}