<?php
function anagram(string $mot1, string $mot2): bool
{
    $mot1 = normaliser($mot1);
    $mot2 = normaliser($mot2);

    if ($mot1 === '' || $mot2 === '' || mb_strlen($mot1) !== mb_strlen($mot2)) {
        return false;
    }

    $compt1 = array_count_values(mb_str_split($mot1));
    $compt2 = array_count_values(mb_str_split($mot2));

    foreach ($compt1 as $lettre => $nombre) {
        if (($compt2[$lettre] ?? 0) !== $nombre) {
            return false;
        }
    }

    return true;
}

?>