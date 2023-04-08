<?php

namespace app\models;

use app\core\DbModel;

class deliveryModel extends DbModel
{

    public string $deliveryID = '';
    public string $deliveredBy = '';
    public string $createdDate = '';
    public string $createdTime = '';
    public string $status = '';
    public string $city = '';
    public string $completedDate = '';
    public string $completedTime = '';



    public function rules(): array
    {
        return [
            'city' => [self::$REQUIRED],
        ];
    }

    public function table(): string
    {
        return 'deliveries';
    }

    public function attributes(): array
    {
        return ['deliveryID','city'];
    }

    public function primaryKey(): string
    {
        return 'deliveryID';
    }

    public static function getEventCategoryIcons() {
        $categories = (new static())->getEventCategories();
        $preparedIcons = [];
        foreach ($categories as $key => $value) {
            $preparedIcons[$key] = "/CommuSupport/public/src/icons/event/eventcategoryicons/" . $value . ".svg";
        }
        return $preparedIcons;
    }
}