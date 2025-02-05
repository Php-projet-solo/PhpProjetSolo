<?php

require_once 'controller/CompetitionController.php';
require_once 'services/CompetitionService.php';

class CompetitionController
{
    private $competitionService;

    public function __construct($pdo)
    {
        $this->competitionService = new CompetitionService($pdo);
    }

    public function handleRequest($method, $id = null, $input = null)
    {
        $headers = getallheaders();
        $headers = array_change_key_case($headers, CASE_LOWER);
        if (!isset($headers['authorization'])) {
            http_response_code(401);
            echo json_encode(['message' => 'Authorization header missing']);
            exit;
        }

        $authHeader = $headers['authorization'];
        if (!str_starts_with($authHeader, 'Bearer ')) {
            http_response_code(401);
            echo json_encode(['message' => 'Invalid Authorization format']);
            exit;
        }

        $token = substr($authHeader, 7);
        $decoded = AuthHelper::validateToken($token);
        if (!$decoded) {
            http_response_code(401);
            echo json_encode(['message' => 'Invalid token']);
            exit;
        }

        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $limit = isset($_GET['limit']) ? (int) $_GET['limit'] : 30;

        switch ($method) {
            case 'GET':
                return $id
                    ? $this->competitionService->getCompetition($id)
                    : $this->competitionService->getAllCompetitions($page, $limit);
            case 'POST':
                return $this->competitionService->createCompetition($input);
            case 'PUT':
                if (!$id) {
                    throw new Exception("ID is required for PUT");
                }
                return $this->competitionService->updateCompetition($id, $input);
            case 'DELETE':
                if (!$id) {
                    throw new Exception("ID is required for DELETE");
                }
                return $this->competitionService->deleteCompetition($id);
            default:
                throw new Exception("Unsupported HTTP method");
        }
    }
}
?>
