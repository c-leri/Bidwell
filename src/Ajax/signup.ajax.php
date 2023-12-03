<?php
// Inclusion du modèle
use Bidwell\Model\Utilisateur;

require_once __DIR__.'/../../vendor/autoload.php';

$login = $_POST["login"] ?? '';
$password = $_POST["password"] ?? '';
$email = $_POST["email"] ?? '';
$phone = $_POST["phone"] ?? '';

//Vérification si utilisateur déjà créé avec un login, un email et un numero de téléphone pas déjà utilisé et si ce n'est pas le cas, on crée l'utilisateur
if(isset($login) && isset($email)&&isset($phone)){
    try{
        $reponse = new stdClass();
        $utilisateur = new Utilisateur($login,$email,$phone);
        //Si l'utilisateur n'est pas déjà dans la base, on le crée
        if(!$utilisateur->isAttributeInDB("login")&& !$utilisateur->isAttributeInDB("email")&&!$utilisateur->isAttributeInDB("numeroTelephone")){
            $utilisateur->setPassword($password);
            $utilisateur->create();
            $reponse->sucess = 1;
            session_start();
            $_SESSION['login'] = $login;
        }else{
           $reponse->sucess =0;
            //Sinon, on signale son erreur
            if($utilisateur->isAttributeInDB("login")){
                $reponse->username =1;
                $reponse->usernameerrormsg="Le nom d'utilisateur ".$login." est déjà utilisé";
            }else{
                $reponse->username=0;
            }
            if($utilisateur->isAttributeInDB("email")){
                $reponse->email=1;
                $reponse->emailerrormsg="L'adresse mail ".$email." est déjà utilisée";
            }else{
                $reponse->email=0;
            }
            if($utilisateur->isAttributeInDB("numeroTelephone")){
                $reponse->tel=1;
                $reponse->telerrormsg="Le numéro de téléphone ".$phone." est déjà utilisé";
            }else{
                $reponse->tel = 0;
            }
        }
        $json = json_encode($reponse);
        echo $json;
    }catch(Exception $e){
        echo json_encode($e);
    }
}