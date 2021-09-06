<?php

namespace app\core;

use PDOStatement;

/**
 * Abstract Class DbModel
 *
 * @author Daishitie <daishitie@gmail.com>
 * @package app\core
 */
abstract class DbModel extends Model
{
    abstract public function tableName(): string;
    abstract public function attributes(): array;

    /**
     * DbModel save method.
     *
     * @return boolean
     */
    public function save(): bool
    {
        $tableName = $this->tableName();
        $attributes = $this->attributes();
        $params = array_map(fn ($attr) => ":$attr", $attributes);
        $statement = self::prepare("INSERT INTO $tableName (" . implode(',', $attributes) . ") VALUES (" . implode(',', $params) . ");");

        foreach ($attributes as $attribute) {
            $statement->bindValue(param: ":$attribute", value: $this->{$attribute});
        }

        $statement->execute();
        return true;
    }

    /**
     * DbModel Prepare
     *
     * @param string $sql
     * @return PDOStatement|false
     */
    public static function prepare(string $sql): PDOStatement|false
    {
        return Application::$app->db->prepare(query: $sql);
    }
}
