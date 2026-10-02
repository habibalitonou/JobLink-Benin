<?php

session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: ../login.php');
    exit;
}

require_once __DIR__ . '/../../classes/Database.php';
require_once __DIR__ . '/../../classes/Entreprise.php';
require_once __DIR__ . '/../../classes/Secteur.php';
require_once __DIR__ . '/../../classes/Ville.php';

$database = new Database();

$secteurs = Secteur::findAll($database);
$villes = Ville::findAll($database);

$erreurs = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telephone = trim($_POST['telephone'] ?? '');
    $idSecteur = (int) ($_POST['id_secteur'] ?? 0);
    $idVille = (int) ($_POST['id_ville'] ?? 0);

    if ($nom === '') {
        $erreurs[] = 'Le nom de l\'entreprise est obligatoire.';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erreurs[] = 'L\'adresse e-mail est invalide.';
    }

    $secteursIds = array_map(
        static fn (Secteur $secteur): int => $secteur->getId(),
        $secteurs
    );

    if (!in_array($idSecteur, $secteursIds, true)) {
        $erreurs[] = 'Le secteur sÈlectionnÈ est invalide.';
    }

    $villesIds = array_map(
        static fn (Ville $ville): int => $ville->getId(),
        $villes
    );

    if (!in_array($idVille, $villesIds, true)) {
        $erreurs[] = 'La ville sÈlectionnÈe est invalide.';
    }

    if ($erreurs === []) {
        $entreprise = new Entreprise(
            $nom,
            $email,
            $telephone !== '' ? $telephone : null,
            $idSecteur,
            $idVille
        );

        $entreprise->create($database);

        header('Location: index.php');
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une entreprise - JobLink B√©nin</title>
</head>
<body>

    <h1>Ajouter une entreprise</h1>

    <form method="post">

        <div>
            <label for="nom">Nom de l'entreprise</label>
            <input type="text" id="nom" name="nom" required>
        </div>

        <div>
            <label for="email">E-mail</label>
            <input type="email" id="email" name="email" required>
        </div>

        <div>
            <label for="telephone">T√©l√©phone</label>
            <input type="text" id="telephone" name="telephone">
        </div>

        <div>
            <label for="id_secteur">Secteur</label>
            <select id="id_secteur" name="id_secteur" required>
                <option value="">S√©lectionner un secteur</option>

                <?php foreach ($secteurs as $secteur): ?>
                    <option value="<?= $secteur->getId() ?>">
                        <?= htmlspecialchars($secteur->getLibelle()) ?>
                    </option>
                <?php endforeach; ?>

            </select>
        </div>

        <div>
            <label for="id_ville">Ville</label>
            <select id="id_ville" name="id_ville" required>
                <option value="">S√©lectionner une ville</option>

                <?php foreach ($villes as $ville): ?>
                    <option value="<?= $ville->getId() ?>">
                        <?= htmlspecialchars($ville->getLibelle()) ?>
                    </option>
                <?php endforeach; ?>

            </select>
        </div>

        <button type="submit">Enregistrer l'entreprise</button>

    </form>

    <p>
        <a href="index.php">Retour √† la liste des entreprises</a>
    </p>

</body>
</html>
