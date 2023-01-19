<?php
namespace Bidwell\Model;

use Exception;

/**
 * Classe représentant le concept de Catégorie
 * "contenant" du modèle composite (Enchere est le "contenu")
 *  - Tant que la catégorie n'est pas présente dans la base de donnée
 *    (méthode create()), le booléen isInDB est faux
 *  - Une catégorie peut être fille d'une autre, elle a donc un attribut
 *	  categorieMere qui correspond à une autre catégorie
 */
class Categorie {
    // Attributs
    private string $libelle;
    private bool $isInDB;
    private Categorie|null $categorieMere;


	// Constucteur

	/**
	 * Constructeur de la classe Categorie
	 *  - La catégorie mère eut être nulle
	 *  - Par défaut, une catégorie n'est pas dans la base de données
	 * @param string $libelle Libelle de la nouvelle Categorie
	 * @param Categorie|null $categorieMere Categorie mère de la nouvelle
	 *		  catégorie
	 */
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

	/**
	 * Le getter getLibelleColle() permet d'obtenir le libellé d'une
	 * catégorie sans espace.
	 * @return string Libellé de la catégorie sans espace
	 */
    public function getLibelleColle() : string {
        $a = str_replace(" ", "", $this->libelle);
        return str_replace("'", '\'', $a);
    }

    public function getCategorieMere() : Categorie|null {
        return $this->categorieMere;
    }

    public function isInDB() : bool {
        return $this->isInDB;
    }


    // Setters
	
    public function setCategorieMere(Categorie|null $categorieMere) :void {
        $this->categorieMere = $categorieMere;
    }

    
    // Méthodes

    /**
     * La fonction getCategorieAutre() permet d'obtenir la catégorie par
     * défault Autre.
     *  - Wrapper de read() qui crée la catégorie si elle n'existe pas déjà
     * @return Categorie Categorie par défaut Autre
     * @throws Exception si on n'arrive pas à lire la catégorie Autre alors qu'elle est dans la base
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
	 * La procédure create() permet d'insérer l'instance courante de la classe
	 * Categorie dans la base de données
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

    /**
     * La fonction readFromCategorieMere() sert à récupérer les catégories
     * en fonction de leur catégorie mère.
     * @param Categorie $categorieMere : categorie mère des catégories à récupérer
     * @return array : Tableau de catégories dont la catégorie mère est categorieMere
     */
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
        return $dao->query($query,array());
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
        // on enlève le fait que cette catégorie est mère dans tous ses fils
        // AVANT de faire la suppression dans la bd
        // les enchères ont alors comme catégorie 'Autre'
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
