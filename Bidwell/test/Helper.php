<?php

// Sort sur le flux des erreurs pour ne pas déclencher la production du header
function print_err(string $str): void
{
    fwrite(STDERR, $str);
}

// Code pour mettre des couleurs
// Affiche un texte avec des couleurs ANSI dans le shell
function printCol(string $text, string $col = 'red'): void
{
    switch ($col) {
        case 'red':
            print_err("\e[1;4;31m");
            break;
        case 'green':
            print_err("\e[1;32m");
            break;
        default:
            break;
    }
    print_err($text . "\e[0m");
}

// Affiche "OK" en vert
function OK(): void
{
    printCol("OK\n", 'green');
}

// Affiche l'erreur en rouge
function KO(string $message): void
{
    printCol("\n" . $message . "\n", 'red');
}