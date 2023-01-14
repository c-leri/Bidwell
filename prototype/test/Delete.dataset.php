<?php
// Fichier retirant les données de test de la bd

// Inclusion du modèle
require_once __DIR__ .'/../model/Categorie.class.php';
require_once __DIR__.'/../model/Utilisateur.class.php';
require_once __DIR__.'/../model/DAO.class.php';

try {
    $dao = DAO::get();

    // Encheres
    $query = 'DELETE FROM Enchere WHERE loginCreateur = ? OR loginCreateur = ?';
    $data = ['john_doe', 'michelleOchell'];
    $dao->exec($query, $data);

    // Utilisateurs
    $utilisateur1 = Utilisateur::read('john_doe');
    $utilisateur1->delete();

    $utilisateur2 = Utilisateur::read('michelleOchell');
    $utilisateur2->delete();

    // Categories
    $categorieFille1 = Categorie::read('Art decoratif');
    $categorieFille1->delete();

    $categorieFille2 = Categorie::read('Art graphique');
    $categorieFille2->delete();

    $categorieMere1 = Categorie::read('Art');
    $categorieMere1->delete();
} catch (Exception $e) {
    echo "Problème lors de la suppression du dataset : " . $e->getMessage();
}