<?php

namespace app\core;

abstract class DbModel extends Model
{

    abstract public function table() : string;

    abstract public function attributes() : array;

    abstract public function primaryKey(): string;

    public static function getPrimaryKey() : string
    {
        return (new static())->primaryKey();
    }

    public static function getUser($where): ?DbModel
    {
        return (new static())->findOne($where);
    }

    public function save(): bool
    {
        $table = $this->table();
        $attributes = $this->attributes();
        $params = array_map(fn($attr) => ":$attr", $attributes);
        $statement = self::prepare("INSERT INTO $table (".implode(',', $attributes).") VALUES (".implode(',', $params).")");

        foreach ($attributes as $attribute) {
            $statement->bindValue(":$attribute", $this->{$attribute});
        }
        var_dump($statement);
        $statement->execute();
        return true;
    }

    public static function prepare($sql)
    {
        return Application::$app->database->pdo->prepare($sql);
    }

    public function findOne($where)
    {
        $tableName = static::table();
        $attributes = array_keys($where);
        $sql = implode("AND ", array_map(fn($attr) => "$attr = :$attr", $attributes));
        $statement = self::prepare("SELECT * FROM $tableName WHERE $sql");
        foreach ($where as $key => $item) {
            $statement->bindValue(":$key", $item);
        }
        $statement->execute();
        return $statement->fetchObject(static::class);
    }
}