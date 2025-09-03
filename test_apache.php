<?php
/**
 * Test de configuration Apache
 */

echo "<h1>Test de configuration Apache</h1>";

// Vérifier si mod_rewrite est activé
if (function_exists('apache_get_modules')) {
    $modules = apache_get_modules();
    if (in_array('mod_rewrite', $modules)) {
        echo "<div style='color: green; padding: 10px; border: 1px solid green; margin: 10px 0;'>";
        echo "✅ mod_rewrite est activé";
        echo "</div>";
    } else {
        echo "<div style='color: red; padding: 10px; border: 1px solid red; margin: 10px 0;'>";
        echo "❌ mod_rewrite n'est pas activé";
        echo "</div>";
    }
} else {
    echo "<div style='color: orange; padding: 10px; border: 1px solid orange; margin: 10px 0;'>";
    echo "⚠️ Impossible de vérifier les modules Apache (fonction apache_get_modules non disponible)";
    echo "</div>";
}

// Informations sur le serveur
echo "<div style='color: blue; padding: 10px; border: 1px solid blue; margin: 10px 0;'>";
echo "<strong>Informations serveur :</strong><br>";
echo "Serveur : " . $_SERVER['SERVER_SOFTWARE'] . "<br>";
echo "Document Root : " . $_SERVER['DOCUMENT_ROOT'] . "<br>";
echo "Script Name : " . $_SERVER['SCRIPT_NAME'] . "<br>";
echo "Request URI : " . $_SERVER['REQUEST_URI'] . "<br>";
echo "</div>";

// Test de .htaccess
echo "<div style='color: purple; padding: 10px; border: 1px solid purple; margin: 10px 0;'>";
echo "<strong>Test .htaccess :</strong><br>";
if (file_exists('.htaccess')) {
    echo "✅ Fichier .htaccess présent<br>";
    echo "Contenu :<br>";
    echo "<pre style='background: #f5f5f5; padding: 10px; margin: 10px 0;'>";
    echo htmlspecialchars(file_get_contents('.htaccess'));
    echo "</pre>";
} else {
    echo "❌ Fichier .htaccess manquant";
}
echo "</div>";

// Liens de test
echo "<div style='color: green; padding: 10px; border: 1px solid green; margin: 10px 0;'>";
echo "<strong>Liens de test :</strong><br>";
echo "<a href='index.php' style='color: blue; margin-right: 20px;'>index.php (direct)</a>";
echo "<a href='test_connection.php' style='color: blue; margin-right: 20px;'>test_connection.php</a>";
echo "<a href='check_status.php' style='color: blue; margin-right: 20px;'>check_status.php</a>";
echo "</div>";

// Configuration PHP
echo "<div style='color: gray; padding: 10px; border: 1px solid gray; margin: 10px 0;'>";
echo "<strong>Configuration PHP :</strong><br>";
echo "Version PHP : " . PHP_VERSION . "<br>";
echo "Extensions chargées : " . implode(', ', get_loaded_extensions()) . "<br>";
echo "</div>";
?>

<style>
body {
    font-family: Arial, sans-serif;
    max-width: 1000px;
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

a {
    text-decoration: none;
    padding: 5px 10px;
    background: #007bff;
    color: white;
    border-radius: 3px;
    display: inline-block;
    margin: 5px;
}

a:hover {
    background: #0056b3;
}
</style>
