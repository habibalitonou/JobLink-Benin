<?php

class TypeContrat
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
                FROM type_contrat
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

        $typeContrat = new self(
            $data['libelle']
        );

        $typeContrat->setId((int) $data['id']);

        return $typeContrat;
    }
}
