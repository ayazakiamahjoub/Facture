<?php
/**
 * Modèle EquipeProjet - Gestion des équipes de projet
 */

require_once 'BaseModel.php';

class EquipeProjet extends BaseModel {
    protected $table = 'equipe_projet';
    
    /**
     * Assigner un utilisateur à un projet
     */
    public function assignUserToProject($projectId, $userId, $role = 'membre') {
        // Vérifier si l'assignation existe déjà
        if ($this->isUserAssigned($projectId, $userId)) {
            return false; // L'utilisateur est déjà assigné
        }
        
        $data = [
            'id_projet' => $projectId,
            'id_user' => $userId,
            'role_projet' => $role,
            'actif' => 1
        ];
        
        return $this->create($data);
    }
    
    /**
     * Retirer un utilisateur d'un projet
     */
    public function removeUserFromProject($projectId, $userId) {
        $sql = "UPDATE {$this->table} 
                SET actif = 0, date_fin_assignation = CURDATE() 
                WHERE id_projet = ? AND id_user = ? AND actif = 1";
        
        $stmt = $this->db->query($sql, [$projectId, $userId]);
        return $stmt->rowCount();
    }
    
    /**
     * Vérifier si un utilisateur est assigné à un projet
     */
    public function isUserAssigned($projectId, $userId) {
        $sql = "SELECT 1 FROM {$this->table} 
                WHERE id_projet = ? AND id_user = ? AND actif = 1";
        
        $stmt = $this->db->query($sql, [$projectId, $userId]);
        return $stmt->fetch() !== false;
    }
    
    /**
     * Récupérer l'équipe d'un projet avec les détails des utilisateurs
     */
    public function getProjectTeam($projectId) {
        $sql = "SELECT ep.*, u.nom, u.email, u.role as user_role 
                FROM {$this->table} ep 
                JOIN users u ON ep.id_user = u.id 
                WHERE ep.id_projet = ? AND ep.actif = 1 
                ORDER BY ep.role_projet, u.nom";
        
        $stmt = $this->db->query($sql, [$projectId]);
        return $stmt->fetchAll();
    }
    
    /**
     * Récupérer les projets d'un utilisateur
     */
    public function getUserProjects($userId) {
        $sql = "SELECT ep.*, p.titre_projet, p.statut, p.date_debut, p.date_fin_prevue, c.nom_client 
                FROM {$this->table} ep 
                JOIN projets p ON ep.id_projet = p.id 
                JOIN clients c ON p.id_client = c.id 
                WHERE ep.id_user = ? AND ep.actif = 1 
                ORDER BY p.date_creation DESC";
        
        $stmt = $this->db->query($sql, [$userId]);
        return $stmt->fetchAll();
    }
    
    /**
     * Mettre à jour le rôle d'un utilisateur dans un projet
     */
    public function updateUserRole($projectId, $userId, $newRole) {
        $sql = "UPDATE {$this->table} 
                SET role_projet = ? 
                WHERE id_projet = ? AND id_user = ? AND actif = 1";
        
        $stmt = $this->db->query($sql, [$newRole, $projectId, $userId]);
        return $stmt->rowCount();
    }
    
    /**
     * Récupérer le chef de projet
     */
    public function getProjectManager($projectId) {
        $sql = "SELECT u.* 
                FROM {$this->table} ep 
                JOIN users u ON ep.id_user = u.id 
                WHERE ep.id_projet = ? AND ep.role_projet = 'chef' AND ep.actif = 1 
                LIMIT 1";
        
        $stmt = $this->db->query($sql, [$projectId]);
        return $stmt->fetch();
    }
    
    /**
     * Récupérer les membres d'un projet (sans le chef)
     */
    public function getProjectMembers($projectId) {
        $sql = "SELECT u.* 
                FROM {$this->table} ep 
                JOIN users u ON ep.id_user = u.id 
                WHERE ep.id_projet = ? AND ep.role_projet != 'chef' AND ep.actif = 1 
                ORDER BY u.nom";
        
        $stmt = $this->db->query($sql, [$projectId]);
        return $stmt->fetchAll();
    }
    
    /**
     * Compter les membres actifs d'un projet
     */
    public function countProjectMembers($projectId) {
        $sql = "SELECT COUNT(*) as total 
                FROM {$this->table} 
                WHERE id_projet = ? AND actif = 1";
        
        $stmt = $this->db->query($sql, [$projectId]);
        $result = $stmt->fetch();
        return $result['total'];
    }
    
    /**
     * Récupérer les utilisateurs non assignés à un projet
     */
    public function getUnassignedUsers($projectId) {
        $sql = "SELECT u.* 
                FROM users u 
                WHERE u.actif = 1 
                AND u.id NOT IN (
                    SELECT ep.id_user 
                    FROM {$this->table} ep 
                    WHERE ep.id_projet = ? AND ep.actif = 1
                ) 
                ORDER BY u.nom";
        
        $stmt = $this->db->query($sql, [$projectId]);
        return $stmt->fetchAll();
    }
    
    /**
     * Transférer tous les membres d'un projet vers un autre
     */
    public function transferTeam($fromProjectId, $toProjectId) {
        try {
            $this->db->beginTransaction();
            
            // Récupérer l'équipe du projet source
            $team = $this->getProjectTeam($fromProjectId);
            
            foreach ($team as $member) {
                // Assigner au nouveau projet
                $this->assignUserToProject($toProjectId, $member['id_user'], $member['role_projet']);
            }
            
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollback();
            return false;
        }
    }
    
    /**
     * Récupérer les statistiques d'un utilisateur
     */
    public function getUserStats($userId) {
        $sql = "SELECT 
                    COUNT(*) as total_projects,
                    SUM(CASE WHEN p.statut = 'en cours' THEN 1 ELSE 0 END) as active_projects,
                    SUM(CASE WHEN p.statut = 'terminé' THEN 1 ELSE 0 END) as completed_projects,
                    SUM(CASE WHEN ep.role_projet = 'chef' THEN 1 ELSE 0 END) as managed_projects
                FROM {$this->table} ep 
                JOIN projets p ON ep.id_projet = p.id 
                WHERE ep.id_user = ? AND ep.actif = 1";
        
        $stmt = $this->db->query($sql, [$userId]);
        return $stmt->fetch();
    }
}
?>
