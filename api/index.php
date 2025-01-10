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
            setcookie('auth_token', $token, [
                'expires' => time() + 3600,
                'path' => '/',
                'httponly' => true,
                'secure' => isset($_SERVER['HTTPS']),
                'samesite' => 'Strict',
            ]);
            echo json_encode(['message' => 'Login successful']);
        } else {
            http_response_code(401);
            echo json_encode(['error' => 'Invalid credentials']);
        }
    } elseif ($method === 'POST' && isset($input['action']) && $input['action'] === 'logout') {
        setcookie('auth_token', '', [
            'expires' => time() - 3600,
            'path' => '/',
            'httponly' => true,
            'secure' => isset($_SERVER['HTTPS']),
            'samesite' => 'Strict',
        ]);
        echo json_encode(['message' => 'Logout successful']);
    } else {
        if (!isset($_COOKIE['auth_token'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Authentication cookie missing']);
            exit;
        }

        $token = $_COOKIE['auth_token'];
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