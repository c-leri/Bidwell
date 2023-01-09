<?php

require_once __DIR__.'/Component.class.php';

/**
 * Classe représentant le concept de Catégorie
 * "contenant" du modèle composite (Enchere est le "contenu")
 */
class Categorie extends Component {
  // Attributs
  private int $id;                        // identifiant unique à chaque catégorie, = -1 tant que la catégorie n'est pas dans la bd 
  private string $libelle;
  // Fils du modèle composite
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

  // Autres méthodes

  /**
   * Vérifie si la catégorie est enregistrée dans la bd
   */
  public function isInDB() : bool {
    return $this->id == -1;
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
    $query = 'INSERT INTO Categorie(libelle, idMere) VALUES (?, ?)';
    $data = [$this->libelle, $this->getParent()];

    // récupération du résultat de l'insertion 
    $r = $dao->exec($query, $data);

    // si on n'a pas exactement une ligne insérée, throw une exception
    if ($r != 1) {
      throw new Exception("L'insertion de l'enchère a échoué");
    }

    // on récupère l'id de l'enchère dans la bd
    $id = (int) $dao->lastInsertId();
    $this->id = $id;
  }

  /////////////////// READ ////////////////////

  /**
   * Récupère une catégorie dans la bd à partir de son id
   * @throws Exception si on ne trouve pas la catégorie dans la bd ou si plusieurs catégories ont le même id dans la bd
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

    // throw une exception si on trouve plusieurs catégories
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

    // on attribue l'id de la catégorie
    $categorie->id = $id;

    return $categorie;
  }

  ////////////////// UPDATE ///////////////////

  /**
   * Met à jour les valeurs de la catégorie dans la bd
   * @throws Exception si la catégorie n'existe pas dans la bd ou si le nombre de lignes modifiées != 1
   */
  public function update() : void {
    if (!$this->isInDB()) {
      throw new Exception("Update : La catégorie n'existe pas dans la bd");
    }

    // récupération du dao
    $dao = DAO::get();

    // si la catégorie a un parent (ce n'est pas la catégorie racine), on l'inclut dans l'update
    if ($this->getParent() !== null) {
      $query = 'UPDATE Categorie SET libelle = ?, idMere = ? WHERE id = ?';
      $data = [$this->libelle, $this->getParent()->getId(), $this->id];
    } else {
      $query = 'UPDATE Categorie SET libelle = ? WHERE id = ?';
      $data = [$this->libelle, $this->id];
    }

    $nbLignesMod = $dao->exec($query, $data);

    // Vérification de la bonne exécution de la requête
    if ($nbLignesMod != 1) {
      throw new Exception("Update : Nombre de ligne modifiée != 1 lors de la modification de la catégorie $this->id");
    }

    // on update récursivement toutes les catégories filles pour que leur idMere soit à jour dans la bd
    foreach ($this->children as $child) {
      if ($child->get_class() == "Categorie") {
        $child->update();
      }
    }
  }

  ////////////////// DELETE ///////////////////

  /**
   * Supprime la catégorie de la bd
   * @throws Exception si la catégorie n'existe pas dans la bd
   */
  public function delete() {
    if (!$this->isInDB()) {
      throw new Exception("Delete : La catégorie n'existe pas dans la bd");
    }

    // récupération du dao
    $dao = DAO::get();

    // préparation du query
    $query = 'DELETE FROM Categorie WHERE id = ?';
    $data = [$this->id];

    $dao->exec($query, $data);

    // on change l'id de la catégorie pour signifier qu'elle n'est plus dans la bd
    $this->id = -1;
  }
}
