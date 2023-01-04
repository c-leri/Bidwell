<?php

require_once __DIR__.'/Component.class.php';

/**
 * Classe représentant le concept de Catégorie
 * "contenant" du pattern composite (Enchere est le "contenu")
 */
class Categorie extends Component {
  // Attributs
  private int $id;                        // identifiant unique à chaque catégorie, = -1 tant que la catégorie n'est pas dans la bd 
  private string $libelle;
  // Fils du pattern composite
  protected SplObjectStorage $children;

  // constructeur
  public function __construct(string $libelle)
  {
    $this->id = -1;
    $this->libelle = $libelle;
    $this->children = new SplObjectStorage;
  }

  // Getters
  public function getId() : int {
    return $this->id;
  }

  public function getLibelle() : string {
    return $this->libelle;
  }

  // Setters
  public function setLibelle(string $libelle) : void {
    $this->libelle = $libelle;
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
