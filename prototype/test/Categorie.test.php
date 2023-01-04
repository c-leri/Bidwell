<?php
// Test la classe Categorie
require_once(__DIR__ . "/../model/Categorie.class.php");
// Fonctions d'aide
require_once(__DIR__ . "/Helper.php");

try {
    // Constructeur
    print('Test du constructeur : ');
    $nomCategorie = 'testConstructeur';
    $categorie = new Categorie($nomCategorie);
    if ($categorie->getLibelle() != $nomCategorie) {
        throw new Exception("Test du constructeur :\n"
            ." - Nom de la catégorie : {$categorie->getLibelle()}"
            ." - Attendu : $nomCategorie");
    }
    OK();

    ////////////////// SETTERS //////////////////
    print('Test des setters : ');

    // setLibelle()
    $nomCategorie = 'testSetLibelle';
    $categorie->setLibelle($nomCategorie);
    if ($categorie->getLibelle() != $nomCategorie) {
        throw new Exception("Test de setLibelle() :\n"
            ." - Nom de la catégorie : {$categorie->getLibelle()}"
            ." - Attendu : $nomCategorie");
    }

    OK();

    ///////////// GESTION DES FILS //////////////
    print('Test gestion des fils : ');

    // add()
    $categorieFille = new Categorie('filleTest');
    $categorie->add($categorieFille);
    if ($categorieFille->getParent() != $categorie) {
        printf("\nCategorie mere :");
        var_dump($categorie);
        printf("Categorie fille :");
        var_dump($categorieFille);
        throw new Exception('Test add() : $categorieFille->getParent() != $categorie');
    }

    // remove()
    $categorie->remove($categorieFille);
    if ($categorie->getParent() !== null) {
        printf("\nCategorie mere :");
        var_dump($categorie);
        printf("Categorie fille :");
        var_dump($categorieFille);
        throw new Exception('Test remove() : $categorieFille->getParent() != null');
    }

    OK();


} catch (Exception $e) {
    KO("Erreur sur Categorie : ".$e->getMessage());
}

