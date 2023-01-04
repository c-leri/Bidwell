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


    // Méthode
    /**
     * Incrémente le compteur d'enchères posées par $utilisateur sur $enchere
     */
    public function aEncheri() {
        $this->nbEncheres++;
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
            throw new Exception("Participation d'utilisateur {$utilisateur->getLogin()} pour l'enchère {$enchere->getId()} non trouvée");
        }

        // throw une exception si on trouve plusieurs participation
        if (count($table) > 1) {
            throw new Exception("Participation d'utilisateur {$utilisateur->getLogin()} pour l'enchère {$enchere->getId()} existe en ".count($table).' exemplaires');
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
}