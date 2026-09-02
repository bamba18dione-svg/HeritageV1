<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\CopieExamen;

class CopieExamenRepository extends AbstractRepository
{
    private string $tableName = 'copie_examen';

    public function findAll(): array
    {
        return $this->getAllData($this->tableName);
    }

    public function findById(int $id): mixed
    {
        $sql = "SELECT * FROM {$this->tableName} WHERE id = :id";

        return $this->executeQuery($sql, ['id' => $id], true);
    }

    public function insert(CopieExamen $copie): int
    {
        $sql = "INSERT INTO {$this->tableName} (date_depot, date_limite, note_brute, note_finale, penalite_appliquee)
                VALUES (:date_depot, :date_limite, :note_brute, :note_finale, :penalite_appliquee)";

        return $this->executeUpdate($sql, [
            'date_depot'         => $copie->getDateDepot()->format('Y-m-d H:i:s'),
            'date_limite'        => $copie->getDateLimite()->format('Y-m-d H:i:s'),
            'note_brute'         => $copie->getNoteBrute(),
            'note_finale'        => $copie->getNoteFinale(),
            'penalite_appliquee' => $copie->getPenaliteAppliquee() > 0 ? true : false,
        ]);
    }
}
