<?php
/**
 * Configuration de la base de données
 */

class Database {
    private static $instance = null;
    private $connection;
    
    // Configuration de la base de données Pioneer Tech
    private $host = 'localhost';
    private $dbname = 'pioneer_tech';
    private $username = 'root';
    private $password = '';
    private $charset = 'utf8mb4';
    
    private function __construct() {
        try {
            $dsn = "mysql:host={$this->host};dbname={$this->dbname};charset={$this->charset}";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            
            $this->connection = new PDO($dsn, $this->username, $this->password, $options);
        } catch (PDOException $e) {
            throw new Exception("Erreur de connexion à la base de données : " . $e->getMessage());
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->connection;
    }
    
    // Méthode pour exécuter des requêtes préparées
    public function query($sql, $params = []) {
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de l'exécution de la requête : " . $e->getMessage());
        }
    }
    
    // Méthode pour obtenir le dernier ID inséré
    public function lastInsertId() {
        return $this->connection->lastInsertId();
    }
    
    // Méthode pour commencer une transaction
    public function beginTransaction() {
        return $this->connection->beginTransaction();
    }
    
    // Méthode pour valider une transaction
    public function commit() {
        return $this->connection->commit();
    }
    
    // Méthode pour annuler une transaction
    public function rollback() {
        return $this->connection->rollback();
    }
}

// Fonction helper pour obtenir la connexion
function getDB() {
    return Database::getInstance();
}
?>
