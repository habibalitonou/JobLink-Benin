<?php

session_start();

if (!isset($_SESSION['candidat_id'])) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/../classes/Database.php';
require_once __DIR__ . '/../classes/Offre.php';
require_once __DIR__ . '/../classes/Entreprise.php';
require_once __DIR__ . '/../classes/Ville.php';
require_once __DIR__ . '/../classes/TypeContrat.php';

$database = new Database();

$offres = Offre::findAll($database);
$entreprises = Entreprise::findAll($database);
$villes = Ville::findAll($database);
$typesContrat = TypeContrat::findAll($database);

$entreprisesParId = [];
foreach ($entreprises as $entreprise) {
    $entreprisesParId[$entreprise->getId()] = $entreprise;
}

$villesParId = [];
foreach ($villes as $ville) {
    $villesParId[$ville->getId()] = $ville;
}

$typesContratParId = [];
foreach ($typesContrat as $typeContrat) {
    $typesContratParId[$typeContrat->getId()] = $typeContrat;
}

$offresPubliees = [];

foreach ($offres as $offre) {
    if ($offre->getStatut() === 'publiee') {
        $offresPubliees[] = $offre;
    }
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Offres d'emploi - JobLink Bénin</title>
</head>
<body>

<h1>Offres d'emploi</h1>

<p>
    <a href="dashboard.php">← Retour au tableau de bord</a>
</p>

<?php if (empty($offresPubliees)): ?>

    <p>Aucune offre publiée pour le moment.</p>

<?php else: ?>

    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>Titre</th>
                <th>Entreprise</th>
                <th>Ville</th>
                <th>Type de contrat</th>
                <th>Salaire</th>
                <th>Date limite</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>

        <?php foreach ($offresPubliees as $offre): ?>

            <?php
            $entreprise = $entreprisesParId[$offre->getIdEntreprise()] ?? null;
            $ville = $villesParId[$offre->getIdVille()] ?? null;
            $typeContrat = $typesContratParId[$offre->getIdTypeContrat()] ?? null;
            ?>

            <tr>
                <td>
                    <?= htmlspecialchars($offre->getTitre()) ?>
                </td>

                <td>
                    <?= $entreprise !== null
                        ? htmlspecialchars($entreprise->getNom())
                        : '—' ?>
                </td>

                <td>
                    <?= $ville !== null
                        ? htmlspecialchars($ville->getLibelle())
                        : '—' ?>
                </td>

                <td>
                    <?= $typeContrat !== null
                        ? htmlspecialchars($typeContrat->getLibelle())
                        : '—' ?>
                </td>

                <td>
                    <?= $offre->getSalaire() !== null
                        ? htmlspecialchars(number_format($offre->getSalaire(), 0, ',', ' ')) . ' FCFA'
                        : 'Non précisé' ?>
                </td>

                <td>
                    <?= $offre->getDateLimite() !== null
                        ? htmlspecialchars($offre->getDateLimite())
                        : 'Non précisée' ?>
                </td>

                <td>
                    <a href="offre.php?id=<?= (int) $offre->getId() ?>">
                        Voir l'offre
                    </a>
                </td>
            </tr>

        <?php endforeach; ?>

        </tbody>
    </table>

<?php endif; ?>

</body>
</html>
