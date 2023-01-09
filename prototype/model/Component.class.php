<?php

/**
 * Classe abstraite du modèle composite
 * Utilisée par les classes Categorie (contenant) et Enchere (contenu)
 */
abstract class Component {
    // le parent du component
    // null si ce component est le component racine 
    protected Component|null $parent;

    // Méthodes pour la gestion du parent 'parent'
    public function getParent() : Component|null {
        return (isset($this->parent)) ? $this->parent : null;
    }

    public function setParent(?Component $parent): void {
        $this->parent = $parent;
    }

    // Méthodes pour la gestion des fils
    public function add(Component $component): void { }
    public function remove(Component $component): void { }

    // Méthodes communes aux Categories et aux Encheres
    public function getId() { }
}
