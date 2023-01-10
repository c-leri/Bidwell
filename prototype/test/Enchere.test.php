<?php
// Test la classe Enchere
require_once(__DIR__ . "/../model/Enchere.class.php");
// Fonctions d'aide
require_once(__DIR__ . "/Helper.php");

// Création d'une nouvelle caégorie
$categorie = new Categorie('test');
$date = DateTime::createFromFormat('d-m-Y', '04-05-2024');

try {
	// Constructeur
	print('Test du constructeur : ');
	$enchere = new Enchere('testConstructeur', $date, 500, 0, 'testConstructeur.png', 'testConstructeur.txt', $categorie);
	OK();
	/* echo("Contenu de la variable :"); */
	/* var_dump($enchere); */
} catch (Exception $e) {
	KO("Erreur sur Enchere : ".$e->getMessage());
}

try {
	print('Test du constructeur avec une date incorrecte : ');
	$dateIncorrecte = DateTime::createFromFormat('d-m-Y', '01-01-2023');
	$enchere = new Enchere('testConstructeur2', $dateIncorrecte, 500, 0, 'testConstructeur2.png', 'testConstructeur2.txt', $categorie);
	KO("La catégorie a été créée malgré la date incorrecte.");
} catch (Exception $e) {
	OK();
}

// Création d'une enchère pour le test des méthodes CRUD
$enchere = new Enchere('Enchere 3', $date, 500, 200, 'enchere3.png', 'enchere3.txt', $categorie);
$categorie->create();
// Test des méthodes CRUD
// create()
try {
	print('Test de la sérialisation de d\'une enchère : ');
	$enchere->create();
	OK();
} catch (Exception $e) {
	KO("Erreur dans la sérialisation de l'enchère : " . $e->getMessage());
}

// read()
try {
	print('Test de la lecture d\'une enchère depuis la base de données : ');
	$enchereLue = Enchere::read($enchere->getId());
	OK();
} catch (Exception $e) {
	KO("Problème dans la lecture de l'enchère : " . $e->getmessage());
}

// update()
try {
	print('Test de la mise à jour d\'une enchère dans la base de données : ');
	$enchere->setLibelle("Amogus");
	$enchere->update();
	if ($enchere != Enchere::read($enchere->getId())) {
		throw new Exception("L'enchère lue ne correspond pas à l'enchère mise à jour.");
	}
	OK();
} catch (Exception $e) {
	KO("Erreur dans la mise à jour de l'enchère : " . $e->getMessage());
}

// delete()
try {
	print('Test de la suppresion d\'une enchère de la base de données : ');
	$enchere->delete();
	OK();
} catch (Exception $e) {
	KO("Erreur dans la suppression de l'enchère : " . $e->getMessage);
}

