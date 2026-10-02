<?php

session_start();

if (!isset($_SESSION['candidat_id'])) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/../classes/Database.php';
require_once __DIR__ . '/../classes/Candidat.php';
require_once __DIR__ . '/../classes/Ville.php';

$database = new Database();

$candidat = Candidat::findById($database, (int) $_SESSION['candidat_id']);

if ($candidat === null) {
    session_unset();
    session_destroy();

    header('Location: login.php');
    exit;
}

$villes = Ville::findAll($database);
$erreurs = [];
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom'] ?? '');
    $prenom = trim($_POST['prenom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telephone = trim($_POST['telephone'] ?? '');
    $idVille = ($_POST['id_ville'] ?? '') !== ''
        ? (int) $_POST['id_ville']
        : null;

    if ($nom === '') {
        $erreurs[] = 'Le nom est obligatoire.';
    }

    if ($prenom === '') {
        $erreurs[] = 'Le prénom est obligatoire.';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erreurs[] = 'L’adresse email est invalide.';
    }

    if ($telephone === '') {
        $erreurs[] = 'Le téléphone est obligatoire.';
    }

    if (empty($erreurs)) {
        $candidatExistant = Candidat::findByEmail($database, $email);

        if (
            $candidatExistant !== null
            && $candidatExistant->getId() !== $candidat->getId()
        ) {
            $erreurs[] = 'Cette adresse email est déjà utilisée par un autre candidat.';
        }
    }

    if (empty($erreurs)) {
        $candidat->setNom($nom);
        $candidat->setPrenom($prenom);
        $candidat->setEmail($email);
        $candidat->setTelephone($telephone);
        $candidat->setIdVille($idVille);

        try {
            if ($candidat->update($database)) {
                $_SESSION['candidat_email'] = $candidat->getEmail();
                $message = 'Votre profil a été mis à jour.';
            } else {
                $erreurs[] = 'Impossible de mettre à jour votre profil.';
            }
        } catch (PDOException $exception) {
            $erreurs[] = 'Impossible de mettre à jour votre profil.';
        }
    }
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon profil - JobLink Bénin</title>
</head>
<body>

<h1>Mon profil</h1>

<p>
    <a href="dashboard.php">← Retour au tableau de bord</a>
</p>

<?php if ($message !== ''): ?>
    <div>
        <?= htmlspecialchars($message) ?>
    </div>
<?php endif; ?>

<?php if (!empty($erreurs)): ?>
    <div>
        <strong>Erreurs :</strong>
        <ul>
            <?php foreach ($erreurs as $erreur): ?>
                <li><?= htmlspecialchars($erreur) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form method="post">

    <div>
        <label for="nom">Nom</label><br>
        <input
            type="text"
            id="nom"
            name="nom"
            value="<?= htmlspecialchars($candidat->getNom()) ?>"
            required
        >
    </div>

    <br>

    <div>
        <label for="prenom">Prénom</label><br>
        <input
            type="text"
            id="prenom"
            name="prenom"
            value="<?= htmlspecialchars($candidat->getPrenom()) ?>"
            required
        >
    </div>

    <br>

    <div>
        <label for="email">Email</label><br>
        <input
            type="email"
            id="email"
            name="email"
            value="<?= htmlspecialchars($candidat->getEmail()) ?>"
            required
        >
    </div>

    <br>

    <div>
        <label for="telephone">Téléphone</label><br>
        <input
            type="text"
            id="telephone"
            name="telephone"
            value="<?= htmlspecialchars($candidat->getTelephone()) ?>"
            required
        >
    </div>

    <br>

    <div>
        <label for="id_ville">Ville</label><br>
        <select id="id_ville" name="id_ville">
            <option value="">-- Choisir une ville --</option>

            <?php foreach ($villes as $ville): ?>
                <option
                    value="<?= (int) $ville->getId() ?>"
                    <?= $candidat->getIdVille() === $ville->getId() ? 'selected' : '' ?>
                >
                    <?= htmlspecialchars($ville->getLibelle()) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <br>

    <button type="submit">Enregistrer les modifications</button>

</form>

</body>
</html>
