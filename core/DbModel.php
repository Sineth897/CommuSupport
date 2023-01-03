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

    public function retrieve($where = []) : array
    {
        $tableName = static::table();
        $attributes = array_keys($where);
        if( empty($attributes) ) {
            $statement = self::prepare("SELECT * FROM $tableName");
            $statement->execute();
            return $statement->fetchAll(\PDO::FETCH_ASSOC);
        }
        $sql = implode("AND ", array_map(fn($attr) => "$attr = :$attr", $attributes));
        $statement = self::prepare("SELECT * FROM $tableName WHERE $sql");
        foreach ($where as $key => $item) {
            $statement->bindValue(":$key", $item);
        }
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function deleteOne($where): bool
    {
        $tableName = static::table();
        $attributes = array_keys($where);
        $sql = implode("AND ", array_map(fn($attr) => "$attr = :$attr", $attributes));
        $statement = self::prepare("DELETE FROM $tableName WHERE $sql");
        foreach ($where as $key => $item) {
            $statement->bindValue(":$key", $item);
        }
        $statement->execute();
        return true;
    }

    public function updateOne($where, $data): bool
    {
        $tableName = static::table();
        $attributes = array_keys($where);
        $sql = implode("AND ", array_map(fn($attr) => "$attr = :$attr", $attributes));
        $statement = self::prepare("UPDATE $tableName SET $data WHERE $sql");
        foreach ($where as $key => $item) {
            $statement->bindValue(":$key", $item);
        }
        $statement->execute();
        return true;
    }

    public function getCC(string $userID): string {
        $table = static::table();
        $primaryKey = static::getPrimaryKey();
        $sql = "SELECT ccID FROM $table WHERE $ = $userID";
        $statement = self::prepare($sql);
        $statement->execute();
        return $statement->fetch(\PDO::FETCH_ASSOC);
    }

    public function retrieveWithJoin(string $tableName, string $onColumn, string $whereColumn = '', string $whereValue = ''): array {
        $table = static::table();
        $primaryKey = static::getPrimaryKey();
        $where = '';
        if($whereColumn && $whereValue) {
            switch (gettype($whereValue)) {
                case 'string':
                    $where = "WHERE $whereColumn = '$whereValue'";
                    break;
                case 'integer':
                    $where = "WHERE $whereColumn = $whereValue";
                    break;
            }
        }
        $sql = "SELECT * FROM $table INNER JOIN $tableName ON $table.$primaryKey = $tableName.$onColumn $where";
        $statement = self::prepare($sql);
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

}