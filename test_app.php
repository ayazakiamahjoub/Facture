<?php
/**
 * Test simple de l'application Pioneer Tech
 */

session_start();

echo "<h1>Test de l'application Pioneer Tech</h1>";

// Test des chemins
echo "<h2>Vérification des chemins</h2>";
echo "Répertoire actuel : " . __DIR__ . "<br>";
echo "BASE_PATH défini : " . (defined('BASE_PATH') ? BASE_PATH : 'Non défini') . "<br>";

// Test des dossiers
$folders = ['config', 'controllers', 'models', 'views', 'assets'];
echo "<h2>Vérification des dossiers</h2>";
foreach ($folders as $folder) {
    $path = __DIR__ . '/' . $folder;
    echo "Dossier {$folder} : " . (is_dir($path) ? '✅ Existe' : '❌ Manquant') . "<br>";
}

// Test des fichiers essentiels
$files = [
    'config/database.php',
    'config/config.php',
    'controllers/AuthController.php',
    'controllers/DashboardController.php',
    'models/User.php',
    'views/layouts/header.php'
];

echo "<h2>Vérification des fichiers essentiels</h2>";
foreach ($files as $file) {
    $path = __DIR__ . '/' . $file;
    echo "Fichier {$file} : " . (file_exists($path) ? '✅ Existe' : '❌ Manquant') . "<br>";
}

// Test de la base de données
echo "<h2>Test de la base de données</h2>";
try {
    if (file_exists(__DIR__ . '/config/database.php')) {
        require_once __DIR__ . '/config/database.php';
        $db = Database::getInstance();
        echo "✅ Connexion à la base de données réussie<br>";
        
        // Test d'une requête simple
        $stmt = $db->query("SELECT COUNT(*) as count FROM users");
        $result = $stmt->fetch();
        echo "✅ Nombre d'utilisateurs : " . $result['count'] . "<br>";
    } else {
        echo "❌ Fichier de configuration de base de données manquant<br>";
    }
} catch (Exception $e) {
    echo "❌ Erreur de base de données : " . $e->getMessage() . "<br>";
}

// Test des sessions
echo "<h2>Test des sessions</h2>";
echo "Session ID : " . session_id() . "<br>";
echo "Utilisateur connecté : " . (isset($_SESSION['user_id']) ? 'Oui (ID: ' . $_SESSION['user_id'] . ')' : 'Non') . "<br>";

// Liens de test
echo "<h2>Liens de test</h2>";
echo "<a href='index.php'>🏠 Page d'accueil</a><br>";
echo "<a href='index.php?page=auth&action=login'>🔐 Connexion</a><br>";
echo "<a href='test_connection.php'>🔧 Test de connexion</a><br>";
echo "<a href='diagnostic.php'>🩺 Diagnostic complet</a><br>";

echo "<hr>";
echo "<p><strong>Si tous les tests sont verts (✅), l'application devrait fonctionner correctement.</strong></p>";
?>
