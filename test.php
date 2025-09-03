<?php
echo "<h1>Test de connexion - Pioneer Tech</h1>";
echo "<p>✅ PHP fonctionne correctement !</p>";
echo "<p>Heure actuelle : " . date('Y-m-d H:i:s') . "</p>";
echo "<p>Version PHP : " . phpversion() . "</p>";

// Test de la base de données
try {
    define('BASE_PATH', __DIR__);
    define('CONFIG_PATH', BASE_PATH . '/config');
    
    if (file_exists(CONFIG_PATH . '/database.php')) {
        require_once CONFIG_PATH . '/database.php';
        $db = Database::getInstance();
        echo "<p>✅ Connexion à la base de données réussie !</p>";
        
        // Test simple
        $stmt = $db->query("SELECT 1 as test");
        $result = $stmt->fetch();
        echo "<p>✅ Requête de test réussie : " . $result['test'] . "</p>";
        
    } else {
        echo "<p>❌ Fichier database.php non trouvé</p>";
    }
} catch (Exception $e) {
    echo "<p>❌ Erreur de base de données : " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<h2>Liens de test :</h2>";
echo "<ul>";
echo "<li><a href='index.php'>Page d'accueil</a></li>";
echo "<li><a href='index.php?page=auth&action=login'>Page de connexion</a></li>";
echo "<li><a href='index.php?page=dashboard'>Dashboard</a></li>";
echo "<li><a href='index.php?page=dashboard&action=recap'>Tableau récapitulatif</a></li>";
echo "</ul>";
?>
