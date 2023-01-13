<?php
require_once __DIR__."/DAO.class.php";
require_once __DIR__.'/Participation.class.php';

/**
 * La classe Utilisateur modélise les utilisateurs enregistrés sur l'application à partir d'un login, d'un mot de passe hashé, d'un nom
 * d'un e-mail, d'un numéro de téléphone et d'un nombre de jetons.
 *  - Les méthodes CRUD permettent de lire et sérialiser les informations liées aux utilisateurs dans la base de données de
 *    l'application
 *  - Une association bidirectionnelle avec la classe Participation permet d'associer les classes Utilisateur et Enchere
 *  - Un second constructeur privé est présent dans la classe : il a accès à tous les objets et permet de regénérer des
 *    instances de la classe Utilisateur à partir de données lues dans la base de données
 */
class Utilisateur {
    private const TEMPS_CONSERVATION = '5 years'; // temps de conservation des données dans la bd

    // informations de connection
    private string $login;
    private string|null $mdpHash;        // le hash correspondant au mot de passe de l'utilisateur, calculé avec password_hash(), null à la création d'un utilisateur
    // informations personnelles
    private string $nom;
    private string $email;
    private string $numeroTelephone;
    // informations pour les enchères
    private int $nbJetons;
    // booleen qui signifie si l'utilisateur est enregistré dans la base
    private bool $isInDB;

    // constructeur

    /**
     * @throws Exception si le numéro de téléphone n'est pas composé de 10 caractères
     */
    public function __construct(string $login, string $email, string $numeroTelephone)
    {
        $this->login = $login;
        $this->nom = '';
        $this->setEmail($email);
        $this->setNumeroTelephone($numeroTelephone);
        $this->nbJetons = 0;
        $this->isInDB = false;
    }

    // Getters
    public function getLogin() : string {
        return $this->login;
    }

    public function getNom() : string {
        return $this->nom;
    }

    public function getEmail() : string {
        return $this->email;
    }

    public function getNumeroTelephone() : string {
        return $this->numeroTelephone;
    }

    public function getNbJetons() : int {
        return $this->nbJetons;
    }

    public function getParticipations() : array {
        return Participation::readFromUtilisateur($this);
    }

    public function getEnchereCreees() : array {
        return Enchere::readFromCreateur($this);
    }

    // Setters
    public function setPassword(string $password) : void {
        $this->mdpHash = password_hash($password, PASSWORD_BCRYPT);
    }

    public function setNom(string $nom) : void {
        $this->nom = $nom;
    }

    public function setEmail(string $email) : void {
        $this->email = $email;
    }

    /**
     * fonction pour modifier le numéro de téléphone d'un Utilisateur
     * @throws Exception si le numéro n'est pas composé de 10 caractères
     */
    public function setNumeroTelephone(string $numeroTelephone) : void {
        if (strlen($numeroTelephone) != 10) {
            throw new Exception("Un numéro de téléphone doit être composée de 10 chiffres" . strlen($numeroTelephone));
        }

        $this->numeroTelephone = $numeroTelephone;
    }

    // Gestion de la connexion

    public static function connectionValide(string $login, string $password) : bool {
        try {
            $utilisateur = Utilisateur::read($login);
            return $utilisateur->mdpValide($password);
        } catch (Exception $e) {
            return false;
        }
    }

    public function mdpValide(string $password) : bool {
        return password_verify($password, $this->mdpHash);
    }

    // Autres méthodes
    public function isInDB() : bool {
        return $this->isInDB;
    }

    /**
     * Vérifie si l'attribut est enregistré dans la bd
     */
    public function isAttributeInDB($attribute) : bool {
        // Récupération de la classe DAO
        $dao = DAO::get();
        $str = '?';
        // Initialisation de la requête et du tableau de valeurs
        $requete = 'SELECT * FROM Utilisateur WHERE '. $attribute .'= '.$str;
        $valeurs = [$this->$attribute];
        // Exécution de la requête
        $table = $dao->query($requete, $valeurs);
        return count($table) != 0;
    }

    /////////////////////////////////////////////
    //                  CRUD                   //
    /////////////////////////////////////////////

    ////////////////// CREATE ///////////////////
    
