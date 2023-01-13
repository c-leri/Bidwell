<?php

// Déclaration d'un tableau out qui contient tout les messages produits par l'API
$out = [];

// Ouverture de la session
session_start();

if (!isset($_POST['idEnchere'])) {
    $out['error'] = "ID enchère manquant.";
} else if (!isset($_SESSION['login'])) {
    $out['error'] = "Pas d'utilisateur connecté.";
} else {
    try {
        $enchere = Enchere::read($_POST['idEnchere']);
        $utililisateur = Utilisateur::read($_SESSION['login']);
        try {
            $participation = Participation::read($enchere, $utililisateur);
        } catch (Exception $e) {
            $participation = new Participation($enchere, $utililisateur);
            $participation->create();
        }
        $participation->encherir();
    } catch (Exception $e) {
        $out['error'] = $e->getMessage();
    }
}
