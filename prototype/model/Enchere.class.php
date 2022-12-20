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
  private double $prixDepart;                   // prix auquel commence l'enchère, utilisé dans les calculs d'augmentation du prix 
  private double $prixRetrait;                  // prix de fin de l'enchère si personne n'enchérit
  private double|null $prixDerniereEnchere;     // prix auquel la dernière enchère a été posée, null jusuqu'à la première enchère
  private Participation|null $derniereEnchere;  // dernière enchère, null jusqu'à la première enchère
  private $participations = array()             // liste des participations sur cette enchère
  private $images = array();                    // liste des noms des fichers contenant les images
  private string $description;                  // nom du fichier contenant la description

  // constructeur
  public function __construct(string $libelle, DateTime $dateDebut, double $prixDepart, double $prixRetrait, string $imagePrincipale, string $description) {
    $this->id = -1;
    $this->libelle = $libelle;
    $this->dateDebut = $dateDebut; 
    $this->prixDepart = $prixDepart;
    $this->prixRetrait = $prixRetrait;
    $this->images[0] = $image;
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

  public function getPrixDepart() : double {
    return $this->prixDepart;
  }

  public function getPrixRetrait() : double {
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

  public function getDescriptionURL : string {
    return $this::ADRESSE_DESCRIPTIONS . $this->description;
  }

  // Setters
  public function setLibelle(string $libelle) : void {
    $this->libelle = $libelle;
  }

  public function setDescription(string $description) : void {
    $this->description = description;
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

  public function create() {
    $dao = DAO::get();

  }

  /////////////////// READ ////////////////////
  
  public static function read(int $id) : Enchere {
    $dao = DAO::get();

    $query = 'SELECT * FROM Enchere WHERE id = ?';
    $data = [$id];

    $table = $dao->query($query, $data);

    if (count($table) == 0) {
      throw new Exception("Enchere $id non trouvée");
    }

    if (count($table) > 1) {
      throw new Exception("Enchere $id existe en {count($table)} exemplaires");
    }

    $row = $table[0];

    $enchere = new Enchere($row['libelle'], $row['dateDebut'], $row[''])
  }
}
