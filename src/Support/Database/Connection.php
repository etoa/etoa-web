<?php

declare(strict_types=1);

namespace App\Support\Database;

use PDO;
use PDOStatement;

class Connection
{
    private $pdo;

    public function __construct(string $driver = 'default')
    {
        $host = config("database.$driver.host");
        $db   = config("database.$driver.database");
        $user = config("database.$driver.user");
        $pass = config("database.$driver.password");
        $charset = config("database.$driver.charset", 'utf8mb4');

        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        try {
            $this->pdo = new PDO($dsn, $user, $pass, $options);
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    public function query(string $query): PDOStatement
    {
        return $this->pdo->query($query);
    }

    public function preparedQuery(string $query, array $args): PDOStatement
    {
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($args);
        return $stmt;
    }
}
