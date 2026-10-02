<?php

class Secteur
{
    private ?int $id = null;
    private string $libelle;

    public function __construct(string $libelle)
    {
        $this->libelle = $libelle;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getLibelle(): string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): void
    {
        $this->libelle = $libelle;
    }

    public static function findById(Database $database, int $id): ?self
    {
        $pdo = $database->getConnection();

        $sql = 'SELECT id, libelle
                FROM secteur
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

        $secteur = new self(
            $data['libelle']
        );

        $secteur->setId((int) $data['id']);

        return $secteur;
    }

    public static function findAll(Database $database): array
    {
        $pdo = $database->getConnection();

        $sql = 'SELECT id, libelle
                FROM secteur
                ORDER BY libelle ASC';

        $statement = $pdo->prepare($sql);
        $statement->execute();

        $secteurs = [];

        while ($data = $statement->fetch()) {
            $secteur = new self(
                $data['libelle']
            );

            $secteur->setId((int) $data['id']);

            $secteurs[] = $secteur;
        }

        return $secteurs;
    }

}
