<?php

require_once __DIR__.'/CompositeComponent.class.php';
require_once __DIR__.'/DAO.class.php';

/**
 * Classe représentant la notion d'enchère
 * "contenu" du pattern composite (Categorie est le "contenant") 
 */
class Enchere extends Component {
  // constantes de classe
  public const DUREE = 3600;
  public const TAUX_AUGMENTATION = 1.05;

  private const ADRESSE_IMAGES = '../data/img/';
  private const ADRESSE_DESCRIPTIONS = '../data/desc/';

  // Attributs
  private int $id;                              // identifiant unique à chaque enchère, = -1 tant que l'enchère n'est pas enregistrée dans la bd
  private string $libelle;
  private DateTime $dateDebut;
  private float $prixDepart;                   // prix auquel commence l'enchère, utilisé dans les calculs d'augmentation du prix 
  private float $prixRetrait;                  // prix de fin de l'enchère si personne n'enchérit
  private Participation|null $derniereEnchere;  // dernière enchère, null jusqu'à la première enchère
  private $participations = array();            // liste des participations sur cette enchère
  private $images = array();                    // liste des noms des fichers contenant les images
  private string $description;                  // nom du fichier contenant la description

  // constructeur
  public function __construct(string $libelle, DateTime $dateDebut, float $prixDepart, float $prixRetrait, string $imagePrincipale, string $description) {
    $this->id = -1;
    $this->libelle = $libelle;
    $this->dateDebut = $dateDebut; 
    $this->prixDepart = $prixDepart;
    $this->prixRetrait = $prixRetrait;
    $this->images[0] = $imagePrincipale;
    $this->description = $description;
  }

  // Getters
  public function getId() : int {
    return $this->id;
  }

  public function getLibelle() : string {
    return $this->libelle;
  }

  public function getDateDebut() : DateTime {
    return $this->dateDebut;
  }

  public function getPrixDepart() : float {
    return $this->prixDepart;
  }

  public function getPrixRetrait() : float {
    return $this->prixRetrait;
  }

  /**
   * méthode pour récupérer l'image en case $id du tableau d'images
   * @throws Exception si la case $id de $images est vide
   */
  public function getImage(int $id) : string {
    if (!isset($this->images[$id])) throw new Exception("Pas d'image d'id $id.");
    return $this->images[$id];
  }

  public function getImageURL(int $id) : string {
    return $this::ADRESSE_IMAGES . $this->getImage($id);
  }

  public function getDescription() : string {
    return $this->description;
  }

  public function getDescriptionURL() : string {
    return $this::ADRESSE_DESCRIPTIONS . $this->description;
  }

  // Setters
  public function setDerniereEnchere(Participation $participation) : void {
    $this->derniereEnchere = $participation;
  }

  public function setLibelle(string $libelle) : void {
    $this->libelle = $libelle;
  }

  public function setDescription(string $description) : void {
    $this->description = $description;
  }

  // Gestion des images
  public function setImagePrincipale(string $image) : void {
    $this->images[0] = $image;
  }

  public function addImage(string $image) : void {
    $this->images[] = $image;
  }

  public function removeImage(int $id) : void {
    unset($this->images[$id]);
  }

  /////////////////////////////////////////////
  //                  CRUD                   //
  /////////////////////////////////////////////

  ////////////////// CREATE ///////////////////

  /**
   * Enregistre l'enchère dans la bd
   * @throws Exception si l'insertion échoue
   */
  public function create() : void {
    // récupération du dao
    $dao = DAO::get();

    // transforme le tableaux d'images en un string avec les images séparées par des espaces
    $imagesString = '';
    foreach ($this->images as $image) {
      $imagesString .= $image . ' '; 
    }

    // préparation de la query
    $query = 'INSERT INTO Enchere(libelle, dateDebut, prixDepart, prixRetrait, images, description) VALUES (?, ?, ?, ?, ?, ?)';
    $data = [$this->libelle, $this->dateDebut->getTimestamp(), $this->prixDepart, $this->prixRetrait, $imagesString, $this->description];

    // récupération du résultat de l'insertion 
    $r = $dao->exec($query, $data);

    // si on n'a pas exactement une ligne modifié, throw une exception
    if ($r != 1) {
      throw new Exception("L'insertion de l'enchère a échoué");
    }
  }

