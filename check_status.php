<?php
/**
 * Script de vérification du statut de l'application
 */

header('Content-Type: application/json');

$status = [
    'application' => 'ProjectManager',
    'version' => '1.0.0',
    'timestamp' => date('Y-m-d H:i:s'),
    'status' => 'OK',
    'checks' => []
];

// Vérification PHP
$status['checks']['php'] = [
    'version' => PHP_VERSION,
    'status' => version_compare(PHP_VERSION, '7.4.0', '>=') ? 'OK' : 'ERROR',
    'message' => version_compare(PHP_VERSION, '7.4.0', '>=') ? 'Version PHP compatible' : 'Version PHP trop ancienne'
];

// Vérification des extensions PHP
$required_extensions = ['pdo', 'pdo_mysql', 'session', 'json'];
$status['checks']['extensions'] = [];

foreach ($required_extensions as $ext) {
    $status['checks']['extensions'][$ext] = [
        'status' => extension_loaded($ext) ? 'OK' : 'ERROR',
        'message' => extension_loaded($ext) ? 'Extension chargée' : 'Extension manquante'
    ];
}

// Vérification de la base de données
try {
    require_once 'config/database.php';
    $db = Database::getInstance();
    $connection = $db->getConnection();
    
    $status['checks']['database'] = [
        'status' => 'OK',
        'message' => 'Connexion à la base de données réussie'
    ];
    
    // Vérification des tables
    $tables = ['users', 'clients', 'projets', 'equipe_projet', 'factures'];
    $status['checks']['tables'] = [];
    
    foreach ($tables as $table) {
        try {
            $stmt = $db->query("SELECT 1 FROM {$table} LIMIT 1");
            $status['checks']['tables'][$table] = [
                'status' => 'OK',
                'message' => 'Table accessible'
            ];
        } catch (Exception $e) {
            $status['checks']['tables'][$table] = [
                'status' => 'ERROR',
                'message' => 'Table inaccessible: ' . $e->getMessage()
            ];
        }
    }
    
    // Compter les utilisateurs
    try {
        $stmt = $db->query("SELECT COUNT(*) as total FROM users");
        $result = $stmt->fetch();
        $status['checks']['data'] = [
            'status' => 'OK',
            'message' => 'Données de test présentes',
            'users_count' => $result['total']
        ];
    } catch (Exception $e) {
        $status['checks']['data'] = [
            'status' => 'ERROR',
            'message' => 'Erreur lors de la lecture des données: ' . $e->getMessage()
        ];
    }
    
} catch (Exception $e) {
    $status['checks']['database'] = [
        'status' => 'ERROR',
        'message' => 'Erreur de connexion: ' . $e->getMessage()
    ];
}

// Vérification des fichiers de configuration
$config_files = [
    'config/config.php' => 'Configuration générale',
    'config/database.php' => 'Configuration base de données',
    'index.php' => 'Point d\'entrée principal'
];

$status['checks']['files'] = [];

foreach ($config_files as $file => $description) {
    $status['checks']['files'][$file] = [
        'status' => file_exists($file) ? 'OK' : 'ERROR',
        'message' => file_exists($file) ? $description . ' présent' : $description . ' manquant'
    ];
}

// Vérification des permissions
$writable_dirs = ['assets', 'views'];
$status['checks']['permissions'] = [];

foreach ($writable_dirs as $dir) {
    $status['checks']['permissions'][$dir] = [
        'status' => is_readable($dir) ? 'OK' : 'WARNING',
        'message' => is_readable($dir) ? 'Répertoire accessible' : 'Problème d\'accès au répertoire'
    ];
}

// Déterminer le statut global
$global_status = 'OK';
foreach ($status['checks'] as $category => $checks) {
    if (is_array($checks)) {
        foreach ($checks as $check) {
            if (isset($check['status']) && $check['status'] === 'ERROR') {
                $global_status = 'ERROR';
                break 2;
            }
        }
    } else {
        if ($checks['status'] === 'ERROR') {
            $global_status = 'ERROR';
            break;
        }
    }
}

$status['status'] = $global_status;

// URLs importantes
$status['urls'] = [
    'application' => 'http://localhost/ProjectManager',
    'test_connection' => 'http://localhost/ProjectManager/test_connection.php',
    'admin_login' => 'http://localhost/ProjectManager/index.php?page=auth&action=login'
];

// Comptes de démonstration
$status['demo_accounts'] = [
    'admin' => [
        'email' => 'admin@projectmanager.com',
        'password' => 'password',
        'role' => 'admin'
    ],
    'employee' => [
        'email' => 'jean.dupont@example.com',
        'password' => 'password',
        'role' => 'employé'
    ]
];

echo json_encode($status, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
?>
