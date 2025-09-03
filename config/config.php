<?php
/**
 * Configuration générale de l'application
 */

// Configuration de l'application
define('APP_NAME', 'Pioneer Tech ProjectManager');
define('APP_VERSION', '1.0.0');
define('APP_URL', 'http://localhost/pioneer tech');

// Configuration de sécurité
define('SALT', 'your_random_salt_here_change_this');

// Configuration des rôles
define('ROLE_ADMIN', 'admin');
define('ROLE_EMPLOYEE', 'employé');

// Configuration des statuts de projet
define('STATUS_EN_COURS', 'en cours');
define('STATUS_TERMINE', 'terminé');
define('STATUS_ANNULE', 'annulé');

// Configuration des statuts de facture
define('STATUS_PAYEE', 'payée');
define('STATUS_IMPAYEE', 'impayée');

// Configuration de pagination
define('ITEMS_PER_PAGE', 10);

// Configuration des uploads
define('UPLOAD_PATH', BASE_PATH . '/uploads');
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB

// Timezone
date_default_timezone_set('Europe/Paris');
?>
