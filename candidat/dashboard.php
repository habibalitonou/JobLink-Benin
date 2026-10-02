<?php

session_start();

if (!isset($_SESSION['candidat_id'])) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/../classes/Database.php';
require_once __DIR__ . '/../classes/Candidat.php';

$database = new Database();

$candidat = Candidat::findById($database, (int) $_SESSION['candidat_id']);

if ($candidat === null) {
    session_unset();
    session_destroy();

    header('Location: login.php');
    exit;
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau de bord candidat - JobLink Bénin</title>
</head>
<body>

<h1>Tableau de bord candidat</h1>

<p>
    Bienvenue
    <strong>
        <?= htmlspecialchars($candidat->getPrenom() . ' ' . $candidat->getNom()) ?>
    </strong>
</p>

<p>
    Email :
    <?= htmlspecialchars($candidat->getEmail()) ?>
</p>

<nav>
    <ul>
        <li><a href="profile.php">Mon profil</a></li>
        <li><a href="offres.php">Rechercher des offres</a></li>
        <li><a href="candidatures.php">Mes candidatures</a></li>
        <li><a href="logout.php">Se déconnecter</a></li>
    </ul>
</nav>

</body>
</html>
