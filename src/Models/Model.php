<?php

namespace SellNow\Models;

use PDO;
use SellNow\Config\Database;

abstract class Model
{
    protected static string $table;
    protected static string $primaryKey = 'id';
    protected PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    // Static methods for User::find() pattern
    public static function find(int $id): ?array
    {
        $instance = new static();
        $stmt = $instance->db->prepare("SELECT * FROM " . static::$table . " WHERE " . static::$primaryKey . " = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public static function findBy(string $column, mixed $value): ?array
    {
        $instance = new static();
        $stmt = $instance->db->prepare("SELECT * FROM " . static::$table . " WHERE {$column} = ?");
        $stmt->execute([$value]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public static function all(): array
    {
        $instance = new static();
        $stmt = $instance->db->query("SELECT * FROM " . static::$table);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function where(string $column, mixed $value): array
    {
        $instance = new static();
        $stmt = $instance->db->prepare("SELECT * FROM " . static::$table . " WHERE {$column} = ?");
        $stmt->execute([$value]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function create(array $data): int
    {
        $instance = new static();
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));

        $sql = "INSERT INTO " . static::$table . " ({$columns}) VALUES ({$placeholders})";
        $stmt = $instance->db->prepare($sql);
        $stmt->execute(array_values($data));

        return (int) $instance->db->lastInsertId();
    }

    public static function updateById(int $id, array $data): bool
    {
        $instance = new static();
        $sets = [];
        foreach (array_keys($data) as $column) {
            $sets[] = "{$column} = ?";
        }
        $setString = implode(', ', $sets);

        $sql = "UPDATE " . static::$table . " SET {$setString} WHERE " . static::$primaryKey . " = ?";
        $stmt = $instance->db->prepare($sql);

        $values = array_values($data);
        $values[] = $id;

        return $stmt->execute($values);
    }

    public static function destroy(int $id): bool
    {
        $instance = new static();
        $stmt = $instance->db->prepare("DELETE FROM " . static::$table . " WHERE " . static::$primaryKey . " = ?");
        return $stmt->execute([$id]);
    }

    public static function query(string $sql, array $params = []): array
    {
        $instance = new static();
        $stmt = $instance->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function count(): int
    {
        $instance = new static();
        $stmt = $instance->db->query("SELECT COUNT(*) as count FROM " . static::$table);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int) $result['count'];
    }

    // Instance methods (for backward compatibility)
    public function update(int $id, array $data): bool
    {
        return static::updateById($id, $data);
    }

    public function delete(int $id): bool
    {
        return static::destroy($id);
    }

    public function first(): ?array
    {
        $stmt = $this->db->query("SELECT * FROM " . static::$table . " LIMIT 1");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }
}