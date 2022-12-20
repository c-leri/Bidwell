<?php
/**
 * Test de la classe Categorie
 */

// Inclusion de la classe Categorie
require_once(__DIR__ . "/../model/Categorie.class.php");
// Inclusion du helper
require_once(__DIR__ . "/helper.php");

// Construction d'un objet Categorie
try {
    $categorie = new Categorie();
    OK();
} catch (Exception $e) {
    printCol("La création d'une instance de Categorie a échoué.");
}

// Test de la méthode add()
// Création d'une nouvelle catégorie
$categorieFille = new Categorie();
// Ajout de la catégorie fille à la précédente catégorie
?>

