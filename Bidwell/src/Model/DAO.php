<?php
namespace Bidwell\Bidwell\Model;

use Exception;
use PDO;
use PDOException;
use PDOStatement;

// Le Data Access Object
// Il représente la base de donnée
class DAO
{
    // le singleton de la classe : l'unique objet
    private static DAO|null $instance = null;

    // L'objet local PDO de la base de donnée
    private PDO $db;

    // Le type, le chemin et le nom de la base de donnée
    private string $database = 'sqlite:' . __DIR__ . '/../../data/database.db';

    /**
     * Constructeur chargé d'ouvrir la BD
     * @throws Exception si l'initialisation du pdo échoue
     */
    private function __construct()
    {
        try {
            $this->db = new PDO($this->database);
            //var_dump($this);
            if (!$this->db) {
                throw new Exception("Impossible d'ouvrir " . $this->database);
            }
            // Positionne PDO pour lancer les erreurs sous forme d'exeptions
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            // Attrape l'exception pour y ajouter la requête
            throw new PDOException(__METHOD__ . " at " . __LINE__ . ": " . $e->getMessage() . " DataBase=\"" . $this->database . '"');
        }
    }

    /**
     * Méthode statique pour accéder au singleton
     */
    public static function get(): DAO
    {
        // Si l'objet n'a pas encore été créé, le crée
        if (is_null(self::$instance)) {
            self::$instance = new DAO();
        }
        return self::$instance;
    }

    /**
     * Lance une requête sur la BD et retourne une table
     */
    public function query(string $query, array $data): array
    {
        return $this->execute($query, $data)->fetchAll();
    }

    /**
     * Lance une requête syr la BD et retourne le nombre de lignes modifiées
     */
    public function exec(string $query, array $data): int
    {
        return $this->execute($query, $data)->rowCount();
    }

    /**
     * Code commun à query et exec
     * @return PDOStatement avec le résultat de la requête
     */
    private function execute(string $query, array $data) : PDOStatement {
        try {
            // Compile la requête, produit un PDOStatement
            $s = $this->db->prepare($query);
            // Exécute la requête avec les données
            $s->execute($data);
        } catch (Exception $e) {
            // Attrape l'exception pour y ajouter la requête
            throw new PDOException(__METHOD__ . " at " . __LINE__ . ": " . $e->getMessage() . " Query=\"" . $query . '"');
        }

        // Affiche en clair l'erreur PDO si la requête ne peut pas s'exécuter
        if ($s === false) {
            throw new PDOException(__METHOD__ . " at " . __LINE__ . ": " . implode('|', $this->db->errorInfo()) . " Query=\"" . $query . '"');
        }

        return $s;
    }

    /**
     * @return string|false le dernier id inséré dans la base
     */
    public function lastInsertId() : string|false {
        return $this->db->lastInsertId();
    }
}