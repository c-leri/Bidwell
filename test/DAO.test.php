<?php
// Test de la classe DAO
use Bidwell\Model\DAO;

require_once dirname(__DIR__) . '/vendor/autoload.php';

require_once __DIR__ . '/Helper.php';

try {
    // Constructeur
    print("Création d'un objet DAO : ");
    $dao = DAO::get();
    OK();

    ////////////////// CREATE ///////////////////

    print("Test d'insertions avec exec : ");

    // Insertion d'une première Catégorie
    $query = 'INSERT INTO Categorie(libelle) VALUES(?)';
    $data = ['mereTest'];
    $res = $dao->exec($query, $data);
    if ($res != 1) {
        throw new Exception("Insertion d'une première Catégorie : $res nombre de lignes insérées, Attendu : 1");
    }

    // Insertion d'une deuxième Catégorie
    $query = 'INSERT INTO Categorie(libelle, libelleMere) VALUES(?,?)';
    $data = ['filleTest', 'mereTest'];
    $res = $dao->exec($query, $data);
    if ($res != 1) {
        throw new Exception("Insertion d'une deuxième Catégorie : $res nombre de lignes insérées, Attendu : 1");
    }

    // Insertion d'un Utilisateur
    $mdpHash = password_hash('mdp', PASSWORD_BCRYPT);
    $loginUtilisateur = 'loginTest';
    $query = 'INSERT INTO Utilisateur(login, mdpHash, email, numeroTelephone, nbJetons, dateFinConservation) VALUES(?,?,?,?,?,?)';
    $data = [$loginUtilisateur, $mdpHash, 'test@example.com', '0146829164', 0, 0];
    $res = $dao->exec($query, $data);
    if ($res != 1) {
        throw new Exception("Insertion d'un Utilisateur : $res nombre de lignes insérées, Attendu : 1");
    }

    // Insertion d'une Enchère
    $query = 'INSERT INTO Enchere(loginCreateur, libelle, dateDebut, prixDepart, prixRetrait, images, description, libelleCategorie, dateFinConservation, codePostal, infoRemiseDirect,infoEnvoiColis, infoEmail,infoTel) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?)';
    $data = ['loginTest', 'testInsert', 20, 100, 0, 'testInsert.png', 'testInsert.txt', 'filleTest', 0, "38100", true, false , false, false ];
    $res = $dao->exec($query, $data);
    if ($res != 1) {
        throw new Exception("Insertion d'une Enchère : $res nombre de lignes insérées, Attendu : 1");
    }
    $idEnchere = $dao->lastInsertId();

    OK();

    /////////////////// READ ////////////////////

    print("Test d'une lecture par query : ");
    $query = 'SELECT * FROM Utilisateur WHERE login=?';
    $login = 'loginTest';
    $data = [$login];
    $value = $dao->query($query, $data);
    $expected = array(
        array(
            'login' => 'loginTest',
            0 => 'loginTest',
            'mdpHash' => $mdpHash,
            1 => $mdpHash,
            'email' => 'test@example.com',
            2 => 'test@example.com',
            'numeroTelephone' => '0146829164',
            3 => '0146829164',
            'nbJetons' => 0,
            4 => 0,
            'dateFinConservation' => 0,
            5 => 0
        )
    );
    if ($value != $expected) {
        var_dump($value);
        print("Attendu : \n");
        var_dump($expected);
        throw new Exception("Utilisateur $login non trouvé");
    }

    OK();

    ////////////////// UPDATE ///////////////////

    print("Test d'une modification par exec : ");
    $query = 'UPDATE Utilisateur SET mdpHash = ?, email = ?, numeroTelephone = ?, nbJetons = ? WHERE login = ?';
    $data = ['mdpHash', 'test2@example.com', '0123456789', 50, 'loginTest'];
    $res = $dao->exec($query, $data);
    if ($res != 1) {
        throw new Exception("$res nombre de lignes modifiées, Attendu : 1");
    }

    OK();

    ////////////////// DELETE ///////////////////

    print("Test de suppressions par exec : ");

    // Suppression de l'Enchère
    $query = 'DELETE FROM Enchere WHERE id = ?';
    $data = [$idEnchere];
    $res = $dao->exec($query, $data);
    if ($res != 1) {
        throw new Exception("Suppresion de l'Enchère : $res nombre de lignes supprimées, Attendu : 1");
    }

    // Suppresion de l'Utilisateur
    $query = 'DELETE FROM Utilisateur WHERE login = ?';
    $data = [$loginUtilisateur];
    $res = $dao->exec($query, $data);
    if ($res != 1) {
        throw new Exception("Suppresion de l'Utilisateur : $res nombre de lignes supprimées, Attendu : 1");
    }

    // Suppression des Catégories
    $query = 'DELETE FROM Categorie WHERE libelle = ? OR libelle = ?';
    $data = ['mereTest', 'filleTest'];
    $res = $dao->exec($query, $data);
    if ($res != 2) {
        throw new Exception("Suppression des Catégories : $res nombre de lignes supprimées, Attendu : 2");
    }

    OK();

} catch(Exception $e) {
    KO("Erreur sur DAO : ".$e->getMessage());
}