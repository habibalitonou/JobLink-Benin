<?php

class Administrateur
{
    private ?int $id = null;
    private string $nom;
    private string $prenom;
    private string $email;
    private string $motDePasse;

    public function __construct(
        string $nom,
        string $prenom,
        string $email,
        string $motDePasse
    ) {
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->email = $email;
        $this->motDePasse = $motDePasse;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public static function findByEmail(Database $database, string $email): ?self
    {
        $pdo = $database->getConnection();

        $sql = 'SELECT id, nom, prenom, email, mot_de_passe
                FROM administrateur
                WHERE email = :email
                LIMIT 1';

        $statement = $pdo->prepare($sql);
        $statement->execute([
            'email' => $email,
        ]);

        $data = $statement->fetch();

        if ($data === false) {
            return null;
        }

        $administrateur = new self(
            $data['nom'],
            $data['prenom'],
            $data['email'],
            $data['mot_de_passe']
        );

        $administrateur->setId((int) $data['id']);

        return $administrateur;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function getPrenom(): string
    {
        return $this->prenom;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getMotDePasse(): string
    {
        return $this->motDePasse;
    }

    public function setNom(string $nom): void
    {
        $this->nom = $nom;
    }

    public function setPrenom(string $prenom): void
    {
        $this->prenom = $prenom;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function setMotDePasse(string $motDePasse): void
    {
        $this->motDePasse = $motDePasse;
    }
}
