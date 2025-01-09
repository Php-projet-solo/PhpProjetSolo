<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/assets/controller/CompetitionController.php';

$controller = new CompetitionController($pdo);

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);
$id = $_GET['id'] ?? null;

header('Content-Type: application/json');
try {
    $response = $controller->handleRequest($method, $id, $input);
    echo json_encode($response);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Internal Server Error', 'message' => $e->getMessage()]);
}
exit;