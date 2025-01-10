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
        if (!isset($_COOKIE['auth_token'])) {
            http_response_code(401);
            echo json_encode(['message' => 'Authentication cookie missing']);
            exit;
        }

        $token = $_COOKIE['auth_token'];
        $decoded = AuthHelper::validateToken($token);

        if (!$decoded) {
            http_response_code(401);
            echo json_encode(['message' => 'Invalid token']);
            exit;
        }

        switch ($method) {
            case 'GET':
                return $id
                    ? $this->competitionService->getCompetition($id)
                    : $this->competitionService->getAllCompetitions();
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
