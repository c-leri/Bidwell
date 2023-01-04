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

    ////////////////// CREATE ///////////////////

    print("Test d'insertions avec exec : ");

    // Insertion d'une première Catégorie
    $query = 'INSERT INTO Categorie(libelle, idMere) VALUES(?,?)';
    $data = ['mereTest', 1];
    $res = $dao->exec($query, $data);
    if ($res != 1) {
        throw new Exception("Insertion d'une première Catégorie : $res nombre de lignes insérées, Attendu : 1");
    }

    // Insertion d'une deuxième Catégorie
    $query = 'INSERT INTO Categorie(libelle, idMere) VALUES(?,?)';
    $data = ['filleTest', 1];
    $res = $dao->exec($query, $data);
    if ($res != 1) {
        throw new Exception("Insertion d'une deuxième Catégorie : $res nombre de lignes insérées, Attendu : 1");
    }

    // Insertion d'un Utilisateur
    $query = 'INSERT INTO Utilisateur(login, mdpHash, nom, email, numeroTelephone, nbJetons) VALUES(?,?,?,?,?,?)';
    $data = ['loginTest', password_hash('mdp', PASSWORD_BCRYPT), 'test', 'test@example.com', '0146829164', 0];
    $res = $dao->exec($query, $data);
    if ($res != 1) {
        throw new Exception("Insertion d'un Utilisateur : $res nombre de lignes insérées, Attendu : 1");
    }

    // Insertion d'une Enchère
    $query = 'INSERT INTO Enchere(libelle, dateDebut, prixDepart, prixRetrait, images, description, idCategorie) VALUES(?,?,?,?,?,?,?)';
    $data = ['testInsert', 20, 100, 0, 'testInsert.png', 'testInsert.txt', 1];
    $res = $dao->exec($query, $data);
    if ($res != 1) {
        throw new Exception("Insertion d'une Enchère : $res nombre de lignes insérées, Attendu : 1");
    }

    OK();

    /////////////////// READ ////////////////////

    print("Test d'une lecture par query : ");
    $query = 'SELECT * FROM Enchere WHERE id=?';
    $id = 1;
    $data = [$id];
    $value = $dao->query($query, $data);
    $expected = array(
        array(
        'id' => 1,
        0 => 1,
        'libelle' => 'testInsert',
        1 => 'testInsert',
        'dateDebut' => 20,
        2 => 20,
        'prixDepart' => 100,
        3 => 100,
        'prixRetrait' => 0,
        4 => 0,
        'loginUtilisateurDerniereEnchere' => null,
        5 => null,
        'images' => 'testInsert.png',
        6 => 'testInsert.png',
        'description' => 'testInsert.txt',
        7 => 'testInsert.txt',
        'idCategorie' => 1,
        8 => 1
        )
    );
    if ($value != $expected) {
        var_dump($value);
        print("Attendu : \n");
        var_dump($expected);
        throw new Exception("Enchere No $id non trouvé");
    }
    
    OK();

    ////////////////// UPDATE ///////////////////

    print("Test d'une modification par exec : ");
    $query = 'UPDATE Enchere SET libelle = ?, dateDebut = ?, prixDepart = ?, prixRetrait = ?, loginUtilisateurDerniereEnchere = ?, images = ?, description = ?, idCategorie = ? WHERE id = ?';
    $data = ['testUpdate', 55, 500, 100, 'loginTest', 'testInsert.png testUpdate.png', 'testUpdate.txt', 2, 1];
    $res = $dao->exec($query, $data);
    if ($res != 1) {
        throw new Exception("$res nombre de lignes modifiées, Attendu : 1");
    }

    OK();

    ////////////////// DELETE ///////////////////

    print("Test de suppressions par exec : ");

    // Suppression de l'Enchère
    $query = 'DELETE FROM Enchere';
    $data = [];
    $res = $dao->exec($query, $data);
    if ($res != 1) {
        throw new Exception("Suppresion de l'Enchère : $res nombre de lignes supprimées, Attendu : 1");
    }

    // Suppresion de l'Utilisateur
    $query = 'DELETE FROM Utilisateur';
    $data = [];
    $res = $dao->exec($query, $data);
    if ($res != 1) {
        throw new Exception("Suppresion de l'Utilisateur : $res nombre de lignes supprimées, Attendu : 1");
    }

    // Suppression des Catégories
    $query = 'DELETE FROM Categorie';
    $data = [];
    $res = $dao->exec($query, $data);
    if ($res != 2) {
        throw new Exception("Suppression des Catégories : $res nombre de lignes supprimées, Attendu : 2");
    }

    OK();

} catch(Exception $e) {
    KO("Erreur sur DAO : ".$e->getMessage());
}