    /**
     * La méthode create() permet de sérialiser dans la base de données de l'application l'instance courante de la classe Utilisateur
     * @throws Exception si le nombre de lignes insérées != 1 ou si le mot de passe de l'utilisateur est null
     */
    public function create() : void {
        // Vérification que le mdp de l'utilisateur est bien set
        if (!isset($this->mdpHash)) {
            throw new Exception("Create : Mot de passe de l'utilisateur $this->login null");
        }

        // Récupération de l'objet DAO
        $dao = DAO::get();

        // variable correspondant à la date de fin de conservation de l'enchère dans la bd
        $dateFinConservation = new DateTime();
        $dateFinConservation->add(DateInterval::createFromDateString(Utilisateur::TEMPS_CONSERVATION));

        // Initialisation de la requête SQL
        $requete = 'INSERT INTO Utilisateur VALUES (?,?,?,?,?,?,?)';
        // Initialisation du tableau de valeurs pour la requête
        $valeurs = [$this->login, $this->mdpHash, $this->nom, $this->email, $this->numeroTelephone, $this->nbJetons, $dateFinConservation->getTimestamp()];

        // Exécution de la requête
        $nbLignesMod = $dao->exec($requete, $valeurs);

        // Vérification de la bonne exécution de la requête
        if ($nbLignesMod != 1) {
            throw new Exception("Create : L'utilisateur $this->login n'a pas été correctement inséré.");
        }

        $this->isInDB = true;
    }

    /////////////////// READ ////////////////////

    /**
     * La méthode read() retourne une instance de la classe Utilisateur à partir de son login
     * @throws Exception si l'utilisateur n'est pas trouvé dans la base
     */
    public static function read(string $login) : Utilisateur {
        // Récupération de la classe DAO
        $dao = DAO::get();

        // Initialisation de la requête et du tableau de valeurs
        $requete = 'SELECT * FROM Utilisateur WHERE login = ?';
        $valeurs = [$login];

        // Exécution de la requête
        $table = $dao->query($requete, $valeurs);

        // throw une exception si on ne trouve pas l'utilisateur
        if (count($table) == 0) {
            throw new Exception("Read : Utilisateur $login non trouvée");
        }
    
        // throw une exception si on trouve plusieurs utilisateurs
        if (count($table) > 1) {
            throw new Exception("Read : Utilisateur $login existe en ".count($table).' exemplaires');
        }

        $tuple = $table[0];

        // crée un utilisateur à partir de la ligne du tableau
        $out = new Utilisateur($tuple['login'], $tuple['email'], $tuple['numeroTelephone']);

        // set les informations qui ne sont pas dans le constructeur
        $out->nom = $tuple['nom'];
        $out->mdpHash = $tuple['mdpHash'];
        $out->nbJetons = $tuple['nbJetons'];
        $out->isInDB = true;

        return $out;
    }

    ////////////////// UPDATE ///////////////////
    
    /**
     * La méthode update() permet de sérialiser des modifications apportées à une instance de la classe Utilisateur dans la
     * base de données
     * @throws Exception si l'utilisateur n'existe pas dans la db ou le nombre de lignes modifiée != 1
     */
    public function update() : void {
        if (!$this->isInDB()) {
            throw new Exception("Update : L'utilisateur $this->login n'existe pas dans la bd");
        }

        // Récupération de l'objet DAO
        $dao = DAO::get();

        // Initialisation de la requête SQL
        $requete = 'UPDATE Utilisateur SET login = ?, email = ?, mdpHash = ?, nom = ?, numeroTelephone = ?, nbJetons = ? WHERE login = ?';
        // Initialisation du tableau de valeurs pour la requête
        $valeurs = [$this->login, $this->email, $this->mdpHash, $this->nom, $this->numeroTelephone, $this->nbJetons, $this->login];

        // Exécution de la requête
        $nbLignesMod = $dao->exec($requete, $valeurs);

        // Vérification de la bonne exécution de la requête
        if ($nbLignesMod > 1) {
            throw new Exception("Update : Nombre de ligne modifiée != 1 lors de la modification de l'utilisateur $this->login");
        }
    }

    ////////////////// DELETE ///////////////////

    /**
     * La méthode delete() permet de supprimer l'utilisateur correspondant à l'instance courante de la classe
     * Utilisateur de la base de données.
     * @throws Exception si l'utilisateur n'est pas présent dans la base
     */
    public function delete() : void {
        if (!$this->isInDB()) {
            throw new Exception("Delete : L'utilisateur $this->login n'existe pas dans la bd");
        }

        // Récupération de l'objet DAO
        $dao = DAO::get();

        // Initialisation de la requête et du tableau de valeurs
        $requete = 'DELETE FROM Utilisateur WHERE login = ?';
        $valeurs = [$this->login];

        // Exécution de la requête
        $dao->exec($requete, $valeurs);

        $this->isInDB = false;
    }
}

