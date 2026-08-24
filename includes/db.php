<?php

declare(strict_types=1);

/* ================================
   Database Configuration
================================ */

const DB_HOST = '127.0.0.1';
const DB_PORT = 3306;
const DB_NAME = 'urbannest';
const DB_USER = 'root';
const DB_PASS = '';
const DB_CHARSET = 'utf8mb4';


/* ================================
   MySQLi Database Connection
================================ */

$con = mysqli_connect(
    DB_HOST,
    DB_USER,
    DB_PASS,
    DB_NAME,
    DB_PORT
);

if (!$con) {
    die('Database connection failed: ' .
        mysqli_connect_error());
}


/* Set UTF-8 character set */

if (!mysqli_set_charset($con, DB_CHARSET)) {
    die('Failed to set database charset: ' .
        mysqli_error($con));
}


/* ================================
   Session
================================ */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


/* ================================
   Helper Functions
================================ */

function e(?string $value): string
{
    return htmlspecialchars(
        (string) $value,
        ENT_QUOTES | ENT_SUBSTITUTE,
        'UTF-8'
    );
}


function un_prefix(): string
{
    $script = trim(
        $_SERVER['SCRIPT_NAME'] ?? '/',
        '/'
    );

    $depth = substr_count($script, '/');

    return str_repeat('../', $depth);
}


function asset(string $path): string
{
    return un_prefix() . 'assets/' . ltrim($path, '/');
}


function url(string $path): string
{
    return un_prefix() . ltrim($path, '/');
}


function is_active(string $needle): string
{
    return str_contains(
        $_SERVER['SCRIPT_NAME'] ?? '',
        $needle
    ) ? 'active' : '';
}
