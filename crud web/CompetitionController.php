<?php
require_once 'database.php';
require_once __DIR__ . '/data/CompetitionJeuVideo.php';

class CompetitionController
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function create(array $data): void
    {
        $query = "
    INSERT INTO competitions (nom, description, date, prixentree, latitude, longitude, nompersonnecontacter, emailcontacter, photo)
    VALUES (:nom, :description, :date, :prixentree, :latitude, :longitude, :nomPersonneContacter, :emailContacter, :photo)
";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([
            ':nom' => $data['nom'],
            ':description' => $data['description'],
            ':date' => $data['date'],
            ':prixentree' => $data['prixentree'],
            ':latitude' => $data['latitude'],
            ':longitude' => $data['longitude'],
            ':nomPersonneContacter' => $data['nomPersonneContacter'],
            ':emailContacter' => $data['emailContacter'],
            ':photo' => $data['photo'],
        ]);
    }

    public function readAll()
    {
        $sql = "SELECT * FROM competitions";
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update($id, $data)
    {
        $sql = "UPDATE competitions 
            SET nom = :nom, description = :description, date = :date, prixentree = :prixentree, 
                latitude = :latitude, longitude = :longitude, nompersonnecontacter = :nomPersonneContacter, 
                emailcontacter = :emailContacter, photo = :photo 
            WHERE id_competition = :id";
        $stmt = $this->pdo->prepare($sql);
        $data['id'] = $id;
        $stmt->execute($data);
    }

    public function delete($id): void
    {
        $sql = "DELETE FROM competitions WHERE id_competition = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
    }

    public function getById($id)
    {
        $sql = "SELECT * FROM competitions WHERE id_competition = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

$controller = new CompetitionController($pdo);