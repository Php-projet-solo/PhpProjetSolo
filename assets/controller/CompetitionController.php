<?php

class CompetitionController
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function handleRequest($method, $id = null, $input = null)
    {
        switch ($method) {
            case 'GET':
                return $id ? $this->getCompetition($id) : $this->getAllCompetitions();
            case 'POST':
                return $this->createCompetition($input);
            case 'PUT':
                if (!$id) {
                    throw new Exception("ID is required for PUT");
                }
                return $this->updateCompetition($id, $input);
            case 'DELETE':
                if (!$id) {
                    throw new Exception("ID is required for DELETE");
                }
                return $this->deleteCompetition($id);
            default:
                throw new Exception("Unsupported HTTP method");
        }
    }

    private function getAllCompetitions()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM competitions");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function getCompetition($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM competitions WHERE id_competition = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $competition = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$competition) {
            throw new Exception("Competition not found");
        }

        return $competition;
    }
    private function createCompetition($input)
    {
        if (!isset($input['nom'], $input['date'], $input['photo'])) {
            throw new Exception("Invalid input");
        }

        $stmt = $this->pdo->prepare("
        INSERT INTO competitions (
            nom, description, date, prixentree, latitude, longitude,
            nompersonnecontacter, emailcontacter, photo
        ) VALUES (
            :nom, :description, :date, :prixentree, :latitude, :longitude,
            :nompersonnecontacter, :emailcontacter, :photo
        )
    ");

        $stmt->execute([
            ':nom' => $input['nom'],
            ':description' => $input['description'] ?? null,
            ':date' => $input['date'],
            ':prixentree' => $input['prixEntree'] ?? null,
            ':latitude' => $input['latitude'] ?? null,
            ':longitude' => $input['longitude'] ?? null,
            ':nompersonnecontacter' => $input['nomPersonneContacter'] ?? null,
            ':emailcontacter' => $input['emailContacter'] ?? null,
            ':photo' => $input['photo']
        ]);

        return ['id' => $this->pdo->lastInsertId()];
    }


    private function updateCompetition($id, $input)
    {
        // Vérification de l'entrée
        if (empty($input)) {
            throw new Exception("No input provided for update");
        }

        $fields = [];
        $params = [':id' => $id];

        if (isset($input['nom'])) {
            $fields[] = "nom = :nom";
            $params[':nom'] = $input['nom'];
        }
        if (isset($input['description'])) {
            $fields[] = "description = :description";
            $params[':description'] = $input['description'];
        }
        if (isset($input['date'])) {
            $fields[] = "date = :date";
            $params[':date'] = $input['date'];
        }
        if (isset($input['prixEntree'])) {
            $fields[] = "prixentree = :prixentree";
            $params[':prixentree'] = $input['prixEntree'];
        }
        if (isset($input['latitude'])) {
            $fields[] = "latitude = :latitude";
            $params[':latitude'] = $input['latitude'];
        }
        if (isset($input['longitude'])) {
            $fields[] = "longitude = :longitude";
            $params[':longitude'] = $input['longitude'];
        }
        if (isset($input['nomPersonneContacter'])) {
            $fields[] = "nompersonnecontacter = :nompersonnecontacter";
            $params[':nompersonnecontacter'] = $input['nomPersonneContacter'];
        }
        if (isset($input['emailContacter'])) {
            $fields[] = "emailcontacter = :emailcontacter";
            $params[':emailcontacter'] = $input['emailContacter'];
        }
        if (isset($input['photo'])) {
            $fields[] = "photo = :photo";
            $params[':photo'] = $input['photo'];
        }

        if (empty($fields)) {
            throw new Exception("No valid fields provided for update");
        }

        $sql = "UPDATE competitions SET " . implode(", ", $fields) . " WHERE id_competition = :id";
        $stmt = $this->pdo->prepare($sql);

        $stmt->execute($params);

        return ['message' => 'Competition updated successfully'];
    }

    private function deleteCompetition($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM competitions WHERE id_competition = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return ['message' => 'Competitions deleted'];
    }
}

?>