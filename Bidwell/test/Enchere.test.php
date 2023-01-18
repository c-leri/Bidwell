<?php
// Test de la classe Enchere
use Bidwell\Model\Enchere;
use Bidwell\Model\Categorie;
use Bidwell\Model\Utilisateur;

require_once dirname(__DIR__) . '/vendor/autoload.php';

require_once __DIR__ . '/Helper.php';

try {
    // catégorie pour la création d'enchère
    $categorie = new Categorie('test');
    $categorie->create();
    $date = DateTime::createFromFormat('d-m-Y', '04-05-2024');

    // utilisateur pour la création d'enchère
    $utilisateur = new Utilisateur('testEnchere', 'test.categorie@example.com', '0606060606');
    $utilisateur->setPassword('motDePasse');
    $utilisateur->create();

    // Constructeur
    print('Test du constructeur : ');

    try {
        $infosContact = array(false,false);
        $infosEnvoi = array(true,false);
        new Enchere($utilisateur, 'testConstructeur', $date, 500, 0, 'testConstructeur.png', 'testConstructeur.txt', $categorie, $infosContact, $infosEnvoi, 56000);
    } catch (Exception $e) {
        KO("Erreur sur Enchere : ".$e->getMessage());
    }

    try {
        $infosContact = array(true,true);
        $infosEnvoi = array(false,true);
        $categorieInexistante = new Categorie('inexistance');
        new Enchere($utilisateur, 'testConstructeur3', $date, 500, 0, 'testConstructeur3.png', 'testConstructeur3.txt', $categorieInexistante, $infosContact, $infosEnvoi, 92000);
        KO("L'enchère a été créée malgré la catégorie non sérialisée");
    } catch (Exception) {}

    OK();

    /////////////////////////////////////////////
    //                  CRUD                   //
    /////////////////////////////////////////////

    // Création d'une enchère pour le test des méthodes CRUD
    $infosContact = array(false,false);
    $infosEnvoi = array(true,false);
    $enchere = new Enchere($utilisateur, 'Enchere 3', $date, 500, 200, 'enchere3.png', 'enchere3.txt', $categorie, $infosContact, $infosEnvoi, 45000);

    /////////////// CREATE + READ ////////////////
    print('Test create() et read() : ');

    // create()
    $enchere->create();

    // read()
    $enchereRead = Enchere::read($enchere->getId());
    if ($enchere != $enchereRead) {
        print("\nEnchère créée :\n");
        var_dump($enchere);
        print("Enchère lue :\n");
        var_dump($enchereRead);
        throw new Exception('Test create() et read() : enchère crée != enchère lue');
    }

    OK();

    /////////////////// READ ////////////////////
    print('Test read() : ');

    // read() enchère inexistante
    try {
        Enchere::read(55555555);
        KO("Erreur sur Enchere : Test read() : lecture d'une Enchère inexistante devrait renvoyer une exception");
    } catch (Exception) {
        OK();
    }

    ////////////////// UPDATE ///////////////////
    print('Test update() : ');

    $enchere->setLibelle("testUpdate");
    $enchere->setDescription("C'est la description ouais ouais lol");
    $enchere->update();
    $enchereRead = Enchere::read($enchere->getId());
    if ($enchere != $enchereRead) {
        print("\nEnchère modifiée :\n");
        var_dump($enchere);
        print("Enchère lue :\n");
        var_dump(Enchere::read($enchere->getId()));
        throw new Exception("L'enchère lue ne correspond pas à l'enchère mise à jour.");
    }

    OK();

    ////////////////// DELETE ///////////////////
    print('Test delete() : ');

    // delete()
    $idEnchere = $enchere->getId();
    $enchere->delete();
    try {
        Enchere::read($idEnchere);
        KO("Test delete() : lire une enchère supprimée devrait renvoyer une erreur");
    } catch (Exception) {}

    // on supprime l'Utilisateur et la catégorie créés pour les tests
    $utilisateur->delete();
    $categorie->delete();

    OK();

} catch (Exception $e) {
    KO('Erreur sur Enchere : '.$e->getMessage());
}