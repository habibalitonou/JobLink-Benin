<?php

session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: ../login.php');
    exit;
}

require_once __DIR__ . '/../../classes/Database.php';
require_once __DIR__ . '/../../classes/Entreprise.php';

$database = new Database();
$entreprises = Entreprise::findAll($database);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entreprises - JobLink Bénin</title>
</head>
<body>

    <header>
        <h1>JobLink Bénin</h1>

        <p>
            <a href="../index.php">Retour au tableau de bord</a>
            |
            <a href="../logout.php">Se déconnecter</a>
        </p>
    </header>

    <main>
        <h2>Gestion des entreprises</h2>

        <?php if (count($entreprises) === 0): ?>
            <p>Aucune entreprise enregistrée.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>E-mail</th>
                        <th>Téléphone</th>
                        <th>Secteur</th>
                        <th>Ville</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($entreprises as $entreprise): ?>
                        <tr>
                            <td><?= $entreprise->getId() ?></td>
                            <td><?= htmlspecialchars($entreprise->getNom(), ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($entreprise->getEmail(), ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($entreprise->getTelephone() ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= $entreprise->getIdSecteur() ?></td>
                            <td><?= $entreprise->getIdVille() ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </main>

</body>
</html>
