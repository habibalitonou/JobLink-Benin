<?php

class Offre
{
    private ?int $id = null;
    private string $titre;
    private string $description;
    private int $idEntreprise;
    private int $idSecteur;
    private int $idVille;
    private int $idTypeContrat;
    private ?float $salaire;
    private ?string $dateLimite;
    private string $statut;

    public function __construct(
        string $titre,
        string $description,
        int $idEntreprise,
        int $idSecteur,
        int $idVille,
        int $idTypeContrat,
        ?float $salaire,
        ?string $dateLimite,
        string $statut
    ) {
        $this->titre = $titre;
        $this->description = $description;
        $this->idEntreprise = $idEntreprise;
        $this->idSecteur = $idSecteur;
        $this->idVille = $idVille;
        $this->idTypeContrat = $idTypeContrat;
        $this->salaire = $salaire;
        $this->dateLimite = $dateLimite;
        $this->statut = $statut;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): void
    {
        $this->titre = $titre;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getIdEntreprise(): int
    {
        return $this->idEntreprise;
    }

    public function setIdEntreprise(int $idEntreprise): void
    {
        $this->idEntreprise = $idEntreprise;
    }

    public function getIdSecteur(): int
    {
        return $this->idSecteur;
    }

    public function setIdSecteur(int $idSecteur): void
    {
        $this->idSecteur = $idSecteur;
    }

    public function getIdVille(): int
    {
        return $this->idVille;
    }

    public function setIdVille(int $idVille): void
    {
        $this->idVille = $idVille;
    }

    public function getIdTypeContrat(): int
    {
        return $this->idTypeContrat;
    }

    public function setIdTypeContrat(int $idTypeContrat): void
    {
        $this->idTypeContrat = $idTypeContrat;
    }

    public function getSalaire(): ?float
    {
        return $this->salaire;
    }

    public function setSalaire(?float $salaire): void
    {
        $this->salaire = $salaire;
    }

    public function getDateLimite(): ?string
    {
        return $this->dateLimite;
    }

    public function setDateLimite(?string $dateLimite): void
    {
        $this->dateLimite = $dateLimite;
    }

    public function getStatut(): string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): void
    {
        $this->statut = $statut;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public static function findById(Database $database, int $id): ?self
    {
        $pdo = $database->getConnection();

        $sql = 'SELECT id, titre, description, salaire, date_limite, statut,
                       id_entreprise, id_secteur, id_ville, id_type_contrat
                FROM offre
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

        $offre = new self(
            $data['titre'],
            $data['description'],
            (int) $data['id_entreprise'],
            (int) $data['id_secteur'],
            (int) $data['id_ville'],
            (int) $data['id_type_contrat'],
            $data['salaire'] !== null ? (float) $data['salaire'] : null,
            $data['date_limite'],
            $data['statut']
        );

        $offre->setId((int) $data['id']);

        return $offre;
    }

    public static function findAll(Database $database): array
    {
        $pdo = $database->getConnection();

        $sql = 'SELECT id, titre, description, salaire, date_limite, statut,
                       id_entreprise, id_secteur, id_ville, id_type_contrat
                FROM offre
                ORDER BY titre ASC';

        $statement = $pdo->prepare($sql);
        $statement->execute();

        $offres = [];

        while ($data = $statement->fetch()) {
            $offre = new self(
                $data['titre'],
                $data['description'],
                (int) $data['id_entreprise'],
                (int) $data['id_secteur'],
                (int) $data['id_ville'],
                (int) $data['id_type_contrat'],
                $data['salaire'] !== null ? (float) $data['salaire'] : null,
                $data['date_limite'],
                $data['statut']
            );

            $offre->setId((int) $data['id']);
            $offres[] = $offre;
        }

        return $offres;
    }

    public static function searchPublished(Database $database, string $keyword = ''): array
    {
        $pdo = $database->getConnection();

        $sql = 'SELECT id, titre, description, salaire, date_limite, statut,
                       id_entreprise, id_secteur, id_ville, id_type_contrat
                FROM offre
                WHERE statut = :statut';

        if ($keyword !== '') {
            $sql .= ' AND (titre LIKE :keyword OR description LIKE :keyword)';
        }

        $sql .= ' ORDER BY titre ASC';

        $statement = $pdo->prepare($sql);

        $params = [
            'statut' => 'publiee',
        ];

        if ($keyword !== '') {
            $params['keyword'] = '%' . $keyword . '%';
        }

        $statement->execute($params);

        $offres = [];

        while ($data = $statement->fetch()) {
            $offre = new self(
                $data['titre'],
                $data['description'],
                (int) $data['id_entreprise'],
                (int) $data['id_secteur'],
                (int) $data['id_ville'],
                (int) $data['id_type_contrat'],
                $data['salaire'] !== null ? (float) $data['salaire'] : null,
                $data['date_limite'],
                $data['statut']
            );

            $offre->setId((int) $data['id']);
            $offres[] = $offre;
        }

        return $offres;
    }
    public function create(Database $database): bool
    {
        $pdo = $database->getConnection();

        $sql = 'INSERT INTO offre
                (titre, description, salaire, date_limite, statut,
                 id_entreprise, id_secteur, id_ville, id_type_contrat)
                VALUES
                (:titre, :description, :salaire, :date_limite, :statut,
                 :id_entreprise, :id_secteur, :id_ville, :id_type_contrat)';

        $statement = $pdo->prepare($sql);

        $statement->execute([
            'titre' => $this->titre,
            'description' => $this->description,
            'salaire' => $this->salaire,
            'date_limite' => $this->dateLimite,
            'statut' => $this->statut,
            'id_entreprise' => $this->idEntreprise,
            'id_secteur' => $this->idSecteur,
            'id_ville' => $this->idVille,
            'id_type_contrat' => $this->idTypeContrat,
        ]);

        $this->id = (int) $pdo->lastInsertId();

        return true;
    }

    public function update(Database $database): bool
    {
        if ($this->id === null) {
            return false;
        }

        $pdo = $database->getConnection();

        $sql = 'UPDATE offre
                SET titre = :titre,
                    description = :description,
                    salaire = :salaire,
                    date_limite = :date_limite,
                    statut = :statut,
                    id_entreprise = :id_entreprise,
                    id_secteur = :id_secteur,
                    id_ville = :id_ville,
                    id_type_contrat = :id_type_contrat
                WHERE id = :id';

        $statement = $pdo->prepare($sql);

        return $statement->execute([
            'titre' => $this->titre,
            'description' => $this->description,
            'salaire' => $this->salaire,
            'date_limite' => $this->dateLimite,
            'statut' => $this->statut,
            'id_entreprise' => $this->idEntreprise,
            'id_secteur' => $this->idSecteur,
            'id_ville' => $this->idVille,
            'id_type_contrat' => $this->idTypeContrat,
            'id' => $this->id,
        ]);
    }

    public function delete(Database $database): bool
    {
        if ($this->id === null) {
            return false;
        }

        $pdo = $database->getConnection();

        $sql = 'DELETE FROM offre
                WHERE id = :id';

        $statement = $pdo->prepare($sql);

        return $statement->execute([
            'id' => $this->id,
        ]);
    }
}
