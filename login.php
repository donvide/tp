<?php
require_once 'config.php';

$pageTitre = 'Anagramme - Connexion';
$pageAuth  = true;

// Un utilisateur déjà connecté est renvoyé vers l'accueil
if (est_connecte()) {
    header('Location: index.php');
    exit;
}

$pseudo  = '';
$erreurs = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pseudo     = trim($_POST['pseudo'] ?? '');
    $motDePasse = $_POST['mot_de_passe'] ?? '';

    if ($pseudo === '' || $motDePasse === '') {
        $erreurs[] = 'Tous les champs sont obligatoires.';
    } else {
        $stmt = $pdo->prepare('SELECT id, pseudo, password FROM users WHERE pseudo = ?');
        $stmt->execute([$pseudo]);
        $utilisateur = $stmt->fetch();

        if ($utilisateur && password_verify($motDePasse, $utilisateur['password'])) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $utilisateur['id'];
            $_SESSION['pseudo']  = $utilisateur['pseudo'];

            header('Location: index.php');
            exit;
        }

        $erreurs[] = 'Pseudo ou mot de passe incorrect.';
    }
}

require 'header.php';
?>

<div class="carte-auth">
    <h2>Connexion</h2>

    <?php if (isset($_GET['inscription'])): ?>
        <p class="message positif">Compte créé avec succès. Connectez-vous.</p>
    <?php endif; ?>

    <?php if ($erreurs): ?>
        <ul class="erreurs">
            <?php foreach ($erreurs as $erreur): ?>
                <li><?= e($erreur) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form method="post" action="login.php" class="formulaire">
        <div class="champ">
            <label for="pseudo">Pseudo</label>
            <input type="text" id="pseudo" name="pseudo" maxlength="30" value="<?= e($pseudo) ?>">
        </div>

        <div class="champ">
            <label for="mot_de_passe">Mot de passe</label>
            <input type="password" id="mot_de_passe" name="mot_de_passe">
        </div>

        <button type="submit" class="bouton">Se connecter</button>
    </form>

    <p class="lien-auth">
        Avez-vous un compte ? <a href="register.php">S'inscrire</a>
    </p>
</div>

<?php require 'footer.php'; ?>
