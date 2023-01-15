<?php
namespace Bidwell\Bidwell\Model;

use Exception;

/**
 * Classe représentant le concept de Catégorie
 * "contenant" du modèle composite (Enchere est le "contenu")
 */
class Categorie {
    // Attributs
    private string $libelle;
    // booleen qui signifie si l'utilisateur est enregistré dans la base
    private bool $isInDB;
    private Categorie|null $categorieMere;

    // constructeur
    public function __construct(string $libelle, Categorie $categorieMere = null)
    {
        $this->libelle = $libelle;
        $this->isInDB = false;
        $this->categorieMere = $categorieMere;
    }

    // Getters
    public function getLibelle() : string {
        return $this->libelle;
    }

    public function getLibelleColle() : string {
        return trim($this->libelle);
    }

    public function getCategorieMere() : Categorie|null {
        return $this->categorieMere;
    }

    // Setters
    public function setCategorieMere(Categorie|null $categorieMere) :void {
        $this->categorieMere = $categorieMere;
    }

    // Autres méthodes

    /**
     * Vérifie si la catégorie est enregistrée dans la bd
     */
    public function isInDB() : bool {
        return $this->isInDB;
    }

    /**
     * Synchronise la catégorie avec ses valeurs en bd
     */
    public function sync() : void {
        $new_this = Categorie::read($this->getLibelle());
        $this->categorieMere = $new_this->categorieMere;
    }

    /**
     * Fonction qui renvoie la catégorie par défaut 'Autre'
     * Wrapper de read() qui crée la catégorie si elle n'existe pas déjà
     */
    public static function getCategorieAutre() : Categorie {
        try {
            return Categorie::read('Autre');
        } catch (Exception) {
            $out = new Categorie('Autre');
            $out->create();
            return $out;
        }
    }

    /////////////////////////////////////////////
    //                  CRUD                   //
    /////////////////////////////////////////////

    ////////////////// CREATE ///////////////////

    /**
     * Enregistre la catégorie dans la bd
     * @throws Exception si la catégorie est déjà dans la bd ou si l'insertion échoue
     */
    public function create() : void {
        if ($this->isInDB()) {
            throw new Exception("La categorie $this->libelle est déjà dans la bd");
        }

        // récupération du dao
        $dao = DAO::get();

        // si la catégorie a un parent (ce n'est pas la catégorie racine), on l'inclut dans le create
        if ($this->categorieMere !== null) {
            $query = 'INSERT INTO Categorie(libelle, libelleMere) VALUES (?,?)';
            $data = [$this->libelle, $this->categorieMere->getLibelle()];
        } else {
            $query = 'INSERT INTO Categorie(libelle) VALUES (?)';
            $data = [$this->libelle];
        }

        // récupération du résultat de l'insertion
        $r = $dao->exec($query, $data);

        // si on n'a pas exactement une ligne insérée, throw une exception
        if ($r != 1) {
            throw new Exception("L'insertion de l'enchère $this->libelle a échoué");
        }

        $this->isInDB = true;
    }

    /////////////////// READ ////////////////////

    /**
     * Récupère une catégorie dans la bd à partir de son libelle
     * @throws Exception si on ne trouve pas la catégorie dans la bd ou si plusieurs catégories ont le même libelle dans la bd
     */
    public static function read(string $libelle) : Categorie {
        // récupératoin du dao
        $dao = DAO::get();

        // préparation de la query
        $query = 'SELECT * FROM Categorie WHERE libelle = ?';
        $data = [$libelle];

        // récupération de la table de résultat
        $table = $dao->query($query, $data);

        // throw une exception si on ne trouve pas la catégorie
        if (count($table) == 0) {
            throw new Exception("Catégorie $libelle non trouvée");
        }

        // throw une exception si on trouve plusieurs catégories
        if (count($table) > 1) {
            throw new Exception("Catégorie $libelle existe en ".count($table).' exemplaires');
        }

        return Categorie::constructFromDB($table[0]);
    }

