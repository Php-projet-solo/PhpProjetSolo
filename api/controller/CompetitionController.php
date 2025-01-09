<?php

require_once 'controller/CompetitionController.php';
require_once 'services/CompetitionService.php';
require_once 'ApiKeyValidator.php';
class CompetitionController
{
    private $competitionService;
    private $apiKeyValidator;

    public function __construct($pdo)
    {
        $this->competitionService = new CompetitionService($pdo);
        $this->apiKeyValidator = new ApiKeyValidator();
    }

    public function handleRequest($method, $id = null, $input = null)
    {
        if (!$this->apiKeyValidator->isValidKey($input['token'] ?? null)) {
            throw new Exception("Cle incorrecte");
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