<?php
// 
// Inclusion du framework
include_once(__DIR__."/../framework/View.class.php");



//var_dump($_POST);
// Récupérations des données du formulaire
$login=$_POST['login'] ?? '';
$password = $_POST['password'] ?? '';
$submit = $_POST['submit'] ?? '';

// Ouverture de la session
session_start();

// Si la session connait le login alors on s'est déjà connecté, on va sur la page principale
if(isset($_SESSION['login'])){
// Recupère le login pour la vue
$login=$_SESSION['login'];
// Charge la page de l'application et termine
$view = new View();
$view->display("/main.view.php");
//C'est terminé !
exit(0);
}
$view = new View();
// On n'est pas déjà connecté, on examine ce qu'on doit faire
switch($submit){

case 'login':
// Vérification du login et mot de passe
if(1 || ($login=="martin")&&($password=='1234')){
$_SESSION['login']=$login;
// Charge le page de l'application
$view->display("main.view.php");
}else {
// Retourne sur le login
$view->display("connect.view.php");
}
break;
case 'new':
// Fonctionalité non implementée
$view->display("main.view.php");
break;

default:
// Envoit sur le login
$view->display("connect.view.php");

}
// Charge la vue
$view->display("connect.view.php");
?>