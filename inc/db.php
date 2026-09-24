<?php
declare(strict_types=1);
function db(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        $host = getenv('OPI_DB_HOST') ?: '127.0.0.1';
        $name = getenv('OPI_DB_NAME') ?: 'opi_eestis';
        $user = getenv('OPI_DB_USER') ?: 'root';
        $pass = getenv('OPI_DB_PASSWORD') ?: '';
        $pdo = new PDO("mysql:host={$host};dbname={$name};charset=utf8mb4", $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
    }
    return $pdo;
}
