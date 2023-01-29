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

    public static function getModel($where)
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

    public static function prepare($sql): \PDOStatement
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

    public function retrieve(array $where = [], array $order = []) : array
    {
        $tableName = static::table();
        $sql = "Select * from $tableName";
        if($where) {
            $attributes = array_keys($where);
            $sql .= " WHERE ".implode(" AND ", array_map(fn($attr) => "$attr = '$where[$attr]'", $attributes));
        }
        if($order) {
            $sql .= " ORDER BY ".implode(" ", $order);
        }
        $statement = self::prepare($sql);
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function delete($where): bool
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

    public function update(array $where,array $data): bool
    {
        $tableName = static::table();
        $attributes = array_keys($where);
        $setData = implode(", ", array_map(fn($data) => "$data",$data));
        $sql = implode("AND ", array_map(fn($attr) => "$attr = :$attr", $attributes));
        $statement = self::prepare("UPDATE $tableName SET $setData WHERE $sql");
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

    public function retrieveWithJoin(string $tableName, string $onColumn, array $whereCondition = [], array $orderBy = []): array {
        $table = static::table();
        $primaryKey = static::getPrimaryKey();
        $sql = "SELECT * FROM $table INNER JOIN $tableName ON $table.$onColumn = $tableName.$onColumn";
        if($whereCondition) {
            $attributes = array_keys($whereCondition);
            $where = implode("AND ", array_map(fn($attr) => "$attr = '$whereCondition[$attr]'", $attributes));
            $sql .= " WHERE $where";
        }
        if($orderBy) {
            $sql .= " ORDER BY ".implode(" ", $orderBy);
        }
        $statement = self::prepare($sql);
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

}