  /////////////////// READ ////////////////////

  /**
   * Récupère une enchère dans la bd à partir de son id
   * @throws Exception si on ne trouve pas l'enchère dans la bd ou si plusieurs enchères on le même id dans la bd
   */
  public static function read(int $id) : Enchere {
    // récupératoin du dao
    $dao = DAO::get();

    // préparation de la query
    $query = 'SELECT * FROM Enchere WHERE id = ?';
    $data = [$id];

    // récupération de la table de résultat
    $table = $dao->query($query, $data);

    // throw une exception si on ne trouve pas l'enchère
    if (count($table) == 0) {
      throw new Exception("Enchere $id non trouvée");
    }

    // throw une exception si on trouve plusieurs enchères
    if (count($table) > 1) {
      throw new Exception("Enchere $id existe en ".count($table).' exemplaires');
    }

    $row = $table[0];
    
    // split le contenu du string images de la bd en un tableaux de string contenant le nom des fichiers contenant les images
    $images = explode(' ', $row['images']);

    // on récupère le DateTime correspondant au timestamp stocké dans la bd
    $dateDebut = new DateTime();
    $dateDebut->setTimestamp($row['dateDebut']);

    // création d'un objet enchère avec les informations de la bd
    $enchere = new Enchere($row['libelle'], $dateDebut, $row['prixDepart'], $row['prixRetrait'], $images[0], $row['description']);

    // on set la derniereEnchere si elle est dans la bd
    if (isset($row['derniereEnchere']))
      $enchere->setDerniereEnchere($row['derniereEnchere']);

    // on ajoute les images restantes dans la liste de string
    unset($images[0]);
    foreach ($images as $image) {
      $enchere->addImage($image);
    }

    // on set l'id de l'enchère
    $enchere->id = $id;

    return $enchere;
  }

  ////////////////// UPDATE ///////////////////

  /**
   * Met à jour les valeurs de l'enchère dans la bd
   */ 
  public function update() : void {
    if ($this->id != -1 && $this->getParent()->getId() != -1) {
      // récupération du dao
      $dao = DAO::get();

      // transforme le tableaux d'images en un string avec les images séparées par des espaces
      $imagesString = '';
      foreach ($this->images as $image) {
        $imagesString .= $image . ' '; 
      }

      // si l'enchere a une derniere enchère, on l'inclut dans l'update
      if (isset($this->derniereEnchere)) {
        $query = 'UPDATE Enchere SET libelle = ?, idCategorie = ?, dateDebut = ?, prixDepart = ?, prixRetrait = ?, loginUtilisateurDerniereEnchere = ?, images = ?, description = ? WHERE id = ?';
        $data = [$this->libelle, $this->getParent()->getId(), $this->dateDebut->getTimestamp(), $this->prixDepart, $this->prixRetrait, $this->derniereEnchere->getUtilisateur()->getLogin(), $imagesString, $this->description, $this->id];
      } else {
        $query = 'UPDATE Enchere SET libelle = ?, idCategorie = ?, dateDebut = ?, prixDepart = ?, prixRetrait = ?, images = ?, description = ? WHERE id = ?';
        $data = [$this->libelle, $this->getParent()->getId(), $this->dateDebut->getTimestamp(), $this->prixDepart, $this->prixRetrait, $imagesString, $this->description, $this->id];
      }

      $dao->exec($query, $data);
    }
  }

  ////////////////// DELETE ///////////////////

  public function delete() {
    if ($this->id != -1) {
      // récupération du dao
      $dao = DAO::get();

      // préparation du query
      $query = 'DELETE FROM Enchere WHERE id = ?';
      $data = [$this->id];

      $dao->exec($query, $data);
    }
  }
}
