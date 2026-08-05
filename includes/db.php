<?php
/**
 * UrbanNest — Database Connection
 * PHP 8+ / MySQL (PDO)
 *
 * Update the constants below to match your environment, then include this
 * file from any page that needs database access:
 *
 *     require_once __DIR__ . '/includes/db.php';
 *     $stmt = $pdo->query("SELECT * FROM properties LIMIT 8");
 */

declare(strict_types=1);

const DB_HOST = '127.0.0.1';
const DB_PORT = 3306;
const DB_NAME = 'urbannest';
const DB_USER = 'root';
const DB_PASS = '';
const DB_CHARSET = 'utf8mb4';

$dsn = sprintf(
    'mysql:host=%s;port=%d;dbname=%s;charset=%s',
    DB_HOST, DB_PORT, DB_NAME, DB_CHARSET
);

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    // Uncomment to enable a real connection.
    // $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
    $pdo = null; // Placeholder — frontend runs without a live DB.
} catch (PDOException $e) {
    http_response_code(500);
    exit('Database connection failed: ' . htmlspecialchars($e->getMessage()));
}

/* ------------------------------------------------------------------ */
/*  Session bootstrap                                                  */
/* ------------------------------------------------------------------ */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* ------------------------------------------------------------------ */
/*  Small helpers                                                      */
/* ------------------------------------------------------------------ */
function e(?string $value): string {
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function un_prefix(): string {
    // Number of slashes in path after trimming the leading one == folder depth.
    // /index.php               -> "index.php"              -> 0 slashes -> depth 0
    // /properties/list.php     -> "properties/list.php"    -> 1 slash  -> depth 1
    $depth = substr_count(trim($_SERVER['SCRIPT_NAME'] ?? '/', '/'), '/');
    return str_repeat('../', $depth);
}

function asset(string $path): string {
    return un_prefix() . 'assets/' . ltrim($path, '/');
}

function url(string $path): string {
    return un_prefix() . ltrim($path, '/');
}

function is_active(string $needle): string {
    return str_contains($_SERVER['SCRIPT_NAME'], $needle) ? 'active' : '';
}
