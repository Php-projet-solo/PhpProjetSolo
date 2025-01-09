<?php

$host = getenv('MYSQL_HOST') ?: 'database';
$dbname = getenv('MYSQL_DATABASE') ?: 'docker';
$user = getenv('MYSQL_USER') ?: 'docker';
$password = getenv('MYSQL_PASSWORD') ?: 'docker';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    if (!headers_sent()) {
        header('Content-Type: application/json');
        http_response_code(500);
    }
    die(json_encode(['error' => 'Database connection failed', 'message' => $e->getMessage()]));
}
?>