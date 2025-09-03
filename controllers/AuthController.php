<?php
/**
 * Contrôleur d'authentification
 * Gestion de la connexion et déconnexion
 */

require_once 'BaseController.php';

class AuthController extends BaseController {
    private $userModel;
    
    public function __construct() {
        parent::__construct();
        $this->userModel = new User();
    }
    
    /**
     * Afficher la page de connexion
     */
    public function login() {    
        // Si l'utilisateur est déjà connecté, rediriger vers le dashboard
        if ($this->isLoggedIn()) {
            $this->redirect('index.php?page=dashboard');
        }
        
        $error = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $this->sanitizeInput($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            
            // Validation
            if (empty($email) || empty($password)) {
                $error = 'Veuillez remplir tous les champs.';
            } elseif (!$this->isValidEmail($email)) {
                $error = 'Format d\'email invalide.';
            } else {
                // Tentative d'authentification
                $user = $this->userModel->authenticate($email, $password);
                
                if ($user) {
                    // Connexion réussie
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['nom'];
                    $_SESSION['user_role'] = $user['role'];
                    $_SESSION['login_time'] = time();
                    
                    // Rediriger vers le dashboard
                    $this->redirect('index.php?page=dashboard');
                } else {
                    $error = 'Email ou mot de passe incorrect.';
                }
            }
        }
        
        $this->renderWithoutLayout('auth/login', [
            'error' => $error,
            'email' => $email ?? ''
        ]);
    }
    
    /**
     * Déconnexion
     */
    public function logout() {
        // Détruire la session
        session_destroy();
        
        // Rediriger vers la page de connexion
        $this->redirect('index.php?page=auth&action=login');
    }
    
    /**
     * Afficher la page d'inscription (pour les admins)
     */
    public function register() {
        $this->requireAdmin();
        
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
                // Créer l'utilisateur
                $userData = [
                    'nom' => $formData['nom'],
                    'email' => $formData['email'],
                    'mot_de_passe' => $formData['mot_de_passe'],
                    'role' => $formData['role']
                ];
                
                try {
                    $userId = $this->userModel->createUser($userData);
                    $this->setFlashMessage('Utilisateur créé avec succès.', 'success');
                    $this->redirect('index.php?page=users');
                } catch (Exception $e) {
                    $errors[] = 'Erreur lors de la création de l\'utilisateur.';
                }
            }
        }
        
        $this->render('auth/register', [
            'errors' => $errors,
            'formData' => $formData
        ]);
    }
    
    /**
     * Changer le mot de passe
     */
    public function changePassword() {
        $this->requireAuth();
        
        $errors = [];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $currentPassword = $_POST['current_password'] ?? '';
            $newPassword = $_POST['new_password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            
            // Validation
            if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
                $errors[] = 'Veuillez remplir tous les champs.';
            } elseif (strlen($newPassword) < 6) {
                $errors[] = 'Le nouveau mot de passe doit contenir au moins 6 caractères.';
            } elseif ($newPassword !== $confirmPassword) {
                $errors[] = 'Les nouveaux mots de passe ne correspondent pas.';
            } else {
                // Vérifier le mot de passe actuel
                $user = $this->userModel->authenticate($this->user['email'], $currentPassword);
                
                if (!$user) {
                    $errors[] = 'Mot de passe actuel incorrect.';
                } else {
                    // Mettre à jour le mot de passe
                    try {
                        $this->userModel->updateUser($this->user['id'], [
                            'mot_de_passe' => $newPassword
                        ]);
                        
                        $this->setFlashMessage('Mot de passe modifié avec succès.', 'success');
                        $this->redirect('index.php?page=dashboard');
                    } catch (Exception $e) {
                        $errors[] = 'Erreur lors de la modification du mot de passe.';
                    }
                }
            }
        }
        
        $this->render('auth/change_password', [
            'errors' => $errors
        ]);
    }
    
    /**
     * Profil utilisateur
     */
    public function profile() {
        $this->requireAuth();
        
        $errors = [];
        $formData = [
            'nom' => $this->user['nom'],
            'email' => $this->user['email']
        ];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $formData = $this->sanitizeInput($_POST);
            
            // Validation
            $requiredFields = ['nom', 'email'];
            $errors = $this->validateRequired($requiredFields);
            
            if (empty($errors)) {
                if (!$this->isValidEmail($formData['email'])) {
                    $errors[] = 'Format d\'email invalide.';
                }
                
                if ($this->userModel->emailExists($formData['email'], $this->user['id'])) {
                    $errors[] = 'Cet email est déjà utilisé.';
                }
            }
            
            if (empty($errors)) {
                try {
                    $this->userModel->updateUser($this->user['id'], [
                        'nom' => $formData['nom'],
                        'email' => $formData['email']
                    ]);
                    
                    // Mettre à jour les données de session
                    $_SESSION['user_name'] = $formData['nom'];
                    
                    $this->setFlashMessage('Profil mis à jour avec succès.', 'success');
                    $this->redirect('index.php?page=auth&action=profile');
                } catch (Exception $e) {
                    $errors[] = 'Erreur lors de la mise à jour du profil.';
                }
            }
        }
        
        $this->render('auth/profile', [
            'errors' => $errors,
            'formData' => $formData
        ]);
    }
}
?>
