<?php

class Entreprise
{
    private ?int $id = null;
    private string $nom;
    private string $email;
    private ?string $telephone;
    private int $idSecteur;
    private int $idVille;

    public function __construct(
        string $nom,
        string $email,
        ?string $telephone,
        int $idSecteur,
        int $idVille
    ) {
        $this->nom = $nom;
        $this->email = $email;
        $this->telephone = $telephone;
        $this->idSecteur = $idSecteur;
        $this->idVille = $idVille;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function setNom(string $nom): void
    {
        $this->nom = $nom;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): void
    {
        $this->telephone = $telephone;
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

    public static function findAll(Database $database): array
    {
        $pdo = $database->getConnection();

        $sql = 'SELECT id, nom, email, telephone, id_secteur, id_ville
                FROM entreprise
                ORDER BY nom ASC';

        $statement = $pdo->prepare($sql);
        $statement->execute();

        $entreprises = [];

        while ($data = $statement->fetch()) {
            $entreprise = new self(
                $data['nom'],
                $data['email'],
                $data['telephone'],
                (int) $data['id_secteur'],
                (int) $data['id_ville']
            );

            $entreprise->setId((int) $data['id']);

            $entreprises[] = $entreprise;
        }

        return $entreprises;
    }

    public static function findById(Database $database, int $id): ?self
    {
        $pdo = $database->getConnection();

        $sql = 'SELECT id, nom, email, telephone, id_secteur, id_ville
                FROM entreprise
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

        $entreprise = new self(
            $data['nom'],
            $data['email'],
            $data['telephone'],
            (int) $data['id_secteur'],
            (int) $data['id_ville']
        );

        $entreprise->setId((int) $data['id']);

        return $entreprise;
    }
}
