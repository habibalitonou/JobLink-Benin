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

$id = (int) ($_GET['id'] ?? 0);

if ($id <= 0) {
    header('Location: index.php');
    exit;
}

$entreprise = Entreprise::findById($database, $id);

if ($entreprise === null) {
    header('Location: index.php');
    exit;
}

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
        $erreurs[] = 'Le secteur sélectionné est invalide.';
    }

    $villesIds = array_map(
        static fn (Ville $ville): int => $ville->getId(),
        $villes
    );

    if (!in_array($idVille, $villesIds, true)) {
        $erreurs[] = 'La ville sélectionnée est invalide.';
    }

    if ($erreurs === []) {
        $entreprise->setNom($nom);
        $entreprise->setEmail($email);
        $entreprise->setTelephone($telephone !== '' ? $telephone : null);
        $entreprise->setIdSecteur($idSecteur);
        $entreprise->setIdVille($idVille);

        $entreprise->update($database);

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
    <title>Modifier une entreprise - JobLink Bénin</title>
</head>
<body>

    <h1>Modifier une entreprise</h1>

    <?php if ($erreurs !== []): ?>
        <div>
            <h2>Erreurs</h2>
            <ul>
                <?php foreach ($erreurs as $erreur): ?>
                    <li><?= htmlspecialchars($erreur) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="post">

        <div>
            <label for="nom">Nom de l'entreprise</label>
            <input
                type="text"
                id="nom"
                name="nom"
                value="<?= htmlspecialchars($entreprise->getNom()) ?>"
                required
            >
        </div>

        <div>
            <label for="email">E-mail</label>
            <input
                type="email"
                id="email"
                name="email"
                value="<?= htmlspecialchars($entreprise->getEmail()) ?>"
                required
            >
        </div>

        <div>
            <label for="telephone">Téléphone</label>
            <input
                type="text"
                id="telephone"
                name="telephone"
                value="<?= htmlspecialchars($entreprise->getTelephone() ?? '') ?>"
            >
        </div>

        <div>
            <label for="id_secteur">Secteur</label>
            <select id="id_secteur" name="id_secteur" required>
                <option value="">Sélectionner un secteur</option>

                <?php foreach ($secteurs as $secteur): ?>
                    <option
                        value="<?= $secteur->getId() ?>"
                        <?= $secteur->getId() === $entreprise->getIdSecteur() ? 'selected' : '' ?>
                    >
                        <?= htmlspecialchars($secteur->getLibelle()) ?>
                    </option>
                <?php endforeach; ?>

            </select>
        </div>

        <div>
            <label for="id_ville">Ville</label>
            <select id="id_ville" name="id_ville" required>
                <option value="">Sélectionner une ville</option>

                <?php foreach ($villes as $ville): ?>
                    <option
                        value="<?= $ville->getId() ?>"
                        <?= $ville->getId() === $entreprise->getIdVille() ? 'selected' : '' ?>
                    >
                        <?= htmlspecialchars($ville->getLibelle()) ?>
                    </option>
                <?php endforeach; ?>

            </select>
        </div>

        <button type="submit">Enregistrer les modifications</button>

    </form>

    <p>
        <a href="index.php">Retour à la liste des entreprises</a>
    </p>

</body>
</html>
