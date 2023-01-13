<?php
namespace Bidwell\Bidwell\Model;

use DateInterval;
use DateTime;
use Exception;

/**
 * Classe représentant la notion d'enchère
 * "contenu" du modèle composite (Categorie est le "contenant")
 */
class Enchere {
    // constantes de classe
    public const DUREE = 3600;
    public const TAUX_AUGMENTATION = 1.05;

    private const ADRESSE_IMAGES = '../data/img/';
    private const ADRESSE_DESCRIPTIONS = '../data/desc/';
    private const TEMPS_CONSERVATION = '5 years'; // temps de conservation des données dans la bd

    // Attributs
    private int $id;                              // identifiant unique à chaque enchère, = -1 tant que l'enchère n'est pas enregistrée dans la bd
    private Utilisateur $createur;
    private string $libelle;
    private DateTime $dateDebut;
    private float $prixDepart;                    // prix auquel commence l'enchère, utilisé dans les calculs d'augmentation du prix
    private float $prixHaut;                      // prix auquel (re)commence le décompte
    private float $prixRetrait;                   // prix de fin de l'enchère si personne n'enchérit
    private Participation|null $derniereEnchere;  // dernière enchère, null jusqu'à la première enchère
    private array $images = array();              // liste des noms des fichers contenant les images
    private string $description;                  // nom du fichier contenant la description
    private Categorie $categorie;

    /**
     * Constructeur
     * @throws Exception si la catégorie n'est pas dans la bd
     */
    public function __construct(Utilisateur $createur, string $libelle, DateTime $dateDebut, float $prixDepart, float $prixRetrait, string $imagePrincipale, string $description, Categorie $categorie) {
        $this->id = -1;
        $this->createur = $createur;
        $this->libelle = $libelle;
        $this->setDateDebut($dateDebut);
        $this->prixDepart = $prixDepart;
        $this->prixHaut = $prixDepart;
        $this->prixRetrait = $prixRetrait;
        $this->images[0] = $imagePrincipale;
        $this->description = $description;
        $this->categorie = $categorie;
    }

    // Getters
    public function getId() : int {
        return $this->id;
    }

