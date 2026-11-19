<?php
// Démarrage de la session (utilisée pour l'authentification)
session_start();

// ------------------------------------------------------------------
// Paramètres de connexion à la base de données (WAMP / MariaDB)
// ------------------------------------------------------------------
define('DB_HOST', '127.0.0.1');
define('DB_PORT', '3307');   // Port  sous WAMP (3306 dans une installation classique)
define('DB_NAME', 'anagramme');
define('DB_USER', 'root');
define('DB_PASS', '');

try {
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME . ';charset=utf8mb4',
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (PDOException $e) {
    exit('Erreur de connexion à la base de données : ' . $e->getMessage());
}

// Échappe une chaîne avant de l'afficher dans la page HTML
function e(string $texte): string
{
    return htmlspecialchars($texte, ENT_QUOTES, 'UTF-8');
}

// Indique si un utilisateur est connecté
function est_connecte(): bool
{
    return isset($_SESSION['user_id']);
}
