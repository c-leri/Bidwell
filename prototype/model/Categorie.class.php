<?php

require_once __DIR__.'/DAO.class.php';

/**
 * Classe représentant le concept de Catégorie
 * "contenant" du modèle composite (Enchere est le "contenu")
 */
class Categorie {
  // stock les catégories qu'on a enregistrées ou lues dans la base
  static private array $instances = array();

  // Attributs
  private string $libelle;
  // booleen qui signifie si l'utilisateur est enregistré dans la base
  private bool $isInDB;
  private Categorie|null $categorieMere;

  // constructeur
  public function __construct(string $libelle, Categorie $categorieMere = null)
  {
    $this->libelle = $libelle;
    $this->isInDB = false;
    $this->categorieMere = $categorieMere;
  }

  // Getters
  public function getLibelle() : string {
    return $this->libelle;
  }

  public function getCategorieMere() : Categorie|null {
    return $this->categorieMere;
  }

  // Setters
  public function setCategorieMere(Categorie|null $categorieMere) :void {
    $this->categorieMere = $categorieMere;
  }

  // Autres méthodes

  /**
   * Vérifie si la catégorie est enregistrée dans la bd
   */
  public function isInDB() : bool {
    return $this->isInDB;
  }

  /**
   * Synchronise la catégorie avec ses valeurs en bd
   */
  public function sync() : void {
    $new_this = Categorie::read($this->getLibelle());
    $this->categorieMere = $new_this->categorieMere;
  }

  /**
   * Fonction qui renvoie la catégorie par défaut 'Autre'
   * Wrapper de read() qui crée la catégorie si elle n'existe pas déjà
   */
  public static function getCategorieAutre() : Categorie {
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
    if ($this->categorieMere !== null) {
      $query = 'INSERT INTO Categorie(libelle, libelleMere) VALUES (?,?)';
      $data = [$this->libelle, $this->categorieMere->getLibelle()];
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

    $this->isInDB = true;
  }

  /////////////////// READ ////////////////////

  /**
   * Récupère une catégorie dans la bd à partir de son libelle
   * @throws Exception si on ne trouve pas la catégorie dans la bd ou si plusieurs catégories ont le même libelle dans la bd
   */
  public static function read(string $libelle) : Categorie {
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
    var_dump($row);
    if (isset($libelleMere)) {
      $categorie->categorieMere = Categorie::read($libelleMere);
    }

    $categorie->isInDB = true;

    return $categorie;
  }

  public static function readFromCategorieMere(Categorie $categorieMere) : array {
    // récupératoin du dao
    $dao = DAO::get();

    // préparation de la query
    $query = 'SELECT * FROM Categorie WHERE libelleMere = ?';
    $data = [$categorieMere->libelle];

    // récupération de la table de résultat
    $table = $dao->query($query, $data);

    $out = array();
    foreach ($table as $row) {
      // création d'un objet catégorie avec les informations de la bd
      $categorie = new Categorie($row['libelle']);

      // on lui attribut sa catégorie mère
      $categorie->categorieMere = $categorieMere;

      $categorie->isInDB = true;

      $out[] = $categorie;
    }

    return $out;
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
    if ($this->categorieMere !== null) {
      $query = 'UPDATE Categorie SET libelleMere = ? WHERE libelle = ?';
      $data = [$this->categorieMere->getLibelle(), $this->libelle];
    } else {
      $query = 'UPDATE Categorie SET libelleMere = ? WHERE libelle = ?';
      $data = [null, $this->libelle];
    }

    $nbLignesMod = $dao->exec($query, $data);

    // Vérification de la bonne exécution de la requête
    if ($nbLignesMod != 1) {
      throw new Exception("Update : Nombre de ligne modifiée != 1 lors de la modification de la catégorie $this->libelle");
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
    foreach (Categorie::readFromCategorieMere($this) as $categorieFille) {
      $categorieFille->categorieMere = null;
      if ($categorieFille->isInDB())
        $categorieFille->update();
    }
    foreach (Enchere::readFromCategorie($this) as $enchereFille) {
      $enchereFille->setCategorie(Categorie::getCategorieAutre());
      if ($enchereFille->isInDB())
        $enchereFille->update();
    }

    // récupération du dao
    $dao = DAO::get();

    // préparation du query
    $query = 'DELETE FROM Categorie WHERE libelle = ?';
    $data = [$this->libelle];

    $dao->exec($query, $data);

    $this->isInDB = false;
  }
}
