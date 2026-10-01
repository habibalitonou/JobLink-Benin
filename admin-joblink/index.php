<?php

session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

echo 'Connexion administrateur réussie.';
echo '<br>';
echo 'ID administrateur : ' . (int) $_SESSION['admin_id'];
echo '<br>';
echo 'Email : ' . htmlspecialchars($_SESSION['admin_email'], ENT_QUOTES, 'UTF-8');
