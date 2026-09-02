<?php

declare(strict_types=1);

namespace App\Repository;

use App\Database\Database;
use PDO;
use PDOStatement;

abstract class AbstractRepository
{
    protected ?PDO $connexion = null;

    protected function getConnexion(): PDO
    {
        if ($this->connexion === null) {
            $this->connexion = Database::getConnection();
        }

        return $this->connexion;
    }

    public function query(string $sql, bool $single = true): mixed
    {
        $query = $this->getConnexion()->query($sql);

        return $single ? $query->fetch(PDO::FETCH_OBJ) : $query->fetchAll(PDO::FETCH_OBJ);
    }

    protected function prepare(string $sql, array $datas): PDOStatement
    {
        $statement = $this->getConnexion()->prepare($sql);

        foreach ($datas as $key => $value) {
            if (is_bool($value)) {
                $statement->bindValue(':' . $key, $value, PDO::PARAM_BOOL);
            } elseif (is_int($value)) {
                $statement->bindValue(':' . $key, $value, PDO::PARAM_INT);
            } elseif (is_null($value)) {
                $statement->bindValue(':' . $key, $value, PDO::PARAM_NULL);
            } else {
                $statement->bindValue(':' . $key, (string) $value, PDO::PARAM_STR);
            }
        }

        $statement->execute();

        return $statement;
    }

    public function executeQuery(string $sql, array $datas, bool $single = true): mixed
    {
        $statement = $this->prepare($sql, $datas);

        return $single ? $statement->fetch(PDO::FETCH_OBJ) : $statement->fetchAll(PDO::FETCH_OBJ);
    }

    public function executeUpdate(string $sql, array $datas): int
    {
        $statement = $this->prepare($sql, $datas);

        return $statement->rowCount();
    }

    public function getAllData(string $tableName): array
    {
        $sql = "SELECT * FROM {$tableName}";

        return $this->query($sql, false);
    }
}
