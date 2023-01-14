<?php
namespace Bidwell\Bidwell\Test;

// Test de la classe Utilisateur
use Bidwell\Bidwell\Model\Utilisateur;

use Exception;

require_once __DIR__.'/../../vendor/autoload.php';

try{
    // Constructeur
    print('Test du constructeur : ');
    $login='MonsieurTest';
    $email='emailtest@gmail.com';
    $numtel='but';
    try{
        $utilisatest= new Utilisateur($login,$email,$numtel);
        Helper::KO("Numéro de téléphone : $numtel accepté");
    }catch(Exception){
        $numtel = '0123456789';
        $utilisatest= new Utilisateur($login,$email,$numtel);
    }

    if($utilisatest->getLogin() != $login){
        throw new Exception("Test du constructeur :\n"
            ." -Login de l'utilisateur : {$utilisatest->getLogin()}"
            ." - Attendu : $login");
    }

    Helper::OK();

    ////////////////// SETTERS //////////////////
    print('Test des setters : ');

    //setPassword/mdpValide
    $pss = 'Mypasswordbrother';
    $fpss = 'mypasswordbrother';
    $gpss = 'Mypasswordsister';
    $utilisatest->setPassword($pss);
    if(!$utilisatest->mdpValide($pss)){
        throw new Exception("Test du mdpValide : Mot de passe juste ne fonctionne pas");
    }elseif($utilisatest->mdpValide($gpss)){
        throw new Exception("Test du mdpValide : Résultat non identique accepté\n"
            . " -Mot de passe de l'utilisateur : $pss"
            . " -Mot de passe donné : $gpss");
    }elseif ($utilisatest->mdpValide($fpss)){
        throw new Exception("Test du mdpValide : Manque de sensibilité à la casse"
            . " -Mot de passe de l'utilisateur : $pss"
            . " -Mot de passe donné : $fpss");
    }

    //setNom
    $nom = 'Nomtest';
    $utilisatest->setNom($nom);
    if($utilisatest->getNom()!=$nom){
        throw new Exception("Test de setNom :\n"
            ." -Nom de l'utilisateur : {$utilisatest->getNom()}"
            ." - Attendu : $nom");
    }else if($utilisatest->getNom()=='nomtest'){
        throw new Exception("Test de setNom : absence d'attention à la casse");
    }

    //setMail
    if($utilisatest->getEmail()!=$email){
        throw new Exception("Test de setMail :\n"
            ." -Login de l'utilisateur : {$utilisatest->getEmail()}"
            ." - Attendu : $email");
    }else if($utilisatest->getEmail()=='Emailtest@gmail.com'){
        throw new Exception("Test de setMail : absence d'attention à la casse"
            . " -Email de l'utilisateur : {$utilisatest->getEmail()}"
            . " -Email donné : Emailtest@gmail.com");
    }

    Helper::OK();

    /////////////// CREATE + READ ////////////////
    print('Test create() et read() : ');

    //Création d'un utilisateur
    $utisilateur= new Utilisateur($login,$email,$numtel);
    try{
        $utisilateur->create();
        throw new Exception("Création d'un utilisateur sans mot de passe");
    }catch(Exception){
        $utisilateur->setPassword($pss);
        $utisilateur->create();
    }


    //Lecture de l'utilisateur
    $utilisateurRead = Utilisateur::read($utisilateur->getLogin());
    if($utisilateur!=$utilisateurRead){
        print("\nUtilisateur créé :");
        var_dump($utisilateur);
        print("\nUtilisateur lu : \n");
        var_dump($utilisateurRead);
        throw new Exception('Test create() et read() : utilisateur créé != utilisateur lu');
    }
    Helper::OK();


    /////////////////// READ ////////////////////
    print('Test read() : ');

    //Read d'un utilisateur inexistant
    try {
        Utilisateur::read('boyoboy');
        Helper::KO("Erreur sur Utilisateur : Test read() : lecture d'un untilisateur inexistante devrait renvoyer une exception");
    } catch (Exception) {
        Helper::OK();
    }

    //Méthodes différentes
    //connectionValide
    print('Test connectionValide() : ');
    if(Utilisateur::connectionValide($login,$gpss)){
        Helper::KO("Erreur sur Utilisateur : Test connectionValide() : Demande de validation d'un faux mot de passe");
    } elseif(Utilisateur::connectionValide($login,$pss)){
        Helper::OK();
    }else{
        throw new Exception('Test connection valide renvoie une erreur même en cas de réussite');
    }


    ////////////////// UPDATE //////////////////
    print('Test update() : ');

    $utisilateur->setPassword('Newmdp');
    $utisilateur->setNom('Newnom');
    $utisilateur->setEmail('NewMail@yahoo.ru');
    $utisilateur->setNumeroTelephone('9876543210');
    $utisilateur->update();
    $utilisateurRead = Utilisateur::read($utisilateur->getLogin());
    if($utisilateur!=$utilisateurRead){
        print("\nUtilisateur mis à jour :");
        var_dump($utisilateur);
        print("\nUtilisateur lu : \n");
        var_dump($utilisateurRead);
        throw new Exception('Test update() : utilisateur mise à jour != utilisateur lu');
    }

    Helper::OK();


    ////////////////// DELETE ///////////////////
    print('Test delete() : ');

    $utisilateur->delete();
    try{
        Utilisateur::read($utisilateur->getLogin());
        Helper::KO("Erreur sur Utilisateur : Test read() : lecture d'un untilisateur delete devrait renvoyer une exception");
    } catch (Exception) {
        Helper::OK();
    }

} catch(Exception $e){
    Helper::KO("Erreur sur information : ".$e->getMessage());
}