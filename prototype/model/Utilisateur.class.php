<?php

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
    // informations de connection
    private string $login;
    private string $password_hash;      // le hash correspondant au mot de passe de l'utilisateur, calculé avec password_hash() 
    // informations personnelles
    private string $nom;
    private string $email;
    private string $numeroTelephone;
    // informations pour les enchères
    private int $nbJetons;

    // constructeur
    public function __construct(string $login, string $passsword, string $email, string $numeroTelephone)
    {
        $this->login = $login;
        $this->setPassword($passsword);

        $this->nom = '';
        $this->setEmail($email);
        $this->setNumeroTelephone($numeroTelephone);

        $this->nbJetons = 0;
    }

    /**
     * Constructeur privé : il permet de construire une instance de la classe Utilisateur depuis un tuple au préalable extrait
     * de la base de données
     */
    private static function construct_fromdb(array $tuple) : Utilisateur {
        $this->login = $tuple['login'];
        $this->password_hash = $tuple['mdpHash'];
        $this->nom = $tuple['nom'];
        $this->email = $tuple['email'];
        $this->numeroTelephone = $tuple['numeroDeTelephone'];
        $this->nbJetons = $tuple['nbJetons'];
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

    // Setters
    public function setPassword(string $password) : void {
        $this->password_hash = password_hash($password, PASSWORD_BCRYPT);
    }

    public function setNom(string $nom) : void {
        $this->nom = $nom;
    }

    public function setEmail(string $email) : void {
        $this->email = $email;
    }

    /**
     * fonction pour modifier le numéro de téléphone d'un Utilisateur
     * @throws Exception si le numéro n'est pas composé de 10 charactères
     */
    public function setNumeroTelephone(string $numeroTelephone) : void {
        if (strlen($numeroTelephone) != 10) {
            throw new Exception("Un numéro de téléphone doit être composée de 10 chiffres");
        }

        $this->numeroTelephone = $numeroTelephone;
    }

    // Autres méthodes
    public function connectionValide(string $login, string $password) : bool {
        return $this->login == $login && $this->password_hash === password_hash($password, PASSWORD_BCRYPT);
    }


    // Méthodes CRUD
    
    /**
     * La méthode create() permet de sérialiser dans la base de données de l'application l'instance courante de la classe Utilisateur
     *  - Une exception est levée si le nombre de lignes modifiées est différent de 1
     */
    public function create() : void {
        // Récupération de l'objet DAO
        $dao = DAO::get();

        // Initialisation de la requête SQL
        $requete = 'INSERT INTO Utilisateur VALUES ?, ?, ?, ?, ?, ?';
        // Initialisation du tableau de valeurs pour la requête
        $valeurs = [$this->login, $this->email, $this->password_hash, $this->nom, $this->numeroTelephone, $this->nbJetons];

        // Exécution de la requête
        $nbLignesMod = $dao->exec($requete, $valeurs);

        // Vérification de la bonne exécution de la requête
        if ($nbLignesMod < 1) {
            throw new Exception("L'utilisateur n'a pas été correctement inséré.");
        }
    }

    /**
     * La méthode read() retourne une instance de la classe Utilisateur à partir de son login
     *  - Une exception est levée si l'utilisateur n'a pas été trouvé
     */
    public static function read(string $login) : Utilisateur {
        // Récupération de la classe DAO
        $dao = DAO::get();

        // Initialisation de la requête et du tableau de valeurs
        $requete = 'SELECT * FROM Utilisateur WHERE login = ?';
        $valeurs = [$login];

        // Exécution de la requête
        $table = $dao->query($requete, $valeurs);

        // Levée d'une exception si la requête ne renvoie pas d'utilisateur
        if (count($table) != 1) {
            throw new Exception("L'utilisateur " . $login . " n'a pas été trouvé.");
        } else {
            $utilisateur = Utilisateur::construct_fromdb($table[0]);
        }
    }
    
    /**
     * La méthode update() permet de sérialiser des modifications apportées à une instance de la classe Utilisateur dans la
     * base de données
     *  - Une exception est levée si l'utilisateur n'a pas pu être modifié
     */
    public function update() : void {
        // Récupération de l'objet DAO
        $dao = DAO::get();

        // Initialisation de la requête SQL
        $requete = 'UPDATE Utilisateur SET login = ?, email = ?, mdpHash = ?, nom = ?, numeroDeTelephone = ?, nbJetons = ? WHERE login = ?';
        // Initialisation du tableau de valeurs pour la requête
        $valeurs = [$this->login, $this->email, $this->password_hash, $this->nom, $this->numeroTelephone, $this->nbJetons, $this->login];

        // Exécution de la requête
        $nbLignesMod = $dao->exec($requete, $valeurs);

        // Vérification de la bonne exécution de la requête
        if ($nbLignesMod < 1) {
            throw new Exception("L'utilisateur n'a pas été modifié.");
        }
    }

    /**
     * La méthode delete() permet de supprimer l'utilisateur correspondant à l'instance courante de la classe
     * Utilisateur de la base de données.
     *  - Une exception est levée si l'utilisateur n'est pas présent dans la base de données
     */
    public function delete() : void {
        // Récupération de l'objet DAO
        $dao = DAO::get();

        // Initialisation de la requête et du tableau de valeurs
        $requete = 'DELETE FROM Utilisateur WHERE login = ?';
        $valeurs = [$this->login];

        // Exécution de la requête
        $nbLignesMod = $dao->exec($requete, $valeurs);

        if ($nbLignesMod < 1) {
            throw new Exception("L'utilisateur " . $this->login . " n'existe pas.");
        }
    }
}

