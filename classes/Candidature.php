<?php

class Candidature
{
    private ?int $id = null;
    private int $idCandidat;
    private int $idOffre;
    private string $lettreMotivation;
    private string $statut;
    private ?string $dateCandidature;

    public function __construct(
        int $idCandidat,
        int $idOffre,
        string $lettreMotivation,
        string $statut,
        ?string $dateCandidature = null
    ) {
        $this->idCandidat = $idCandidat;
        $this->idOffre = $idOffre;
        $this->lettreMotivation = $lettreMotivation;
        $this->statut = $statut;
        $this->dateCandidature = $dateCandidature;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getIdCandidat(): int
    {
        return $this->idCandidat;
    }

    public function setIdCandidat(int $idCandidat): void
    {
        $this->idCandidat = $idCandidat;
    }

    public function getIdOffre(): int
    {
        return $this->idOffre;
    }

    public function setIdOffre(int $idOffre): void
    {
        $this->idOffre = $idOffre;
    }

    public function getLettreMotivation(): string
    {
        return $this->lettreMotivation;
    }

    public function setLettreMotivation(string $lettreMotivation): void
    {
        $this->lettreMotivation = $lettreMotivation;
    }

    public function getStatut(): string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): void
    {
        $this->statut = $statut;
    }

    public function getDateCandidature(): ?string
    {
        return $this->dateCandidature;
    }

    public function setDateCandidature(?string $dateCandidature): void
    {
        $this->dateCandidature = $dateCandidature;
    }

    public static function findById(Database $database, int $id): ?self
    {
        $pdo = $database->getConnection();

        $sql = 'SELECT id, id_candidat, id_offre, lettre_motivation,
                       statut, date_candidature
                FROM candidature
                WHERE id = :id
                LIMIT 1';

        $statement = $pdo->prepare($sql);
        $statement->execute([
            'id' => $id,
        ]);

        $data = $statement->fetch();

        if ($data === false) {
            return null;
        }

        $candidature = new self(
            (int) $data['id_candidat'],
            (int) $data['id_offre'],
            $data['lettre_motivation'],
            $data['statut'],
            $data['date_candidature']
        );

        $candidature->setId((int) $data['id']);

        return $candidature;
    }
}
