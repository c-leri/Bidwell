<?php

require_once __DIR__.'/Component.class.php';
require_once __DIR__.'/DAO.class.php';

/**
 * Classe représentant le concept de Catégorie
 * "contenant" du modèle composite (Enchere est le "contenu")
 */
class Categorie extends Component {
  // stock les catégories qu'on a enregistrées ou lues dans la base
  static private array $instances = array();

  // Attributs
  private int $id;                        // identifiant unique à chaque catégorie, = -1 tant que la catégorie n'est pas dans la bd 
  private string $libelle;
  // Fils du modèle composite
  protected array $children;

  // constructeur
  public function __construct(string $libelle, Categorie $categorieMere = null)
  {
    $this->id = -1;
    $this->libelle = $libelle;
    $this->children = array();
    $this->setCategorieMere($categorieMere);
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
    $this->children[($component instanceof Categorie) ? 'c'.$component->getId() : 'e'.$component->getId()] = $component;
    $component->setCategorieMere($this);
  }

  public function remove(Component $component) : void {
    unset($this->children[($component instanceof Categorie) ? 'c'.$component->getId() : 'e'.$component->getId()]);
    $component->setCategorieMere(null);
  }

  // Autres méthodes

  /**
   * Vérifie si la catégorie est enregistrée dans la bd
   */
  public function isInDB() : bool {
    return $this->id != -1;
  }

  /**
   * Syncrhonise la catégorie avec ses valeurs en bd
   */
  public function sync() : void {
    $new_this = Categorie::read($this->getId(), true);
    $this->libelle = $new_this->libelle;
    $this->children = $new_this->children;
    ($new_this->getIdCategorieMere() !== null) ? $new_this->getCategorieMere()->add($this) : $this->setCategorieMere(null);
  }

  /////////////////////////////////////////////
  //                  CRUD                   //
  /////////////////////////////////////////////

  ////////////////// CREATE ///////////////////

  /**
   * Enregistre la catégorie dans la bd
   * @throws Exception si la catégorie est déjà dans la bd ou si l'insertion échoue
   */
  public function create() : void {
    if ($this->isInDB()) {
      throw new Exception("La categorie $this->id est déjà dans la bd");
    }

    // récupération du dao
    $dao = DAO::get();

    // si la catégorie a un parent (ce n'est pas la catégorie racine), on l'inclut dans le create
    if ($this->getIdCategorieMere() !== null) {
      $query = 'INSERT INTO Categorie(libelle, idMere) VALUES (?,?)';
      $data = [$this->libelle, $this->getIdCategorieMere()];
    } else {
      $query = 'INSERT INTO Categorie(libelle) VALUES (?)';
      $data = [$this->libelle];
    }

    // récupération du résultat de l'insertion 
    $r = $dao->exec($query, $data);

    // si on n'a pas exactement une ligne insérée, throw une exception
    if ($r != 1) {
      throw new Exception("L'insertion de l'enchère a échoué");
    }

    // on récupère l'id de l'enchère dans la bd
    $id = (int) $dao->lastInsertId();
    $this->id = $id;

    // maintenant que la catégorie est dans la base et à un id,
    // on peut la rajouter en fille de sa mère
    $this->getCategorieMere()?->add($this);

    // on rajoute aussi la catégorie dans la liste d'instances de Categorie
    Categorie::$instances[$id] = $this;
  }

  /////////////////// READ ////////////////////

  /**
   * Récupère une catégorie dans la bd à partir de son id
   * @param forceDansBD spécifie si on veut obligatoirement lire dans la bd et pas dans le tableau d'instances, faux par défaut
   * @throws Exception si on ne trouve pas la catégorie dans la bd ou si plusieurs catégories ont le même id dans la bd
   */
  public static function read(int $id, bool $forceDansBD = false) : Categorie {
    // si la catégorie recherchée est déjà dans le tableau d'instance on la renvoie
    if (isset(Categorie::$instances[$id]) && !$forceDansBD) {
      $categorie = Categorie::$instances[$id];
    }
    // sinon on la lit dans la bd
    else {
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

      // création d'un objet catégorie avec les informations de la bd
      $categorie = new Categorie($row['libelle']);

      // on attribue l'id de la catégorie
      $categorie->id = $id;

      // on read la catégorie mère si elle existe
      $idMere = $row['idMere'];
      if (isset($idMere)) {
        Categorie::read($idMere)->add($categorie);
      }

      // pour les catégories filles de la catégorie
      $query = 'SELECT * FROM Categorie WHERE idMere = ?';
      $data = [$id];

      $table = $dao->query($query, $data);

      for ($i = 0; $i < count($table); $i++) {
        $categorie->add(Categorie::read($table[$i]['id']));
      }

      // pour les encheres ayant comme catégorie $categorie
      $query = 'SELECT * FROM Enchere WHERE idCategorie = ?';
      $data = [$id];

      $table = $dao->query($query, $data);

      for ($i = 0; $i < count($table); $i++) {
        $categorie->add(Enchere::read($table[$i]['id']));
      }
    }

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

    // si la catégorie a un parent (ce n'est pas la catégorie racine) on passe son id en paramètre
    if ($this->getIdCategorieMere() !== null) {
      $query = 'UPDATE Categorie SET libelle = ?, idMere = ? WHERE id = ?';
      $data = [$this->libelle, $this->getIdCategorieMere(), $this->id];
    } else {
      $query = 'UPDATE Categorie SET libelle = ?, idMere = ? WHERE id = ?';
      $data = [$this->libelle, null, $this->id];
    }

    $nbLignesMod = $dao->exec($query, $data);

    // Vérification de la bonne exécution de la requête
    if ($nbLignesMod != 1) {
      throw new Exception("Update : Nombre de ligne modifiée != 1 lors de la modification de la catégorie $this->id");
    }

    // on update récursivement toutes les catégories filles pour que leur idMere soit à jour dans la bd
    foreach ($this->children as $child) {
      if ($child instanceof Categorie) {
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

    // on retire la catégorie du tableau d'instance
    unset(Categorie::$instances[$this->getId()]);

    // on enleve le fait que cette catégorie est mère dans tous ses fils
    // AVANT de faire la suppression dans la bd
    foreach ($this->children as $child) {
      $child->setCategorieMere(null);
      $child->update();
    }

    // récupération du dao
    $dao = DAO::get();

    // préparation du query
    $query = 'DELETE FROM Categorie WHERE id = ?';
    $data = [$this->id];

    $dao->exec($query, $data);

    // on retire le fait que cette catégorie est fille dans sa mère
    $this->getCategorieMere()?->remove($this);

    // on change l'id de la catégorie pour signifier qu'elle n'est plus dans la bd
    $this->id = -1;
  }
}
