<?php

class CompetitionJeuxVideo
{
    private ?int $idCompetition;
    private ?string $nom;
    private ?string $description;
    private ?DateTime $date;
    private ?int $prixEntree;
    private ?float $latitude;
    private ?float $longitude;
    private ?string $nomPersonneContacter;
    private ?string $emailContacter;
    private string $photo;

    public function __construct(
        ?int $idCompetition = null,
        ?string $nom = null,
        ?string $description = null,
        ?DateTime $date = null,
        ?int $prixEntree = null,
        ?float $latitude = null,
        ?float $longitude = null,
        ?string $nomPersonneContacter = null,
        ?string $emailContacter = null,
        string $photo = ''
    ) {
        $this->idCompetition = $idCompetition;
        $this->nom = $nom;
        $this->description = $description;
        $this->date = $date;
        $this->prixEntree = $prixEntree;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->nomPersonneContacter = $nomPersonneContacter;
        $this->emailContacter = $emailContacter;
        $this->photo = $photo;
    }

    public function getIdCompetition(): ?int
    {
        return $this->idCompetition;
    }

    public function setIdCompetition(?int $idCompetition): void
    {
        $this->idCompetition = $idCompetition;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): void
    {
        $this->nom = $nom;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getDate(): ?DateTime
    {
        return $this->date;
    }

    public function setDate(?DateTime $date): void
    {
        $this->date = $date;
    }

    public function getPrixEntree(): ?int
    {
        return $this->prixEntree;
    }

    public function setPrixEntree(?int $prixEntree): void
    {
        $this->prixEntree = $prixEntree;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(?float $latitude): void
    {
        $this->latitude = $latitude;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(?float $longitude): void
    {
        $this->longitude = $longitude;
    }

    public function getNomPersonneContacter(): ?string
    {
        return $this->nomPersonneContacter;
    }

    public function setNomPersonneContacter(?string $nomPersonneContacter): void
    {
        $this->nomPersonneContacter = $nomPersonneContacter;
    }

    public function getEmailContacter(): ?string
    {
        return $this->emailContacter;
    }

    public function setEmailContacter(?string $emailContacter): void
    {
        $this->emailContacter = $emailContacter;
    }

    public function getPhoto(): string
    {
        return $this->photo;
    }

    public function setPhoto(string $photo): void
    {
        $this->photo = $photo;
    }
}