<?php
/**
 * Contrôleur Projet - Gestion des projets
 */

require_once 'BaseController.php';

class ProjetController extends BaseController {
    private $projetModel;
    private $clientModel;
    private $equipeModel;
    
    public function __construct() {
        parent::__construct();
        $this->requireAuth();
        $this->projetModel = new Projet();
        $this->clientModel = new Client();
        $this->equipeModel = new EquipeProjet();
    }
    
    /**
     * Liste des projets
     */
    public function index() {
        $page = $_GET['p'] ?? 1;
        $search = $_GET['search'] ?? '';
        $status = $_GET['status'] ?? '';
        
        if (!empty($search)) {
            $projets = $this->projetModel->searchProjects($search, $status);
            $totalProjets = count($projets);
            $pagination = null;
        } else if (!empty($status)) {
            $projets = $this->projetModel->getByStatus($status);
            $totalProjets = count($projets);
            $pagination = null;
        } else {
            $totalProjets = $this->projetModel->count();
            $pagination = $this->paginate($totalProjets, $page);
            $projets = $this->projetModel->getAllWithClient();
        }
        
        // Si l'utilisateur n'est pas admin, filtrer ses projets
        if (!$this->isAdmin()) {
            $userProjets = $this->equipeModel->getUserProjects($this->user['id']);
            $userProjetIds = array_column($userProjets, 'id_projet');
            $projets = array_filter($projets, function($projet) use ($userProjetIds) {
                return in_array($projet['id'], $userProjetIds);
            });
        }
        
        $this->render('projets/index', [
            'projets' => $projets,
            'pagination' => $pagination,
            'search' => $search,
            'status' => $status,
            'totalProjets' => $totalProjets
        ]);
    }
    
    /**
     * Afficher un projet
     */
    public function view() {
        $id = $_GET['id'] ?? null;
        
        if (!$id || !$this->projetModel->exists($id)) {
            $this->setFlashMessage('Projet non trouvé.', 'error');
            $this->redirect('index.php?page=projets');
        }
        
        // Vérifier les permissions
        if (!$this->isAdmin() && !$this->equipeModel->isUserAssigned($id, $this->user['id'])) {
            $this->setFlashMessage('Accès refusé à ce projet.', 'error');
            $this->redirect('index.php?page=projets');
        }
        
        $projet = $this->projetModel->getByIdWithClient($id);
        $equipe = $this->projetModel->getProjectTeam($id);
        $factures = $this->projetModel->getProjectInvoices($id);
        $revenue = $this->projetModel->getProjectRevenue($id);
        
        $this->render('projets/view', [
            'projet' => $projet,
            'equipe' => $equipe,
            'factures' => $factures,
            'revenue' => $revenue
        ]);
    }
    
    /**
     * Créer un nouveau projet
     */
    public function create() {
        $this->requireAdmin();
        
        $errors = [];
        $formData = [];
        $clients = $this->clientModel->getActiveClients();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $formData = $this->sanitizeInput($_POST);
            
            // Validation
            $requiredFields = ['titre_projet', 'id_client'];
            $errors = $this->validateRequired($requiredFields);
            
            if (empty($errors)) {
                // Validations spécifiques
                if (!$this->clientModel->exists($formData['id_client'])) {
                    $errors[] = 'Client sélectionné invalide.';
                }
                
                if (!empty($formData['date_debut']) && !$this->isValidDate($formData['date_debut'])) {
                    $errors[] = 'Format de date de début invalide.';
                }
                
                if (!empty($formData['date_fin_prevue']) && !$this->isValidDate($formData['date_fin_prevue'])) {
                    $errors[] = 'Format de date de fin prévue invalide.';
                }
                
                if (!empty($formData['date_debut']) && !empty($formData['date_fin_prevue'])) {
                    if (strtotime($formData['date_fin_prevue']) <= strtotime($formData['date_debut'])) {
                        $errors[] = 'La date de fin doit être postérieure à la date de début.';
                    }
                }
            }
            
            if (empty($errors)) {
                try {
                    $projetData = [
                        'titre_projet' => $formData['titre_projet'],
                        'description' => $formData['description'] ?? '',
                        'id_client' => $formData['id_client'],
                        'statut' => $formData['statut'] ?? 'en cours',
                        'date_debut' => $formData['date_debut'] ?: null,
                        'date_fin_prevue' => $formData['date_fin_prevue'] ?: null,
                        'budget' => !empty($formData['budget']) ? floatval($formData['budget']) : null
                    ];
                    
                    $projetId = $this->projetModel->create($projetData);
                    
                    // Assigner le créateur comme chef de projet
                    $this->equipeModel->assignUserToProject($projetId, $this->user['id'], 'chef');
                    
                    $this->setFlashMessage('Projet créé avec succès.', 'success');
                    $this->redirect('index.php?page=projets&action=view&id=' . $projetId);
                } catch (Exception $e) {
                    $errors[] = 'Erreur lors de la création du projet.';
                }
            }
        }
        
