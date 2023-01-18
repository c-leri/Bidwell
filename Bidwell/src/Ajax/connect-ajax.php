<?php
// Inclusion du modèle
use Bidwell\Model\Utilisateur;

require_once __DIR__.'/../../vendor/autoload.php';

$login = $_POST["login"] ?? '';
$password = $_POST["password"] ?? '';
if(isset($login)&&isset($password)){
    $reponse = new stdClass();
    if(Utilisateur::connectionValide($login,$password)){
        session_start();
        $_SESSION['login']=$login;
        $reponse->sucess =1;
    }else{
        $reponse->sucess =0;
        try{
           $utilisateur = Utilisateur::read($login);
           //Ancune Exception levée, l'utilisateur existe, le problème est donc un mdp incorrecte, on le signale à l'utilisateur
            $reponse->loginerror =0;
            $reponse->passworderror =1;
            $reponse->passworderrormsg="Le mot de passe est incorrect";
        }catch(Exception){
            //Exception levée, l'utilisateur n'existe pas, le problème vient de là, on signale à l'utilisateur que l'utilisateur login n'existe pas
            $reponse->loginerror =1;
            $reponse->passworderror =0;
            $reponse->loginerrormsg="Le nom d'utilisateur ".$login ." n'existe pas";
        }
    }
    $json = json_encode($reponse);
    echo $json;
}