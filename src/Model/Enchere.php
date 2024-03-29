<?php

namespace Bidwell\Model;

use Bidwell\Util\Helper;

use DateInterval;
use DateTime;
use Exception;

/**
 * Classe représentant la notion d'enchère
 * "contenu" du modèle composite (Categorie est le "contenant")
 */
class Enchere
{
    // constantes de classe
    public const ADRESSE_IMAGES = '../../data/img/';
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
    private string $description;
    private Categorie $categorie;
    private array $infosContact;
    private array $infosEnvoi;
    private string $codePostal;

    // Constructeurs

    /**
     * @throws Exception si la catégorie n'est pas dans la bd
     */
    public function __construct(Utilisateur $createur, string $libelle, DateTime $dateDebut, float $prixDepart, float $prixRetrait, string $imagePrincipale, string $description, Categorie $categorie, array $infosContact, array $infosEnvoi, string $codePostal)
    {
        $this->id = -1;
        $this->createur = $createur;
        $this->libelle = $libelle;
        $this->dateDebut = $dateDebut;
        $this->prixDepart = $prixDepart;
        $this->prixHaut = $prixDepart;
        $this->prixRetrait = $prixRetrait;
        $this->images[0] = $imagePrincipale;
        $this->description = $description;
        $this->setCategorie($categorie);
        $this->infosContact = $infosContact;
        $this->infosEnvoi = $infosEnvoi;
        $this->codePostal = $codePostal;
        $this->derniereEnchere = null;
    }

    /**
     * Construit une enchère à partir d'une ligne de la bd
     */
    private static function constructFromDB(array $row): Enchere
    {
        // split le contenu du string images de la bd en un tableaux de string contenant le nom des fichiers contenant les images
        $images = explode(' ', $row['images']);

        // on retire les strings vides de $images
        while (($key = array_search("", $images)) !== false) {
            unset($images[$key]);
        }

        // on récupère le DateTime correspondant au timestamp stocké dans la bd
        $dateDebut = new DateTime();
        $dateDebut->setTimestamp($row['dateDebut']);
        $infosEnvoi = array();
        array_push($infosEnvoi, $row['infoRemiseDirect'] === 'true', $row['infoEnvoiColis'] === 'true');
        $infosContact = array();
        array_push($infosContact, $row['infoEmail'] === 'true', $row['infoTel'] === 'true');

        // création d'un objet enchère avec les informations de la bd
        $enchere = new Enchere(Utilisateur::read($row['loginCreateur']), $row['libelle'], $dateDebut, $row['prixDepart'], $row['prixRetrait'], $images[0], $row['description'], Categorie::read($row['libelleCategorie']),$infosContact,$infosEnvoi,$row['codePostal']);

        // on set l'id de l'enchère
        $enchere->id = $row['id'];

        // on set la derniereEnchere si elle est dans la bd
        if (isset($row['loginUtilisateurDerniereEnchere'])) {
            $enchere->derniereEnchere = Participation::read($enchere, Utilisateur::read($row['loginUtilisateurDerniereEnchere']));
        }

        // on ajoute les images restantes dans la liste de string
        unset($images[0]);
        foreach ($images as $image) {
            $enchere->addImage($image);
        }

        return $enchere;
    }

    // Getters
    

    public function getId(): int
    {
        return $this->id;
    }

    public function getCreateur(): Utilisateur
    {
        return $this->createur;
    }

    public function getLibelle(): string
    {
        return $this->libelle;
    }

    public function getDateDebut(): DateTime
    {
        return $this->dateDebut;
    }

