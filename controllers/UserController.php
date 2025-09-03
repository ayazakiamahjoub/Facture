<?php
/**
 * Contrôleur User - Gestion des utilisateurs (Admin uniquement)
 */

require_once 'BaseController.php';

class UserController extends BaseController {
    private $userModel;
    
    public function __construct() {
        parent::__construct();
        $this->requireAdmin();
        $this->userModel = new User();
    }
    
    /**
     * Liste des utilisateurs
     */
    public function index() {
        $page = $_GET['p'] ?? 1;
        $search = $_GET['search'] ?? '';
        
        if (!empty($search)) {
            $users = $this->userModel->findWhere(
                'nom LIKE ? OR email LIKE ?', 
                ["%{$search}%", "%{$search}%"]
            );
            $totalUsers = count($users);
            $pagination = null;
        } else {
            $totalUsers = $this->userModel->count();
            $pagination = $this->paginate($totalUsers, $page);
            $users = $this->userModel->getAll();
        }
        
        $this->render('users/index', [
            'users' => $users,
            'pagination' => $pagination,
            'search' => $search,
            'totalUsers' => $totalUsers
        ]);
    }
    
    /**
     * Afficher un utilisateur
     */
    public function view() {
        $id = $_GET['id'] ?? null;
        
        if (!$id || !$this->userModel->exists($id)) {
            $this->setFlashMessage('Utilisateur non trouvé.', 'error');
            $this->redirect('index.php?page=users');
        }
        
        $user = $this->userModel->getById($id);
        $projects = $this->userModel->getUserProjects($id);
        $activeProjects = $this->userModel->countActiveProjects($id);
        
        $this->render('users/view', [
            'user' => $user,
            'projects' => $projects,
            'activeProjects' => $activeProjects
        ]);
    }
    
    /**
     * Créer un nouvel utilisateur
     */
    public function create() {
        $errors = [];
        $formData = [];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $formData = $this->sanitizeInput($_POST);
            
            // Validation
            $requiredFields = ['nom', 'email', 'mot_de_passe', 'confirm_password', 'role'];
            $errors = $this->validateRequired($requiredFields);
            
            if (empty($errors)) {
                // Validations spécifiques
                if (!$this->isValidEmail($formData['email'])) {
                    $errors[] = 'Format d\'email invalide.';
                }
                
                if (strlen($formData['mot_de_passe']) < 6) {
                    $errors[] = 'Le mot de passe doit contenir au moins 6 caractères.';
                }
                
                if ($formData['mot_de_passe'] !== $formData['confirm_password']) {
                    $errors[] = 'Les mots de passe ne correspondent pas.';
                }
                
                if ($this->userModel->emailExists($formData['email'])) {
                    $errors[] = 'Cet email est déjà utilisé.';
                }
                
                if (!in_array($formData['role'], [ROLE_ADMIN, ROLE_EMPLOYEE])) {
                    $errors[] = 'Rôle invalide.';
                }
            }
            
            if (empty($errors)) {
                try {
                    $userData = [
                        'nom' => $formData['nom'],
                        'email' => $formData['email'],
                        'mot_de_passe' => $formData['mot_de_passe'],
                        'role' => $formData['role']
                    ];
                    
                    $userId = $this->userModel->createUser($userData);
                    $this->setFlashMessage('Utilisateur créé avec succès.', 'success');
                    $this->redirect('index.php?page=users&action=view&id=' . $userId);
                } catch (Exception $e) {
                    $errors[] = 'Erreur lors de la création de l\'utilisateur.';
                }
            }
        }
        
        $this->render('users/create', [
            'errors' => $errors,
            'formData' => $formData
        ]);
    }
    
    /**
     * Modifier un utilisateur
     */
    public function edit() {
        $id = $_GET['id'] ?? null;
        
        if (!$id || !$this->userModel->exists($id)) {
            $this->setFlashMessage('Utilisateur non trouvé.', 'error');
            $this->redirect('index.php?page=users');
        }
        
        $user = $this->userModel->getById($id);
        $errors = [];
        $formData = $user;
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $formData = $this->sanitizeInput($_POST);
            
            // Validation
            $requiredFields = ['nom', 'email', 'role'];
            $errors = $this->validateRequired($requiredFields);
            
            if (empty($errors)) {
                // Validations spécifiques
                if (!$this->isValidEmail($formData['email'])) {
                    $errors[] = 'Format d\'email invalide.';
                }
                
                if ($this->userModel->emailExists($formData['email'], $id)) {
                    $errors[] = 'Cet email est déjà utilisé.';
                }
                
                if (!in_array($formData['role'], [ROLE_ADMIN, ROLE_EMPLOYEE])) {
                    $errors[] = 'Rôle invalide.';
                }
                
                // Validation du mot de passe si fourni
                if (!empty($formData['mot_de_passe'])) {
                    if (strlen($formData['mot_de_passe']) < 6) {
                        $errors[] = 'Le mot de passe doit contenir au moins 6 caractères.';
                    }
                    
                    if ($formData['mot_de_passe'] !== $formData['confirm_password']) {
                        $errors[] = 'Les mots de passe ne correspondent pas.';
                    }
                }
            }
            
            if (empty($errors)) {
                try {
                    $userData = [
                        'nom' => $formData['nom'],
                        'email' => $formData['email'],
                        'role' => $formData['role']
                    ];
                    
                    // Ajouter le mot de passe seulement s'il est fourni
                    if (!empty($formData['mot_de_passe'])) {
                        $userData['mot_de_passe'] = $formData['mot_de_passe'];
                    }
                    
                    $this->userModel->updateUser($id, $userData);
                    $this->setFlashMessage('Utilisateur modifié avec succès.', 'success');
                    $this->redirect('index.php?page=users&action=view&id=' . $id);
                } catch (Exception $e) {
                    $errors[] = 'Erreur lors de la modification de l\'utilisateur.';
                }
            }
        }
        
        $this->render('users/edit', [
            'user' => $user,
            'errors' => $errors,
            'formData' => $formData
        ]);
    }
    
    /**
     * Désactiver un utilisateur
     */
    public function deactivate() {
        $id = $_GET['id'] ?? null;
        
        if (!$id || !$this->userModel->exists($id)) {
            $this->setFlashMessage('Utilisateur non trouvé.', 'error');
            $this->redirect('index.php?page=users');
        }
        
        // Empêcher la désactivation de son propre compte
        if ($id == $this->user['id']) {
            $this->setFlashMessage('Vous ne pouvez pas désactiver votre propre compte.', 'error');
            $this->redirect('index.php?page=users&action=view&id=' . $id);
        }
        
        try {
            $this->userModel->deactivate($id);
            $this->setFlashMessage('Utilisateur désactivé avec succès.', 'success');
        } catch (Exception $e) {
            $this->setFlashMessage('Erreur lors de la désactivation de l\'utilisateur.', 'error');
        }
        
        $this->redirect('index.php?page=users');
    }
    
    /**
     * Réactiver un utilisateur
     */
    public function activate() {
        $id = $_GET['id'] ?? null;
        
        if (!$id || !$this->userModel->exists($id)) {
            $this->setFlashMessage('Utilisateur non trouvé.', 'error');
            $this->redirect('index.php?page=users');
        }
        
        try {
            $this->userModel->activate($id);
            $this->setFlashMessage('Utilisateur réactivé avec succès.', 'success');
        } catch (Exception $e) {
            $this->setFlashMessage('Erreur lors de la réactivation de l\'utilisateur.', 'error');
        }
        
        $this->redirect('index.php?page=users&action=view&id=' . $id);
    }
}
?>
