<?php

require_once __DIR__.'/Enchere.class.php';

/**
 * La classe Participation modélise la participation d'un utilisateur à des enchères.
 * Il s'agit d'une classe assiociation entre la classe Utilisateur et la classe Enchere. Elle permet à des enchères d'avoir autant de participations qu'elle veut
 * et à un utilisateur d'avoir autant de participation à une enchère qu'il veut, mais aussi de vérifier que l'utilisateur a enchéri ou non.
 * 
 *   - Une participation n'est liée qu'à un utilisateur et une enchère
 *   - Une enchère et un utilisateur peuvent avoir un nombre infini de participation
 */
class Participation {
    // Attributs
    private Enchere $enchere;
    private Utilisateur $utilisateur;
    private int $nbEncheres;
    private float|null $montantDerniereEnchere;

    // Constructeur
    public function __construct(Enchere $enchere, Utilisateur $utilisateur) {
        $this->enchere = $enchere;
        $this->utilisateur = $utilisateur;
        $this->nbEncheres = 0;
    }

    // Getters
    public function getEnchere() : Enchere {
        return $this->enchere;
    }

    public function getUtilisateur() : Utilisateur {
        return $this->utilisateur;
    }

    // Autres méthodes

    /**
     * Incrémente le compteur d'enchères posées par $utilisateur sur $enchere
     */
    public function aEncheri() {
        $this->nbEncheres++;
    }

    /**
     * Vérifie si la participation est enregistrée dans la bd
     */
    public function isInDB() : bool {
        // Récupération de la classe DAO
        $dao = DAO::get();

        // Initialisation de la requête et du tableau de valeurs
        $query = 'SELECT * FROM Participation WHERE idEnchere = ?, loginUtilisateur = ?';
        $data = [$this->enchere->getId(), $this->utilisateur->getLogin()];

        // Exécution de la requête
        $table = $dao->query($query, $data);

        return count($table) == 0;
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

        // préparation de la query
        $query = 'INSERT INTO Participation(idEnchere, loginUtilisateur, nbEncheres) VALUES (?,?,?)';
        $data = [$this->enchere->getId(), $this->utilisateur->getLogin(), $this->nbEncheres];

        // récupération du résultat de l'insertion 
        $r = $dao->exec($query, $data);

        // si on n'a pas exactement une ligne insérée, throw une exception
        if ($r != 1) {
            throw new Exception("L'insertion de la participation a échouée");
        }
    }

    /////////////////// READ ////////////////////

    /**
     * Récupère une participation dans la bd à partir de son id
     * @throws Exception si on ne trouve pas la participation dans la bd ou si plusieurs participations on le même id dans la bd
     */
    public static function read(Enchere $enchere, Utilisateur $utilisateur) : Participation {
        // récupératoin du dao
        $dao = DAO::get();

        // préparation de la query
        $query = 'SELECT * FROM Participation WHERE idEnchere = ?, loginUtilisateur = ?';
        $data = [$enchere->getId(), $utilisateur->getLogin()];

        // récupération de la table de résultat
        $table = $dao->query($query, $data);

        // throw une exception si on ne trouve pas la participation
        if (count($table) == 0) {
            throw new Exception("Participation de utilisateur {$utilisateur->getLogin()} à l'enchère {$enchere->getId()} non trouvée");
        }

        // throw une exception si on trouve plusieurs participation
        if (count($table) > 1) {
            throw new Exception("Participation de l'utilisateur {$utilisateur->getLogin()} à l'enchère {$enchere->getId()} existe en ".count($table).' exemplaires');
        }

        $row = $table[0];

        // création d'un objet participation avec les informations de la bd
        $participation = new Participation(Enchere::read($row['idEnchere']), Utilisateur::read($row['loginUtilisateur']));

        // on set le nombre d'enchères de la participation
        $participation->nbEncheres = $row['nbEncheres'];

        // on set le montantDerniereEnchere si il existe
        $participation->montantDerniereEnchere = $row['montantDerniereEnchere'];

        return $participation;
    }

    ////////////////// UPDATE ///////////////////

    /**
     * Enregistre les modification faites à la paticipation dans la bd
     * @throws Exception si la participation n'existe pas dans la bd ou si le nombre de ligne modifiée != 1
     */
    public function update() : void {
        if (!$this->isInDB()) {
            throw new Exception("Update : La participation de l'utilisateur {$this->utilisateur->getLogin()} à l'enchère {$this->enchere->getId()} n'existe pas dans la bd");
        }

        // récupératoin du dao
        $dao = DAO::get();

        // update le montant de la dernière enchère si il existe
        if (isset($this->montantDerniereEnchere)) {
            $query = 'UPDATE Participation SET nbEncheres = ?, montantDerniereEnchere = ? WHERE idEnchere = ? AND loginUtilisateur = ?';
            $data = [$this->nbEncheres, $this->montantDerniereEnchere, $this->enchere->getId(), $this->utilisateur->getLogin()];
        } else {
            $query = 'UPDATE Participation SET nbEncheres = ?, montantDerniereEnchere = ? WHERE idEnchere = ? AND loginUtilisateur = ?';
            $data = [$this->nbEncheres, $this->montantDerniereEnchere, $this->enchere->getId(), $this->utilisateur->getLogin()];
        }
        
        // Exécution de la requête
        $nbLignesMod = $dao->exec($query, $data);

        // Vérification de la bonne exécution de la requête
        if ($nbLignesMod > 1) {
            throw new Exception("Update : Nombre de ligne modifiée != 1 lors de la modification de la participation de l'utilisateur {$this->utilisateur->getLogin()} à l'enchère {$this->enchere->getId()}");
        }
    }

    ////////////////// DELETE ///////////////////

    /**
     * Supprime la participation de la bd
     * @throws Exception si la participation n'existe pas dans la bd
     */
    public function delete() : void {
        if (!$this->isInDB()) {
            throw new Exception("Delete : La participation de l'utilisateur {$this->utilisateur->getLogin()} à l'enchère {$this->enchere->getId()} n'existe pas dans la bd");
        }

        // récupératoin du dao
        $dao = DAO::get();

        // Initialisation de la requête et du tableau de valeurs
        $query = 'DELETE FROM Participation WHERE idEnchere = ?, loginUtilisateur = ?';
        $data = [$this->enchere->getId(), $this->utilisateur->getLogin()];

        // Exécution de la requête
        $dao->exec($query, $data);
    }
}