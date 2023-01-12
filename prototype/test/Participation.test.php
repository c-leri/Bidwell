#!/usr/bin/env php -a

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
$enchere = new Enchere(
	"Saucisson",
	$dateEnchere,
	500,
	200,
	"saucisson.png",
	"saucisson.txt",
    "Nourriture"
);

/**
 * Test de la création d'une instance de la classe Participation
 */
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
if ($participation->isInDB()) {
	var_dump($participation);
	KO("L'enchère n'est pas dans la base de données, mais la fonction a renvoyé vrai.");
} else {
	OK();
}

// Test des méthodes CRUD
// Insertion des autres objets dans la base de données
$enchere->create();
$utilisateur->create();

/**
 * Test de la méthode read()
 *  - L'instance n'a pas encore été insérée dans la base de données, une exception est levée
 */
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
try {
	$readTest = Participation::read($enchere, $utilisateur);
	OK();
} catch (Exception $e) {
	KO("La lecture a échoué : " . $e->getMessage());
}

/**
 * Test de la méthode update()
 *  - La méthode incrementeEncheri est utilisée pour modifier un attribut
 */
try {
	$participation->incrementeEncheri();
	$participation->update();
	if ($participation != Participation::read($enchere, $utilisateur)) {
		throw new Exception("La participation lue dans la base de données est incorrecte.");
	}
	OK();
} catch (Exception $e) {
	KO("Erreur dans la mise à jour de la base de données : " . $e->getMessage());
}

/**
 * Test de la méthode delete()
 */
try {
	$participation->delete();
	try {
		$readTest = Participation::read($enchere, $utilisateur);
	} catch (Exception $e) {
		OK();
	}
} catch (Exception $e) {
	KO("Erreur dans la suppression de la participation.");
}
?>

