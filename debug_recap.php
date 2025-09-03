<?php
/**
 * Script de diagnostic pour le tableau récapitulatif
 */

// Configuration de base
define('BASE_PATH', __DIR__);
define('CONFIG_PATH', BASE_PATH . '/config');

// Inclusion des fichiers de configuration
require_once CONFIG_PATH . '/database.php';

echo "<h1>🔍 Diagnostic du Tableau Récapitulatif</h1>";

try {
    $db = Database::getInstance();
    echo "<p>✅ Connexion à la base de données réussie</p>";
    
    // 1. Vérifier les tables existantes
    echo "<h2>1. Tables existantes</h2>";
    $stmt = $db->query("SHOW TABLES");
    $tables = $stmt->fetchAll();
    
    $tableNames = [];
    foreach ($tables as $table) {
        $tableName = array_values($table)[0];
        $tableNames[] = $tableName;
        echo "<li>{$tableName}</li>";
    }
    
    // 2. Vérifier la table equipe_projet
    echo "<h2>2. Structure de la table equipe_projet</h2>";
    if (in_array('equipe_projet', $tableNames)) {
        echo "<p>✅ Table equipe_projet trouvée</p>";
        
        $stmt = $db->query("DESCRIBE equipe_projet");
        $columns = $stmt->fetchAll();
        
        echo "<table border='1' style='border-collapse: collapse;'>";
        echo "<tr style='background-color: #f0f0f0;'><th>Colonne</th><th>Type</th></tr>";
        foreach ($columns as $col) {
            echo "<tr><td>{$col['Field']}</td><td>{$col['Type']}</td></tr>";
        }
        echo "</table>";
        
        // Compter les enregistrements
        $stmt = $db->query("SELECT COUNT(*) as count FROM equipe_projet");
        $count = $stmt->fetch()['count'];
        echo "<p>Nombre d'enregistrements dans equipe_projet: <strong>{$count}</strong></p>";
        
    } else {
        echo "<p>❌ Table equipe_projet non trouvée</p>";
        echo "<p>Tables disponibles: " . implode(', ', $tableNames) . "</p>";
    }
    
    // 3. Vérifier les autres tables nécessaires
    echo "<h2>3. Vérification des autres tables</h2>";
    $requiredTables = ['clients', 'projets', 'users', 'factures'];
    
    foreach ($requiredTables as $table) {
        if (in_array($table, $tableNames)) {
            $stmt = $db->query("SELECT COUNT(*) as count FROM {$table}");
            $count = $stmt->fetch()['count'];
            echo "<p>✅ {$table}: {$count} enregistrements</p>";
        } else {
            echo "<p>❌ {$table}: table manquante</p>";
        }
    }
    
    // 4. Test de la requête principale
    echo "<h2>4. Test de la requête principale</h2>";
    
    $sql = "
        SELECT 
            c.id as client_id,
            c.nom_client,
            c.email as client_email,
            c.telephone as client_telephone,
            c.actif as client_actif,
            c.date_creation as client_date_creation,
            COUNT(DISTINCT p.id) as nb_projets,
            SUM(CASE WHEN p.statut = 'en cours' THEN 1 ELSE 0 END) as projets_en_cours,
            SUM(CASE WHEN p.statut = 'terminé' THEN 1 ELSE 0 END) as projets_termines,
            COALESCE(SUM(p.budget), 0) as budget_total,
            COALESCE(SUM(f.montant), 0) as chiffre_affaires
        FROM clients c
        LEFT JOIN projets p ON c.id = p.id_client
        LEFT JOIN factures f ON c.id = f.id_client AND f.statut = 'payée'
        WHERE c.actif = 1
        GROUP BY c.id, c.nom_client, c.email, c.telephone, c.actif, c.date_creation
        ORDER BY c.nom_client ASC
        LIMIT 5
    ";
    
    try {
        $clients = $db->query($sql)->fetchAll();
        echo "<p>✅ Requête principale réussie</p>";
        echo "<p>Nombre de clients trouvés: <strong>" . count($clients) . "</strong></p>";
        
        if (!empty($clients)) {
            echo "<h3>Premiers clients:</h3>";
            echo "<table border='1' style='border-collapse: collapse;'>";
            echo "<tr style='background-color: #f0f0f0;'>";
            echo "<th>ID</th><th>Nom</th><th>Email</th><th>Nb Projets</th>";
            echo "</tr>";
            
            foreach ($clients as $client) {
                echo "<tr>";
                echo "<td>{$client['client_id']}</td>";
                echo "<td>{$client['nom_client']}</td>";
                echo "<td>{$client['client_email']}</td>";
                echo "<td>{$client['nb_projets']}</td>";
                echo "</tr>";
            }
            echo "</table>";
        }
        
    } catch (Exception $e) {
        echo "<p>❌ Erreur dans la requête principale: " . $e->getMessage() . "</p>";
    }
    
    // 5. Test de la requête équipe
    echo "<h2>5. Test de la requête équipe</h2>";
    
    if (in_array('equipe_projet', $tableNames)) {
        $sqlEquipe = "
            SELECT 
                u.id as id_utilisateur,
                u.nom,
                u.email,
                u.role as role_utilisateur,
                ep.role_projet,
                ep.date_assignation,
                ep.id_projet
            FROM equipe_projet ep
            JOIN users u ON ep.id_utilisateur = u.id
            WHERE u.actif = 1
            LIMIT 5
        ";
        
        try {
            $equipes = $db->query($sqlEquipe)->fetchAll();
            echo "<p>✅ Requête équipe réussie</p>";
            echo "<p>Nombre d'assignations trouvées: <strong>" . count($equipes) . "</strong></p>";
            
            if (!empty($equipes)) {
                echo "<h3>Premières assignations:</h3>";
                echo "<table border='1' style='border-collapse: collapse;'>";
                echo "<tr style='background-color: #f0f0f0;'>";
                echo "<th>Projet ID</th><th>Utilisateur</th><th>Rôle</th><th>Date</th>";
                echo "</tr>";
                
                foreach ($equipes as $equipe) {
                    echo "<tr>";
                    echo "<td>{$equipe['id_projet']}</td>";
                    echo "<td>{$equipe['nom']}</td>";
                    echo "<td>{$equipe['role_projet']}</td>";
                    echo "<td>{$equipe['date_assignation']}</td>";
                    echo "</tr>";
                }
                echo "</table>";
            }
            
        } catch (Exception $e) {
            echo "<p>❌ Erreur dans la requête équipe: " . $e->getMessage() . "</p>";
        }
    }
    
    // 6. Test d'accès au contrôleur
    echo "<h2>6. Test d'accès au contrôleur</h2>";
    
    if (file_exists('controllers/DashboardController.php')) {
        echo "<p>✅ DashboardController.php trouvé</p>";
        
        // Vérifier si la méthode recap existe
        $content = file_get_contents('controllers/DashboardController.php');
        if (strpos($content, 'function recap') !== false) {
            echo "<p>✅ Méthode recap() trouvée dans le contrôleur</p>";
        } else {
            echo "<p>❌ Méthode recap() non trouvée dans le contrôleur</p>";
        }
        
    } else {
        echo "<p>❌ DashboardController.php non trouvé</p>";
    }
    
    // 7. Test d'accès à la vue
    echo "<h2>7. Test d'accès à la vue</h2>";
    
    if (file_exists('views/dashboard/recap.php')) {
        echo "<p>✅ Vue recap.php trouvée</p>";
    } else {
        echo "<p>❌ Vue recap.php non trouvée</p>";
    }
    
} catch (Exception $e) {
    echo "<h1>❌ Erreur de connexion</h1>";
    echo "<p>Erreur : " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<h2>🔗 Liens de test</h2>";
echo "<ul>";
echo "<li><a href='index.php'>Page d'accueil</a></li>";
echo "<li><a href='index.php?page=dashboard'>Dashboard</a></li>";
echo "<li><a href='index.php?page=dashboard&action=recap'>Tableau récapitulatif</a></li>";
echo "</ul>";
?>
