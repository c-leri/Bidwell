<?php
// Test de la classe Categorie
use Bidwell\Model\Categorie;
use Bidwell\Model\Enchere;
use Bidwell\Model\Utilisateur;

require_once __DIR__ . '/../vendor/autoload.php';

require_once __DIR__ . '/Helper.php';

try {
    // Constructeur
    print('Test du constructeur : ');
    $nomCategorie = 'testConstructeur';
    $categorie = new Categorie($nomCategorie);
    if ($categorie->getLibelle() != $nomCategorie) {
        KO("Test du constructeur :\n"
            ." - Nom de la catégorie : {$categorie->getLibelle()}"
            ." - Attendu : $nomCategorie");
    }
    OK();

    /////////////// CREATE + READ ////////////////
    print('Test create() et read() : ');

    // create() catégorie mère
    $categorieMere = new Categorie('testCreateMere');
    $categorieMere->create();

    // read() catégorie mère
    $categorieMereRead = Categorie::read($categorieMere->getLibelle());
    if ($categorieMere != $categorieMereRead) {
        print("\nCategorie créée :\n");
        var_dump($categorieMere);
        print("Categorie lue :\n");
        var_dump($categorieMereRead);
        KO('Test create() et read() : categorie créée != catégorie lue');
    }

    // create() catégorie fille
    $categorieFille = new Categorie('testCreateFille', $categorieMere);
    $categorieFille->create();

    // read() catégorie fille
    $categorieFilleRead = Categorie::read($categorieFille->getLibelle());
    if ($categorieFille != $categorieFilleRead) {
        print("\nCategorie créée :\n");
        var_dump($categorieFille);
        print("\nCategorie lue :\n");
        var_dump($categorieFilleRead);
        KO('Test create() et read() : categorie créée != catégorie lue');
    }

    OK();

    /////////////////// READ ////////////////////
    print('Test read() : ');

    // read() catégorie inexistante
    try {
        Categorie::read("Cette Categorie n'existe pas");
        KO("Test read() : lecture d'une catégorie inexistante devrait renvoyer une exception");
    } catch (Exception) {
        OK();
    }

    ////////////////// UPDATE ///////////////////
    print('Test update() : ');

    // utilisateur pour la création d'enchère
    $utilisateur = new Utilisateur('testCategorie', 'test.categorie@example.com', '0606060606');
    $utilisateur->setPassword('motDePasse');
    $utilisateur->create();

    // update() avec des enchères
    $dateDebut = DateTime::createFromFormat('Y-m-d', '2050-12-24');
    $enchere1 = new Enchere($utilisateur, 'enchere1', $dateDebut, 500, 0, 'enchere1.png', 'enchere1.txt', $categorieFille);
    $enchere1->create();
    $enchere2 = new Enchere($utilisateur, 'enchere2', $dateDebut, 500, 0, 'enchere2.png', 'enchere2.txt', $categorieFille);
    $enchere2->create();
    $categorieFille->update();
    $categorieFilleRead = Categorie::read($categorieFille->getLibelle());
    if ($categorieFille != $categorieFilleRead) {
        print("\nCategorie mise à jour :\n");
        var_dump($categorieFille);
        print("\nCategorie lue :\n");
        var_dump($categorieFilleRead);
        KO('Test update() : categorie mise à jour != catégorie lue');
    }

    // update() catégorie mère
    $categorieMere->setCategorieMere($categorieFille);       // $categorieFille devient la mère de $categorieMere
    $categorieFille->setCategorieMere(null);                 // on retire le fait que $categorieFille est la fille de $categorieMere pour éviter une boucle
    $categorieFille->update();
    $categorieMere->update();

    $categorieFilleRead = Categorie::read($categorieFille->getLibelle());
    if ($categorieFille != $categorieFilleRead) {
        print("\nCategorie mise à jour :\n");
        var_dump($categorieFille);
        print("\nCategorie lue :\n");
        var_dump($categorieFilleRead);
        KO('Test update() : categorie mise à jour != catégorie lue');
    }

    $categorieMereRead = Categorie::read($categorieMere->getLibelle());
    if ($categorieMere != $categorieMereRead) {
        print("\nCategorie mise à jour :\n");
        var_dump($categorieMere);
        print("\nCategorie lue :\n");
        var_dump($categorieMereRead);
        KO('Test update() : categorie mise à jour != catégorie lue');
    }

    OK();

    ////////////////// DELETE ///////////////////
    print('Test delete() : ');

    // test delete() sur une catégorie mère
    $libelleCategorieFille = $categorieFille->getLibelle();
    $categorieFille->delete();
    try {
        Categorie::read($libelleCategorieFille);
        KO("Test delete() : read une catégorie delete devrait renvoyer une exception");
    } catch (Exception) {}
    if ($categorieFille->isInDB()) {
        var_dump($categorieFille);
        KO("Test delete() : la catégorie ne devrait plus être dans la bd après un delete");
    }
    // on vérifie que $categorieFille n'est plus la catégorie mère de $catégorieMere
    // il faut read les catégories filles pour que la modification soit effective
    $categorieMere->sync();
    if ($categorieMere->getCategorieMere() !== null) {
        var_dump($categorieMere);
        KO("Test delete() : la catégorie mère ne devrait pas être encore set si il a été delete");
    }

    // test delete() sur une catégorie fille
    $libelleCategorieMere = $categorieMere->getLibelle();
    $categorieMere->delete();
    try {
        Categorie::read($libelleCategorieMere);
        KO("Test delete() : read une catégorie delete devrait renvoyer une exception");
    } catch (Exception) {}
    if ($categorieMere->isInDB()) {
        var_dump($categorieMere);
        KO("Test delete() : la catégorie ne devrait plus être dans la bd après un delete");
    }

    // test delete() sur une catégorie non dans la bd
    try {
        $categorie->delete();
        KO("Test delete() : delete une catégorie non dans la base devrait renvoyer une exception");
    } catch (Exception) {}

    // on supprime les Enchères et l'Utilisateur créés pour les tests
    $enchere1->delete();
    $enchere2->delete();
    $utilisateur->delete();

    OK();

} catch (Exception $e) {
    KO("Erreur sur Categorie : ".$e->getMessage());
}