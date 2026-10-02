<?php

session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: ../login.php');
    exit;
}

require_once __DIR__ . '/../../classes/Database.php';
require_once __DIR__ . '/../../classes/Offre.php';
require_once __DIR__ . '/../../classes/Entreprise.php';

$database = new Database();
$offres = Offre::findAll($database);
$entreprises = Entreprise::findAll($database);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int) ($_POST['id'] ?? 0);

    if ($id > 0) {
        $offre = Offre::findById($database, $id);

        if ($offre !== null) {
            $offre->delete($database);
        }
    }

    header('Location: index.php');
    exit;
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Offres - JobLink Bénin</title>
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
        <h2>Gestion des offres</h2>

        <p>
            <a href="create.php">Ajouter une offre</a>
        </p>

        <?php if (count($offres) === 0): ?>
            <p>Aucune offre enregistrée.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Titre</th>
                        <th>Salaire</th>
                        <th>Date limite</th>
                        <th>Statut</th>
                        <th>Entreprise</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($offres as $offre): ?>
                        <tr>
                            <td><?= $offre->getId() ?></td>
                            <td><?= htmlspecialchars($offre->getTitre(), ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars(number_format($offre->getSalaire() ?? 0, 0, ',', ' ') . ' FCFA', ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($offre->getDateLimite() ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= $offre->getStatut() ?></td>
                            <td>
                                <?php
                                $entrepriseNom = 'Entreprise inconnue';

                                foreach ($entreprises as $entreprise) {
                                    if ($entreprise->getId() === $offre->getIdEntreprise()) {
                                        $entrepriseNom = $entreprise->getNom();
                                        break;
                                    }
                                }
                                ?>
                                <?= htmlspecialchars($entrepriseNom, ENT_QUOTES, 'UTF-8') ?>
                            </td>
                            <td>
                                <a href="edit.php?id=<?= $offre->getId() ?>">
                                    Modifier
                                </a>

                                <form
                                    method="post"
                                    style="display: inline;"
                                    onsubmit="return confirm('Voulez-vous vraiment supprimer cette offre ?');"
                                >
                                    <input
                                        type="hidden"
                                        name="id"
                                        value="<?= $offre->getId() ?>"
                                    >
                                    <button type="submit">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </main>

</body>
</html>
