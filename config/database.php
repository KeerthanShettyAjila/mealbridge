<?php
// XAMPP settings. Your working MySQL port is 3307.
const DB_HOST = '127.0.0.1';
const DB_PORT = 3307;
const DB_NAME = 'mealbridge';
const DB_USER = 'root';
const DB_PASS = '';
function db() {
    static $pdo;
    if (!$pdo) {
        $pdo = new PDO('mysql:host='.DB_HOST.';port='.DB_PORT.';dbname='.DB_NAME.';charset=utf8mb4', DB_USER, DB_PASS,
            [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC, PDO::ATTR_EMULATE_PREPARES=>false]);
        $pdo->exec("SET time_zone = '+05:30'");
    }
    return $pdo;
}
