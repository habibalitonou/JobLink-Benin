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
        string $statut = 'en_attente',
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

    public static function findAll(Database $database): array
    {
        $pdo = $database->getConnection();

        $sql = 'SELECT id, id_candidat, id_offre, lettre_motivation,
                       statut, date_candidature
                FROM candidature
                ORDER BY date_candidature DESC, id DESC';

        $statement = $pdo->query($sql);
        $candidatures = [];

        while ($data = $statement->fetch()) {
            $candidature = new self(
                (int) $data['id_candidat'],
                (int) $data['id_offre'],
                $data['lettre_motivation'],
                $data['statut'],
                $data['date_candidature']
            );

            $candidature->setId((int) $data['id']);
            $candidatures[] = $candidature;
        }

        return $candidatures;
    }

    public static function findByCandidat(Database $database, int $idCandidat): array
    {
        $pdo = $database->getConnection();

        $sql = 'SELECT id, id_candidat, id_offre, lettre_motivation,
                       statut, date_candidature
                FROM candidature
                WHERE id_candidat = :id_candidat
                ORDER BY date_candidature DESC, id DESC';

        $statement = $pdo->prepare($sql);
        $statement->execute([
            'id_candidat' => $idCandidat,
        ]);

        $candidatures = [];

        while ($data = $statement->fetch()) {
            $candidature = new self(
                (int) $data['id_candidat'],
                (int) $data['id_offre'],
                $data['lettre_motivation'],
                $data['statut'],
                $data['date_candidature']
            );

            $candidature->setId((int) $data['id']);
            $candidatures[] = $candidature;
        }

        return $candidatures;
    }

    public static function existsForCandidatAndOffre(
        Database $database,
        int $idCandidat,
        int $idOffre
    ): bool {
        $pdo = $database->getConnection();

        $sql = 'SELECT id
                FROM candidature
                WHERE id_candidat = :id_candidat
                  AND id_offre = :id_offre
                LIMIT 1';

        $statement = $pdo->prepare($sql);
        $statement->execute([
            'id_candidat' => $idCandidat,
            'id_offre' => $idOffre,
        ]);

        return $statement->fetch() !== false;
    }

    public function create(Database $database): bool
    {
        $pdo = $database->getConnection();

        if (self::existsForCandidatAndOffre(
            $database,
            $this->idCandidat,
            $this->idOffre
        )) {
            return false;
        }

        $sql = 'INSERT INTO candidature (
                    id_candidat,
                    id_offre,
                    lettre_motivation,
                    statut
                ) VALUES (
                    :id_candidat,
                    :id_offre,
                    :lettre_motivation,
                    :statut
                )';

        $statement = $pdo->prepare($sql);

        $success = $statement->execute([
            'id_candidat' => $this->idCandidat,
            'id_offre' => $this->idOffre,
            'lettre_motivation' => $this->lettreMotivation,
            'statut' => $this->statut,
        ]);

        if ($success) {
            $this->setId((int) $pdo->lastInsertId());
        }

        return $success;
    }

    public function update(Database $database): bool
    {
        if ($this->id === null) {
            return false;
        }

        $pdo = $database->getConnection();

        $sql = 'UPDATE candidature
                SET lettre_motivation = :lettre_motivation,
                    statut = :statut
                WHERE id = :id';

        $statement = $pdo->prepare($sql);

        return $statement->execute([
            'lettre_motivation' => $this->lettreMotivation,
            'statut' => $this->statut,
            'id' => $this->id,
        ]);
    }

    public function delete(Database $database): bool
    {
        if ($this->id === null) {
            return false;
        }

        $pdo = $database->getConnection();

        $sql = 'DELETE FROM candidature
                WHERE id = :id';

        $statement = $pdo->prepare($sql);

        return $statement->execute([
            'id' => $this->id,
        ]);
    }
}
