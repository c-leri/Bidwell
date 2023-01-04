<?php

require_once __DIR__.'/Enchere.class.php';

/**
 * La classe Participation modélise la participation d'un utilisateur à des enchères.
 * Il s'agit d'une classe assiociation entre la classe Utilisateur et la classe Enchere. Elle permet à des enchères d'avoir autant de participations qu'elle veut
 * et à un utilisateur d'avoir autant de participation à une enchère qu'il veut, mais aussi de vérifier que l'utilisateur a enchéri ou non.
 * 
 *   - Une participation n'est liée qu'à un utilisateur et une enchère
 *   - Une enchère et un utilisateur peuvent avoir un nombre infini de participation
 */
class Participation {
    // Attributs
    private Enchere $enchere;
    private Utilisateur $utilisateur;
    private int $nbEncheres;
    private float|null $montantDerniereEnchere;


    // Constructeur
    public function __construct(Enchere $enchere, Utilisateur $utilisateur) {
        $this->enchere = $enchere;
        $this->utilisateur = $utilisateur;
        $this->nbEncheres = 0;
    }

    // Getters
    public function getEnchere() : Enchere {
        return $this->enchere;
    }

    public function getUtilisateur() : Utilisateur {
        return $this->utilisateur;
    }


    // Méthode
    /**
     * Incrémente le compteur d'enchères posées par $utilisateur sur $enchere
     */
    public function aEncheri() {
        $this->nbEncheres++;
    }
}
?>

