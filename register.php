<?php
require_once 'config.php';

$pageTitre = 'Anagramme - Inscription';
$pageAuth  = true;

// Un utilisateur déjà connecté n'a pas besoin de s'inscrire
if (est_connecte()) {
    header('Location: index.php');
    exit;
}

$pseudo  = '';
$erreurs = [];
$succes  = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pseudo            = trim($_POST['pseudo'] ?? '');
    $motDePasse        = $_POST['mot_de_passe'] ?? '';
    $confirmation      = $_POST['confirmation'] ?? '';

    // Validation des informations
    if ($pseudo === '' || $motDePasse === '' || $confirmation === '') {
        $erreurs[] = 'Tous les champs sont obligatoires.';
    }
    if (!preg_match('/^[A-Za-z0-9_]{3,30}$/', $pseudo)) {
        $erreurs[] = 'Le pseudo doit contenir entre 3 et 30 caractères (lettres, chiffres ou underscore).';
    }
    if (mb_strlen($motDePasse) < 4) {
        $erreurs[] = 'Le mot de passe doit contenir au moins 4 caractères.';
    }
    if ($motDePasse !== $confirmation) {
        $erreurs[] = 'Les deux mots de passe ne correspondent pas.';
    }

    // Disponibilité du pseudo
    if (!$erreurs) {
        $stmt = $pdo->prepare('SELECT id FROM users WHERE pseudo = ?');
        $stmt->execute([$pseudo]);
        if ($stmt->fetch()) {
            $erreurs[] = 'Ce pseudo est déjà utilisé.';
        }
    }

    // Enregistrement de l'utilisateur
    if (!$erreurs) {
        $stmt = $pdo->prepare('INSERT INTO users (pseudo, password) VALUES (?, ?)');
        $stmt->execute([$pseudo, password_hash($motDePasse, PASSWORD_DEFAULT)]);
        $succes = true;
    }
}

require 'header.php';
?>

<div class="carte-auth">
    <h2>Inscription</h2>

    <?php if ($succes): ?>
        <p class="message positif">Votre compte a bien été créé. Vous pouvez maintenant vous connecter.</p>
    <?php endif; ?>

    <?php if ($erreurs): ?>
        <ul class="erreurs">
            <?php foreach ($erreurs as $erreur): ?>
                <li><?= e($erreur) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form method="post" action="register.php" class="formulaire">
        <div class="champ">
            <label for="pseudo">Pseudo</label>
            <input type="text" id="pseudo" name="pseudo" maxlength="30" value="<?= e($pseudo) ?>">
        </div>

        <div class="champ">
            <label for="mot_de_passe">Mot de passe</label>
            <input type="password" id="mot_de_passe" name="mot_de_passe">
        </div>

        <div class="champ">
            <label for="confirmation">Confirmer mot de passe</label>
            <input type="password" id="confirmation" name="confirmation">
        </div>

        <button type="submit" class="bouton">S'inscrire</button>
    </form>

    <p class="lien-auth">
        Avez-vous un compte ? <a href="login.php">Se connecter</a>
    </p>
</div>

<?php require 'footer.php'; ?>
