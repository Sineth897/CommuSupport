<?php

namespace app\models;

use app\core\DbModel;

class categoryModel extends DbModel
{

    public string $categoryID = '';
    public string $categoryName = '';

    public function table(): string
    {
        return 'category';
    }

    public function attributes(): array
    {
        return ['categoryID','categoryName'];
    }

    public function primaryKey(): string
    {
        return 'categoryID';
    }

    public function rules(): array
    {
        return [
            'categoryName' => [self::$REQUIRED,[self::$UNIQUE,'class' => self::class]],
        ];
    }

    public function save(): bool
    {

        $this->categoryID = substr(uniqid('category',true),0,23);

        return parent::save();
    }

    public static function getCategories() : array {

        $stmnt = self::prepare("SELECT * FROM category");
        $stmnt->execute();
        return $stmnt->fetchAll(\PDO::FETCH_KEY_PAIR);

    }

}