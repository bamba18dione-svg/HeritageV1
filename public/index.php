<?php

declare(strict_types=1);

// Démarrage et initialisation de l'application via le bootstrap backend
require_once dirname(__DIR__) . '/config/bootstrap.php';

use App\DTO\SoumettreCopieDTO;
use App\Entity\CopieExamen;
use App\Service\CalculNoteAvecRetardService;
use App\Service\CalculNoteSimpleService;

echo "<h1>--- Système de Gestion des Copies d'Examen ---</h1>";

// 1. Simulation d'une soumission avec retard
$donneesFormulaire = [
    'note_brute'  => '15.0',
    'date_limite' => '2026-09-01 12:00:00',
    'date_depot'  => '2026-09-04 14:00:00' // 3 jours de retard
];

echo "<h2>1. Données du formulaire</h2>";
$dto = SoumettreCopieDTO::fromArray($donneesFormulaire);
echo "<p>Note brute : " . $dto->getNoteBrute() . "/20</p>";
echo "<p>Date limite : " . $dto->getDateLimite()->format('d/m/Y H:i') . "</p>";
echo "<p>Date de dépôt : " . $dto->getDateDepot()->format('d/m/Y H:i') . "</p>";

// 2. Création de l'Entité
$copie = new CopieExamen(
    dateLimite: $dto->getDateLimite(),
    noteBrute: $dto->getNoteBrute(),
    noteFinale: $dto->getNoteBrute(),
    penaliteAppliquee: 0.0,
    dateDepot: $dto->getDateDepot()
);

echo "<h2>2. Application de la Stratégie de Calcul (Strategy Pattern)</h2>";
$strategieRetard = new CalculNoteAvecRetardService(penaliteParJour: 2.0);
$noteFinale = $strategieRetard->calculer($copie);

echo "<p>Est en retard ? " . ($copie->isEnRetard() ? '<strong>Oui (' . $copie->calculerRetardJours() . ' jours)</strong>' : 'Non') . "</p>";
echo "<p>Pénalité appliquée : <strong>-" . $copie->getPenaliteAppliquee() . " points</strong> (2 pts / jour de retard)</p>";
echo "<p>Note finale après calcul : <strong>" . $copie->getNoteFinale() . "/20</strong></p>";

echo "<h2>3. État final de l'Entité CopieExamen</h2>";
echo "<pre>";
var_dump($copie);
echo "</pre>";
