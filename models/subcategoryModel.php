<?php

namespace app\models;

use app\core\DbModel;

class subcategoryModel extends DbModel
{

    public string $subcategoryID = '';

    public string $subcategoryName = '';

    public string $categoryID = '';

    public string $scale = '';

    public function table(): string
    {
        return 'subcategory';
    }

    public function attributes(): array
    {
        return ['subcategoryID','subcategoryName','categoryID','scale'];
    }

    public function primaryKey(): string
    {
        return 'subcategoryID';
    }

    public function rules(): array
    {
        return [
            'subcategoryName' => [self::$REQUIRED,[self::$UNIQUE,'class' => self::class]],
            'categoryID' => [self::$REQUIRED],
            'scale' => [self::$REQUIRED],
        ];
    }

    public function save(): bool
    {

        $this->subcategoryID = substr(uniqid('subcategory',true),0,23);

        return parent::save();

    }
}