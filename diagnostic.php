<?php
/**
 * Script de diagnostic pour ProjectManager
 */

header('Content-Type: text/html; charset=utf-8');

echo "<!DOCTYPE html>";
echo "<html><head><title>Diagnostic ProjectManager</title>";
echo "<style>
body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
.ok { color: green; background: #e8f5e8; padding: 10px; margin: 5px 0; border-left: 4px solid green; }
.error { color: red; background: #ffe8e8; padding: 10px; margin: 5px 0; border-left: 4px solid red; }
.warning { color: orange; background: #fff8e8; padding: 10px; margin: 5px 0; border-left: 4px solid orange; }
.info { color: blue; background: #e8f0ff; padding: 10px; margin: 5px 0; border-left: 4px solid blue; }
h1 { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; text-align: center; }
.section { background: white; padding: 15px; margin: 10px 0; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
</style></head><body>";

echo "<h1>🔍 Diagnostic ProjectManager</h1>";

// 1. Vérification de l'environnement
echo "<div class='section'>";
echo "<h2>📋 Environnement</h2>";

echo "<div class='info'>PHP Version: " . PHP_VERSION . "</div>";
echo "<div class='info'>Serveur: " . $_SERVER['SERVER_SOFTWARE'] . "</div>";
echo "<div class='info'>Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "</div>";
echo "<div class='info'>Script actuel: " . $_SERVER['SCRIPT_NAME'] . "</div>";
echo "<div class='info'>URL demandée: " . $_SERVER['REQUEST_URI'] . "</div>";

echo "</div>";

// 2. Vérification des fichiers
echo "<div class='section'>";
echo "<h2>📁 Fichiers essentiels</h2>";

$files = [
    'index.php' => 'Point d\'entrée principal',
    'config/database.php' => 'Configuration base de données',
    'config/config.php' => 'Configuration générale',
    'controllers/AuthController.php' => 'Contrôleur d\'authentification',
    'models/User.php' => 'Modèle utilisateur',
    'views/layouts/header.php' => 'Layout header'
];

foreach ($files as $file => $description) {
    if (file_exists($file)) {
        echo "<div class='ok'>✅ {$description} ({$file})</div>";
    } else {
        echo "<div class='error'>❌ {$description} manquant ({$file})</div>";
    }
}

echo "</div>";

// 3. Vérification de la base de données
echo "<div class='section'>";
echo "<h2>🗄️ Base de données</h2>";

try {
    require_once 'config/database.php';
    $db = Database::getInstance();
    echo "<div class='ok'>✅ Connexion à la base de données réussie</div>";
    
    $tables = ['users', 'clients', 'projets', 'equipe_projet', 'factures'];
    foreach ($tables as $table) {
        try {
            $stmt = $db->query("SELECT COUNT(*) as count FROM {$table}");
            $result = $stmt->fetch();
            echo "<div class='ok'>✅ Table {$table}: {$result['count']} enregistrements</div>";
        } catch (Exception $e) {
            echo "<div class='error'>❌ Erreur table {$table}: " . $e->getMessage() . "</div>";
        }
    }
} catch (Exception $e) {
    echo "<div class='error'>❌ Erreur de connexion: " . $e->getMessage() . "</div>";
}

echo "</div>";

// 4. Vérification des extensions PHP
echo "<div class='section'>";
echo "<h2>🔧 Extensions PHP</h2>";

$extensions = ['pdo', 'pdo_mysql', 'session', 'json', 'mbstring'];
foreach ($extensions as $ext) {
    if (extension_loaded($ext)) {
        echo "<div class='ok'>✅ Extension {$ext} chargée</div>";
    } else {
        echo "<div class='error'>❌ Extension {$ext} manquante</div>";
    }
}

echo "</div>";

// 5. Vérification des permissions
echo "<div class='section'>";
echo "<h2>🔐 Permissions</h2>";

$dirs = ['assets', 'config', 'views', 'controllers', 'models'];
foreach ($dirs as $dir) {
    if (is_readable($dir)) {
        echo "<div class='ok'>✅ Répertoire {$dir} accessible en lecture</div>";
    } else {
        echo "<div class='warning'>⚠️ Problème d'accès au répertoire {$dir}</div>";
    }
}

echo "</div>";

// 6. Test des URLs
echo "<div class='section'>";
echo "<h2>🌐 URLs de test</h2>";

$base_url = "http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['SCRIPT_NAME']);
$urls = [
    'index.php' => 'Application principale',
    'test_connection.php' => 'Test de connexion',
    'check_status.php' => 'Statut JSON'
];

foreach ($urls as $url => $description) {
    $full_url = $base_url . '/' . $url;
    echo "<div class='info'>🔗 <a href='{$full_url}' target='_blank'>{$description}</a> ({$url})</div>";
}

echo "</div>";

// 7. Informations de débogage
echo "<div class='section'>";
echo "<h2>🐛 Informations de débogage</h2>";

echo "<div class='info'>Répertoire de travail: " . getcwd() . "</div>";
echo "<div class='info'>Chemin du script: " . __FILE__ . "</div>";
echo "<div class='info'>Heure du serveur: " . date('Y-m-d H:i:s') . "</div>";

if (file_exists('.htaccess')) {
    echo "<div class='info'>📄 Fichier .htaccess présent</div>";
    echo "<pre style='background: #f0f0f0; padding: 10px; border-radius: 3px; font-size: 12px;'>";
    echo htmlspecialchars(file_get_contents('.htaccess'));
    echo "</pre>";
} else {
    echo "<div class='warning'>⚠️ Fichier .htaccess absent</div>";
}

echo "</div>";

// 8. Actions recommandées
echo "<div class='section'>";
echo "<h2>💡 Actions recommandées</h2>";

echo "<div class='info'>
<strong>Si vous voyez cette page, c'est bon signe !</strong><br><br>
Pour accéder à l'application :<br>
1. <a href='index.php'>Cliquez ici pour accéder à ProjectManager</a><br>
2. Utilisez le compte admin : admin@projectmanager.com / password<br>
3. Ou le compte employé : jean.dupont@example.com / password
</div>";

echo "</div>";

echo "</body></html>";
?>
