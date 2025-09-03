<?php
/**
 * Contrôleur Equipe - Gestion des équipes de projet
 */

require_once 'BaseController.php';

class EquipeController extends BaseController {
    private $equipeModel;
    private $projetModel;
    private $userModel;
    
    public function __construct() {
        parent::__construct();
        $this->requireAuth();
        $this->equipeModel = new EquipeProjet();
        $this->projetModel = new Projet();
        $this->userModel = new User();
    }
    
    /**
     * Vue d'ensemble des équipes
     */
    public function index() {
        // Si l'utilisateur n'est pas admin, rediriger vers ses projets
        if (!$this->isAdmin()) {
            $this->redirect('index.php?page=projets');
        }
        
        // Récupérer tous les projets avec leurs équipes
        $projets = $this->projetModel->getAllWithClient();
        
        foreach ($projets as &$projet) {
            $projet['equipe'] = $this->equipeModel->getProjectTeam($projet['id']);
            $projet['nb_membres'] = $this->equipeModel->countProjectMembers($projet['id']);
        }
        
        $this->render('equipes/index', [
            'projets' => $projets
        ]);
    }
    
    /**
     * Gérer l'équipe d'un projet spécifique
     */
    public function manage() {
        $projetId = $_GET['projet_id'] ?? null;
        
        if (!$projetId || !$this->projetModel->exists($projetId)) {
            $this->setFlashMessage('Projet non trouvé.', 'error');
            $this->redirect('index.php?page=projets');
        }
        
        // Vérifier les permissions
        $isChef = $this->equipeModel->getProjectManager($projetId);
        if (!$this->isAdmin() && (!$isChef || $isChef['id'] != $this->user['id'])) {
            $this->setFlashMessage('Accès refusé. Seuls les administrateurs et chefs de projet peuvent gérer l\'équipe.', 'error');
            $this->redirect('index.php?page=projets&action=view&id=' . $projetId);
        }
        
        $projet = $this->projetModel->getByIdWithClient($projetId);
        $equipe = $this->equipeModel->getProjectTeam($projetId);
        $availableUsers = $this->equipeModel->getUnassignedUsers($projetId);
        
        $this->render('equipes/manage', [
            'projet' => $projet,
            'equipe' => $equipe,
            'availableUsers' => $availableUsers
        ]);
    }
    
    /**
     * Assigner un utilisateur à un projet
     */
    public function assign() {
        $projetId = $_POST['projet_id'] ?? null;
        $userId = $_POST['user_id'] ?? null;
        $role = $_POST['role'] ?? 'membre';
        
        if (!$projetId || !$userId) {
            $this->setFlashMessage('Données manquantes.', 'error');
            $this->redirect('index.php?page=equipes');
        }
        
        // Vérifier les permissions
        $isChef = $this->equipeModel->getProjectManager($projetId);
        if (!$this->isAdmin() && (!$isChef || $isChef['id'] != $this->user['id'])) {
            $this->setFlashMessage('Accès refusé.', 'error');
            $this->redirect('index.php?page=projets&action=view&id=' . $projetId);
        }
        
        // Vérifier que l'utilisateur et le projet existent
        if (!$this->userModel->exists($userId) || !$this->projetModel->exists($projetId)) {
            $this->setFlashMessage('Utilisateur ou projet invalide.', 'error');
            $this->redirect('index.php?page=equipes&action=manage&projet_id=' . $projetId);
        }
        
        // Vérifier que l'utilisateur n'est pas déjà assigné
        if ($this->equipeModel->isUserAssigned($projetId, $userId)) {
            $this->setFlashMessage('Cet utilisateur est déjà assigné à ce projet.', 'error');
            $this->redirect('index.php?page=equipes&action=manage&projet_id=' . $projetId);
        }
        
        try {
            $this->equipeModel->assignUserToProject($projetId, $userId, $role);
            $this->setFlashMessage('Utilisateur assigné avec succès au projet.', 'success');
        } catch (Exception $e) {
            $this->setFlashMessage('Erreur lors de l\'assignation.', 'error');
        }
        
        $this->redirect('index.php?page=equipes&action=manage&projet_id=' . $projetId);
    }
    
    /**
     * Retirer un utilisateur d'un projet
     */
    public function remove() {
        $projetId = $_GET['projet_id'] ?? null;
        $userId = $_GET['user_id'] ?? null;
        
        if (!$projetId || !$userId) {
            $this->setFlashMessage('Données manquantes.', 'error');
            $this->redirect('index.php?page=equipes');
        }
        
        // Vérifier les permissions
        $isChef = $this->equipeModel->getProjectManager($projetId);
        if (!$this->isAdmin() && (!$isChef || $isChef['id'] != $this->user['id'])) {
            $this->setFlashMessage('Accès refusé.', 'error');
            $this->redirect('index.php?page=projets&action=view&id=' . $projetId);
        }
        
        // Empêcher de retirer le chef de projet
        if ($isChef && $isChef['id'] == $userId) {
            $this->setFlashMessage('Impossible de retirer le chef de projet. Assignez d\'abord un nouveau chef.', 'error');
            $this->redirect('index.php?page=equipes&action=manage&projet_id=' . $projetId);
        }
        
        try {
            $this->equipeModel->removeUserFromProject($projetId, $userId);
            $this->setFlashMessage('Utilisateur retiré du projet avec succès.', 'success');
        } catch (Exception $e) {
            $this->setFlashMessage('Erreur lors du retrait.', 'error');
        }
        
        $this->redirect('index.php?page=equipes&action=manage&projet_id=' . $projetId);
    }
    
