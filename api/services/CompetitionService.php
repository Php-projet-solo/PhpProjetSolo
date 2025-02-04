<?php

class CompetitionService
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAllCompetitions()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM competitions");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCompetition($id)
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

    public function createCompetition($input)
    {
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
            ':prixentree' => $input['prixentree'] ?? null,
            ':latitude' => $input['latitude'] ?? null,
            ':longitude' => $input['longitude'] ?? null,
            ':nompersonnecontacter' => $input['nompersonnecontacter'] ?? null,
            ':emailcontacter' => $input['emailcontacter'] ?? null,
            ':photo' => $input['photo']
        ]);

        return ['id' => $this->pdo->lastInsertId()];
    }

    public function updateCompetition($id, $input)
    {
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
            $params[':prixentree'] = $input['prixentree'];
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
            $params[':nompersonnecontacter'] = $input['nompersonnepontacter'];
        }
        if (isset($input['emailContacter'])) {
            $fields[] = "emailcontacter = :emailcontacter";
            $params[':emailcontacter'] = $input['emailcontacter'];
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

    public function deleteCompetition($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM competitions WHERE id_competition = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return ['message' => 'Competition deleted'];
    }
}
?>