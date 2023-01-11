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
    if ($categorieFille->getIdCategorieMere() != $categorie->getId()) {
        print("\nCategorie mere :");
        var_dump($categorie);
        print("Categorie fille :");
        var_dump($categorieFille);
        throw new Exception('Test add() : $categorieFille->getParent() != $categorie');
    }

    // remove()
    $categorie->remove($categorieFille);
    if ($categorie->getIdCategorieMere() !== null) {
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
    $categorieMereRead = Categorie::read($categorieMere->getId(), true);
    if ($categorieMere != $categorieMereRead) {
        print("\nCategorie créée :\n");
        var_dump($categorieMere);
        print("\nCategorie lue :\n");
        var_dump($categorieMereRead);
        throw new Exception('Test create() et read() : categorie créée != catégorie lue');
    }

    // create() catégorie fille
    $categorieFille = new Categorie('testCreateFille', $categorieMere);
    $categorieFille->create();

    // read() catégorie fille
    $categorieFilleRead = Categorie::read($categorieFille->getId(), true);
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
        Categorie::read(55555555);
        KO("Erreur sur Categorie : Test read() : lecture d'une catégorie inexistante devrait renvoyer une exception");
    } catch (Exception $e) {
        OK();
    }

    ////////////////// UPDATE ///////////////////
    print('Test update() : ');

    // update() catégorie fille
    $categorieFille->setLibelle('testUpdateFille');
    $categorieFille->update();
    $categorieFilleRead = Categorie::read($categorieFille->getId());
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

    $categorieFilleRead = Categorie::read($categorieFille->getId(), true);
    if ($categorieFille != $categorieFilleRead) {
        print("\nCategorie mise à jour :\n");
        var_dump($categorieFille);
        print("\nCategorie lue :\n");
        var_dump($categorieFilleRead);
        throw new Exception('Test update() : categorie mise à jour != catégorie lue');
    }

    $categorieMereRead = Categorie::read($categorieMere->getId(), true);
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
    $idCategorieFille = $categorieFille->getId();
    $categorieFille->delete();
    try {
        Categorie::read($idCategorieFille);
        throw new Exception("Test delete() : read une catégorie delete devrait renvoyer une exception");
    } catch (Exception $e) {}
    if ($categorieFille->getId() != -1) {
        var_dump($categorieFille);
        throw new Exception("Test delete() : l'id d'une catégorie devrait être -1 après un delete");
    }
    // on vérifie que $categorieFille n'est plus la catégorie mère de $catégorieMere
    // il faut read les catégories filles pour que la modification soit effective
    $categorieMere->sync();
    if ($categorieMere->getIdCategorieMere() !== null) {
        var_dump($categorieMere);
        throw new Exception("Test delete() : la catégorie mère ne devrait pas être encore set si il a été delete");
    }

    // test delete() sur une catégorie fille
    $idCategorieMere = $categorieMere->getId();
    $categorieMere->delete();
    try {
        Categorie::read($idCategorieMere);
        throw new Exception("Test delete() : read une catégorie delete devrait renvoyer une exception");
    } catch (Exception $e) {}
    if ($categorieMere->getId() != -1) {
        var_dump($categorieMere);
        throw new Exception("Test delete() : l'id d'une catégorie devrait être -1 après un delete");
    }

    // test delete() sur une catégorie non dans la bd
    try {
        $categorie->delete();
        throw new Exception("Test delete() : delete une catégorie non dans la base devrait renvoyer une exception");
    } catch (Exception $e) {}

    OK();

} catch (Exception $e) {
    KO("Erreur sur Categorie : ".$e->getMessage());
}

