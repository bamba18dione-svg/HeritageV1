<?php

namespace App\Database;

use PDO;
use PDOException;

/**
 * Classe responsable de la gestion unique de la connexion PDO (Singleton).
 */
class Database
{
    private static ?PDO $instance = null;

    /**
     * Constructeur privé pour empêcher l'instanciation directe (Singleton).
     */
    private function __construct()
    {
    }

    /**
     * Empêche le clonage de l'instance.
     */
    private function __clone()
    {
    }

    /**
     * Retourne l'instance unique de la connexion PDO.
     *
     * @return PDO
     * @throws PDOException
     */
    public static function getConnection(): PDO
    {
        if (self::$instance === null) {
            self::loadEnv(__DIR__ . '/../../.env');

            $driver   = getenv('DB_DRIVER') ?: ($_ENV['DB_DRIVER'] ?? 'pgsql');
            $host     = getenv('DB_HOST') ?: ($_ENV['DB_HOST'] ?? '127.0.0.1');
            $port     = getenv('DB_PORT') ?: ($_ENV['DB_PORT'] ?? '5432');
            $dbname   = getenv('DB_NAME') ?: ($_ENV['DB_NAME'] ?? 'heritage_v1');
            $user     = getenv('DB_USER') ?: ($_ENV['DB_USER'] ?? 'postgres');
            $password = getenv('DB_PASSWORD') ?: ($_ENV['DB_PASSWORD'] ?? '');

            try {
                if ($driver === 'sqlite') {
                    $dsn = "sqlite:" . $dbname;
                    self::$instance = new PDO($dsn);
                } else {
                    $dsn = "{$driver}:host={$host};port={$port};dbname={$dbname}";
                    self::$instance = new PDO($dsn, $user, $password, [
                        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES   => false,
                    ]);
                }
            } catch (PDOException $e) {
                throw new PDOException("Erreur de connexion à la base de données : " . $e->getMessage(), (int)$e->getCode());
            }
        }

        return self::$instance;
    }

    /**
     * Charge les variables d'environnement depuis un fichier .env si non définies.
     *
     * @param string $path Chemin vers le fichier .env
     */
    private static function loadEnv(string $path): void
    {
        if (!file_exists($path)) {
            return;
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '' || str_starts_with($line, '#')) {
                continue;
            }

            if (str_contains($line, '=')) {
                [$key, $value] = explode('=', $line, 2);
                $key   = trim($key);
                $value = trim($value, " \t\n\r\0\x0B\"'");
                
                if (getenv($key) === false) {
                    putenv("{$key}={$value}");
                }
                if (!isset($_ENV[$key])) {
                    $_ENV[$key] = $value;
                }
            }
        }
    }
}
