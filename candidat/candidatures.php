<?php

session_start();

if (!isset($_SESSION['candidat_id'])) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/../classes/Database.php';
require_once __DIR__ . '/../classes/Candidature.php';
require_once __DIR__ . '/../classes/Offre.php';
require_once __DIR__ . '/../classes/Entreprise.php';

$database = new Database();

$idCandidat = (int) $_SESSION['candidat_id'];

$candidatures = Candidature::findByCandidat($database, $idCandidat);
$offres = Offre::findAll($database);
$entreprises = Entreprise::findAll($database);

$offresParId = [];
foreach ($offres as $offre) {
    $offresParId[$offre->getId()] = $offre;
}

$entreprisesParId = [];
foreach ($entreprises as $entreprise) {
    $entreprisesParId[$entreprise->getId()] = $entreprise;
}

$message = '';

if (isset($_GET['retiree']) && $_GET['retiree'] === '1') {
    $message = 'Votre candidature a été retirée.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idCandidature = (int) ($_POST['id_candidature'] ?? 0);

    if ($idCandidature > 0) {
        $candidature = Candidature::findById($database, $idCandidature);

        if (
            $candidature !== null
            && $candidature->getIdCandidat() === $idCandidat
            && $candidature->getStatut() === 'en_attente'
        ) {
            if ($candidature->delete($database)) {
                header('Location: candidatures.php?retiree=1');
                exit;
            }
        }
    }

    $message = 'Impossible de retirer cette candidature.';
    $candidatures = Candidature::findByCandidat($database, $idCandidat);
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mes candidatures - JobLink Bénin</title>
</head>
<body>

<h1>Mes candidatures</h1>

<p>
    <a href="dashboard.php">← Retour au tableau de bord</a>
</p>

<?php if ($message !== ''): ?>
    <div>
        <?= htmlspecialchars($message) ?>
    </div>
<?php endif; ?>

<?php if (empty($candidatures)): ?>

    <p>Vous n’avez encore envoyé aucune candidature.</p>

<?php else: ?>

    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>Offre</th>
                <th>Entreprise</th>
                <th>Statut</th>
                <th>Date de candidature</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>

        <?php foreach ($candidatures as $candidature): ?>

            <?php
            $offre = $offresParId[$candidature->getIdOffre()] ?? null;
            $entreprise = null;

            if ($offre !== null) {
                $entreprise = $entreprisesParId[$offre->getIdEntreprise()] ?? null;
            }
            ?>

            <tr>
                <td>
                    <?php if ($offre !== null): ?>
                        <?= htmlspecialchars($offre->getTitre()) ?>
                    <?php else: ?>
                        Offre indisponible
                    <?php endif; ?>
                </td>

                <td>
                    <?php if ($entreprise !== null): ?>
                        <?= htmlspecialchars($entreprise->getNom()) ?>
                    <?php else: ?>
                        -
                    <?php endif; ?>
                </td>

                <td>
                    <?= htmlspecialchars($candidature->getStatut()) ?>
                </td>

                <td>
                    <?= htmlspecialchars($candidature->getDateCandidature()) ?>
                </td>

                <td>
                    <?php if ($offre !== null): ?>
                        <a href="offre.php?id=<?= (int) $offre->getId() ?>">
                            Voir l'offre
                        </a>
                    <?php endif; ?>

                    <?php if ($candidature->getStatut() === 'en_attente'): ?>
                        <form method="post" style="display:inline;">
                            <input
                                type="hidden"
                                name="id_candidature"
                                value="<?= (int) $candidature->getId() ?>"
                            >
                            <button type="submit">
                                Retirer
                            </button>
                        </form>
                    <?php endif; ?>
                </td>
            </tr>

        <?php endforeach; ?>

        </tbody>
    </table>

<?php endif; ?>

</body>
</html>
