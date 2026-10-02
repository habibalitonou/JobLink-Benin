<?php

session_start();

if (!isset($_SESSION['candidat_id'])) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/../classes/Database.php';
require_once __DIR__ . '/../classes/Offre.php';
require_once __DIR__ . '/../classes/Entreprise.php';
require_once __DIR__ . '/../classes/Secteur.php';
require_once __DIR__ . '/../classes/Ville.php';
require_once __DIR__ . '/../classes/TypeContrat.php';

$database = new Database();

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
    header('Location: offres.php');
    exit;
}

$offre = Offre::findById($database, $id);

if ($offre === null || $offre->getStatut() !== 'publiee') {
    header('Location: offres.php');
    exit;
}

$entreprises = Entreprise::findAll($database);
$secteurs = Secteur::findAll($database);
$villes = Ville::findAll($database);
$typesContrat = TypeContrat::findAll($database);

$entreprise = null;
foreach ($entreprises as $element) {
    if ($element->getId() === $offre->getIdEntreprise()) {
        $entreprise = $element;
        break;
    }
}

$secteur = null;
foreach ($secteurs as $element) {
    if ($element->getId() === $offre->getIdSecteur()) {
        $secteur = $element;
        break;
    }
}

$ville = null;
foreach ($villes as $element) {
    if ($element->getId() === $offre->getIdVille()) {
        $ville = $element;
        break;
    }
}

$typeContrat = null;
foreach ($typesContrat as $element) {
    if ($element->getId() === $offre->getIdTypeContrat()) {
        $typeContrat = $element;
        break;
    }
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($offre->getTitre()) ?> - JobLink Bénin</title>
</head>
<body>

<h1><?= htmlspecialchars($offre->getTitre()) ?></h1>

<p>
    <a href="offres.php">← Retour aux offres</a>
</p>

<h2>Informations sur l'offre</h2>

<p>
    <strong>Entreprise :</strong>
    <?= $entreprise !== null ? htmlspecialchars($entreprise->getNom()) : '—' ?>
</p>

<p>
    <strong>Secteur :</strong>
    <?= $secteur !== null ? htmlspecialchars($secteur->getLibelle()) : '—' ?>
</p>

<p>
    <strong>Ville :</strong>
    <?= $ville !== null ? htmlspecialchars($ville->getLibelle()) : '—' ?>
</p>

<p>
    <strong>Type de contrat :</strong>
    <?= $typeContrat !== null ? htmlspecialchars($typeContrat->getLibelle()) : '—' ?>
</p>

<p>
    <strong>Salaire :</strong>
    <?= $offre->getSalaire() !== null
        ? htmlspecialchars(number_format($offre->getSalaire(), 0, ',', ' ')) . ' FCFA'
        : 'Non précisé' ?>
</p>

<p>
    <strong>Date limite :</strong>
    <?= $offre->getDateLimite() !== null
        ? htmlspecialchars($offre->getDateLimite())
        : 'Non précisée' ?>
</p>

<h2>Description</h2>

<p>
    <?= nl2br(htmlspecialchars($offre->getDescription())) ?>
</p>

<p>
    <a href="postuler.php?id=<?= (int) $offre->getId() ?>">
        Postuler à cette offre
    </a>
</p>

</body>
</html>
