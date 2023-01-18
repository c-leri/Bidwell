<?php
// Test de la classe Participation
use Bidwell\Model\Participation;
use Bidwell\Model\Categorie;
use Bidwell\Model\Enchere;
use Bidwell\Model\Utilisateur;

require_once dirname(__DIR__) . '/vendor/autoload.php';

require_once __DIR__ . '/Helper.php';

try {
// Création et insertion d'instances des classes Utilisateur, Enchere et Categorie
// pour les tests.
    $dateEnchere = DateTime::createFromFormat('Y-m-d H:i', '2023-02-14 15:00');
    $utilisateur = new Utilisateur("John Sus", "john.sus@amog.us", '0659715532');
    $utilisateur->setPassword('testMDP');
    $utilisateur->create();
    $categorie = new Categorie('Nourriture');
    $categorie->create();
    $infosContact = array(false,true);
    $infosEnvoi = array(true,true);
    $enchere = new Enchere(
        $utilisateur,
        "Saucisson",
        $dateEnchere,
        500,
        200,
        "saucisson.png",
        "saucisson.txt",
        $categorie,
        $infosContact,
        $infosEnvoi,
        85000
    );
    $enchere->create();

    /**
     * Test de la création d'une instance de la classe Participation
     */
    print('Test constructeur : ');

    $participation = new Participation($enchere, $utilisateur);
    if ($participation->getEnchere() != $enchere) {
        echo "Enchere :\n";
        var_dump($enchere);
        echo "Enchere de la participation :\n";
        var_dump($participation->getEnchere());
        KO("Test constructeur : l'enchère de la participation n'est pas la même que celle passée en paramètre");
    }
    if ($participation->getUtilisateur() != $utilisateur) {
        echo "Utilisateur :\n";
        var_dump($utilisateur);
        echo "Utilisateur de la participation :\n";
        var_dump($participation->getUtilisateur());
        KO("Test constructeur : l'utilisateur de la participation n'est pas le même que celui passé en paramètre");
    }
    OK();

    /**
     * Test de la fonction isInDB().
     *  - L'enchère n'est pas dans la base de données, une exception est levée
     */
    print('Test isInDB() : ');
    if ($participation->isInDB()) {
        var_dump($participation);
        KO("L'enchère n'est pas dans la base de données, mais la fonction a renvoyé vrai.");
    } else {
        OK();
    }

// Test des méthodes CRUD

    /**
     * Test de la méthode read()
     *  - L'instance n'a pas encore été insérée dans la base de données, une exception est levée
     */
    print('Test read() : ');
    try {
        $readTest = Participation::read($enchere, $utilisateur);
        var_dump($readTest);
        KO("La méthode aurait du lever une exception.");
    } catch (Exception) {
        OK();
    }

    /**
     * Test de la méthode create()
     */
    print('Test create() : ');
    try {
        $participation->create();
        OK();
    } catch (Exception $e) {
        KO("L'insertion a échoué : " . $e->getMessage());
    }

    /**
     * Test à nouveau de la méthode read().
     *  - Elle ne doit plus lever d'exception
     */
    print('Test read() 2 : ');
    try {
        Participation::read($enchere, $utilisateur);
        OK();
    } catch (Exception $e) {
        KO("La lecture a échoué : " . $e->getMessage());
    }

    /**
     * Test de la méthode update()
     *  - La méthode setNbEncheres est utilisée pour modifier un attribut
     */
    print('Test update() : ');
    try {
        $participation->setNbEncheres(5);
        $participation->update();
        $participationRead = Participation::read($enchere, $utilisateur);
        if ($participation != $participationRead) {
            print("\nParticipation mise à jour :\n");
            var_dump($participation);
            print("Participation lue :\n");
            var_dump($participationRead);
            throw new Exception("La participation lue dans la base de données est incorrecte.");
        }
        OK();
    } catch (Exception $e) {
        KO("Erreur dans la mise à jour de la base de données : " . $e->getMessage());
    }

    /**
     * Test de la méthode delete()
     */
    print('Test delete() : ');
    try {
        $participation->delete();
        try {
            Participation::read($enchere, $utilisateur);
        } catch (Exception) {
            OK();
        }
    } catch (Exception $e) {
        KO("Erreur dans la suppression de la participation : " . $e->getMessage());
    }

// On supprime les objets qu'on a créés pour les tests
    $categorie->delete();
    $utilisateur->delete();
    $enchere->delete();
} catch (Exception $e) {
    KO('Erreur sur Participation : '.$e->getMessage());
}