    public function getCreateur() : Utilisateur {
        return $this->createur;
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

    public function getCategorie() : Categorie {
        return $this->categorie;
    }

    /**
     * @return DateTime correspondant à l'instant de la fin de l'enchère (datedebut + 1 heure)
     */
    private function getInstantFin() : DateTime {
        $instantFin = $this->dateDebut;
        $instantFin->add(DateInterval::createFromDateString('1 hour'));
        return $instantFin;
    }

    /**
     * Calcul le ratio représentant l'avancée temporelle de l'enchère entre le prix haut et le prix de retrait
     */
    private function getRatioTempsActuel() : int {
        $maintenant = new DateTime();
        $differenceMaintenantFin = (int) $maintenant->format('Uv') - (int) $this->getInstantFin()->format('Uv');
        return (isset($this->derniereEnchere))
            ? $differenceMaintenantFin / ((int) $this->derniereEnchere->getInstantDerniereEnchere()->format('Uv') - (int) $this->getInstantFin()->format('Uv'))
            : $differenceMaintenantFin / (int) DateInterval::createFromDateString('1 hour')->format('Uv');
    }

    /**
     * @return float le prix courant de l'enchère
     */
    public function getPrixCourant() : float {
        return $this->prixRetrait + $this->getRatioTempsActuel() * ($this->prixHaut - $this->prixRetrait);
    }

    public function getParticipations() : array {
        return Participation::readFromEnchere($this);
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

    public function setDateDebut(DateTime $date) : void {
        $currentDate = new DateTime();
        if ($date > $currentDate) {
            $this->dateDebut = $date;
        } else {
            throw new Exception($date->format('Y-m-d') . " est antérieure à la date courante : " . $currentDate->format('Y-m-d'));
        }
    }

    public function setCategorie(Categorie $categorie) : void {
        $this->categorie = $categorie;
    }

    // Gestion des images
    public function setImagePrincipale(string $image) : void {
        $this->images[0] = $image;
    }

    public function addImage(string $image) : void {
        $this->images[] = $image;
    }

    public function removeImage(int $id) : void {
        if (!isset($this->images[$id])) throw new Exception("Pas d'image d'id $id.");
        unset($this->images[$id]);
    }

    // Autres méthodes

    /**
     * Vérifie si l'enchère est enregistrée dans la bd
     */
    public function isInDB() : bool {
        return $this->id != -1;
    }

    /////////////////////////////////////////////
    //                  CRUD                   //
    /////////////////////////////////////////////

    ////////////////// CREATE ///////////////////

    /**
     * Enregistre l'enchère dans la bd
     * @throws Exception si l'enchère est déjà dans la bd,
     * si la catégorie de n'enchère est null ou si ell n'est pas dans la bd
     * ou si l'insertion échoue
     */
    public function create() : void {
        if ($this->id != -1) {
            throw new Exception("Create : L'enchère $this->id existe déjà dans la bd");
        }

        if (!$this->categorie->isInDB()) {
            throw new Exception("Create : La catégorie de l'enchère n'existe pas dans la bd");
        }

        // récupération du dao
        $dao = DAO::get();

        // transforme le tableau d'images en un string avec les images séparées par des espaces
        $imagesString = '';
        foreach ($this->images as $image) {
            $imagesString .= $image . ' ';
        }

        // variable correspondant à la date de fin de conservation de l'enchère dans la bd
        $dateFinConservation = new DateTime();
        $dateFinConservation->add(DateInterval::createFromDateString(Enchere::TEMPS_CONSERVATION));

        // préparation de la query
        $query = 'INSERT INTO Enchere(loginCreateur, libelle, dateDebut, prixDepart, prixRetrait, images, description, libelleCategorie, dateFinConservation) VALUES (?,?,?,?,?,?,?,?,?)';
        $data = [$this->createur->getLogin(), $this->libelle, $this->dateDebut->getTimestamp(), $this->prixDepart, $this->prixRetrait, $imagesString, $this->description, $this->categorie->getLibelle(), $dateFinConservation->getTimestamp()];

        // récupération du résultat de l'insertion
        $r = $dao->exec($query, $data);

        // si on n'a pas exactement une ligne insérée, throw une exception
        if ($r != 1) {
            throw new Exception("Create : L'insertion de l'enchère a échouée");
        }

        // on récupère l'id de l'enchère dans la bd
        $id = (int) $dao->lastInsertId();
        $this->id = $id;
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
            throw new Exception("Read : Enchere $id non trouvée");
        }

        // throw une exception si on trouve plusieurs enchères
        if (count($table) > 1) {
            throw new Exception("Read : Enchere $id existe en ".count($table).' exemplaires');
        }

        return Enchere::constructFromDB($table[0]);
    }

    public static function readFromCreateur(Utilisateur $createur) : array {
        // récupératoin du dao
        $dao = DAO::get();

        // préparation de la query
        $query = 'SELECT * FROM Enchere WHERE loginCreateur = ?';
        $data = [$createur->getLogin()];

        // récupération de la table de résultat
        $table = $dao->query($query, $data);

        $out = array();
        foreach($table as $row) {
            $out[] = Enchere::constructFromDB($row);
        }

        return $out;
    }

    public static function readFromCategorie(Categorie $categorie) : array {
        // récupératoin du dao
        $dao = DAO::get();

        // préparation de la query
        $query = 'SELECT * FROM Enchere WHERE libelleCategorie = ?';
        $data = [$categorie->getLibelle()];

        // récupération de la table de résultat
        $table = $dao->query($query, $data);

        $out = array();
        foreach($table as $row) {
            $out[] = Enchere::constructFromDB($row);
        }

        return $out;
    }

    public static function readLike(string $categorie, string $pattern, string $tri, string $ordre ,int $page, int $pageSize) : array {
        // récupératoin du dao
        $dao = DAO::get();

        $order = match ($tri) {
            'date' => 'dateDebut',
            'prix' => 'prixDepart',
            default => 'libelle',
        };

        $decalage = ($page - 1) * $pageSize;

        // préparation de la query
        if ($categorie == '') {
            $query = "SELECT * FROM Enchere WHERE libelle LIKE ? ORDER BY $order COLLATE NOCASE $ordre LIMIT ?, ?";
            $data = ['%' . $pattern . '%', $decalage, $pageSize];
        } else {
            $query = "SELECT * FROM Enchere WHERE libelleCategorie = ? AND libelle LIKE ? ORDER BY $order COLLATE NOCASE $ordre LIMIT ?, ?";
            $data = [$categorie, '%' . $pattern . '%', $decalage, $pageSize];
        }
        // récupération de la table de résultat
        $table = $dao->query($query, $data);

        $out = array();
        foreach($table as $row) {
            $out[] = Enchere::constructFromDB($row);
        }

        return $out;
    }

    private static function constructFromDB(array $row) : Enchere {
        // split le contenu du string images de la bd en un tableaux de string contenant le nom des fichiers contenant les images
        $images = explode(' ', $row['images']);

        // on retire les strings vides de $images
        while (($key = array_search("", $images)) !== false) {
            unset($images[$key]);
        }

        // on récupère le DateTime correspondant au timestamp stocké dans la bd
        $dateDebut = new DateTime();
        $dateDebut->setTimestamp($row['dateDebut']);

        // création d'un objet enchère avec les informations de la bd
        $enchere = new Enchere(Utilisateur::read($row['loginCreateur']), $row['libelle'], $dateDebut, $row['prixDepart'], $row['prixRetrait'], $images[0], $row['description'], Categorie::read($row['libelleCategorie']));

        // on set la derniereEnchere si elle est dans la bd
        if (isset($row['loginUtilisateurDerniereEnchere']) && $row['loginUtilisateurDerniereEnchere'] != '') {
            $enchere->derniereEnchere = Participation::read($enchere, Utilisateur::read($row['loginUtilisateurDerniereEnchere']));
        }

        // on ajoute les images restantes dans la liste de string
        unset($images[0]);
        foreach ($images as $image) {
            $enchere->addImage($image);
        }

        // on set l'id de l'enchère
        $enchere->id = $row['id'];

        return $enchere;
    }

    ////////////////// UPDATE ///////////////////

    /**
     * Met à jour les valeurs de l'enchère dans la bd
     * @throws Exception si l'enchère ou sa catégorie mère n'existent pas dans la bd ou si
     */
    public function update() : void {
        if (!$this->isInDB()) {
            throw new Exception("Update : L'enchère n'existe pas dans la bd");
        }

        if (!$this->categorie->isInDB()) {
            throw new Exception("Update : La catégorie mère de l'enchère $this->id n'existe pas dans la bd");
        }

        // récupération du dao
        $dao = DAO::get();

        // transforme le tableaux d'images en un string avec les images séparées par des espaces
        $imagesString = '';
        foreach ($this->images as $image) {
            $imagesString .= $image . ' ';
        }

        // si l'enchere a une derniere enchère, on l'inclut dans l'update
        if (isset($this->derniereEnchere)) {
            $query = 'UPDATE Enchere SET libelle = ?, libelleCategorie = ?, dateDebut = ?, prixDepart = ?, prixRetrait = ?, loginUtilisateurDerniereEnchere = ?, images = ?, description = ? WHERE id = ?';
            $data = [$this->libelle, $this->categorie->getLibelle(), $this->dateDebut->getTimestamp(), $this->prixDepart, $this->prixRetrait, $this->derniereEnchere->getUtilisateur()->getLogin(), $imagesString, $this->description, $this->id];
        } else {
            $query = 'UPDATE Enchere SET libelle = ?, libelleCategorie = ?, dateDebut = ?, prixDepart = ?, prixRetrait = ?, images = ?, description = ? WHERE id = ?';
            $data = [$this->libelle, $this->categorie->getLibelle(), $this->dateDebut->getTimestamp(), $this->prixDepart, $this->prixRetrait, $imagesString, $this->description, $this->id];
        }

        $nbLignesMod = $dao->exec($query, $data);

        // Vérification de la bonne exécution de la requête
        if ($nbLignesMod != 1) {
            throw new Exception("Update : Nombre de ligne modifiée != 1 lors de la modification de l'enchère $this->id");
        }
    }

    ////////////////// DELETE ///////////////////

    /**
     * Supprime l'enchère de la bd
     * @throws Exception si l'enchère n'existe pas dans la bd
     */
    public function delete() : void {
        if (!$this->isInDB()) {
            throw new Exception("Delete : L'enchère n'existe pas dans la bd");
        }

        // récupération du dao
        $dao = DAO::get();

        // préparation du query
        $query = 'DELETE FROM Enchere WHERE id = ?';
        $data = [$this->id];

        $dao->exec($query, $data);

        // on change l'id de l'enchère pour signifier qu'elle n'est plus dans la bd
        $this->id = -1;
    }
}