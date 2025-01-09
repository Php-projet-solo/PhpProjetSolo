<?php
// services/CompetitionService.php

class CompetitionService {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function createCompetition($data) {
        $stmt = $this->pdo->prepare('INSERT INTO competitions (name, date, description) VALUES (?, ?, ?)');
        $stmt->execute([$data['name'], $data['date'], $data['description']]);
        return $this->pdo->lastInsertId();
    }

    public function getAllCompetitions() {
        $stmt = $this->pdo->query('SELECT * FROM competitions');
        return $stmt->fetchAll();
    }

    public function getCompetitionById($id) {
        $stmt = $this->pdo->prepare('SELECT * FROM competitions WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function updateCompetition($id, $data) {
        $stmt = $this->pdo->prepare('UPDATE competitions SET name = ?, date = ?, description = ? WHERE id = ?');
        $stmt->execute([$data['name'], $data['date'], $data['description'], $id]);
        return $stmt->rowCount();
    }

    public function deleteCompetition($id) {
        $stmt = $this->pdo->prepare('DELETE FROM competitions WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->rowCount();
    }
}
?>