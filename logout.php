<?php
require_once 'config.php';

// Fin de la session : on vide les données puis on détruit la session
$_SESSION = [];
session_destroy();

header('Location: index.php');
exit;
