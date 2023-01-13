<?php
namespace Bidwell\Bidwell\Test;

class Helper
{
    // Sort sur le flux des erreurs pour ne pas déclencher la production du header
    public static function print_err(string $str): void
    {
        fwrite(STDERR, $str);
    }

    // Code pour mettre des couleurs
    // Affiche un texte avec des couleurs ANSI dans le shell
    public static function printCol(string $text, string $col = 'red'): void
    {
        switch ($col) {
            case 'red':
                Helper::print_err("\e[1;4;31m");
                break;
            case 'green':
                Helper::print_err("\e[1;32m");
                break;
            default:
                break;
        }
        Helper::print_err($text . "\e[0m");
    }

    // Affiche "OK" en vert
    public static function OK(): void
    {
        Helper::printCol("OK\n", 'green');
    }

    // Affiche l'erreur en rouge
    public static function KO(string $message): void
    {
        Helper::printCol("\n" . $message . "\n", 'red');
    }
}