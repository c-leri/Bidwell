<?php
namespace Bidwell\Model;

use DateInterval;
use DateTime;
use Exception;

/**
 * La classe Participation modélise la participation d'un utilisateur à des enchères.
 * Il s'agit d'une classe association entre la classe Utilisateur et la classe Enchere. Elle permet à des enchères d'avoir autant de participations qu'elle veut
 * et à un utilisateur d'avoir autant de participation à une enchère qu'il désire, mais aussi de vérifier que l'utilisateur a enchéri ou non.
 *
 *   - Une participation n'est liée qu'à un utilisateur et une enchère
 *   - Une enchère et un utilisateur peuvent avoir un nombre infini de participations
 */
class Participation {
    private const TEMPS_CONSERVATION = '5 years'; // temps de conservation des données dans la bd

    // Attributs
    private int $idEnchere;
    private string $loginUtilisateur;
    private int $nbEncheres;
    private float|null $montantDerniereEnchere;
    private DateTime|null $instantDerniereEnchere;
    // booléen qui signifie si l'utilisateur est enregistré dans la base
    private bool $isInDB;

    // Constructeur
    /**
     * @throws Exception si l'enchère ou l'utilisateur ne sont pas enregistrés dans la bd
     */
    public function __construct(Enchere $enchere, Utilisateur $utilisateur) {
        $this->setEnchere($enchere);
        $this->setUtilisateur($utilisateur);
        $this->nbEncheres = 0;
        $this->montantDerniereEnchere = null;
        $this->instantDerniereEnchere = null;
        $this->isInDB = false;
    }

    // Getters
    public function getEnchere() : Enchere {
        return Enchere::read($this->idEnchere);
    }

    public function getUtilisateur() : Utilisateur {
        return Utilisateur::read($this->loginUtilisateur);
    }

    public function getInstantDerniereEnchere() : DateTime|null {
        return $this->instantDerniereEnchere;
    }

    // Setters
    /**
     * @throws Exception si l'enchère n'est pas enregistrée dans la bd
     */
    private function setEnchere(Enchere $enchere) : void {
        if (!$enchere->isInDB())
            throw new Exception("L'enchère n'est pas enregistrée dans la bd");
        $this->idEnchere = $enchere->getId();
    }

    /**
     * @throws Exception si l'utilisateur n'est pas enregistré dans la bd
     */
    private function setUtilisateur(Utilisateur $utilisateur) : void {
        if (!$utilisateur->isInDB())
            throw new Exception("L'utilisateur n'est pas enregistré dans la bd");
        $this->loginUtilisateur = $utilisateur->getLogin();
    }


    public function setNbEncheres(int $nbEncheres) : void {
        $this->nbEncheres = $nbEncheres;
    }

    // Autres méthodes

    /**
     * Trigger tous les événements lié au fait que $utilisateur enchérisse sur $enchère
     *  - incrémentre nbEnchere
     *  - change montantDerniereEnchere au prix courant de l'enchère et instantDerniereEnchere à maintenant
     *  - changer la derniere Enchere de $enchere $this
     */
    public function encherir() : void {
        $this->nbEncheres++;
        $enchere = Enchere::read($this->idEnchere);
        $this->montantDerniereEnchere = $enchere->getPrixCourant();
        $this->instantDerniereEnchere = new DateTime();
        $enchere->setDerniereEnchere($this);
    }

    /**
     * Vérifie si la participation est enregistrée dans la bd
     */
    public function isInDB() : bool {
        return $this->isInDB;
    }

    /////////////////////////////////////////////
    //                  CRUD                   //
    /////////////////////////////////////////////

    ////////////////// CREATE ///////////////////

    /**
     * Enregistre la participation dans la bd
     * @throws Exception si l'insertion échoue
     */
    public function create() : void {
        // récupération du dao
        $dao = DAO::get();

        // variable correspondant à la date de fin de conservation de l'enchère dans la bd
        $dateFinConservation = new DateTime();
        $dateFinConservation->add(DateInterval::createFromDateString(Participation::TEMPS_CONSERVATION));

        // préparation de la query
        $query = 'INSERT INTO Participation(idEnchere, loginUtilisateur, nbEncheres, dateFinConservation) VALUES (?,?,?,?)';
        $data = [$this->idEnchere, $this->loginUtilisateur, $this->nbEncheres, $dateFinConservation->getTimestamp()];

        // récupération du résultat de l'insertion
        $r = $dao->exec($query, $data);

        // si on n'a pas exactement une ligne insérée, throw une exception
        if ($r != 1) {
            throw new Exception("L'insertion de la participation a échouée");
        }

        $this->isInDB = true;
    }

    /////////////////// READ ////////////////////

