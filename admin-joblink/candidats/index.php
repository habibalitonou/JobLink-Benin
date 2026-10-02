<?php

session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: ../login.php');
    exit;
}

require_once __DIR__ . '/../../classes/Database.php';
require_once __DIR__ . '/../../classes/Candidat.php';
require_once __DIR__ . '/../../classes/Ville.php';


$database = new Database();
$candidats = Candidat::findAll($database);
$villes = Ville::findAll($database);

$nomsVilles = [];

foreach ($villes as $ville) {
    $nomsVilles[$ville->getId()] = $ville->getLibelle();
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des candidats - JobLink Bénin</title>
</head>
<body>

<h1>Gestion des candidats</h1>

<p>
    <a href="../index.php">Retour au tableau de bord</a>
</p>

<table border="1" cellpadding="8" cellspacing="0">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Email</th>
            <th>Téléphone</th>
            <th>Ville</th>
            <th>Statut</th>
            <th>Date d'inscription</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($candidats as $candidat): ?>
            <tr>
                <td><?= htmlspecialchars((string) $candidat->getId()) ?></td>
                <td><?= htmlspecialchars($candidat->getNom()) ?></td>
                <td><?= htmlspecialchars($candidat->getPrenom()) ?></td>
                <td><?= htmlspecialchars($candidat->getEmail()) ?></td>
                <td><?= htmlspecialchars($candidat->getTelephone() ?? '') ?></td>
                <td>
                    <?= htmlspecialchars(
                        $candidat->getIdVille() !== null
                            ? ($nomsVilles[$candidat->getIdVille()] ?? '—')
                            : '—'
                    ) ?>
                </td>
                <td><?= htmlspecialchars($candidat->getStatut()) ?></td>
                <td><?= htmlspecialchars($candidat->getDateInscription() ?? '') ?></td>
                <td>
                    <a href="edit.php?id=<?= (int) $candidat->getId() ?>">Modifier</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>
