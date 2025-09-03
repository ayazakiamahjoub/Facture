<?php
/**
 * Script pour créer la table equipe_projets
 */

// Configuration de base
define('BASE_PATH', __DIR__);
define('CONFIG_PATH', BASE_PATH . '/config');

// Inclusion des fichiers de configuration
require_once CONFIG_PATH . '/database.php';

try {
    $db = Database::getInstance();
    
    echo "<h1>Création de la table equipe_projets</h1>";
    
    // Vérifier si la table existe déjà
    $stmt = $db->query("SHOW TABLES LIKE 'equipe_projets'");
    $exists = $stmt->fetch();
    
    if ($exists) {
        echo "<p>✅ La table equipe_projets existe déjà.</p>";
    } else {
        // Créer la table equipe_projets
        $sql = "
            CREATE TABLE equipe_projets (
                id INT PRIMARY KEY AUTO_INCREMENT,
                id_projet INT NOT NULL,
                id_utilisateur INT NOT NULL,
                role_projet ENUM('chef', 'membre') DEFAULT 'membre',
                date_assignation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                date_retrait TIMESTAMP NULL,
                actif BOOLEAN DEFAULT 1,
                FOREIGN KEY (id_projet) REFERENCES projets(id) ON DELETE CASCADE,
                FOREIGN KEY (id_utilisateur) REFERENCES users(id) ON DELETE CASCADE,
                UNIQUE KEY unique_assignment (id_projet, id_utilisateur)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ";
        
        $db->query($sql);
        echo "<p>✅ Table equipe_projets créée avec succès !</p>";
        
        // Ajouter quelques données de test
        echo "<h2>Ajout de données de test</h2>";
        
        // Récupérer quelques projets et utilisateurs existants
        $projets = $db->query("SELECT id FROM projets LIMIT 5")->fetchAll();
        $users = $db->query("SELECT id FROM users WHERE actif = 1 LIMIT 5")->fetchAll();
        
        if (!empty($projets) && !empty($users)) {
            $testData = [
                // Projet 1
                ['id_projet' => $projets[0]['id'], 'id_utilisateur' => $users[0]['id'], 'role_projet' => 'chef'],
                ['id_projet' => $projets[0]['id'], 'id_utilisateur' => $users[1]['id'], 'role_projet' => 'membre'],
                
                // Projet 2 (si existe)
                ['id_projet' => isset($projets[1]) ? $projets[1]['id'] : $projets[0]['id'], 'id_utilisateur' => $users[0]['id'], 'role_projet' => 'membre'],
                ['id_projet' => isset($projets[1]) ? $projets[1]['id'] : $projets[0]['id'], 'id_utilisateur' => isset($users[2]) ? $users[2]['id'] : $users[1]['id'], 'role_projet' => 'chef'],
                
                // Projet 3 (si existe)
                ['id_projet' => isset($projets[2]) ? $projets[2]['id'] : $projets[0]['id'], 'id_utilisateur' => isset($users[1]) ? $users[1]['id'] : $users[0]['id'], 'role_projet' => 'chef'],
            ];
            
            foreach ($testData as $data) {
                try {
                    $insertSql = "INSERT INTO equipe_projets (id_projet, id_utilisateur, role_projet) VALUES (?, ?, ?)";
                    $db->query($insertSql, [$data['id_projet'], $data['id_utilisateur'], $data['role_projet']]);
                    echo "<p>✅ Assignation ajoutée : Projet {$data['id_projet']} - Utilisateur {$data['id_utilisateur']} ({$data['role_projet']})</p>";
                } catch (Exception $e) {
                    echo "<p>⚠️ Assignation ignorée (probablement déjà existante) : Projet {$data['id_projet']} - Utilisateur {$data['id_utilisateur']}</p>";
                }
            }
        } else {
            echo "<p>⚠️ Pas assez de projets ou d'utilisateurs pour créer des données de test.</p>";
        }
    }
    
    // Afficher le contenu de la table
    echo "<h2>Contenu de la table equipe_projets</h2>";
    $stmt = $db->query("
        SELECT 
            ep.id,
            ep.id_projet,
            p.titre_projet,
            ep.id_utilisateur,
            u.nom as nom_utilisateur,
            ep.role_projet,
            ep.date_assignation
        FROM equipe_projets ep
        JOIN projets p ON ep.id_projet = p.id
        JOIN users u ON ep.id_utilisateur = u.id
        WHERE ep.actif = 1
        ORDER BY ep.id_projet, ep.role_projet DESC, u.nom
    ");
    $assignments = $stmt->fetchAll();
    
    if (!empty($assignments)) {
        echo "<table border='1' style='border-collapse: collapse;'>";
        echo "<tr style='background-color: #f0f0f0;'>";
        echo "<th>ID</th><th>Projet</th><th>Utilisateur</th><th>Rôle</th><th>Date assignation</th>";
        echo "</tr>";
        
        foreach ($assignments as $assignment) {
            echo "<tr>";
            echo "<td>{$assignment['id']}</td>";
            echo "<td>{$assignment['titre_projet']}</td>";
            echo "<td>{$assignment['nom_utilisateur']}</td>";
            echo "<td><span style='color: " . ($assignment['role_projet'] === 'chef' ? 'orange' : 'blue') . ";'>{$assignment['role_projet']}</span></td>";
            echo "<td>{$assignment['date_assignation']}</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>Aucune assignation trouvée.</p>";
    }
    
    echo "<br><br>";
    echo "<a href='index.php?page=dashboard&action=recap' style='padding: 10px; background: #007bff; color: white; text-decoration: none; border-radius: 5px;'>Aller au tableau récapitulatif</a>";
    
} catch (Exception $e) {
    echo "<h1>❌ Erreur</h1>";
    echo "<p>Erreur : " . $e->getMessage() . "</p>";
}
?>