    /**
     * Récupère une participation dans la bd à partir de son id
     * @throws Exception si on ne trouve pas la participation dans la bd ou si plusieurs participations ont le même id dans la bd
     */
    public static function read(Enchere $enchere, Utilisateur $utilisateur) : Participation {
        // récupération du dao
        $dao = DAO::get();

        // préparation de la query
        $query = 'SELECT * FROM Participation WHERE idEnchere = ? AND loginUtilisateur = ?';
        $data = [$enchere->getId(), $utilisateur->getLogin()];

        // récupération de la table de résultat
        $table = $dao->query($query, $data);

        // throw une exception si on ne trouve pas la participation
        if (count($table) == 0) {
            throw new Exception("Participation de utilisateur {$utilisateur->getLogin()} à l'enchère {$enchere->getId()} non trouvée");
        }

        // throw une exception si on trouve plusieurs participations
        if (count($table) > 1) {
            throw new Exception("Participation de l'utilisateur {$utilisateur->getLogin()} à l'enchère {$enchere->getId()} existe en ".count($table).' exemplaires');
        }

        return Participation::constructFromDB($table[0]);
    }

    public static function readFromUtilisateur(Utilisateur $utilisateur) : array {
        // récupération du dao
        $dao = DAO::get();

        // préparation de la query
        $query = 'SELECT * FROM Participation WHERE loginUtilisateur  = ?';
        $data = [$utilisateur->getLogin()];

        // récupération de la table de résultat
        $table = $dao->query($query, $data);

        $out = array();
        foreach($table as $row) {
            $out[] = Participation::constructFromDB($row);
        }

        return $out;
    }

    public static function readFromEnchere(Enchere $enchere) : array {
        // récupération du dao
        $dao = DAO::get();

        // préparation de la query
        $query = 'SELECT * FROM Participation WHERE idEnchere  = ?';
        $data = [$enchere->getId()];

        // récupération de la table de résultat
        $table = $dao->query($query, $data);

        $out = array();
        foreach($table as $row) {
            $out[] = Participation::constructFromDB($row);
        }

        return $out;
    }


    private static function constructFromDB(array $row) : Participation {
        // création d'un objet participation avec les informations de la bd
        $participation = new Participation(Enchere::read($row['idEnchere']), Utilisateur::read($row['loginUtilisateur']));

        // on set le nombre d'enchères de la participation
        $participation->nbEncheres = $row['nbEncheres'];

        // on set le montantDerniereEnchere si il existe
        $participation->montantDerniereEnchere = $row['montantDerniereEnchere'];

        if (isset($row['instantDerniereEnchere'])) {
            $instantDerniereEnchere = new DateTime();
            $instantDerniereEnchere->setTimestamp($row['instantDerniereEnchere']);
        } else $instantDerniereEnchere = null;
        $participation->instantDerniereEnchere = $instantDerniereEnchere;

        $participation->isInDB = true;

        return $participation;
    }

    ////////////////// UPDATE ///////////////////

    /**
     * Enregistre les modifications faites à la participation dans la bd
     * @throws Exception si la participation n'existe pas dans la bd ou si le nombre de lignes modifiées != 1
     */
    public function update() : void {
        if (!$this->isInDB()) {
            throw new Exception("Update : La participation de l'utilisateur $this->loginUtilisateur à l'enchère $this->idEnchere n'existe pas dans la bd");
        }

        // récupération du dao
        $dao = DAO::get();

        // update le montant de la dernière enchère s'il existe
        if (isset($this->montantDerniereEnchere) && isset($this->instantDerniereEnchere)) {
            $query = 'UPDATE Participation SET nbEncheres = ?, montantDerniereEnchere = ?, instantDerniereEnchere = ? WHERE idEnchere = ? AND loginUtilisateur = ?';
            $data = [$this->nbEncheres, $this->montantDerniereEnchere, (int) $this->instantDerniereEnchere->format('Uv'), $this->idEnchere, $this->loginUtilisateur];
        } else {
            $query = 'UPDATE Participation SET nbEncheres = ? WHERE idEnchere = ? AND loginUtilisateur = ?';
            $data = [$this->nbEncheres, $this->idEnchere, $this->loginUtilisateur];
        }

        // Exécution de la requête
        $nbLignesMod = $dao->exec($query, $data);

        // Vérification de la bonne exécution de la requête
        if ($nbLignesMod > 1) {
            throw new Exception("Update : Nombre de ligne modifiée != 1 lors de la modification de la participation de l'utilisateur $this->loginUtilisateur à l'enchère $this->idEnchere");
        }
    }

    ////////////////// DELETE ///////////////////

    /**
     * Supprime la participation de la bd
     * @throws Exception si la participation n'existe pas dans la bd
     */
    public function delete() : void {
        if (!$this->isInDB()) {
            throw new Exception("Delete : La participation de l'utilisateur $this->loginUtilisateur à l'enchère $this->idEnchere n'existe pas dans la bd");
        }

        // récupération du dao
        $dao = DAO::get();

        // Initialisation de la requête et du tableau de valeurs
        $query = 'DELETE FROM Participation WHERE idEnchere = ? AND loginUtilisateur = ?';
        $data = [$this->idEnchere, $this->loginUtilisateur];

        // Exécution de la requête
        $dao->exec($query, $data);

        $this->isInDB = false;
    }
}