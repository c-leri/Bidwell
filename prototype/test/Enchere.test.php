<?php
// Test la classe Enchere
require_once(__DIR__ . "/../model/Enchere.class.php");
// Fonctions d'aide
require_once(__DIR__ . "/Helper.php");

try {
    // Constructeur
    print('Test du constructeur : ');
    $date = DateTime::createFromFormat('d-m-Y', '04-01-2023');
    $categorie = new Categorie('test');
    $enchere = new Enchere('testConstructeur', $date, 500, 0, 'testConstructeur.png', 'testConstructeur.txt', $categorie);
    


} catch (Exception $e) {
    KO("Erreur sur Enchere : ".$e->getMessage());
}