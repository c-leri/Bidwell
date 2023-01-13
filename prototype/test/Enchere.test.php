<?php
// Test la classe Enchere
require_once __DIR__.'/../model/Enchere.class.php';
require_once __DIR__.'/../model/Utilisateur.class.php';
// Fonctions d'aide
require_once __DIR__.'/Helper.php';

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
		$enchere = new Enchere($utilisateur, 'testConstructeur', $date, 500, 0, 'testConstructeur.png', 'testConstructeur.txt', $categorie);
	} catch (Exception $e) {
		echo("Contenu de la variable :");
		var_dump($enchere);
		KO("Erreur sur Enchere : ".$e->getMessage());
	}

	try {
		$dateIncorrecte = DateTime::createFromFormat('d-m-Y', '01-01-2023');
		$enchere = new Enchere($utilisateur, 'testConstructeur2', $dateIncorrecte, 500, 0, 'testConstructeur2.png', 'testConstructeur2.txt', $categorie);
		KO("La catégorie a été créée malgré la date incorrecte.");
	} catch (Exception $e) {}

	try {
		$categorieInexistante = new Categorie('inexistance');
		$enchere = new Enchere($utilisateur, 'testConstructeur3', $dateIncorrecte, 500, 0, 'testConstructeur3.png', 'testConstructeur3.txt', $categorieInexistante);
		KO("La catégorie a été créée malgré la catégorie non sérialisée");
	} catch (Exception $e) {}

	OK();

	/////////////////////////////////////////////
	//                  CRUD                   //
	/////////////////////////////////////////////

	// Création d'une enchère pour le test des méthodes CRUD
	$enchere = new Enchere($utilisateur, 'Enchere 3', $date, 500, 200, 'enchere3.png', 'enchere3.txt', $categorie);

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
	} catch (Exception $e) {
		OK();
	}

	////////////////// UPDATE ///////////////////
	print('Test update() : ');

	$enchere->setLibelle("testUpdate");
	$dateDebut = DateTime::createFromFormat('Y-m-d', '2050-12-24');
	$enchere->setDateDebut($dateDebut);
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
	} catch (Exception $e) {}

	// on supprime l'Utilisateur et la catégorie créés pour les tests
	$utilisateur->delete();
	$categorie->delete();

	OK();

} catch (Exception $e) {
	KO('Erreur sur Enchere : '.$e->getMessage());
}

