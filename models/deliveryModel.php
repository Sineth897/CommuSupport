<?php

namespace app\models;

use app\core\DbModel;

class deliveryModel extends DbModel
{

    public string $deliveryID = '';
    public string $deliveredBy = '';
    public string $createdDate = '';
    public string $status = '';
    public string $fromto = '';
    public float $fromLongitude = 0.0;
    public float $fromLatitude = 0.0;
    public float $toLongitude = 0.0;
    public float $toLatitude = 0.0;
    public string $completedDate = '';
    public string $completedTime = '';



    public function rules(): array
    {
        return [

        ];
    }

    public function table(): string
    {
        return 'delivery';
    }

    public function attributes(): array
    {
        return ['deliveryID','fromto','fromLongitude','fromLatitude','toLongitude','toLatitude',];
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