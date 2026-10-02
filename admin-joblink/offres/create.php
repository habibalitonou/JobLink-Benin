<?php

session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: ../login.php');
    exit;
}

require_once __DIR__ . '/../../classes/Database.php';
require_once __DIR__ . '/../../classes/Entreprise.php';
require_once __DIR__ . '/../../classes/Offre.php';
require_once __DIR__ . '/../../classes/TypeContrat.php';
require_once __DIR__ . '/../../classes/Secteur.php';
require_once __DIR__ . '/../../classes/Ville.php';

$database = new Database();
$secteurs = Secteur::findAll($database);
$entreprises = Entreprise::findAll($database);
$typesContrat = TypeContrat::findAll($database);
$villes = Ville::findAll($database);

$erreurs = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = trim($_POST['titre'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $idEntreprise = (int) ($_POST['id_entreprise'] ?? 0);
    $idSecteur = (int) ($_POST['id_secteur'] ?? 0);
    $idVille = (int) ($_POST['id_ville'] ?? 0);
    $idTypeContrat = (int) ($_POST['id_type_contrat'] ?? 0);
    $salaire = trim($_POST['salaire'] ?? '');
    $dateLimite = trim($_POST['date_limite'] ?? '');
    $statut = trim($_POST['statut'] ?? '');

    if ($titre === '') {
        $erreurs[] = "Le titre de l'offre est obligatoire.";
    }
    if ($description === '') {
        $erreurs[] = "La description de l'offre est obligatoire.";
    }
    if ($idEntreprise <= 0) {
        $erreurs[] = "L'entreprise est obligatoire.";
    }
    if ($idSecteur <= 0) {
        $erreurs[] = "Le secteur est obligatoire.";
    }
    if ($idVille <= 0) {
        $erreurs[] = "La ville est obligatoire.";
    }
    if ($idTypeContrat <= 0) {
        $erreurs[] = "Le type de contrat est obligatoire.";
    }

    $salaireValue = null;
    if ($salaire !== '') {
        if (!is_numeric($salaire) || (float) $salaire < 0) {
            $erreurs[] = "Le salaire doit etre un nombre positif.";
        } else {
            $salaireValue = (float) $salaire;
        }
    }

    $dateLimiteValue = $dateLimite !== '' ? $dateLimite : null;

    if (!in_array($statut, ['publiee', 'brouillon', 'archivee'], true)) {
        $erreurs[] = "Le statut selectionne est invalide.";
    }

    if ($erreurs === []) {
        $offre = new Offre(
            $titre,
            $description,
            $idEntreprise,
            $idSecteur,
            $idVille,
            $idTypeContrat,
            $salaireValue,
            $dateLimiteValue,
            $statut
        );

        if ($offre->create($database)) {
            header('Location: index.php');
            exit;
        }

        $erreurs[] = "Impossible d'enregistrer l'offre.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une offre - JobLink Bénin</title>
</head>
<body>
    <h1>Ajouter une offre</h1>

    <?php if ($erreurs !== []): ?>
        <div>
            <strong>Veuillez corriger les erreurs suivantes :</strong>
            <ul>
                <?php foreach ($erreurs as $erreur): ?>
                    <li><?= htmlspecialchars($erreur) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="post">
        <div>
            <label for="titre">Titre de l'offre</label>
            <input type="text" id="titre" name="titre" value="<?= htmlspecialchars($_POST['titre'] ?? '') ?>" required>
        </div>

        <div>
            <label for="description">Description</label>
            <textarea id="description" name="description" rows="6" required><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
        </div>

        <div>
            <label for="id_entreprise">Entreprise</label>
            <select id="id_entreprise" name="id_entreprise" required>
                <option value="">Sélectionner une entreprise</option>
                <?php foreach ($entreprises as $entreprise): ?>
                    <option value="<?= $entreprise->getId() ?>" <?= ((int) ($_POST['id_entreprise'] ?? 0) === $entreprise->getId()) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($entreprise->getNom()) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <label for="id_secteur">Secteur</label>
            <select id="id_secteur" name="id_secteur" required>
                <option value="">Sélectionner un secteur</option>
                <?php foreach ($secteurs as $secteur): ?>
                    <option value="<?= $secteur->getId() ?>" <?= ((int) ($_POST['id_secteur'] ?? 0) === $secteur->getId()) ? 'selected' : '' ?>>
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
                    <option value="<?= $ville->getId() ?>" <?= ((int) ($_POST['id_ville'] ?? 0) === $ville->getId()) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($ville->getLibelle()) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <label for="id_type_contrat">Type de contrat</label>
            <select id="id_type_contrat" name="id_type_contrat" required>
                <option value="">Sélectionner un type de contrat</option>
                <?php foreach ($typesContrat as $typeContrat): ?>
                    <option value="<?= $typeContrat->getId() ?>" <?= ((int) ($_POST['id_type_contrat'] ?? 0) === $typeContrat->getId()) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($typeContrat->getLibelle()) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <label for="salaire">Salaire</label>
            <input type="number" step="0.01" min="0" id="salaire" name="salaire" value="<?= htmlspecialchars($_POST['salaire'] ?? '') ?>">
        </div>

        <div>
            <label for="date_limite">Date limite</label>
            <input type="date" id="date_limite" name="date_limite" value="<?= htmlspecialchars($_POST['date_limite'] ?? '') ?>">
        </div>

        <div>
            <label for="statut">Statut</label>
            <select id="statut" name="statut" required>
                <?php $statutActuel = $_POST['statut'] ?? 'publiee'; ?>
                <option value="publiee" <?= $statutActuel === 'publiee' ? 'selected' : '' ?>>Publiée</option>
                <option value="brouillon" <?= $statutActuel === 'brouillon' ? 'selected' : '' ?>>Brouillon</option>
                <option value="archivee" <?= $statutActuel === 'archivee' ? 'selected' : '' ?>>Archivée</option>
            </select>
            </div>

        <button type="submit">Enregistrer l'offre</button>
    </form>

    <p><a href="index.php">Retour à la liste des offres</a></p>
</body>
</html>