    public function getPrixDepart(): float
    {
        return $this->prixDepart;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getInfosEnvoi(): array
    {
        return $this->infosEnvoi;
    }

    public function getInfosContact(): array
    {
        return $this->infosContact;
    }

    public function getCodePostal(): string
    {
        return $this->codePostal;
    }

    public function getCategorie(): Categorie
    {
        return $this->categorie;
    }

    public function getParticipations(): array
    {
        return Participation::readFromEnchere($this);
    }

    public function getDerniereEnchere(): ?Participation
    {
        return $this->derniereEnchere;
    }

    // Setters

    public function setLibelle(string $libelle): void
    {
        $this->libelle = $libelle;
    }  

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function setDerniereEnchere(Participation $participation): void
    {
        $this->derniereEnchere = $participation;
    }

    /**
     * @throws Exception si la catégorie n'est pas enregistrée dans la bd
     */
    public function setCategorie(Categorie $categorie): void
    {
        if (!$categorie->isInDB()) {
            throw new Exception("Impossible de donner une catégorie non existante dans la bd à une enchère");
        }
        $this->categorie = $categorie;
    }

    // Gestion des images

    public function setImagePrincipale(string $image): void
    {
        $this->images[0] = $image;
    }

    public function addImage(string $image): void
    {
        $this->images[] = $image;
    }

    public function removeImage(int $id): void
    {
        if (!isset($this->images[$id])) throw new Exception("Pas d'image d'id $id.");
        unset($this->images[$id]);
    }

    public function getImageURL(int $id): string
    {
        return $this::ADRESSE_IMAGES . $this->getImage($id);
    }

    /**
     * méthode pour récupérer l'image en case $id du tableau d'images
     * @throws Exception si la case $id de $images est vide
     */
    public function getImage(int $id): string
    {
        if (!isset($this->images[$id])) throw new Exception("Pas d'image d'id $id.");
        return $this->images[$id];
    }

    public function getImages() : array {
        return $this->images;
    } 

    // Autres méthodes

    /**
     * Vérifie si l'enchère est enregistrée dans la bd
     */
    public function isInDB(): bool
    {
        return $this->id != -1;
    }

    /**
     * @return float le prix courant de l'enchère
     */
    public function getPrixCourant(): float
    {
        return $this->getPrixRetrait() + $this->getRatioTempsActuel() * ($this->getPrixHaut() - $this->getPrixRetrait());
    }

    public function getPrixRetrait(): float
    {
        return (isset($this->derniereEnchere))
            ? $this->derniereEnchere->getMontantDerniereEnchere()
            : $this->prixRetrait;
    }

    public function getPrixHaut(): float
    {
        return (isset($this->derniereEnchere))
            ? $this->derniereEnchere->getMontantDerniereEnchere() + $this->getPrixDepart() * 0.05
            : $this->prixDepart;
    }

    /**
     * Calcul le ratio représentant l'avancée temporelle de l'enchère entre le prix haut et le prix de retrait
     */
    private function getRatioTempsActuel(): float
    {
        $maintenant = new DateTime();
        if ($maintenant > $this->getDateDebut()) {
            $differenceMaintenantFin = $this->getInstantFin()->diff($maintenant);
            return Helper::intervalToSeconds($differenceMaintenantFin) / Helper::intervalToSeconds($this->getInstantFin()->diff($this->getInstantDerniereEnchere()));
        } else {
            return 1;
        }
    }

    /**
     * @return DateTime l'instant de la dernière enchère 
     */
    public function getInstantDerniereEnchere(): DateTime
    {
        return (isset($this->derniereEnchere) && $this->derniereEnchere->getInstantDerniereEnchere() !== null)
            ? $this->derniereEnchere->getInstantDerniereEnchere()
            : $this->dateDebut;
    }

    /**
     * @return DateTime correspondant à l'instant de la fin de l'enchère (datedebut + 1 heure)
     */
    public function getInstantFin(): DateTime
    {
        $instantFin = clone $this->getDateDebut();
        $instantFin->modify("+1 hour");
        return $instantFin;
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
    public function create(): void
    {
        if ($this->id != -1) {
            throw new Exception("Create : L'enchère $this->id existe déjà dans la bd");
        }

        if (!$this->categorie->isInDB()) {
            throw new Exception("Create : La catégorie de l'enchère n'existe pas dans la bd");
        }

        // récupération du dao
        $dao = DAO::get();


        // transforme le tableau d'images en un string avec les images séparées par des espaces
        $imagesString = implode(" ",$this->images);
        // variable correspondant à la date de fin de conservation de l'enchère dans la bd
        $dateFinConservation = new DateTime();
        $dateFinConservation->add(DateInterval::createFromDateString(Enchere::TEMPS_CONSERVATION));
        // préparation de la query
        $query = 'INSERT INTO Enchere(loginCreateur, libelle, dateDebut, prixDepart, prixRetrait, images, description, libelleCategorie, dateFinConservation, codePostal, infoRemiseDirect, infoEnvoiColis, infoEmail, infoTel) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)';
        $data = [$this->createur->getLogin(), $this->libelle, $this->dateDebut->getTimestamp(), $this->prixDepart, $this->prixRetrait, $imagesString, $this->description, $this->categorie->getLibelle(), $dateFinConservation->getTimestamp(),$this->getCodePostal(),$this->getInfosEnvoi()[0] ? 'true' : 'false',$this->getInfosEnvoi()[1] ? 'true' : 'false',$this->getInfosContact()[0] ? 'true' : 'false',$this->getInfosContact()[1] ? 'true' : 'false'];

        // récupération du résultat de l'insertion
        $r = $dao->exec($query, $data);

        // si on n'a pas exactement une ligne insérée, throw une exception
        if ($r != 1) {
            throw new Exception("Create : L'insertion de l'enchère a échouée");
        }

        // on récupère l'id de l'enchère dans la bd
        $id = (int)$dao->lastInsertId();
        $this->id = $id;
    }

    /////////////////// READ ////////////////////

    /**
     * Récupère une enchère dans la bd à partir de son id
     * @throws Exception si on ne trouve pas l'enchère dans la bd ou si plusieurs enchères on le même id dans la bd
     */
    public static function read(int $id): Enchere
    {
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
            throw new Exception("Read : Enchere $id existe en " . count($table) . ' exemplaires');
        }

        return Enchere::constructFromDB($table[0]);
    }

    public static function readFromCreateur(Utilisateur $createur): array
    {
        return Enchere::readFromCreateurString($createur->getLogin());
    }

    public static function readFromCreateurString(string $createur): array
    {
        // récupération du dao
        $dao = DAO::get();

        // préparation de la query
        $query = 'SELECT * FROM Enchere WHERE loginCreateur = ?';
        $data = [$createur];

        // récupération de la table de résultat
        $table = $dao->query($query, $data);

        $out = array();
        foreach ($table as $row) {
            $out[] = Enchere::constructFromDB($row);
        }

        return $out;
    }

    public static function readFromCategorie(Categorie $categorie): array
    {
        // récupération du dao
        $dao = DAO::get();

        // préparation de la query
        $query = 'SELECT * FROM Enchere WHERE libelleCategorie = ?';
        $data = [$categorie->getLibelle()];

        // récupération de la table de résultat
        $table = $dao->query($query, $data);

        $out = array();
        foreach ($table as $row) {
            $out[] = Enchere::constructFromDB($row);
        }

        return $out;
    }

    public static function readLike(array $categories, string $pattern, string $tri, int $prix, string $ordre, int $page, int $pageSize): array
    {
        // récupération du dao
        $dao = DAO::get();

        $order = match ($tri) {
            'date' => 'dateDebut',
            'prix' => 'prixDepart',
            default => 'libelle',
        };

        $intervalle = match ($prix) {
            1 => 'AND prixDepart < 10',
            2 => 'AND prixDepart BETWEEN 10 AND 20',
            3 => 'AND prixDepart BETWEEN 20 AND 50',
            4 => 'AND prixDepart BETWEEN 50 AND 100',
            5 => 'AND prixDepart BETWEEN 100 AND 500',
            6 => 'AND prixDepart > 500',
            default => '',
        };

        $decalage = ($page - 1) * $pageSize;

        // préparation de la query selon le nombre de catégories sélectionnées (0, 1 ou plusieurs)
        //Si 0, simple requête ne demandant pas de filtre par catégorie
        if (!isset($categories[0]) || $categories[0] == '') {
            $query = "SELECT * FROM Enchere WHERE libelle LIKE ? $intervalle ORDER BY $order COLLATE NOCASE $ordre LIMIT ?, ?";
            $data = ['%' . $pattern . '%', $decalage, $pageSize];

        //Si 1, ajout du filtre par la catégorie unique
        } else if (sizeof($categories) == 1) {
            $query = "SELECT * FROM Enchere WHERE libelleCategorie = ? AND libelle LIKE ? $intervalle ORDER BY $order COLLATE NOCASE $ordre LIMIT ?, ?";
            $data = [$categories[0], '%' . $pattern . '%', $decalage, $pageSize];

        //Si plus de 1, nécessité de préparer une requête qui contient autant de '?' que nécessaire ($nb) et d'attribuer autant d'éléments dans $data
        } else {
            $nb = '';
            for ($i = 0; $i < sizeof($categories) - 1; $i++) {
                $nb .= '?, ';
                $data[] = $categories[$i];

            }
            $nb .= '?';
            $data[] = end($categories);

            $query = "SELECT * FROM Enchere WHERE libelleCategorie IN (" . $nb . ") AND libelle LIKE ? $intervalle ORDER BY $order COLLATE NOCASE $ordre LIMIT ?, ?";

            $data[] = '%' . $pattern . '%';
            $data[] = $decalage;
            $data[] = $pageSize;
        }

        // récupération de la table de résultat
        $table = $dao->query($query, $data);

        $out = array();
        foreach ($table as $row) {
            $out[] = Enchere::constructFromDB($row);
        }

        return $out;
    }

    public static function readFromGagnant(string $gagnant, int $limite) : array {
        $dao = DAO::get();

        $query = "SELECT * FROM Enchere WHERE loginUtilisateurDerniereEnchere = ? LIMIT ?";
        $data = [$gagnant, $limite];

        $table = $dao->query($query, $data);

        $out = array();
        foreach ($table as $row) {
            $x = Enchere::constructFromDB($row);

            $maintenant = new DateTime();
            if ($x->getInstantFin() < $maintenant){
                $out[] = $x;
            }
        }

        return $out;
    }


    ////////////////// UPDATE ///////////////////

    /**
     * Met à jour les valeurs de l'enchère dans la bd
     * @throws Exception si l'enchère ou sa catégorie mère n'existent pas dans la bd ou si
     */
    public function update(): void
    {
        if (!$this->isInDB()) {
            throw new Exception("Update : L'enchère n'existe pas dans la bd");
        }

        if (!$this->categorie->isInDB()) {
            throw new Exception("Update : La catégorie mère de l'enchère $this->id n'existe pas dans la bd");
        }

        // récupération du dao
        $dao = DAO::get();

        // transforme le tableau d'images en un string avec les images séparées par des espaces
        $imagesString = '';
        foreach ($this->images as $image) {
            $imagesString .= $image . ' ';
        }

        // si l'enchere a une derniere enchère, on l'inclut dans l'update
        if (isset($this->derniereEnchere)) {
            $query = 'UPDATE Enchere SET libelle = ?, libelleCategorie = ?, dateDebut = ?, prixDepart = ?, prixRetrait = ?, loginUtilisateurDerniereEnchere = ?, images = ?, description = ?, codePostal = ?, infoRemiseDirect= ?, infoEnvoiColis = ?, infoEmail = ?, infoTel = ? WHERE id = ?';
            $data = [$this->libelle, $this->categorie->getLibelle(), $this->dateDebut->getTimestamp(), $this->prixDepart, $this->prixRetrait, $this->derniereEnchere->getUtilisateur()->getLogin(), $imagesString, $this->description, $this->getCodePostal(),$this->getInfosEnvoi()[0] ? 'true' : 'false',$this->getInfosEnvoi()[1] ? 'true' : 'false',$this->getInfosContact()[0] ? 'true' : 'false',$this->getInfosContact()[1] ? 'true' : 'false',$this->id];
        } else {
            $query = 'UPDATE Enchere SET libelle = ?, libelleCategorie = ?, dateDebut = ?, prixDepart = ?, prixRetrait = ?, images = ?, description = ?, codePostal = ?, infoRemiseDirect= ?, infoEnvoiColis = ?, infoEmail = ?, infoTel = ? WHERE id = ?';
            $data = [$this->libelle, $this->categorie->getLibelle(), $this->dateDebut->getTimestamp(), $this->prixDepart, $this->prixRetrait, $imagesString, $this->description, $this->getCodePostal(),$this->getInfosEnvoi()[0] ? 'true' : 'false',$this->getInfosEnvoi()[1] ? 'true' : 'false',$this->getInfosContact()[0] ? 'true' : 'false',$this->getInfosContact()[1] ? 'true' : 'false', $this->id];
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
    public function delete(): void
    {
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
