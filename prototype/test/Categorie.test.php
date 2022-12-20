<?php
/**
 * Test de la classe Categorie
 */

// Inclusion de la classe Categorie
require_once(__DIR__ . "/../model/Categorie.class.php");
// Inclusion du helper
require_once(__DIR__ . "/helper.php");

// Construction d'un objet Categorie ; Récupération du libellé
try {
    $nomCategorie = "it's a me";
    $categorie = new Categorie("it's a me");
    verify($categorie->getLibelle() == $nomCategorie, "Le nom de la catégorie est erroné : " . getLibelle());
} catch (Exception $e) {
    printCol("La création d'une instance de Categorie a échoué.");
}

/**
 * Test du setter setLibelle()
 */

try {
    $nomCategorie = "mama mia";
    $categorie->setLibelle($nomCategorie);
    verify($categorie->getLibelle(), $nomCategorie, "Le libellé a mal été mis à jour: " . $categorie->getLibelle());
    OK();
} catch (Exception $e) {
    printCol("Erreur dans la mise à jour du libellé");
    $e->getMessage();
}

/**
 * Test de la méthode add()
 */
// Création d'une nouvelle catégorie
$categorieFille = new Categorie("mario");
// Ajout de la catégorie fille à la précédente catégorie
try {
//    $categorie->
?>

