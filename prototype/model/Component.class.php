<?php

/**
 * Classe abstraite du pattern composite
 * Utilisée par les classes Categorie (contenant) et Enchere (contenu)
 */
abstract class Component {
    // le parent du component
    // null si ce component est le component racine 
    protected Component|null $parent;

    // Méthodes pour la gestion du parent parent
    public function getParent() : Component {
        return $this->parent;
    }

    public function setParent(?Component $parent) {
        $this->parent = $parent;
    }

    // Méthodes pour la gestions des fils
    public function add(Component $component): void { }
    public function remove(Component $component): void { }
}
