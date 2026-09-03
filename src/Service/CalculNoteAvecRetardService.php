<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\CopieExamen;
use InvalidArgumentException;

/**
 * Stratégie de calcul appliquant une pénalité par jour de retard.
 * Patron de conception : Strategy.
 */
class CalculNoteAvecRetardService implements CalculNoteInterface
{
    private const PENALITE_PAR_JOUR_PAR_DEFAUT = 2.0;

    public function __construct(private readonly float $penaliteParJour = self::PENALITE_PAR_JOUR_PAR_DEFAUT)
    {
        if (!is_finite($this->penaliteParJour) || $this->penaliteParJour < 0.0) {
            throw new InvalidArgumentException('La pénalité par jour doit être un nombre fini positif ou nul.');
        }
    }

    /**
     * Calcule et enregistre la note finale selon la règle de pénalité :
     * - une copie à l'heure conserve sa note brute ;
     * - un retard est facturé par journée commencée ;
     * - la note finale ne peut pas être inférieure à zéro.
     */
    public function calculer(CopieExamen $copie): float
    {
        $joursRetard = $this->determinerJoursRetard($copie);
        $penalite = $joursRetard * $this->penaliteParJour;
        $noteFinale = max(0.0, $copie->getNoteBrute() - $penalite);

        $copie->setPenaliteAppliquee($penalite);
        $copie->setNoteFinale($noteFinale);

        return $noteFinale;
    }

    public function getPenaliteParJour(): float
    {
        return $this->penaliteParJour;
    }

    private function determinerJoursRetard(CopieExamen $copie): int
    {
        if (!$copie->isEnRetard()) {
            return 0;
        }

        // Tout retard inférieur à 24 heures constitue une journée commencée.
        return max(1, $copie->calculerRetardJours());
    }
}
