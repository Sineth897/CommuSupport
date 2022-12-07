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


    public function table(): string
    {
        return 'ccdonation';
    }

    public function attributes(): array
    {
        return ['ccDonationID', 'fromCC', 'toCC', 'createdDate', 'completedDate', 'deliveryID', 'deliveryStatus', 'item', 'amount'];
    }

    public function primaryKey(): string
    {
        return 'ccDonationID';
    }

    public function rules(): array
    {
        return [
            'fromCC' => [self::$REQUIRED],
            'toCC' => [self::$REQUIRED],
            'item' => [self::$REQUIRED],
            'amount' => [self::$REQUIRED],
        ];
    }

    public function save(): bool
    {
        $this->ccDonationID = uniqid('ccdonation',true);
        return parent::save();
    }

    public function getSubcategories() : bool|array
    {
        $sql = "SELECT `subcategoryID`,`subcategoryName` FROM subcategory";
        $stmt = self::prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_KEY_PAIR);
    }
}