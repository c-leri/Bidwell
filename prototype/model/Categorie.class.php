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
  public function __construct(string $libelle, Categorie $categorieMere = null)
  {
    $this->id = -1;
    $this->libelle = $libelle;
    $this->children = new SplObjectStorage;
    $this->setParent($categorieMere);
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

  /////////////////////////////////////////////
  //                  CRUD                   //
  /////////////////////////////////////////////

  ////////////////// CREATE ///////////////////

  /**
   * Enregistre la catégorie dans la bd
   * @throws Exception si l'insertion échoue
   */
  public function create() : void {
    // récupération du dao
    $dao = DAO::get();

    // préparation de la query
    $query = 'INSERT INTO Catehorie(libelle, idMere) VALUES (?, ?)';
    $data = [$this->libelle, $this->getParent()];

    // récupération du résultat de l'insertion 
    $r = $dao->exec($query, $data);

    // si on n'a pas exactement une ligne insérée, throw une exception
    if ($r != 1) {
      throw new Exception("L'insertion de l'enchère a échoué");
    }
  }

  /////////////////// READ ////////////////////

  /**
   * Récupère une catégorie dans la bd à partir de son id
   * @throws Exception si on ne trouve pas la catégorie dans la bd ou si plusieurs catégories on le même id dans la bd
   */
  public static function read(int $id) : Categorie {
    // récupératoin du dao
    $dao = DAO::get();

    // préparation de la query
    $query = 'SELECT * FROM Categorie WHERE id = ?';
    $data = [$id];

    // récupération de la table de résultat
    $table = $dao->query($query, $data);

    // throw une exception si on ne trouve pas la catégorie
    if (count($table) == 0) {
      throw new Exception("Catégorie $id non trouvée");
    }

    // throw une exception si on trouve plusieurs catégorie
    if (count($table) > 1) {
      throw new Exception("Catégorie $id existe en ".count($table).' exemplaires');
    }

    $row = $table[0];

    $idMere = $row['idMere'];

    // création d'un objet catégorie avec les informations de la bd
    if (isset($idMere))
      $categorie = new Categorie($row['libelle'], Categorie::read($idMere));
    else
      $categorie = new Categorie($row['libelle']);

    // on set l'id de la catégorie
    $categorie->id = $id;

    return $categorie;
  }
}
