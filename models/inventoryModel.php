<?php

namespace app\models;

use app\core\Application;
use app\core\DbModel;

class inventoryModel extends DbModel
{
    public string $ccID = '';
    public string $itemID = '';
    public int $amount = 0;
    public string $updatedDate = '';

    public function table(): string
    {
        return 'inventory';
    }

    public function attributes(): array
    {
        return ['ccID','itemID','amount'];
    }

    public function primaryKey(): string
    {
        return 'itemID';
    }

    public function rules(): array
    {
        return [
            'itemID' => [self::$REQUIRED],
            'amount' => [self::$REQUIRED],
        ];
    }

    public function save(): bool
    {
        $logisticOfficer = logisticModel::getUser(['employeeID' => Application::session()->get('user')]);
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
}