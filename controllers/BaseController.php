<?php
/**
 * Contrôleur de base pour tous les contrôleurs
 * Contient les méthodes communes
 */

class BaseController {
    protected $user;
    
    public function __construct() {
        // Vérifier si l'utilisateur est connecté
        if (isset($_SESSION['user_id'])) {
            $userModel = new User();
            $this->user = $userModel->getById($_SESSION['user_id']);
        }
    }
    
    /**
     * Vérifier si l'utilisateur est connecté
     */
    protected function requireAuth() {
        if (!$this->isLoggedIn()) {
            $this->redirect('index.php?page=auth&action=login');
        }
    }
    
    /**
     * Vérifier si l'utilisateur est admin
     */
    protected function requireAdmin() {
        $this->requireAuth();
        if (!$this->isAdmin()) {
            $this->showError('Accès refusé. Droits administrateur requis.');
        }
    }
    
    /**
     * Vérifier si l'utilisateur est connecté
     */
    protected function isLoggedIn() {
        return isset($_SESSION['user_id']) && $this->user;
    }
    
    /**
     * Vérifier si l'utilisateur est admin
     */
    protected function isAdmin() {
        return $this->user && $this->user['role'] === ROLE_ADMIN;
    }
    
    /**
     * Rediriger vers une URL
     */
    protected function redirect($url) {
        header("Location: {$url}");
        exit;
    }
    
    /**
     * Afficher une vue
     */
    protected function render($view, $data = []) {
        // Extraire les données pour les rendre disponibles dans la vue
        extract($data);

        // Données communes à toutes les vues
        $currentUser = $this->user;
        $isAdmin = $this->isAdmin();
        $appName = APP_NAME;

        // Inclure le header
        include VIEWS_PATH . '/layouts/header.php';

        // Inclure la vue demandée
        $viewFile = VIEWS_PATH . '/' . $view . '.php';
        if (file_exists($viewFile)) {
            include $viewFile;
        } else {
            $this->showError("Vue non trouvée : {$view}");
        }

        // Inclure le footer
        include VIEWS_PATH . '/layouts/footer.php';
    }

    /**
     * Afficher une vue sans layout (pour les pages d'authentification)
     */
    protected function renderWithoutLayout($view, $data = []) {
        // Extraire les données pour les rendre disponibles dans la vue
        extract($data);

        // Données communes à toutes les vues
        $currentUser = $this->user;
        $isAdmin = $this->isAdmin();
        $appName = APP_NAME;

        // Inclure seulement la vue demandée
        $viewFile = VIEWS_PATH . '/' . $view . '.php';
        if (file_exists($viewFile)) {
            include $viewFile;
        } else {
            $this->showError("Vue non trouvée : {$view}");
        }
    }
    
    /**
     * Retourner une réponse JSON
     */
    protected function jsonResponse($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    
    /**
     * Afficher une page d'erreur
     */
    protected function showError($message, $statusCode = 500) {
        http_response_code($statusCode);
        $this->render('error', ['message' => $message]);
        exit;
    }
    
    /**
     * Valider les données POST
     */
    protected function validateRequired($fields) {
        $errors = [];
        
        foreach ($fields as $field) {
            if (!isset($_POST[$field]) || empty(trim($_POST[$field]))) {
                $errors[] = "Le champ {$field} est requis.";
            }
        }
        
        return $errors;
    }
    
    /**
     * Nettoyer les données d'entrée
     */
    protected function sanitizeInput($data) {
        if (is_array($data)) {
            return array_map([$this, 'sanitizeInput'], $data);
        }
        
        return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
    }
    
    /**
     * Ajouter un message flash
     */
    protected function setFlashMessage($message, $type = 'info') {
        $_SESSION['flash_message'] = [
            'message' => $message,
            'type' => $type
        ];
    }
    
    /**
     * Récupérer et supprimer le message flash
     */
    protected function getFlashMessage() {
        if (isset($_SESSION['flash_message'])) {
            $message = $_SESSION['flash_message'];
            unset($_SESSION['flash_message']);
            return $message;
        }
        return null;
    }
    
    /**
     * Paginer les résultats
     */
    protected function paginate($totalItems, $currentPage = 1, $itemsPerPage = ITEMS_PER_PAGE) {
        $totalPages = ceil($totalItems / $itemsPerPage);
        $currentPage = max(1, min($currentPage, $totalPages));
        $offset = ($currentPage - 1) * $itemsPerPage;
        
        return [
            'current_page' => $currentPage,
            'total_pages' => $totalPages,
            'total_items' => $totalItems,
            'items_per_page' => $itemsPerPage,
            'offset' => $offset,
            'has_previous' => $currentPage > 1,
            'has_next' => $currentPage < $totalPages,
            'previous_page' => $currentPage - 1,
            'next_page' => $currentPage + 1
        ];
    }
    
    /**
     * Valider un email
     */
    protected function isValidEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
    
    /**
     * Valider une date
     */
    protected function isValidDate($date, $format = 'Y-m-d') {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }
    
    /**
     * Formater un montant
     */
    protected function formatAmount($amount) {
        return number_format($amount, 2, ',', ' ') . ' €';
    }
    
    /**
     * Formater une date
     */
    protected function formatDate($date, $format = 'd/m/Y') {
        if (empty($date)) return '';
        return date($format, strtotime($date));
    }
}
?>
