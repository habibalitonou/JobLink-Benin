<?php

class Candidat
{
    private ?int $id = null;
    private string $nom;
    private string $prenom;
    private string $email;
    private string $motDePasse;
    private string $telephone;
    private ?int $idVille;
    private ?string $cvFichier;
    private string $statut;
    private ?string $dateInscription;

    public function __construct(
        string $nom,
        string $prenom,
        string $email,
        string $motDePasse,
        string $telephone,
        ?int $idVille,
        ?string $cvFichier,
        string $statut,
        ?string $dateInscription = null
    ) {
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->email = $email;
        $this->motDePasse = $motDePasse;
        $this->telephone = $telephone;
        $this->idVille = $idVille;
        $this->cvFichier = $cvFichier;
        $this->statut = $statut;
        $this->dateInscription = $dateInscription;
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

    public function getPrenom(): string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): void
    {
        $this->prenom = $prenom;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getMotDePasse(): string
    {
        return $this->motDePasse;
    }

    public function setMotDePasse(string $motDePasse): void
    {
        $this->motDePasse = $motDePasse;
    }

    public function getTelephone(): string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): void
    {
        $this->telephone = $telephone;
    }

    public function getIdVille(): ?int
    {
        return $this->idVille;
    }

    public function setIdVille(?int $idVille): void
    {
        $this->idVille = $idVille;
    }

    public function getCvFichier(): ?string
    {
        return $this->cvFichier;
    }

    public function setCvFichier(?string $cvFichier): void
    {
        $this->cvFichier = $cvFichier;
    }

    public function getStatut(): string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): void
    {
        $this->statut = $statut;
    }

    public function getDateInscription(): ?string
    {
        return $this->dateInscription;
    }

    public function setDateInscription(?string $dateInscription): void
    {
        $this->dateInscription = $dateInscription;
    }

    public static function findAll(Database $database): array
    {
        $pdo = $database->getConnection();

        $sql = 'SELECT id, nom, prenom, email, mot_de_passe, telephone,
                       id_ville, cv_fichier, statut, date_inscription
                FROM candidat
                ORDER BY nom ASC, prenom ASC';

        $statement = $pdo->query($sql);
        $candidats = [];

        while ($data = $statement->fetch()) {
            $candidat = new self(
                $data['nom'],
                $data['prenom'],
                $data['email'],
                $data['mot_de_passe'],
                $data['telephone'],
                $data['id_ville'] !== null ? (int) $data['id_ville'] : null,
                $data['cv_fichier'],
                $data['statut'],
                $data['date_inscription']
            );

            $candidat->setId((int) $data['id']);
            $candidats[] = $candidat;
        }

        return $candidats;
    }

    public function update(Database $database): bool
    {
        if ($this->id === null) {
            return false;
        }

        $pdo = $database->getConnection();

        $sql = 'UPDATE candidat
                SET nom = :nom,
                    prenom = :prenom,
                    email = :email,
                    telephone = :telephone,
                    id_ville = :id_ville,
                    cv_fichier = :cv_fichier,
                    statut = :statut
                WHERE id = :id';

        $statement = $pdo->prepare($sql);

        return $statement->execute([
            'nom' => $this->nom,
            'prenom' => $this->prenom,
            'email' => $this->email,
            'telephone' => $this->telephone,
            'id_ville' => $this->idVille,
            'cv_fichier' => $this->cvFichier,
            'statut' => $this->statut,
            'id' => $this->id,
        ]);
    }

    public static function findById(Database $database, int $id): ?self
    {
        $pdo = $database->getConnection();

        $sql = 'SELECT id, nom, prenom, email, mot_de_passe, telephone,
                       id_ville, cv_fichier, statut, date_inscription
                FROM candidat
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

        $candidat = new self(
            $data['nom'],
            $data['prenom'],
            $data['email'],
            $data['mot_de_passe'],
            $data['telephone'],
            $data['id_ville'] !== null ? (int) $data['id_ville'] : null,
            $data['cv_fichier'],
            $data['statut'],
            $data['date_inscription']
        );

        $candidat->setId((int) $data['id']);

        return $candidat;
    }
    public static function findByEmail(Database $database, string $email): ?self
    {
        $pdo = $database->getConnection();

        $sql = 'SELECT id, nom, prenom, email, mot_de_passe, telephone,
                       id_ville, cv_fichier, statut, date_inscription
                FROM candidat
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

        $candidat = new self(
            $data['nom'],
            $data['prenom'],
            $data['email'],
            $data['mot_de_passe'],
            $data['telephone'],
            $data['id_ville'] !== null ? (int) $data['id_ville'] : null,
            $data['cv_fichier'],
            $data['statut'],
            $data['date_inscription']
        );

        $candidat->setId((int) $data['id']);

        return $candidat;
    }

    public function create(Database $database): bool
    {
        $pdo = $database->getConnection();

        $sql = 'INSERT INTO candidat (
                    nom,
                    prenom,
                    email,
                    mot_de_passe,
                    telephone,
                    id_ville,
                    cv_fichier,
                    statut
                ) VALUES (
                    :nom,
                    :prenom,
                    :email,
                    :mot_de_passe,
                    :telephone,
                    :id_ville,
                    :cv_fichier,
                    :statut
                )';

        $statement = $pdo->prepare($sql);

        $success = $statement->execute([
            'nom' => $this->nom,
            'prenom' => $this->prenom,
            'email' => $this->email,
            'mot_de_passe' => $this->motDePasse,
            'telephone' => $this->telephone,
            'id_ville' => $this->idVille,
            'cv_fichier' => $this->cvFichier,
            'statut' => $this->statut,
        ]);

        if ($success) {
            $this->setId((int) $pdo->lastInsertId());
        }

        return $success;
    }
}
