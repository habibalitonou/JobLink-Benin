<?php

session_start();

require_once __DIR__ . '/../classes/Database.php';
require_once __DIR__ . '/../classes/Administrateur.php';

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $motDePasse = $_POST['mot_de_passe'] ?? '';

    if ($email === '' || $motDePasse === '') {
        $error = 'Veuillez renseigner votre adresse e-mail et votre mot de passe.';
    } else {
        $database = new Database();
        $administrateur = Administrateur::findByEmail($database, $email);

        if ($administrateur !== null
            && password_verify($motDePasse, $administrateur->getMotDePasse())
        ) {
            session_regenerate_id(true);

            $_SESSION['admin_id'] = $administrateur->getId();
            $_SESSION['admin_email'] = $administrateur->getEmail();

            header('Location: index.php');
            exit;
        }

        $error = 'Adresse e-mail ou mot de passe incorrect.';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion administrateur - JobLink Bénin</title>
</head>
<body>

    <h1>Connexion administrateur</h1>

    <?php if ($error !== null): ?>
        <p><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
    <?php endif; ?>

    <form method="post" action="">
        <div>
            <label for="email">Adresse e-mail</label>
            <input
                type="email"
                id="email"
                name="email"
                required
                autocomplete="email"
            >
        </div>

        <div>
            <label for="mot_de_passe">Mot de passe</label>
            <input
                type="password"
                id="mot_de_passe"
                name="mot_de_passe"
                required
                autocomplete="current-password"
            >
        </div>

        <button type="submit">Se connecter</button>
    </form>

</body>
</html>
