<?php
// controller/CompetitionController.php

class CompetitionController {
    private $service;

    public function __construct($pdo) {
        $this->service = new CompetitionService($pdo);
    }

    public function handleRequest($method, $id, $input) {
        switch ($method) {
            case 'GET':
                if ($id) {
                    return $this->service->getCompetitionById($id);
                } else {
                    return $this->service->getAllCompetitions();
                }
            case 'POST':
                return $this->service->createCompetition($input);
            case 'PUT':
                return $this->service->updateCompetition($id, $input);
            case 'DELETE':
                return $this->service->deleteCompetition($id);
            default:
                throw new Exception('Method Not Allowed');
        }
    }
}
?>