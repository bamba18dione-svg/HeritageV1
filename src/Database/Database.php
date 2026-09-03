<?php

declare(strict_types=1);

namespace App\Database;

use PDO;
use PDOException;

final class Database
{
    private static ?PDO $instance = null;

    private function __construct()
    {
    }

    private function __clone()
    {
    }

    public static function getConnection(?array $customConfig = null): PDO
    {
        if (self::$instance === null) {
            $configFile = dirname(__DIR__, 2) . '/config/database.php';
            if ($customConfig !== null) {
                $config = $customConfig;
            } elseif (file_exists($configFile)) {
                $config = require $configFile;
            } else {
                $config = [
                    'driver'   => $_ENV['DB_DRIVER'] ?? 'pgsql',
                    'host'     => $_ENV['DB_HOST'] ?? '127.0.0.1',
                    'port'     => (int) ($_ENV['DB_PORT'] ?? 5432),
                    'dbname'   => $_ENV['DB_NAME'] ?? 'heritage_v1',
                    'user'     => $_ENV['DB_USER'] ?? 'postgres',
                    'password' => $_ENV['DB_PASSWORD'] ?? '',
                    'options'  => [
                        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES   => false,
                    ],
                ];
            }

            $driver   = $config['driver'] ?? 'pgsql';
            $host     = $config['host'] ?? '127.0.0.1';
            $port     = (int) ($config['port'] ?? 5432);
            $dbname   = $config['dbname'] ?? 'heritage_v1';
            $user     = $config['user'] ?? 'postgres';
            $password = $config['password'] ?? '';
            $databaseUrl = $config['url'] ?? '';
            $options  = $config['options'] ?? [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            try {
                if ($databaseUrl !== '') {
                    $databaseUrlParts = parse_url($databaseUrl);
                    if ($databaseUrlParts === false || empty($databaseUrlParts['host'])) {
                        throw new PDOException('DATABASE_URL est invalide.');
                    }

                    $dsn = sprintf(
                        'pgsql:host=%s;port=%d;dbname=%s;sslmode=%s',
                        $databaseUrlParts['host'],
                        (int) ($databaseUrlParts['port'] ?? 5432),
                        ltrim($databaseUrlParts['path'] ?? '', '/'),
                        $_ENV['DB_SSLMODE'] ?? 'require'
                    );
                    $user = $databaseUrlParts['user'] ?? $user;
                    $password = $databaseUrlParts['pass'] ?? $password;
                    self::$instance = new PDO($dsn, $user, $password, $options);
                } elseif ($driver === 'sqlite') {
                    $dsn = "sqlite:" . $dbname;
                    self::$instance = new PDO($dsn, null, null, $options);
                } else {
                    $dsn = sprintf('%s:host=%s;port=%d;dbname=%s', $driver, $host, $port, $dbname);
                    self::$instance = new PDO($dsn, $user, $password, $options);
                }
            } catch (PDOException $e) {
                throw new PDOException("Erreur de connexion a la base de donnees : " . $e->getMessage(), (int) $e->getCode(), $e);
            }
        }

        return self::$instance;
    }

    public static function disconnect(): void
    {
        self::$instance = null;
    }
}
