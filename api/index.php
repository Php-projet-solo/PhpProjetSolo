<?php
require_once '../config.php';
require_once 'controller/CompetitionController.php';
require_once 'services/CompetitionService.php';
require_once 'AuthHelper.php';

$controller = new CompetitionController($pdo);

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);
$id = $_GET['id'] ?? null;

header('Content-Type: application/json');

try {
    if ($method === 'POST' && isset($input['action']) && $input['action'] === 'login') {
        $username = $input['username'] ?? null;
        $password = $input['password'] ?? null;

        if ($username === 'admin' && $password === 'password') {
            $token = AuthHelper::generateToken(1);
            echo json_encode(['message' => 'Login successful', 'token' => $token]);
        } else {
            http_response_code(401);
            echo json_encode(['error' => 'Invalid credentials']);
        }
    } elseif ($method === 'POST' && isset($input['action']) && $input['action'] === 'logout') {
        echo json_encode(['message' => 'Logout successful']);
    } else {
        $headers = getallheaders();
        $headers = array_change_key_case($headers, CASE_LOWER);
    //        echo json_encode($headers);
        if (!isset($headers['authorization'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Authorization header missing']);
            exit;
        }

        $authHeader = $headers['authorization'];
        if (!str_starts_with($authHeader, 'Bearer ')) {
            http_response_code(401);
            echo json_encode(['error' => 'Invalid Authorization format']);
            exit;
        }

        $token = substr($authHeader, 7);
        $decoded = AuthHelper::validateToken($token);

        if (!$decoded) {
            http_response_code(401);
            echo json_encode(['error' => 'Invalid token']);
            exit;
        }

        $response = $controller->handleRequest($method, $id, $input);
        echo json_encode($response);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Internal Server Error', 'message' => $e->getMessage()]);
}

exit;
?>