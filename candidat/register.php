<?php

session_start();

if (isset($_SESSION['candidat_id'])) {
    header('Location: dashboard.php');
    exit;
}

require_once __DIR__ . '/../classes/Database.php';
require_once __DIR__ . '/../classes/Candidat.php';
require_once __DIR__ . '/../classes/Ville.php';

$database = new Database();
$villes = Ville::findAll($database);

$erreurs = [];

$nom = '';
$prenom = '';
$email = '';
$telephone = '';
$idVille = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom'] ?? '');
    $prenom = trim($_POST['prenom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telephone = trim($_POST['telephone'] ?? '');
    $idVille = ($_POST['id_ville'] ?? '') !== ''
        ? (int) $_POST['id_ville']
        : null;

    $motDePasse = $_POST['mot_de_passe'] ?? '';
    $confirmation = $_POST['confirmation'] ?? '';

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

    if (strlen($motDePasse) < 8) {
        $erreurs[] = 'Le mot de passe doit contenir au moins 8 caractères.';
    }

    if ($motDePasse !== $confirmation) {
        $erreurs[] = 'Les mots de passe ne correspondent pas.';
    }

    if (empty($erreurs) && Candidat::findByEmail($database, $email) !== null) {
        $erreurs[] = 'Cette adresse email est déjà utilisée.';
    }

    if (empty($erreurs)) {
        $hash = password_hash($motDePasse, PASSWORD_DEFAULT);

        $candidat = new Candidat(
            $nom,
            $prenom,
            $email,
            $hash,
            $telephone,
            $idVille,
            null,
            'actif'
        );

        try {
            if ($candidat->create($database)) {
                session_regenerate_id(true);

                $_SESSION['candidat_id'] = $candidat->getId();
                $_SESSION['candidat_email'] = $candidat->getEmail();

                header('Location: dashboard.php');
                exit;
            }

            $erreurs[] = 'Impossible de créer le compte.';
        } catch (PDOException $exception) {
            $erreurs[] = 'Impossible de créer le compte. Vérifiez les informations saisies.';
        }
    }
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription candidat - JobLink Bénin</title>
</head>
<body>

<h1>Créer un compte candidat</h1>

<p>
    <a href="login.php">Déjà inscrit ? Se connecter</a>
</p>

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
            value="<?= htmlspecialchars($nom) ?>"
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
            value="<?= htmlspecialchars($prenom) ?>"
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
            value="<?= htmlspecialchars($email) ?>"
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
            value="<?= htmlspecialchars($telephone) ?>"
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
                    <?= $idVille === $ville->getId() ? 'selected' : '' ?>
                >
                    <?= htmlspecialchars($ville->getLibelle()) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <br>

    <div>
        <label for="mot_de_passe">Mot de passe</label><br>
        <input
            type="password"
            id="mot_de_passe"
            name="mot_de_passe"
            minlength="8"
            required
        >
    </div>

    <br>

    <div>
        <label for="confirmation">Confirmer le mot de passe</label><br>
        <input
            type="password"
            id="confirmation"
            name="confirmation"
            minlength="8"
            required
        >
    </div>

    <br>

    <button type="submit">Créer mon compte</button>

</form>

</body>
</html>
