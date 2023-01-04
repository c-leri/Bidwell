<?php

/**
 * Les utilisateurs enregistrés sur l'application
 */
class Utilisateur {
    // informations de connection
    private string $login;
    private string $mdpHash;        // le hash correspondant au mot de passe de l'utilisateur, calculé avec password_hash() 
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
        return $this->login == $login && $this->mdpHash === password_hash($password, PASSWORD_BCRYPT);
    }
}