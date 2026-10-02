<?php

session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: ../login.php');
    exit;
}

require_once __DIR__ . '/../../classes/Database.php';
require_once __DIR__ . '/../../classes/Candidature.php';

$database = new Database();

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($id === false || $id === null) {
    header('Location: index.php');
    exit;
}

$candidature = Candidature::findById($database, $id);

if ($candidature === null) {
    header('Location: index.php');
    exit;
}

$erreurs = [];

$lettreMotivationValeur = $candidature->getLettreMotivation();
$statutValeur = $candidature->getStatut();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $lettreMotivationValeur = trim($_POST['lettre_motivation'] ?? '');
    $statutValeur = trim($_POST['statut'] ?? '');

    if ($lettreMotivationValeur === '') {
        $erreurs[] = 'La lettre de motivation est obligatoire.';
    }

    $statutsAutorises = [
        'en_attente',
        'acceptee',
        'refusee',
    ];

    if (!in_array($statutValeur, $statutsAutorises, true)) {
        $erreurs[] = 'Le statut sélectionné est invalide.';
    }

    if (empty($erreurs)) {
        $candidature->setLettreMotivation($lettreMotivationValeur);
        $candidature->setStatut($statutValeur);

        try {
            $candidature->update($database);

            header('Location: index.php');
            exit;
        } catch (PDOException $exception) {
            $erreurs[] = 'Impossible de modifier la candidature.';
        }
    }
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier une candidature - JobLink Bénin</title>
</head>
<body>

<h1>Modifier la candidature</h1>

<p>
    <a href="index.php">Retour à la liste des candidatures</a>
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

<p>
    <strong>ID de la candidature :</strong>
    <?= (int) $candidature->getId() ?>
</p>

<p>
    <strong>ID candidat :</strong>
    <?= (int) $candidature->getIdCandidat() ?>
</p>

<p>
    <strong>ID offre :</strong>
    <?= (int) $candidature->getIdOffre() ?>
</p>

<form method="post">

    <div>
        <label for="lettre_motivation">Lettre de motivation</label><br>
        <textarea
            id="lettre_motivation"
            name="lettre_motivation"
            rows="8"
            cols="60"
            required
        ><?= htmlspecialchars($lettreMotivationValeur) ?></textarea>
    </div>

    <br>

    <div>
        <label for="statut">Statut</label><br>

        <select id="statut" name="statut" required>
            <option
                value="en_attente"
                <?= $statutValeur === 'en_attente' ? 'selected' : '' ?>
            >
                En attente
            </option>

            <option
                value="acceptee"
                <?= $statutValeur === 'acceptee' ? 'selected' : '' ?>
            >
                Acceptée
            </option>

            <option
                value="refusee"
                <?= $statutValeur === 'refusee' ? 'selected' : '' ?>
            >
                Refusée
            </option>
        </select>
    </div>

    <br>

    <button type="submit">Enregistrer les modifications</button>
    <a href="index.php">Annuler</a>

</form>

</body>
</html>
