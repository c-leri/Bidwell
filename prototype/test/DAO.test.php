<?php
// Test de la classe DAO
require_once(__DIR__ . '/../model/DAO.class.php');
// Fonctions d'aide
require_once(__DIR__ . '/Helper.php');

try {
    // Constructeur
    print("Création d'un objet DAO : ");
    $dao = DAO::get();
    OK();

    // Create
    print("Test d'insertions avec exec : ");
    $query = 'INSERT INTO Categorie(libelle, idMere) VALUES(?,?)';
    $data = ['test', 1];
    $res = $dao->exec($query, $data);
    if ($res != 1) {
        throw new Exception("$res nombre de lignes insérées, Attendu : 1");
    }

    $query = 'INSERT INTO Enchere(libelle, idCategorie, dateDebut, prixDepart, prixRetrait, loginUtilisateurDerniereEnchere, images, description) VALUES(?,?,?,?,?,?,?,?)';
    $data = ['test', 1, 20, 100, 0, null, 'test.png', 'test.txt'];
    $res = $dao->exec($query, $data);
    if ($res != 1) {
        throw new Exception("$res nombre de lignes insérées, Attendu : 1");
    }
    OK();

    // Read
    print("Test d'une lecture par query : ");
    $query = "SELECT * FROM Enchere WHERE id=?";
    $id = 1;
    $data = [$id];
    $value = $dao->query($query, $data);
    $expected = array(
        array(
        "id" => 1,
        0 => 1,
        "idCategorie" => 1,
        1 => 1,
        "libelle" => "test",
        2 => "test",
        "dateDebut" => 20,
        3 => 20,
        "prixDepart" => 100,
        4 => 100,
        "prixRetrait" => 0,
        5 => 0,
        "loginUtilisateurDerniereEnchere" => null,
        6 => null,
        "images" => "test.png",
        7 => "test.png",
        "description" => "test.txt",
        8 => "test.txt"
        )
    );
    if ($value != $expected) {
        var_dump($value);
        print("Attendu : \n");
        var_dump($expected);
        throw new Exception("Enchere No $id non trouvé");
    }
    OK();
} catch(Exception $e) {
    KO("Erreur sur DAO : ".$e->getMessage());
}