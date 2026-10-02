<?php

session_start();

if (!isset($_SESSION['candidat_id'])) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/../classes/Database.php';
require_once __DIR__ . '/../classes/Candidat.php';
require_once __DIR__ . '/../classes/Offre.php';
require_once __DIR__ . '/../classes/Candidature.php';

$database = new Database();

$idOffre = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($idOffre <= 0) {
    header('Location: offres.php');
    exit;
}

$candidat = Candidat::findById(
    $database,
    (int) $_SESSION['candidat_id']
);

$offre = Offre::findById($database, $idOffre);

if ($candidat === null || $offre === null || $offre->getStatut() !== 'publiee') {
    header('Location: offres.php');
    exit;
}

if (Candidature::existsForCandidatAndOffre(
    $database,
    $candidat->getId(),
    $offre->getId()
)) {
    header('Location: candidatures.php');
    exit;
}

$erreurs = [];
$lettreMotivation = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $lettreMotivation = trim($_POST['lettre_motivation'] ?? '');

    if ($lettreMotivation === '') {
        $erreurs[] = 'La lettre de motivation est obligatoire.';
    }

    if ($candidat->getCvFichier() === null || $candidat->getCvFichier() === '') {
        $erreurs[] = 'Vous devez enregistrer votre CV avant de postuler.';
    }

    if (
        empty($erreurs)
        && Candidature::existsForCandidatAndOffre(
            $database,
            $candidat->getId(),
            $offre->getId()
        )
    ) {
        $erreurs[] = 'Vous avez déjà candidaté à cette offre.';
    }

    if (empty($erreurs)) {
        $candidature = new Candidature(
            $candidat->getId(),
            $offre->getId(),
            $lettreMotivation
        );

        try {
            if ($candidature->create($database)) {
                header('Location: candidatures.php');
                exit;
            }

            $erreurs[] = 'Impossible d’enregistrer votre candidature.';
        } catch (PDOException $exception) {
            $erreurs[] = 'Impossible d’enregistrer votre candidature.';
        }
    }
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Postuler - JobLink Bénin</title>
</head>
<body>

<h1>Postuler à une offre</h1>

<p>
    <a href="offre.php?id=<?= (int) $offre->getId() ?>">
        ← Retour à l'offre
    </a>
</p>

<h2><?= htmlspecialchars($offre->getTitre()) ?></h2>

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

<p>
    CV enregistré :
    <strong><?= htmlspecialchars($candidat->getCvFichier()) ?></strong>
</p>

<form method="post">

    <div>
        <label for="lettre_motivation">
            Lettre de motivation
        </label><br>

        <textarea
            id="lettre_motivation"
            name="lettre_motivation"
            rows="10"
            cols="70"
            required
        ><?= htmlspecialchars($lettreMotivation) ?></textarea>
    </div>

    <br>

    <button type="submit">Envoyer ma candidature</button>

</form>

</body>
</html>
