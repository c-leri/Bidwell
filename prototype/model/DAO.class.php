<?php

// Copié des TPs de R3.01

// Le Data Access Object 
// Il représente la base de donnée
class DAO
{
  // le singleton de la classe : l'unique objet
  private static $instance = null;

  // L'objet local PDO de la base de donnée
  private PDO $db;

  // Le type, le chemin et le nom de la base de donnée
  private string $database = 'sqlite:' . __DIR__ . '/../data/database.db';

  // Constructeur chargé d'ouvrir la BD
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

  // Méthode statique pour acceder au singleton
  public static function get(): DAO
  {
    // Si l'objet n'a pas encore été crée, le crée
    if (is_null(self::$instance)) {
      self::$instance = new DAO();
    }
    return self::$instance;
  }

  // Lance une requête sur la BD, et retourne une table
  // C'est une requête préparée avec des '?' à la place des données
  // Les données sont à passer séparément dans le même ordre dans $data
  public function query(string $query, array $data): array
  {
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
    $table = $s->fetchAll();
    return $table;
  }

  // Lance une requête syr la BD, et retourne le nombre de ligne modifiées
  // C'est une requête préparée avec des '?' à la place des données
  // Les données sont à passer séparément dans le même ordre dans $data
  public function exec(string $query, array $data): int
  {
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
    $result = $s->rowCount();
    return $result;
  }
}
