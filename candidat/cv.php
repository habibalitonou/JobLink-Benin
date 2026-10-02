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

$erreurs = [];
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_FILES['cv'])) {
        $erreurs[] = 'Veuillez sélectionner un fichier PDF.';
    } elseif ($_FILES['cv']['error'] === UPLOAD_ERR_INI_SIZE) {
        $erreurs[] = 'Le CV ne doit pas dépasser 2 Mo.';
    } elseif ($_FILES['cv']['error'] !== UPLOAD_ERR_OK) {
        $erreurs[] = 'Une erreur est survenue lors de l’envoi du CV.';
    } else {
        $fichier = $_FILES['cv'];

        if ($fichier['size'] > 2 * 1024 * 1024) {
            $erreurs[] = 'Le CV ne doit pas dépasser 2 Mo.';
        }

        $extension = strtolower(pathinfo($fichier['name'], PATHINFO_EXTENSION));

        if ($extension !== 'pdf') {
            $erreurs[] = 'Le CV doit être au format PDF.';
        }

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $typeMime = $finfo->file($fichier['tmp_name']);

        if ($typeMime !== 'application/pdf') {
            $erreurs[] = 'Le fichier sélectionné n’est pas un PDF valide.';
        }

        if (empty($erreurs)) {
            $repertoire = __DIR__ . '/../uploads/cv';

            if (!is_dir($repertoire)) {
                mkdir($repertoire, 0755, true);
            }

            $nomFichier = 'candidat_' . $candidat->getId() . '_' . bin2hex(random_bytes(8)) . '.pdf';
            $destination = $repertoire . '/' . $nomFichier;

            if (move_uploaded_file($fichier['tmp_name'], $destination)) {
                $ancienCv = $candidat->getCvFichier();

                $candidat->setCvFichier($nomFichier);

                if ($candidat->update($database)) {
                    if (
                        $ancienCv !== null
                        && $ancienCv !== ''
                        && is_file($repertoire . '/' . basename($ancienCv))
                    ) {
                        unlink($repertoire . '/' . basename($ancienCv));
                    }

                    $message = 'Votre CV a été enregistré avec succès.';
                } else {
                    unlink($destination);
                    $erreurs[] = 'Impossible d’enregistrer votre CV.';
                }
            } else {
                $erreurs[] = 'Impossible de déplacer le fichier envoyé.';
            }
        }
    }
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon CV - JobLink Bénin</title>
</head>
<body>

<h1>Mon CV</h1>

<p>
    <a href="dashboard.php">← Retour au tableau de bord</a>
</p>

<?php if ($message !== ''): ?>
    <div>
        <?= htmlspecialchars($message) ?>
    </div>
<?php endif; ?>

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

<?php if ($candidat->getCvFichier() !== null && $candidat->getCvFichier() !== ''): ?>
    <p>
        CV actuel :
        <strong><?= htmlspecialchars($candidat->getCvFichier()) ?></strong>
    </p>
<?php else: ?>
    <p>Aucun CV n’est actuellement enregistré.</p>
<?php endif; ?>

<form method="post" enctype="multipart/form-data">

    <div>
        <label for="cv">CV PDF</label><br>
        <input
            type="file"
            id="cv"
            name="cv"
            accept="application/pdf,.pdf"
            required
        >
    </div>

    <p>Format accepté : PDF — taille maximale : 2 Mo.</p>

    <button type="submit">Enregistrer mon CV</button>

</form>

</body>
</html>
