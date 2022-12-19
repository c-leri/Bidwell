<?php

require_once __DIR__.'/CompositeComponent.class.php';

class Categorie extends Component {
    protected SplObjectStorage $children;

    // constructeur
    public function __construct()
    {
        $this->children = new SplObjectStorage;
    }

    // gestion des fils
    public function add(Component $component) : void {
        $this->children->attach($component);
        $component->setParent($this);
    }

    public function remove(Component $component) : void {
        $this->children->detach($component);
        $component->setParent(null);
    }
}