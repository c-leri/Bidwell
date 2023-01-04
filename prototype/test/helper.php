<?php

// Sort sur le flux des erreurs pour ne pa déclancher la production du header
function print_err(string $str) {
    fwrite(STDERR, $str);
}

function verify(bool $condition, $error_string) {
    $condition ? OK(); : throw new Exception($error_string);
}

// Code pour mettre des couleurs
// Affiche un texte avec des couleurs ANSI dans le shell
function printCol(string $text, string $col = 'red') {
    switch ($col) {
        case 'red':
            print_err("\e[1;4;31m");
            break;
        case 'green':
            print_err("\e[1;32m");
        default:
            break;
    }
    print_err($text . "\e[0m");
}

// Affiche "OK en vert"
function OK() {
    printCol("OK\n", 'green');
}
?>

