<?php
/**
 * Ce fichier contient les tests unitaires pour la classe
 * Participation.
 */

// Inclusion des classes utilisées dans le test
require_once(__DIR__ . "/../model/Participation.class.php");
require_once(__DIR__ . "/../model/Utilisateur.class.php");
require_once(__DIR__ . "/../model/Enchere.class.php");
// Inclusion des fonctions d'aide
require_once(__DIR__ . "/Helper.php");

// Création d'une instance de la classe Utilisateur et Enchere
// pour les tests.
$dateEnchere = DateTime::createFromFormat('Y-m-d H:i', '2023-02-14 15:00');
$utilisateur = new Utilisateur("John Sus", "john.sus@amog.us", '0659715532');
$categorie = new Categorie('Nourriture');
$enchere = new Enchere(
	$utilisateur,
	"Saucisson",
	$dateEnchere,
	500,
	200,
	"saucisson.png",
	"saucisson.txt",
    $categorie
);

// Insertion des autres objets dans la base de données
$categorie->create();
$utilisateur->setPassword('testMDP');
$utilisateur->create();
$enchere->create();

/**
 * Test de la création d'une instance de la classe Participation
 */
print('Test constructeur : ');
try {
	$participation = new Participation($enchere, $utilisateur);
	OK();
} catch (Exception $e) {
	KO("La création de l'instance a échoué : " . $e->getMessage());
}

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
} catch (Exception $e) {
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
	$readTest = Participation::read($enchere, $utilisateur);
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
		$readTest = Participation::read($enchere, $utilisateur);
	} catch (Exception $e) {
		OK();
	}
} catch (Exception $e) {
	KO("Erreur dans la suppression de la participation : ".$e->getMessage());
}

// On supprime les objets qu'on a créé pour les tests
$categorie->delete();
$utilisateur->delete();
$enchere->delete();