<?php

/**
 * Classe abstraite du modèle composite
 * Utilisée par les classes Categorie (contenant) et Enchere (contenu)
 */
abstract class Component {
    // le libelle de la categorie mère du component
    // null si ce component est le component racine 
    protected ?string $libelleCategorieMere;

    // Méthodes pour la gestion de la catégorie mère
    public function getLibelleCategorieMere() : ?string {
        return (isset($this->libelleCategorieMere)) ? $this->libelleCategorieMere : null;
    }

    public function getCategorieMere() : ?Categorie {
        return (isset($this->libelleCategorieMere)) ? Categorie::read($this->libelleCategorieMere) : null;
    }

    public function setLibelleCategorieMere(?string $libelleCategorie): void {
        $this->libelleCategorieMere = $libelleCategorie;
    }

    public function setCategorieMere(?Categorie $categorie): void {
        $this->libelleCategorieMere = (isset($categorie)) ? $categorie->getLibelle() : null;
    }

    // Méthodes pour la gestion des fils
    public function add(Component $component): void { }
    public function remove(Component $component): void { }

    // Méthodes communes aux Categories et aux Encheres
    public function getId() { }
}
