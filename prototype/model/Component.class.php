<?php

/**
 * Classe abstraite du modèle composite
 * Utilisée par les classes Categorie (contenant) et Enchere (contenu)
 */
abstract class Component {
    // l'id de la categorie mère du component
    // null si ce component est le component racine 
    protected int|null $idCategorieMere;

    // Méthodes pour la gestion de la catégorie mère
    public function getIdCategorieMere() : int|null {
        return (isset($this->idCategorieMere)) ? $this->idCategorieMere : null;
    }

    public function getCategorieMere() : Categorie|null {
        return (isset($this->idCategorieMere)) ? Categorie::read($this->idCategorieMere) : null;
    }

    public function setCategorieMere(?Categorie $categorie): void {
        $this->idCategorieMere = (isset($categorie)) ? $categorie->getId() : null;
    }

    // Méthodes pour la gestion des fils
    public function add(Component $component): void { }
    public function remove(Component $component): void { }

    // Méthodes communes aux Categories et aux Encheres
    public function getId() { }
}
