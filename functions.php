<?php

/**
 * Nettoie une chaîne : suppression des espaces autour, passage en minuscules
 * et suppression de tous les espaces internes.
 */
function normaliser(string $chaine): string
{
    $chaine = trim($chaine);
    $chaine = mb_strtolower($chaine, 'UTF-8');

    return preg_replace('/\s+/u', '', $chaine);
}

/**
 * Vérifie si deux chaînes sont des anagrammes.
 *
 * Principe : on compte les occurrences de chaque caractère de la première
 * chaîne avec array_count_values(), puis on vérifie que chaque caractère a
 * exactement le même nombre d'occurrences dans la deuxième chaîne.
 *
 * Complexité : O(n) en temps et O(k) en espace (k = caractères distincts).
 */
function sont_anagrammes(string $a, string $b): bool
{
    $a = normaliser($a);
    $b = normaliser($b);

    // Chaînes vides ou longueurs différentes : inutile de continuer
    if ($a === '' || $b === '' || mb_strlen($a) !== mb_strlen($b)) {
        return false;
    }

    // On compte les lettres : clé = caractère, valeur = nombre d'occurrences
    $compteurA = array_count_values(mb_str_split($a));
    $compteurB = array_count_values(mb_str_split($b));

    // Chaque lettre doit avoir le même nombre d'occurrences dans les deux chaînes
    foreach ($compteurA as $lettre => $nombre) {
        if (($compteurB[$lettre] ?? 0) !== $nombre) {
            return false;
        }
    }

    return true;
}

/**
 * Vérifie que la chaîne ne contient que des lettres, des espaces,
 * des apostrophes ou des tirets.
 */
function chaine_valide(string $chaine): bool
{
    return preg_match("/^[\p{L}\s'’-]+$/u", $chaine) === 1;
}
