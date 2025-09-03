<?php
/**
 * Script de débogage pour l'authentification
 */

session_start();

// Configuration de base
define('BASE_PATH', __DIR__);
define('CONFIG_PATH', BASE_PATH . '/config');

// Inclusion des fichiers de configuration
require_once CONFIG_PATH . '/database.php';

try {
    $db = Database::getInstance();
    
    echo "<h1>Debug Authentification - Pioneer Tech</h1>";
    
    // 1. Vérifier la connexion à la base de données
    echo "<h2>1. Connexion à la base de données</h2>";
    echo "✅ Connexion réussie<br><br>";
    
    // 2. Vérifier la table users
    echo "<h2>2. Table users</h2>";
    $stmt = $db->query("DESCRIBE users");
    $columns = $stmt->fetchAll();
    
    echo "<table border='1' style='border-collapse: collapse;'>";
    echo "<tr><th>Colonne</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
    foreach ($columns as $col) {
        echo "<tr>";
        echo "<td>{$col['Field']}</td>";
        echo "<td>{$col['Type']}</td>";
        echo "<td>{$col['Null']}</td>";
        echo "<td>{$col['Key']}</td>";
        echo "<td>{$col['Default']}</td>";
        echo "</tr>";
    }
    echo "</table><br>";
    
    // 3. Lister les utilisateurs
    echo "<h2>3. Utilisateurs existants</h2>";
    $stmt = $db->query("SELECT id, nom, email, role, actif, date_creation FROM users");
    $users = $stmt->fetchAll();
    
    if (empty($users)) {
        echo "❌ <strong>Aucun utilisateur trouvé !</strong><br>";
        echo "Il faut créer des utilisateurs de test.<br><br>";
        
        // Créer des utilisateurs de test
        echo "<h3>Création d'utilisateurs de test...</h3>";
        
        $testUsers = [
            [
                'nom' => 'Administrateur',
                'email' => 'admin@pioneertech.com',
                'password' => 'password',
                'role' => 'admin'
            ],
            [
                'nom' => 'Jean Dupont',
                'email' => 'jean.dupont@pioneertech.com',
                'password' => 'password',
                'role' => 'employé'
            ],
            [
                'nom' => 'Marie Martin',
                'email' => 'marie.martin@pioneertech.com',
                'password' => 'password',
                'role' => 'employé'
            ]
        ];
        
        foreach ($testUsers as $user) {
            $hashedPassword = password_hash($user['password'], PASSWORD_DEFAULT);
            
            $sql = "INSERT INTO users (nom, email, mot_de_passe, role, actif, date_creation) 
                    VALUES (?, ?, ?, ?, 1, NOW())";
            
            try {
                $stmt = $db->query($sql, [
                    $user['nom'],
                    $user['email'],
                    $hashedPassword,
                    $user['role']
                ]);
                echo "✅ Utilisateur créé : {$user['email']} (mot de passe: {$user['password']})<br>";
            } catch (Exception $e) {
                echo "❌ Erreur création {$user['email']} : " . $e->getMessage() . "<br>";
            }
        }
        
        echo "<br><strong>Rechargez cette page pour voir les utilisateurs créés.</strong><br><br>";
        
    } else {
        echo "<table border='1' style='border-collapse: collapse;'>";
        echo "<tr><th>ID</th><th>Nom</th><th>Email</th><th>Rôle</th><th>Actif</th><th>Date création</th></tr>";
        foreach ($users as $user) {
            $actif = $user['actif'] ? '✅ Oui' : '❌ Non';
            echo "<tr>";
            echo "<td>{$user['id']}</td>";
            echo "<td>{$user['nom']}</td>";
            echo "<td>{$user['email']}</td>";
            echo "<td>{$user['role']}</td>";
            echo "<td>{$actif}</td>";
            echo "<td>{$user['date_creation']}</td>";
            echo "</tr>";
        }
        echo "</table><br>";
    }
    
    // 4. Test d'authentification
    echo "<h2>4. Test d'authentification</h2>";
    
    if (!empty($users)) {
        $testEmail = 'admin@pioneertech.com';
        $testPassword = 'password';
        
        echo "Test avec : {$testEmail} / {$testPassword}<br>";
        
        // Récupérer l'utilisateur
        $stmt = $db->query("SELECT * FROM users WHERE email = ? AND actif = 1", [$testEmail]);
        $user = $stmt->fetch();
        
        if ($user) {
            echo "✅ Utilisateur trouvé : {$user['nom']}<br>";
            echo "Hash stocké : " . substr($user['mot_de_passe'], 0, 20) . "...<br>";
            
            // Tester password_verify
            if (password_verify($testPassword, $user['mot_de_passe'])) {
                echo "✅ <strong>Mot de passe correct !</strong><br>";
            } else {
                echo "❌ <strong>Mot de passe incorrect !</strong><br>";
                
                // Recréer le hash
                $newHash = password_hash($testPassword, PASSWORD_DEFAULT);
                $updateSql = "UPDATE users SET mot_de_passe = ? WHERE email = ?";
                $db->query($updateSql, [$newHash, $testEmail]);
                echo "🔧 Hash du mot de passe mis à jour.<br>";
            }
        } else {
            echo "❌ Utilisateur non trouvé ou inactif<br>";
        }
    }
    
    // 5. Vérifier les sessions
    echo "<h2>5. Session actuelle</h2>";
    if (isset($_SESSION['user_id'])) {
        echo "✅ Utilisateur connecté :<br>";
        echo "- ID: {$_SESSION['user_id']}<br>";
        echo "- Nom: {$_SESSION['user_name']}<br>";
        echo "- Rôle: {$_SESSION['user_role']}<br>";
        echo "- Connecté depuis: " . date('d/m/Y H:i:s', $_SESSION['login_time']) . "<br>";
    } else {
        echo "❌ Aucun utilisateur connecté<br>";
    }
    
    echo "<br>";
    echo "<h2>6. Actions</h2>";
    echo "<a href='index.php?page=auth&action=login' style='padding: 10px; background: #007bff; color: white; text-decoration: none; border-radius: 5px;'>Aller à la connexion</a> ";
    echo "<a href='index.php?page=dashboard' style='padding: 10px; background: #28a745; color: white; text-decoration: none; border-radius: 5px;'>Aller au dashboard</a> ";
    echo "<a href='debug_auth.php' style='padding: 10px; background: #ffc107; color: black; text-decoration: none; border-radius: 5px;'>Recharger debug</a>";
    
} catch (Exception $e) {
    echo "<h1>❌ Erreur de connexion à la base de données</h1>";
    echo "<p>Erreur : " . $e->getMessage() . "</p>";
    echo "<p>Vérifiez votre configuration dans config/database.php</p>";
}
?>
