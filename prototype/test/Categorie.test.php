<?php
// Test la classe Categorie
require_once(__DIR__ . "/../model/Categorie.class.php");
require_once(__DIR__."/../model/Enchere.class.php");
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

    ///////////// GESTION DES FILS //////////////
    print('Test gestion des fils : ');

    // add()
    $categorieFille = new Categorie('filleTest');
    $categorie->add($categorieFille);
    if ($categorieFille->getLibelleCategorieMere() != $categorie->getLibelle()) {
        print("\nCategorie mere :");
        var_dump($categorie);
        print("Categorie fille :");
        var_dump($categorieFille);
        throw new Exception('Test add() : $categorieFille->getParent() != $categorie');
    }

    // remove()
    $categorie->remove($categorieFille);
    if ($categorie->getLibelleCategorieMere() !== null) {
        print("\nCategorie mere :\n");
        var_dump($categorie);
        print("Categorie fille :\n");
        var_dump($categorieFille);
        throw new Exception('Test remove() : $categorieFille->getParent() != null');
    }

    OK();

    /////////////// CREATE + READ ////////////////
    print('Test create() et read() : ');

    // create() catégorie mère
    $categorieMere = new Categorie('testCreateMere');
    $categorieMere->create();

    // read() catégorie mère
    $categorieMereRead = Categorie::read($categorieMere->getLibelle(), true);
    if ($categorieMere != $categorieMereRead) {
        print("\nCategorie créée :\n");
        var_dump($categorieMere);
        print("Categorie lue :\n");
        var_dump($categorieMereRead);
        throw new Exception('Test create() et read() : categorie créée != catégorie lue');
    }

    // create() catégorie fille
    $categorieFille = new Categorie('testCreateFille', $categorieMere);
    $categorieFille->create();

    // read() catégorie fille
    $categorieFilleRead = Categorie::read($categorieFille->getLibelle(), true);
    if ($categorieFille != $categorieFilleRead) {
        print("\nCategorie créée :\n");
        var_dump($categorieFille);
        print("\nCategorie lue :\n");
        var_dump($categorieFilleRead);
        throw new Exception('Test create() et read() : categorie créée != catégorie lue');
    }

    OK();

    /////////////////// READ ////////////////////
    print('Test read() : ');

    // read() catégorie inexistante
    try {
        Categorie::read("Cette Categorie n'existe pas");
        KO("Erreur sur Categorie : Test read() : lecture d'une catégorie inexistante devrait renvoyer une exception");
    } catch (Exception $e) {
        OK();
    }

    ////////////////// UPDATE ///////////////////
    print('Test update() : ');

    // update() avec des enchères
    $dateDebut = DateTime::createFromFormat('Y-m-d', '2050-12-24');
    $enchere1 = new Enchere('enchere1', $dateDebut, 500, 0, 'enchere1.png', 'enchere1.txt', $categorieFille->getLibelle());
    $enchere1->create();
    $enchere2 = new Enchere('enchere2', $dateDebut, 500, 0, 'enchere2.png', 'enchere2.txt', $categorieFille->getLibelle());
    $enchere2->create();
    $categorieFille->update();
    $categorieFilleRead = Categorie::read($categorieFille->getLibelle());
    if ($categorieFille != $categorieFilleRead) {
        print("\nCategorie mise à jour :\n");
        var_dump($categorieFille);
        print("\nCategorie lue :\n");
        var_dump($categorieFilleRead);
        throw new Exception('Test update() : categorie mise à jour != catégorie lue');
    }

    // update() catégorie mère
    $categorieFille->add($categorieMere);       // $categorieFille devient la mère de $categorieMere
    $categorieMere->remove($categorieFille);    // on retire le fait que $categorieFille est la fille de $categorieMere pour éviter une boucle
    $categorieFille->update();                  // devrait aussi update $categorieMere

    $categorieFilleRead = Categorie::read($categorieFille->getLibelle(), true);
    if ($categorieFille != $categorieFilleRead) {
        print("\nCategorie mise à jour :\n");
        var_dump($categorieFille);
        print("\nCategorie lue :\n");
        var_dump($categorieFilleRead);
        throw new Exception('Test update() : categorie mise à jour != catégorie lue');
    }

    $categorieMereRead = Categorie::read($categorieMere->getLibelle(), true);
    if ($categorieMere != $categorieMereRead) {
        print("\nCategorie mise à jour :\n");
        var_dump($categorieMere);
        print("\nCategorie lue :\n");
        var_dump($categorieMereRead);
        throw new Exception('Test update() : categorie mise à jour != catégorie lue');
    }

    OK();

    ////////////////// DELETE ///////////////////
    print('Test delete() : ');

    // test delete() sur une catégorie mère
    $libelleCategorieFille = $categorieFille->getLibelle();
    $categorieFille->delete();
    try {
        Categorie::read($libelleCategorieFille);
        KO("Erreur sur Categorie : Test delete() : read une catégorie delete devrait renvoyer une exception");
    } catch (Exception $e) {}
    if ($categorieFille->isInDB()) {
        var_dump($categorieFille);
        throw new Exception("Test delete() : la catégorie ne devrait plus être dans la bd après un delete");
    }
    // on vérifie que $categorieFille n'est plus la catégorie mère de $catégorieMere
    // il faut read les catégories filles pour que la modification soit effective
    $categorieMere->sync();
    if ($categorieMere->getLibelleCategorieMere() !== null) {
        var_dump($categorieMere);
        throw new Exception("Test delete() : la catégorie mère ne devrait pas être encore set si il a été delete");
    }

    // test delete() sur une catégorie fille
    $libelleCategorieMere = $categorieMere->getLibelle();
    $categorieMere->delete();
    try {
        Categorie::read($libelleCategorieMere);
        OK("Erreur sur Categorie : Test delete() : read une catégorie delete devrait renvoyer une exception");
    } catch (Exception $e) {}
    if ($categorieMere->isInDB()) {
        var_dump($categorieMere);
        throw new Exception("Test delete() : la catégorie ne devrait plus être dans la bd après un delete");
    }

    // test delete() sur une catégorie non dans la bd
    try {
        $categorie->delete();
        OK("Erreur sur Categorie : Test delete() : delete une catégorie non dans la base devrait renvoyer une exception");
    } catch (Exception $e) {}

    OK();

} catch (Exception $e) {
    KO("Erreur sur Categorie : ".$e->getMessage());
}

