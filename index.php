<?php
require_once 'config.php';
require_once 'functions.php';

$pageTitre = 'Anagramme - Vérificateur';

$string1  = '';
$string2  = '';
$resultat = null;   // true : anagrammes, false : pas des anagrammes, null : pas encore soumis
$erreurs  = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $string1 = trim($_POST['string1'] ?? '');
    $string2 = trim($_POST['string2'] ?? '');

    //  Champs vides
    if ($string1 === '' || $string2 === '') {
        $erreurs[] = 'Les deux champs sont obligatoires.';
    }
    //  Caractères non autorisés
    elseif (!chaine_valide($string1) || !chaine_valide($string2)) {
        $erreurs[] = "Caractères non autorisés : utilisez uniquement des lettres, des espaces, des apostrophes ou des tirets.";
    }
    //  Vérification de l'anagramme
    else {
        $resultat = sont_anagrammes($string1, $string2);

        // Enregistrement dans l'historique si l'utilisateur est connecté
        if (est_connecte()) {
            $stmt = $pdo->prepare(
                'INSERT INTO verifications (user_id, string1, string2, is_anagram) VALUES (?, ?, ?, ?)'
            );
            $stmt->execute([$_SESSION['user_id'], $string1, $string2, $resultat ? 1 : 0]);
        }
    }
}

// Récupération de l'historique personnel
$historique = [];
if (est_connecte()) {
    $stmt = $pdo->prepare(
        'SELECT string1, string2, is_anagram, created_at
         FROM verifications
         WHERE user_id = ?
         ORDER BY id DESC'
    );
    $stmt->execute([$_SESSION['user_id']]);
    $historique = $stmt->fetchAll();
}

// Classe CSS appliquée aux champs selon le résultat
$classeChamp = '';
if ($resultat === true) {
    $classeChamp = 'champ-ok';
} elseif ($resultat === false) {
    $classeChamp = 'champ-ko';
}

require 'header.php';
?>

<div class="contenu">
    <div class="colonne-formulaire">
        <h2>Vérifier des anagrammes</h2>

        <form method="post" action="index.php" class="formulaire">
            <div class="champs">
                <div class="champ">
                    <label for="string1">String 1</label>
                    <input type="text" id="string1" name="string1" maxlength="255"
                           value="<?= e($string1) ?>" class="<?= $classeChamp ?>">
                </div>
                <div class="champ">
                    <label for="string2">String 2</label>
                    <input type="text" id="string2" name="string2" maxlength="255"
                           value="<?= e($string2) ?>" class="<?= $classeChamp ?>">
                </div>
            </div>

            <?php if ($erreurs): ?>
                <ul class="erreurs">
                    <?php foreach ($erreurs as $erreur): ?>
                        <li><?= e($erreur) ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

            <?php if ($resultat === true): ?>
                <p class="message positif">Les deux chaînes sont des anagrammes.</p>
            <?php elseif ($resultat === false): ?>
                <p class="message negatif">Les deux chaînes ne sont pas des anagrammes.</p>
            <?php endif; ?>

            <button type="submit" class="bouton">Soumettre</button>
        </form>
    </div>

    <div class="colonne-historique">
        <h2>Historique</h2>

        <?php if (!est_connecte()): ?>
            <p class="info">
                <a href="login.php">Connectez-vous</a> pour enregistrer vos vérifications
                et consulter votre historique personnel.
            </p>
        <?php elseif (!$historique): ?>
            <p class="info">Aucune vérification enregistrée pour le moment.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>N°</th>
                        <th>String 1</th>
                        <th>String 2</th>
                        <th>Résultat</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                <?php $numero = 0; ?>
                <?php foreach ($historique as $ligne): ?>
                    <tr class="<?= $ligne['is_anagram'] ? 'positif' : 'negatif' ?>">
                        <td><?= $numero ?></td>
                        <td><?= e($ligne['string1']) ?></td>
                        <td><?= e($ligne['string2']) ?></td>
                        <td><?= $ligne['is_anagram'] ? 'Oui' : 'Non' ?></td>
                        <td><?= e($ligne['created_at']) ?></td>
                    </tr>
                    <?php $numero++; ?>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<?php require 'footer.php'; ?>
