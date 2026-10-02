<?php

session_start();

if (isset($_SESSION['candidat_id'])) {
    header('Location: dashboard.php');
    exit;
}

require_once __DIR__ . '/../classes/Database.php';
require_once __DIR__ . '/../classes/Candidat.php';

$database = new Database();

$erreurs = [];

$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $motDePasse = $_POST['mot_de_passe'] ?? '';

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erreurs[] = 'L’adresse email est invalide.';
    }

    if ($motDePasse === '') {
        $erreurs[] = 'Le mot de passe est obligatoire.';
    }

    if (empty($erreurs)) {
        $candidat = Candidat::findByEmail($database, $email);

        if (
            $candidat === null
            || !password_verify($motDePasse, $candidat->getMotDePasse())
        ) {
            $erreurs[] = 'Email ou mot de passe incorrect.';
        } elseif ($candidat->getStatut() !== 'actif') {
            $erreurs[] = 'Votre compte n’est pas actif.';
        } else {
            session_regenerate_id(true);

            $_SESSION['candidat_id'] = $candidat->getId();
            $_SESSION['candidat_email'] = $candidat->getEmail();

            header('Location: dashboard.php');
            exit;
        }
    }
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion candidat - JobLink Bénin</title>
</head>
<body>

<h1>Connexion candidat</h1>

<p>
    <a href="register.php">Créer un compte</a>
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
        <label for="mot_de_passe">Mot de passe</label><br>
        <input
            type="password"
            id="mot_de_passe"
            name="mot_de_passe"
            required
        >
    </div>

    <br>

    <button type="submit">Se connecter</button>

</form>

</body>
</html>
