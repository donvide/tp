<?php
if (!isset($pageTitre)) {
    $pageTitre = 'Anagramme';
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($pageTitre) ?></title>
    <link rel="stylesheet" href="style.css?v=2">
</head>
<body>
<div class="carte">
    <div class="entete">
        <div class="logo">Anagramme</div>
        <div class="nav">
            <?php if (est_connecte()): ?>
                <span class="utilisateur"><span class="point"></span><?= e($_SESSION['pseudo']) ?></span>
                <a class="lien-deconnexion" href="logout.php" title="Se déconnecter">&#9099; Déconnexion</a>
            <?php elseif (empty($pageAuth)): ?>
                <a class="bouton" href="login.php">Se connecter</a>
            <?php endif; ?>
        </div>
    </div>
