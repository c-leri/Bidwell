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
  private string $libelle;
  // Fils du modèle composite
  protected array $children;

  // constructeur
  public function __construct(string $libelle, Categorie $categorieMere = null)
  {
    $this->libelle = $libelle;
    $this->children = array();
    if (isset($categorieMere)) {
      $categorieMere->add($this);
    } else {
      $this->setLibelleCategorieMere(null);
    }
  }

  // Getters
  public function getId() : string {
    return $this->libelle;
  }

  public function getLibelle() : string {
    return $this->libelle;
  }

  // gestion des fils
  public function add(Component $component) : void {
    $this->children[$component->getId()] = $component;
    $component->setCategorieMere($this);
  }

  public function remove(Component $component) : void {
    unset($this->children[$component->getId()]);
    $component->setLibelleCategorieMere(null);
  }

  // Autres méthodes

  /**
   * Vérifie si la catégorie est enregistrée dans la bd
   */
  public function isInDB() : bool {
    // Récupération de la classe DAO
    $dao = DAO::get();

    // Initialisation de la requête et du tableau de valeurs
    $requete = 'SELECT * FROM Categorie WHERE libelle = ?';
    $valeurs = [$this->libelle];

    // Exécution de la requête
    $table = $dao->query($requete, $valeurs);

    return count($table) != 0;
  }

  /**
   * Synchronise la catégorie avec ses valeurs en bd
   */
  public function sync() : void {
    $new_this = Categorie::read($this->getLibelle(), true);
    $this->children = $new_this->children;
    ($new_this->getLibelleCategorieMere() !== null) ? $new_this->getCategorieMere()->add($this) : $this->setLibelleCategorieMere(null);
  }

  /**
   * Fonction qui renvoie la catégorie par défaut 'Autre'
   * Wrapper de read() qui crée la catégorie si elle n'existe pas déjà
   */
  public function getCategorieAutre() : Categorie {
    try {
      return Categorie::read('Autre');
    } catch (Exception $e) {
      $out = new Categorie('Autre');
      $out->create();
      return $out;
    }
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
      throw new Exception("La categorie $this->libelle est déjà dans la bd");
    }

    // récupération du dao
    $dao = DAO::get();

    // si la catégorie a un parent (ce n'est pas la catégorie racine), on l'inclut dans le create
    if ($this->getLibelleCategorieMere() !== null) {
      $query = 'INSERT INTO Categorie(libelle, libelleMere) VALUES (?,?)';
      $data = [$this->libelle, $this->getLibelleCategorieMere()];
    } else {
      $query = 'INSERT INTO Categorie(libelle) VALUES (?)';
      $data = [$this->libelle];
    }

    // récupération du résultat de l'insertion 
    $r = $dao->exec($query, $data);

    // si on n'a pas exactement une ligne insérée, throw une exception
    if ($r != 1) {
      throw new Exception("L'insertion de l'enchère $this->libelle a échoué");
    }

    // on rajoute la catégorie dans la liste d'instances de Categorie
    Categorie::$instances[$this->libelle] = $this;
  }

  /////////////////// READ ////////////////////

  /**
   * Récupère une catégorie dans la bd à partir de son libelle
   * @param forceDansBD spécifie si on veut obligatoirement lire dans la bd et pas dans le tableau d'instances, faux par défaut
   * @throws Exception si on ne trouve pas la catégorie dans la bd ou si plusieurs catégories ont le même libelle dans la bd
   */
  public static function read(string $libelle, bool $forceDansBD = false) : Categorie {
    // si la catégorie recherchée est déjà dans le tableau d'instance on la renvoie
    if (isset(Categorie::$instances[$libelle]) && !$forceDansBD) {
      $categorie = Categorie::$instances[$libelle];
    }
    // sinon on la lit dans la bd
    else {
      // récupératoin du dao
      $dao = DAO::get();

      // préparation de la query
      $query = 'SELECT * FROM Categorie WHERE libelle = ?';
      $data = [$libelle];

      // récupération de la table de résultat
      $table = $dao->query($query, $data);

      // throw une exception si on ne trouve pas la catégorie
      if (count($table) == 0) {
        throw new Exception("Catégorie $libelle non trouvée");
      }

      // throw une exception si on trouve plusieurs catégories
      if (count($table) > 1) {
        throw new Exception("Catégorie $libelle existe en ".count($table).' exemplaires');
      }

      $row = $table[0];

      // création d'un objet catégorie avec les informations de la bd
      $categorie = new Categorie($row['libelle']);

      // on read la catégorie mère si elle existe
      $libelleMere = $row['libelleMere'];
      if (isset($libelleMere)) {
        Categorie::read($libelleMere)->add($categorie);
      }

      // pour les catégories filles de la catégorie
      $query = 'SELECT * FROM Categorie WHERE libelleMere = ?';
      $data = [$libelle];

      $table = $dao->query($query, $data);

      for ($i = 0; $i < count($table); $i++) {
        $categorie->add(Categorie::read($table[$i]['libelle']));
      }

      // pour les encheres ayant comme catégorie $categorie
      $query = 'SELECT * FROM Enchere WHERE libelleCategorie = ?';
      $data = [$libelle];

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
      throw new Exception("Update : La catégorie $this->libelle n'existe pas dans la bd");
    }

    // récupération du dao
    $dao = DAO::get();

    // si la catégorie a un parent (ce n'est pas la catégorie racine) on passe son libelle en paramètre
    if ($this->getLibelleCategorieMere() !== null) {
      $query = 'UPDATE Categorie SET libelleMere = ? WHERE libelle = ?';
      $data = [$this->getLibelleCategorieMere(), $this->libelle];
    } else {
      $query = 'UPDATE Categorie SET libelleMere = ? WHERE libelle = ?';
      $data = [null, $this->libelle];
    }

    $nbLignesMod = $dao->exec($query, $data);

    // Vérification de la bonne exécution de la requête
    if ($nbLignesMod != 1) {
      throw new Exception("Update : Nombre de ligne modifiée != 1 lors de la modification de la catégorie $this->libelle");
    }

    // on update récursivement toutes les catégories filles pour que leur libelleMere soit à jour dans la bd
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
      throw new Exception("Delete : La catégorie $this->libelle n'existe pas dans la bd");
    }

    // on retire la catégorie du tableau d'instance
    unset(Categorie::$instances[$this->getLibelle()]);

    // on enleve le fait que cette catégorie est mère dans tous ses fils
    // AVANT de faire la suppression dans la bd
    // les enchère ont alors comme catégorie 'Autre'
    foreach ($this->children as $child) {
      $child->setCategorieMere(($child instanceof Enchere) ? Categorie::getCategorieAutre() : null);
      $child->update();
    }

    // récupération du dao
    $dao = DAO::get();

    // préparation du query
    $query = 'DELETE FROM Categorie WHERE libelle = ?';
    $data = [$this->libelle];

    $dao->exec($query, $data);

    // on retire le fait que cette catégorie est fille dans sa mère
    $this->getCategorieMere()?->remove($this);
  }
}
