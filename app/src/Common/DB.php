<?php

namespace App\Common;

class DB
{
    private static string $charset = 'utf8mb4';
    private static ?\PDO $instance = null;

    // Скрываем конструктор, чтобы нельзя было создать объект через new
    private function __construct()
    {
    }

    private function __clone()
    {
    }

    public static function getConnection(): \PDO
    {
        if (self::$instance === null) {
            $host = (string)Config::get('db_host');
            $port = (string)Config::get('db_port');
            $dbname = (string)Config::get('db_name');
            $user = (string)Config::get('db_user');
            $pass = (string)Config::get('db_password');
            $charset = self::$charset;
            $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=$charset";
            $options = [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                \PDO::ATTR_EMULATE_PREPARES => false,
            ];

            try {
                self::$instance = new \PDO($dsn, $user, $pass, $options);
            } catch (\PDOException $e) {
                throw new \Exception("Ошибка подключения: " . $e->getMessage());
            }
        }
        return self::$instance;
    }

    private static function detectPdoParamType(mixed $value): int
    {
        return match (true) {
            $value === null => \PDO::PARAM_NULL,
            is_bool($value) => \PDO::PARAM_BOOL,
            is_int($value) => \PDO::PARAM_INT,
            default => \PDO::PARAM_STR,
        };
    }
    
    private static function bindParams(\PDOStatement $stmt, array $params): void
    {
        foreach ($params as $key => $param) {
            $value = $param;
            $type = null;

            if (is_array($param) && array_key_exists('value', $param)) {
                $value = $param['value'];
                $type = $param['type'] ?? null;
            }

            if ($value instanceof \DateTimeInterface) {
                $value = $value->format('Y-m-d H:i:s');
            }

            if (is_int($key)) {
                // If array is 0-based, PDO positional parameters are 1-based.
                $position = ($key === 0 || array_key_exists(0, $params)) ? ($key + 1) : $key;
                $stmt->bindValue($position, $value, $type ?? self::detectPdoParamType($value));
                continue;
            }

            $name = str_starts_with($key, ':') ? $key : (':' . $key);
            $stmt->bindValue($name, $value, $type ?? self::detectPdoParamType($value));
        }
    }

    public static function execute(string $sql, array $params = []): false|\PDOStatement
    {
        $stmt = self::$instance->prepare($sql);
        self::bindParams($stmt, $params);
        $stmt->execute();
        return $stmt;
    }
    public static function select(string $query, array $params = []): array
    {
        try {
            $stmt = self::execute($query, $params);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            throw new \Exception("Ошибка select-запроса: " . $e->getMessage());
        }
    }

    public static function count(string $query, array $params = []): int
    {
        try {
            $stmt = self::execute($query, $params);
            return (int)$stmt->fetchColumn();
        } catch (\PDOException $e) {
            throw new \Exception("Ошибка count-запроса: " . $e->getMessage());
        }
    }

    public static function insert(string $query, array $params = []): string
    {
        try {
            self::execute($query, $params);
            return self::$instance->lastInsertId();
        } catch (\PDOException $e) {
            throw new \Exception("Ошибка insert-запроса: " . $e->getMessage());
        }
    }

    public static function update(string $query, array $params = []): int
    {
        try {
            $stmt = self::execute($query, $params);
            return $stmt->rowCount();
        } catch (\PDOException $e) {
            throw new \Exception("Ошибка update-запроса: " . $e->getMessage());
        }
    }
}