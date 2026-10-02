<?php

session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: ../login.php');
    exit;
}

require_once __DIR__ . '/../../classes/Database.php';
require_once __DIR__ . '/../../classes/Candidature.php';
require_once __DIR__ . '/../../classes/Candidat.php';
require_once __DIR__ . '/../../classes/Offre.php';

$database = new Database();

$candidatures = Candidature::findAll($database);
$candidats = Candidat::findAll($database);
$offres = Offre::findAll($database);

$nomsCandidats = [];
$titresOffres = [];

foreach ($candidats as $candidat) {
    $nomsCandidats[$candidat->getId()] =
        $candidat->getNom() . ' ' . $candidat->getPrenom();
}

foreach ($offres as $offre) {
    $titresOffres[$offre->getId()] = $offre->getTitre();
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des candidatures - JobLink Bénin</title>
</head>
<body>

<h1>Gestion des candidatures</h1>

<p>
    <a href="../index.php">Retour au tableau de bord</a>
</p>

<?php if (empty($candidatures)): ?>
    <p>Aucune candidature enregistrée pour le moment.</p>
<?php else: ?>

<table border="1" cellpadding="8" cellspacing="0">
    <thead>
        <tr>
            <th>ID</th>
            <th>Candidat</th>
            <th>Offre</th>
            <th>Lettre de motivation</th>
            <th>Statut</th>
            <th>Date</th>
            <th>Actions</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($candidatures as $candidature): ?>
            <tr>
                <td><?= (int) $candidature->getId() ?></td>

                <td>
                    <?= htmlspecialchars(
                        $nomsCandidats[$candidature->getIdCandidat()] ?? 'Candidat inconnu'
                    ) ?>
                </td>

                <td>
                    <?= htmlspecialchars(
                        $titresOffres[$candidature->getIdOffre()] ?? 'Offre inconnue'
                    ) ?>
                </td>

                <td>
                    <?= nl2br(
                        htmlspecialchars($candidature->getLettreMotivation())
                    ) ?>
                </td>

                <td>
                    <?= htmlspecialchars($candidature->getStatut()) ?>
                </td>

                <td>
                    <?= htmlspecialchars($candidature->getDateCandidature() ?? '') ?>
                </td>

                <td>
                    <a href="edit.php?id=<?= (int) $candidature->getId() ?>">
                        Modifier
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php endif; ?>

</body>
</html>
