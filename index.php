<?php
/**
 * Point d'entrée principal de l'application ProjectManager
 * Gestion du routage et initialisation de l'application
 */

session_start();

// Configuration de base
define('BASE_PATH', __DIR__);
define('CONFIG_PATH', BASE_PATH . '/config');
define('CONTROLLERS_PATH', BASE_PATH . '/controllers');
define('MODELS_PATH', BASE_PATH . '/models');
define('VIEWS_PATH', BASE_PATH . '/views');

// Inclusion des fichiers de configuration
require_once CONFIG_PATH . '/database.php';
require_once CONFIG_PATH . '/config.php';

// Autoloader simple pour les classes
spl_autoload_register(function ($class) {
    $paths = [
        MODELS_PATH . '/' . $class . '.php',
        CONTROLLERS_PATH . '/' . $class . '.php'
    ];
    
    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            break;
        }
    }
});

// Routage simple
$page = $_GET['page'] ?? 'dashboard';
$action = $_GET['action'] ?? 'index';

// Vérification de l'authentification
if (!isset($_SESSION['user_id']) && $page !== 'auth') {
    header('Location: index.php?page=auth&action=login');
    exit;
}

// Routage vers les contrôleurs
switch ($page) {
    case 'auth':
        $controller = new AuthController();
        break;
    case 'users':
        $controller = new UserController();
        break;
    case 'clients':
        $controller = new ClientController();
        break;
    case 'projets':
        $controller = new ProjetController();
        break;
    case 'equipes':
        $controller = new EquipeController();
        break;
    case 'factures':
        $controller = new FactureController();
        break;
    case 'dashboard':
    default:
        $controller = new DashboardController();
        break;
}

// Exécution de l'action
if (method_exists($controller, $action)) {
    $controller->$action();
} else {
    $controller->index();
}
?>
