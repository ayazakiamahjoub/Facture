<?php
/**
 * Script de test pour vérifier la connexion à la base de données
 */

// Inclure la configuration
require_once 'config/database.php';

echo "<h1>Test de connexion - ProjectManager</h1>";

try {
    // Tester la connexion à la base de données
    $db = Database::getInstance();
    $connection = $db->getConnection();
    
    echo "<div style='color: green; padding: 10px; border: 1px solid green; margin: 10px 0;'>";
    echo "✅ Connexion à la base de données réussie !";
    echo "</div>";
    
    // Tester une requête simple
    $stmt = $db->query("SELECT COUNT(*) as total FROM users");
    $result = $stmt->fetch();
    
    echo "<div style='color: blue; padding: 10px; border: 1px solid blue; margin: 10px 0;'>";
    echo "📊 Nombre d'utilisateurs dans la base : " . $result['total'];
    echo "</div>";
    
    // Lister les tables
    $stmt = $db->query("SHOW TABLES");
    $tables = $stmt->fetchAll();
    
    echo "<div style='color: purple; padding: 10px; border: 1px solid purple; margin: 10px 0;'>";
    echo "📋 Tables disponibles :<br>";
    foreach ($tables as $table) {
        echo "- " . array_values($table)[0] . "<br>";
    }
    echo "</div>";
    
    // Informations PHP
    echo "<div style='color: orange; padding: 10px; border: 1px solid orange; margin: 10px 0;'>";
    echo "🐘 Version PHP : " . PHP_VERSION . "<br>";
    echo "📁 Répertoire de travail : " . __DIR__ . "<br>";
    echo "🌐 URL actuelle : " . $_SERVER['REQUEST_URI'] ?? 'N/A';
    echo "</div>";
    
    echo "<div style='color: green; padding: 10px; border: 1px solid green; margin: 10px 0;'>";
    echo "🎉 <strong>Tout fonctionne parfaitement !</strong><br>";
    echo "<a href='index.php' style='color: blue; text-decoration: none;'>➡️ Accéder à l'application ProjectManager</a>";
    echo "</div>";
    
} catch (Exception $e) {
    echo "<div style='color: red; padding: 10px; border: 1px solid red; margin: 10px 0;'>";
    echo "❌ Erreur de connexion : " . $e->getMessage();
    echo "</div>";
    
    echo "<div style='color: orange; padding: 10px; border: 1px solid orange; margin: 10px 0;'>";
    echo "🔧 <strong>Vérifications à effectuer :</strong><br>";
    echo "1. MySQL est-il démarré ?<br>";
    echo "2. La base de données 'project_manager' existe-t-elle ?<br>";
    echo "3. Les paramètres dans config/database.php sont-ils corrects ?<br>";
    echo "4. L'utilisateur MySQL a-t-il les bonnes permissions ?";
    echo "</div>";
}
?>

<style>
body {
    font-family: Arial, sans-serif;
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
    background-color: #f5f5f5;
}

h1 {
    color: #333;
    text-align: center;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 20px;
    border-radius: 10px;
    margin-bottom: 30px;
}

div {
    border-radius: 5px;
    font-family: monospace;
}
</style>
