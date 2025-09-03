<?php
/**
 * Script de débogage pour vérifier les tables de la base de données
 */

// Configuration de base
define('BASE_PATH', __DIR__);
define('CONFIG_PATH', BASE_PATH . '/config');

// Inclusion des fichiers de configuration
require_once CONFIG_PATH . '/database.php';

try {
    $db = Database::getInstance();
    
    echo "<h1>Structure de la base de données - Pioneer Tech</h1>";
    
    // 1. Lister toutes les tables
    echo "<h2>1. Tables existantes</h2>";
    $stmt = $db->query("SHOW TABLES");
    $tables = $stmt->fetchAll();
    
    echo "<ul>";
    foreach ($tables as $table) {
        $tableName = array_values($table)[0];
        echo "<li><strong>{$tableName}</strong></li>";
    }
    echo "</ul>";
    
    // 2. Structure de chaque table
    foreach ($tables as $table) {
        $tableName = array_values($table)[0];
        
        echo "<h3>Table: {$tableName}</h3>";
        
        $stmt = $db->query("DESCRIBE {$tableName}");
        $columns = $stmt->fetchAll();
        
        echo "<table border='1' style='border-collapse: collapse; margin-bottom: 20px;'>";
        echo "<tr style='background-color: #f0f0f0;'><th>Colonne</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
        foreach ($columns as $col) {
            echo "<tr>";
            echo "<td>{$col['Field']}</td>";
            echo "<td>{$col['Type']}</td>";
            echo "<td>{$col['Null']}</td>";
            echo "<td>{$col['Key']}</td>";
            echo "<td>{$col['Default']}</td>";
            echo "<td>{$col['Extra']}</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        // Compter les enregistrements
        $stmt = $db->query("SELECT COUNT(*) as count FROM {$tableName}");
        $count = $stmt->fetch()['count'];
        echo "<p><em>Nombre d'enregistrements: {$count}</em></p>";
        
        echo "<hr>";
    }
    
} catch (Exception $e) {
    echo "<h1>❌ Erreur de connexion à la base de données</h1>";
    echo "<p>Erreur : " . $e->getMessage() . "</p>";
}
?>
