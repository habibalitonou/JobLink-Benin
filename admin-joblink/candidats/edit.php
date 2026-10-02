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

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($id === false || $id === null) {
    header('Location: index.php');
    exit;
}

$candidat = Candidat::findById($database, $id);

if ($candidat === null) {
    header('Location: index.php');
    exit;
}

$villes = Ville::findAll($database);

$erreurs = [];

$nomValeur = $candidat->getNom();
$prenomValeur = $candidat->getPrenom();
$emailValeur = $candidat->getEmail();
$telephoneValeur = $candidat->getTelephone() ?? '';
$idVilleValeur = $candidat->getIdVille();
$statutValeur = $candidat->getStatut();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nomValeur = trim($_POST['nom'] ?? '');
    $prenomValeur = trim($_POST['prenom'] ?? '');
    $emailValeur = trim($_POST['email'] ?? '');
    $telephoneValeur = trim($_POST['telephone'] ?? '');
    $idVilleValeur = ($_POST['id_ville'] ?? '') !== ''
        ? (int) $_POST['id_ville']
        : null;
    $statutValeur = trim($_POST['statut'] ?? '');

    if ($nomValeur === '') {
        $erreurs[] = 'Le nom est obligatoire.';
    }

    if ($prenomValeur === '') {
        $erreurs[] = 'Le prénom est obligatoire.';
    }

    if ($emailValeur === '' || !filter_var($emailValeur, FILTER_VALIDATE_EMAIL)) {
        $erreurs[] = 'L’adresse email est invalide.';
    }

    if ($statutValeur === '') {
        $erreurs[] = 'Le statut est obligatoire.';
    }

    if (empty($erreurs)) {
        $candidat->setNom($nomValeur);
        $candidat->setPrenom($prenomValeur);
        $candidat->setEmail($emailValeur);
        $candidat->setTelephone($telephoneValeur !== '' ? $telephoneValeur : null);
        $candidat->setIdVille($idVilleValeur);
        $candidat->setStatut($statutValeur);

        try {
            $candidat->update($database);

            header('Location: index.php');
            exit;
        } catch (PDOException $exception) {
            $erreurs[] = 'Impossible de modifier le candidat. Vérifiez notamment que l’adresse email n’est pas déjà utilisée.';
        }
    }
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier un candidat - JobLink Bénin</title>
</head>
<body>

<h1>Modifier le candidat</h1>

<p>
    <a href="index.php">Retour à la liste des candidats</a>
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

<form method="post">

    <div>
        <label for="nom">Nom</label><br>
        <input
            type="text"
            id="nom"
            name="nom"
            value="<?= htmlspecialchars($nomValeur) ?>"
            required
        >
    </div>

    <br>

    <div>
        <label for="prenom">Prénom</label><br>
        <input
            type="text"
            id="prenom"
            name="prenom"
            value="<?= htmlspecialchars($prenomValeur) ?>"
            required
        >
    </div>

    <br>

    <div>
        <label for="email">Email</label><br>
        <input
            type="email"
            id="email"
            name="email"
            value="<?= htmlspecialchars($emailValeur) ?>"
            required
        >
    </div>

    <br>

    <div>
        <label for="telephone">Téléphone</label><br>
        <input
            type="text"
            id="telephone"
            name="telephone"
            value="<?= htmlspecialchars($telephoneValeur) ?>"
        >
    </div>

    <br>

    <div>
        <label for="id_ville">Ville</label><br>
        <select id="id_ville" name="id_ville">
            <option value="">-- Aucune ville --</option>

            <?php foreach ($villes as $ville): ?>
                <option
                    value="<?= (int) $ville->getId() ?>"
                    <?= $idVilleValeur === $ville->getId() ? 'selected' : '' ?>
                >
                    <?= htmlspecialchars($ville->getLibelle()) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <br>

    <div>
        <label for="statut">Statut</label><br>
        <select id="statut" name="statut" required>
            <option value="actif" <?= $statutValeur === 'actif' ? 'selected' : '' ?>>Actif</option>
            <option value="suspendu" <?= $statutValeur === 'suspendu' ? 'selected' : '' ?>>Suspendu</option>
        </select>
    </div>

    <br>

    <button type="submit">Enregistrer les modifications</button>
    <a href="index.php">Annuler</a>

</form>

</body>
</html>