    /**
     * Changer le rôle d'un utilisateur dans un projet
     */
    public function changeRole() {
        $projetId = $_POST['projet_id'] ?? null;
        $userId = $_POST['user_id'] ?? null;
        $newRole = $_POST['new_role'] ?? null;
        
        if (!$projetId || !$userId || !$newRole) {
            $this->setFlashMessage('Données manquantes.', 'error');
            $this->redirect('index.php?page=equipes');
        }
        
        // Vérifier les permissions
        $isChef = $this->equipeModel->getProjectManager($projetId);
        if (!$this->isAdmin() && (!$isChef || $isChef['id'] != $this->user['id'])) {
            $this->setFlashMessage('Accès refusé.', 'error');
            $this->redirect('index.php?page=projets&action=view&id=' . $projetId);
        }
        
        // Vérifier que l'utilisateur est assigné au projet
        if (!$this->equipeModel->isUserAssigned($projetId, $userId)) {
            $this->setFlashMessage('Cet utilisateur n\'est pas assigné à ce projet.', 'error');
            $this->redirect('index.php?page=equipes&action=manage&projet_id=' . $projetId);
        }
        
        try {
            $this->equipeModel->updateUserRole($projetId, $userId, $newRole);
            $this->setFlashMessage('Rôle mis à jour avec succès.', 'success');
        } catch (Exception $e) {
            $this->setFlashMessage('Erreur lors de la mise à jour du rôle.', 'error');
        }
        
        $this->redirect('index.php?page=equipes&action=manage&projet_id=' . $projetId);
    }
    
    /**
     * Statistiques des équipes (admin uniquement)
     */
    public function stats() {
        $this->requireAdmin();
        
        // Utilisateurs les plus actifs
        $activeUsers = $this->getMostActiveUsers(10);
        
        // Projets avec le plus de membres
        $biggestTeams = $this->getBiggestTeams(10);
        
        // Répartition des rôles
        $roleDistribution = $this->getRoleDistribution();
        
        $this->render('equipes/stats', [
            'activeUsers' => $activeUsers,
            'biggestTeams' => $biggestTeams,
            'roleDistribution' => $roleDistribution
        ]);
    }
    
    /**
     * Récupérer les utilisateurs les plus actifs
     */
    private function getMostActiveUsers($limit = 10) {
        $sql = "SELECT 
                    u.id,
                    u.nom,
                    u.email,
                    COUNT(DISTINCT ep.id_projet) as total_projects,
                    COUNT(CASE WHEN p.statut = 'en cours' THEN 1 END) as active_projects,
                    COUNT(CASE WHEN ep.role_projet = 'chef' THEN 1 END) as managed_projects
                FROM users u
                JOIN equipe_projet ep ON u.id = ep.id_user
                JOIN projets p ON ep.id_projet = p.id
                WHERE ep.actif = 1 AND u.actif = 1
                GROUP BY u.id, u.nom, u.email
                ORDER BY total_projects DESC, active_projects DESC
                LIMIT ?";
        
        $db = getDB();
        $stmt = $db->query($sql, [$limit]);
        return $stmt->fetchAll();
    }

    /**
     * Récupérer les équipes les plus importantes
     */
    private function getBiggestTeams($limit = 10) {
        $db = getDB();
        $sql = "SELECT
                    p.id,
                    p.titre_projet,
                    c.nom_client,
                    p.statut,
                    COUNT(ep.id_user) as team_size
                FROM projets p
                JOIN clients c ON p.id_client = c.id
                JOIN equipe_projet ep ON p.id = ep.id_projet
                WHERE ep.actif = 1
                GROUP BY p.id, p.titre_projet, c.nom_client, p.statut
                ORDER BY team_size DESC
                LIMIT ?";

        $stmt = $db->query($sql, [$limit]);
        return $stmt->fetchAll();
    }

    /**
     * Récupérer la répartition des rôles
     */
    private function getRoleDistribution() {
        $db = getDB();
        $sql = "SELECT
                    role_projet,
                    COUNT(*) as count
                FROM equipe_projet
                WHERE actif = 1
                GROUP BY role_projet
                ORDER BY count DESC";

        $stmt = $db->query($sql);
        return $stmt->fetchAll();
    }
}
?>
