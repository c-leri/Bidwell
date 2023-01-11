<?php
// Test la classe Categorie
require_once(__DIR__ . "/../model/Utilisateur.class.php");
// Fonctions d'aide
require_once(__DIR__ . "/Helper.php");

try{
    // Constructeur
    print('Test du constructeur : ');
    $login='MonsieurTest';
    $email='emailtest@gmail.com';
    $numtel='but';
    try{
        $utilisatest= new Utilisateur($login,$email,$numtel);
        KO("Numéro de téléphone : $numtel accepté");
    }catch(Exception $e){
        $numtel = '0123456789';
        $utilisatest= new Utilisateur($login,$email,$numtel);
        OK();
    }

    if($utilisatest->getLogin() != $login){
        throw new Exception("Test du constructeur :\n"
        ." -Login de l'utilisateur : {$utilisatest->getLogin()}"
        ." - Attendu : $login");
    }
    OK();

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
            . " -Mot de passe de l'utilisateur : {$pss}"
            . " -Mot de passe donné : {$gpss}");
    }elseif ($utilisatest->mdpValide($fpss)){
        throw new Exception("Test du mdpValide : Manque de sensibilité à la casse"
            . " -Mot de passe de l'utilisateur : {$pss}"
            . " -Mot de passe donné : {$fpss}");
    }
    OK();

    //setNom
    $nom = 'Nomtest';
    $utilisatest->setNom($nom);
    if($utilisatest->getNom()!=$nom){
        throw new Exception("Test de setNom :\n"
        ." -Nom de l'utilisateur : {$utilisatest->getNom()}"
        ." - Attendu : $nom");
    }elseif($utilisatest->getNom()=='nomtest'){
        throw new Exception("Test de setNom : absence d'attention à la casse");
    }

    //setMail
    if($utilisatest->getEmail()!=$email){
        throw new Exception("Test de setMail :\n"
        ." -Login de l'utilisateur : {$utilisatest->getEmail()}"
        ." - Attendu : $email");
    }elseif($utilisatest->getEmail()=='Emailtest@gmail.com'){
        throw new Exception("Test de setMail : absence d'attention à la casse"
        . " -Email de l'utilisateur : {$utilisatest->getEmail()}"
        . " -Email donné : Emailtest@gmail.com");
    }


    /////////////// CREATE + READ ////////////////
    print('Test create() et read() : ');
    
    //Création d'un utilisateur
    $utisilateur= new Utilisateur($login,$email,$numtel);
    try{
        $utisilateur->create();
        throw new Exception("Création d'un utilisateur sans mot de passe");
    }catch(Exception $e){
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
    OK();


    /////////////////// READ ////////////////////
    print('Test read() : ');

    //Read d'un utilisateur inexistant
    try {
        Utilisateur::read('boyoboy');
        KO("Erreur sur Utilisateur : Test read() : lecture d'un untilisateur inexistante devrait renvoyer une exception");
    } catch (Exception $e) {
        OK();
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


     ////////////////// DELETE ///////////////////
     print('Test delete() : ');

     $utisilateur->delete();
     try{
        Utilisateur::read($utisilateur->getLogin());
        KO("Erreur sur Utilisateur : Test read() : lecture d'un untilisateur delete devrait renvoyer une exception");
    } catch (Exception $e) {
        OK();
    }

} catch(Exception $e){
    KO("Erreur sur information : ".$e->getMessage());
}