    public static function readFromCategorieMere(Categorie $categorieMere) : array {
        // récupératoin du dao
        $dao = DAO::get();

        // préparation de la query
        $query = 'SELECT * FROM Categorie WHERE libelleMere = ?';
        $data = [$categorieMere->libelle];

        // récupération de la table de résultat
        $table = $dao->query($query, $data);

        $out = array();
        foreach ($table as $row) {
            $out[] = Categorie::constructFromDB($row, $categorieMere);
        }

        return $out;
    }

    public static function readOnlyCategorieMere() : array {
        // récupératoin du dao
        $dao = DAO::get();

        // préparation de la query
        $query = 'SELECT * FROM Categorie WHERE libelleMere IS NULL';
        $data = [];

        // récupération de la table de résultat
        $table = $dao->query($query, $data);

        $out = array();
        foreach ($table as $row) {
            $out[] = Categorie::constructFromDB($row);
        }

        return $out;
    }

    public static function readOnlyCategorieFille() : array {
        // récupératoin du dao
        $dao = DAO::get();

        // préparation de la query
        $query = 'SELECT * FROM Categorie WHERE libelleMere IS NOT NULL';
        $data = [];

        // récupération de la table de résultat
        $table = $dao->query($query, $data);

        $out = array();
        foreach ($table as $row) {
            $out[] = Categorie::constructFromDB($row);
        }

        return $out;
    }
    public static function readLibelleCategorieFilles(): array{
        $dao = DAO::get();
        $query = 'SELECT libelle FROM Categorie WHERE libelleMere IS NOT NULL';
        // récupération de la table de résultat
        $table = $dao->query($query,array()); 
        return $table;  
    }

    private static function constructFromDB(array $row, ?Categorie $categorieMere = null) : Categorie {
        // création d'un objet catégorie avec les informations de la bd
        $categorie = new Categorie($row['libelle']);

        if (!isset($categorieMere) && isset($row['libelleMere'])) {
            $categorieMere = Categorie::read($row['libelleMere']);
        }

        $categorie->categorieMere = $categorieMere;

        $categorie->isInDB = true;

        return $categorie;
    }

    ////////////////// UPDATE ///////////////////

    /**
     * Met à jour les valeurs de la catégorie dans la bd
     * @throws Exception si la catégorie n'existe pas dans la bd ou si le nombre de lignes modifiées != 1
     */
    public function update() : void {
        if (!$this->isInDB()) {
            throw new Exception("Update : La catégorie $this->libelle n'existe pas dans la bd");
        }

        // récupération du dao
        $dao = DAO::get();

        // si la catégorie a un parent (ce n'est pas la catégorie racine) on passe son libelle en paramètre
        $query = 'UPDATE Categorie SET libelleMere = ? WHERE libelle = ?';
        if ($this->categorieMere !== null) {
            $data = [$this->categorieMere->getLibelle(), $this->libelle];
        } else {
            $data = [null, $this->libelle];
        }

        $nbLignesMod = $dao->exec($query, $data);

        // Vérification de la bonne exécution de la requête
        if ($nbLignesMod != 1) {
            throw new Exception("Update : Nombre de ligne modifiée != 1 lors de la modification de la catégorie $this->libelle");
        }
    }

    ////////////////// DELETE ///////////////////

    /**
     * Supprime la catégorie de la bd
     * @throws Exception si la catégorie n'existe pas dans la bd
     */
    public function delete() : void {
        if (!$this->isInDB()) {
            throw new Exception("Delete : La catégorie $this->libelle n'existe pas dans la bd");
        }

        // on enleve le fait que cette catégorie est mère dans tous ses fils
        // AVANT de faire la suppression dans la bd
        // les enchère ont alors comme catégorie 'Autre'
        foreach (Categorie::readFromCategorieMere($this) as $categorieFille) {
            $categorieFille->categorieMere = null;
            if ($categorieFille->isInDB())
                $categorieFille->update();
        }
        foreach (Enchere::readFromCategorie($this) as $enchereFille) {
            $enchereFille->setCategorie(Categorie::getCategorieAutre());
            if ($enchereFille->isInDB())
                $enchereFille->update();
        }

        // récupération du dao
        $dao = DAO::get();

        // préparation du query
        $query = 'DELETE FROM Categorie WHERE libelle = ?';
        $data = [$this->libelle];

        $dao->exec($query, $data);

        $this->isInDB = false;
    }

  
}