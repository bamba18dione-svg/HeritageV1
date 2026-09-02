<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/vendor/autoload.php';

use Dotenv\Dotenv;
use App\Entity\CopieExamen;
use App\Database\Database;
use App\Repository\CopieExamenRepository;

// Chargement des variables d'environnement
$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->safeLoad();

echo "<h1>--- Test du Système de Gestion des Copies ---</h1>";

// 1. Instanciation de l'Entité CopieExamen
echo "<h2>1. Entité CopieExamen</h2>";
$dateLimite = new \DateTimeImmutable('2026-09-05 23:59:59');
$dateDepot  = new \DateTimeImmutable('2026-09-01 10:00:00');

$copie = new CopieExamen(
    dateLimite: $dateLimite,
    noteBrute: 15.50,
    noteFinale: 15.50,
    penaliteAppliquee: 0.0,
    dateDepot: $dateDepot
);

echo "<p>Note brute : " . $copie->getNoteBrute() . "/20</p>";
echo "<p>Date de dépôt : " . $copie->getDateDepot()->format('d/m/Y H:i') . "</p>";
echo "<p>En retard ? " . ($copie->isEnRetard() ? 'Oui' : 'Non') . "</p>";

// 2. Test du Repository
echo "<h2>2. Récupération des données via CopieExamenRepository</h2>";
try {
    $repo = new CopieExamenRepository();
    $copies = $repo->findAll();
    echo "<pre>";
    var_dump($copies);
    echo "</pre>";
} catch (\Exception $e) {
    echo "<p style='color:red;'>Erreur : " . $e->getMessage() . "</p>";
}
