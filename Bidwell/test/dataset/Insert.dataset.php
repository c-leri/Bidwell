<?php
// Fichier rajoutant des données dans la bd pour tester les interfaces du site

// Inclusion du modèle
use Bidwell\Model\Categorie;
use Bidwell\Model\Enchere;
use Bidwell\Model\Utilisateur;

require_once dirname(__DIR__, 2) . '/vendor/autoload.php';

try {
    // Utilisateurs
    $utilisateur1 = new Utilisateur('john_doe', 'john.doe@gmail.com', '0654638291');
    $utilisateur1->setNom('John Doe');
    $utilisateur1->setPassword('john51397');
    $utilisateur1->create();

    $utilisateur2 = new Utilisateur('michelleOchell', 'mich.deuneuzieu@yahoo.fr', '0456738291');
    $utilisateur2->setNom('Michelle Deneuzieu');
    $utilisateur2->setPassword('12121969');
    $utilisateur2->create();

    // Categories
    $categorieMere1 = new Categorie('Art');
    $categorieMere1->create();

    $categorieFille1 = new Categorie('Art decoratif', $categorieMere1);
    $categorieFille1->create();

    $categorieFille2 = new Categorie('Art graphique', $categorieMere1);
    $categorieFille2->create();

    // Encheres
    $enchere1 = new Enchere($utilisateur2, "Statuette de Boodah en bois", DateTime::createFromFormat('d-m-Y', '30-08-2030'), 500, 10, 'boodah.png', 'boodah.txt', $categorieFille1,"true,true","true,true","Grenoble rpz");
    $enchere1->create();

    $enchere2 = new Enchere($utilisateur1, "La Nuit Étoilée - Van Gogh ORIGINALE", DateTime::createFromFormat('d-m-Y', '11-09-2025'), 10000, 5000, 'nuitetoilee.png', 'boodah.txt', $categorieFille2,"true,false","true,false","Lyon");
    $enchere2->create();

    $enchere3 = new Enchere($utilisateur1, "test", DateTime::createFromFormat('d-m-Y', '11-09-2025'), 200, 100, 'nuitetoilee.png', 'test xd', $categorieFille2,"false,true","false,true","Paris");
    $enchere3->create();

} catch (Exception $e) {
    echo "Problème lors de l'insertion du dataset : " . $e->getMessage();
}
