<?php

declare(strict_types=1);

// 1. Constante pointant vers la racine du projet
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(__DIR__));
}

// 2. Chargement de l'autoloader Composer
require_once ROOT_PATH . '/vendor/autoload.php';

// 3. Chargement sécurisé des variables d'environnement
$dotenv = \Dotenv\Dotenv::createImmutable(ROOT_PATH);
$dotenv->safeLoad();

// 4. Configuration du fuseau horaire
date_default_timezone_set('UTC');

// 5. Gestion de l'affichage des erreurs
if (($_ENV['APP_ENV'] ?? 'development') === 'development') {
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', '0');
    ini_set('display_startup_errors', '0');
}