        $this->render('projets/create', [
            'errors' => $errors,
            'formData' => $formData,
            'clients' => $clients
        ]);
    }
    
    /**
     * Modifier un projet
     */
    public function edit() {
        $id = $_GET['id'] ?? null;
        
        if (!$id || !$this->projetModel->exists($id)) {
            $this->setFlashMessage('Projet non trouvé.', 'error');
            $this->redirect('index.php?page=projets');
        }
        
        // Vérifier les permissions (admin ou chef de projet)
        $isChef = $this->equipeModel->getProjectManager($id);
        if (!$this->isAdmin() && (!$isChef || $isChef['id'] != $this->user['id'])) {
            $this->setFlashMessage('Accès refusé. Seuls les administrateurs et chefs de projet peuvent modifier ce projet.', 'error');
            $this->redirect('index.php?page=projets&action=view&id=' . $id);
        }
        
        $projet = $this->projetModel->getById($id);
        $clients = $this->clientModel->getActiveClients();
        $errors = [];
        $formData = $projet;
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $formData = $this->sanitizeInput($_POST);
            
            // Validation
            $requiredFields = ['titre_projet', 'id_client'];
            $errors = $this->validateRequired($requiredFields);
            
            if (empty($errors)) {
                // Validations spécifiques
                if (!$this->clientModel->exists($formData['id_client'])) {
                    $errors[] = 'Client sélectionné invalide.';
                }
                
                if (!empty($formData['date_debut']) && !$this->isValidDate($formData['date_debut'])) {
                    $errors[] = 'Format de date de début invalide.';
                }
                
                if (!empty($formData['date_fin_prevue']) && !$this->isValidDate($formData['date_fin_prevue'])) {
                    $errors[] = 'Format de date de fin prévue invalide.';
                }
            }
            
            if (empty($errors)) {
                try {
                    $projetData = [
                        'titre_projet' => $formData['titre_projet'],
                        'description' => $formData['description'] ?? '',
                        'id_client' => $formData['id_client'],
                        'statut' => $formData['statut'],
                        'date_debut' => $formData['date_debut'] ?: null,
                        'date_fin_prevue' => $formData['date_fin_prevue'] ?: null,
                        'budget' => !empty($formData['budget']) ? floatval($formData['budget']) : null
                    ];
                    
                    $this->projetModel->update($id, $projetData);
                    
                    $this->setFlashMessage('Projet modifié avec succès.', 'success');
                    $this->redirect('index.php?page=projets&action=view&id=' . $id);
                } catch (Exception $e) {
                    $errors[] = 'Erreur lors de la modification du projet.';
                }
            }
        }
        
        $this->render('projets/edit', [
            'projet' => $projet,
            'errors' => $errors,
            'formData' => $formData,
            'clients' => $clients
        ]);
    }
    
    /**
     * Marquer un projet comme terminé
     */
    public function complete() {
        if (!isset($_GET['id'])) {
            $this->setFlashMessage('ID du projet manquant', 'error');
            $this->redirect('index.php?page=projets');
        }

        $id = (int)$_GET['id'];
        
        // Vérifier si le projet existe
        if (!$this->projetModel->exists($id)) {
            $this->setFlashMessage('Projet non trouvé.', 'error');
            $this->redirect('index.php?page=projets');
        }
        
        // Vérifier les permissions (admin ou chef de projet)
        $isChef = $this->equipeModel->getProjectManager($id);
        if (!$this->isAdmin() && (!$isChef || $isChef['id'] != $this->user['id'])) {
            $this->setFlashMessage('Accès refusé. Seuls les administrateurs et chefs de projet peuvent terminer ce projet.', 'error');
            $this->redirect('index.php?page=projets&action=view&id=' . $id);
        }
        
        try {
            // Mettre à jour le statut et la date de fin réelle
            $success = $this->projetModel->update($id, [
                'statut' => 'terminé',
                'date_fin_reelle' => date('Y-m-d')
            ]);
            
            if ($success) {
                $this->setFlashMessage('Projet marqué comme terminé avec succès', 'success');
            } else {
                $this->setFlashMessage('Erreur lors de la mise à jour du projet', 'error');
            }
        } catch (Exception $e) {
            $this->setFlashMessage('Erreur: ' . $e->getMessage(), 'error');
        }
        
        $this->redirect('index.php?page=projets');
    }
    
    /**
     * Gérer l'équipe d'un projet
     */
    public function team() {
        $id = $_GET['id'] ?? null;
        
        if (!$id || !$this->projetModel->exists($id)) {
            $this->setFlashMessage('Projet non trouvé.', 'error');
            $this->redirect('index.php?page=projets');
        }
        
        // Vérifier les permissions
        $isChef = $this->equipeModel->getProjectManager($id);
        if (!$this->isAdmin() && (!$isChef || $isChef['id'] != $this->user['id'])) {
            $this->setFlashMessage('Accès refusé.', 'error');
            $this->redirect('index.php?page=projets&action=view&id=' . $id);
        }
        
        $projet = $this->projetModel->getById($id);
        $equipe = $this->equipeModel->getProjectTeam($id);
        $availableUsers = $this->equipeModel->getUnassignedUsers($id);
        
        $this->render('projets/team', [
            'projet' => $projet,
            'equipe' => $equipe,
            'availableUsers' => $availableUsers
        ]);
    }
    
    /**
     * Ajouter un membre à l'équipe
     */
    public function addMember() {
        $projetId = $_POST['projet_id'] ?? null;
        $userId = $_POST['user_id'] ?? null;
        $role = $_POST['role'] ?? 'membre';
        
        if (!$projetId || !$userId) {
            $this->setFlashMessage('Données manquantes.', 'error');
            $this->redirect('index.php?page=projets');
        }
        
        try {
            $this->equipeModel->assignUserToProject($projetId, $userId, $role);
            $this->setFlashMessage('Membre ajouté à l\'équipe.', 'success');
        } catch (Exception $e) {
            $this->setFlashMessage('Erreur lors de l\'ajout du membre.', 'error');
        }
        
        $this->redirect('index.php?page=projets&action=team&id=' . $projetId);
    }
    
    /**
     * Retirer un membre de l'équipe
     */
    public function removeMember() {
        $projetId = $_GET['projet_id'] ?? null;
        $userId = $_GET['user_id'] ?? null;
        
        if (!$projetId || !$userId) {
            $this->setFlashMessage('Données manquantes.', 'error');
            $this->redirect('index.php?page=projets');
        }
        
        try {
            $this->equipeModel->removeUserFromProject($projetId, $userId);
            $this->setFlashMessage('Membre retiré de l\'équipe.', 'success');
        } catch (Exception $e) {
            $this->setFlashMessage('Erreur lors du retrait du membre.', 'error');
        }
        
        $this->redirect('index.php?page=projets&action=team&id=' . $projetId);
    }
    
    /**
     * Changer le statut d'un projet
     */
    public function updateStatus() {
        $id = $_GET['id'] ?? null;
        $status = $_GET['status'] ?? null;
        
        if (!$id || !$this->projetModel->exists($id)) {
            $this->setFlashMessage('Projet non trouvé.', 'error');
            $this->redirect('index.php?page=projets');
        }
        
        $validStatuses = ['en cours', 'terminé', 'annulé'];
        if (!in_array($status, $validStatuses)) {
            $this->setFlashMessage('Statut invalide.', 'error');
            $this->redirect('index.php?page=projets&action=view&id=' . $id);
        }
        
        // Vérifier les permissions
        $isChef = $this->equipeModel->getProjectManager($id);
        if (!$this->isAdmin() && (!$isChef || $isChef['id'] != $this->user['id'])) {
            $this->setFlashMessage('Accès refusé.', 'error');
            $this->redirect('index.php?page=projets&action=view&id=' . $id);
        }
        
        try {
            $updateData = ['statut' => $status];
            
            // Si on termine le projet, ajouter la date de fin réelle
            if ($status === 'terminé') {
                $updateData['date_fin_reelle'] = date('Y-m-d');
            }
            
            $this->projetModel->update($id, $updateData);
            $this->setFlashMessage('Statut du projet mis à jour.', 'success');
        } catch (Exception $e) {
            $this->setFlashMessage('Erreur lors de la mise à jour du statut.', 'error');
        }
        
        $this->redirect('index.php?page=projets&action=view&id=' . $id);
    }
    
}
?>