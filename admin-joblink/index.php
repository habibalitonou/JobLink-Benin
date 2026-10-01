<?php

session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/../classes/Database.php';
require_once __DIR__ . '/../classes/Administrateur.php';

$database = new Database();

$administrateur = Administrateur::findByEmail(
    $database,
    $_SESSION['admin_email']
);

if ($administrateur === null) {
    $_SESSION = [];
    session_destroy();

    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord - JobLink Bénin</title>
</head>
<body>

    <header>
        <h1>JobLink Bénin</h1>

        <p>
            Bienvenue,
            <?= htmlspecialchars(
                $administrateur->getPrenom() . ' ' . $administrateur->getNom(),
                ENT_QUOTES,
                'UTF-8'
            ) ?>
        </p>

        <a href="logout.php">Se déconnecter</a>
    </header>

    <main>
        <h2>Tableau de bord administrateur</h2>

        <p>Gestion de la plateforme JobLink Bénin.</p>

        <nav>
            <ul>
                <li><a href="entreprises/">Entreprises</a></li>
                <li><a href="offres/">Offres</a></li>
                <li><a href="candidats/">Candidats</a></li>
                <li><a href="candidatures/">Candidatures</a></li>
                <li><a href="referentiels/">Référentiels</a></li>
            </ul>
        </nav>
    </main>

</body>
</html>
