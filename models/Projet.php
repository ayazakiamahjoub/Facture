<?php
/**
 * Modèle Projet - Gestion des projets
 */

require_once 'BaseModel.php';

class Projet extends BaseModel {
    protected $table = 'projets';
    
    /**
     * Récupérer tous les projets avec les informations du client
     */
    public function getAllWithClient($limit = null, $offset = 0, $orderBy = 'p.date_creation DESC') {
        $sql = "SELECT p.*, c.nom_client, c.email as client_email 
                FROM {$this->table} p 
                JOIN clients c ON p.id_client = c.id";
        
        if ($orderBy) {
            $sql .= " ORDER BY {$orderBy}";
        }
        
        if ($limit) {
            $sql .= " LIMIT {$limit} OFFSET {$offset}";
        }
        
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
    
    /**
     * Récupérer un projet avec les informations du client
     */
    public function getByIdWithClient($id) {
        $sql = "SELECT p.*, c.nom_client, c.email as client_email, c.telephone as client_telephone 
                FROM {$this->table} p 
                JOIN clients c ON p.id_client = c.id 
                WHERE p.id = ?";
        
        $stmt = $this->db->query($sql, [$id]);
        return $stmt->fetch();
    }
    
    /**
     * Récupérer les projets par statut
     */
    public function getByStatus($status) {
        $sql = "SELECT p.*, c.nom_client 
                FROM {$this->table} p 
                JOIN clients c ON p.id_client = c.id 
                WHERE p.statut = ? 
                ORDER BY p.date_creation DESC";
        
        $stmt = $this->db->query($sql, [$status]);
        return $stmt->fetchAll();
    }
    
    /**
     * Récupérer les projets d'un client
     */
    public function getByClient($clientId) {
        return $this->findWhere('id_client = ?', [$clientId], null, 0, 'date_creation DESC');
    }
    
    /**
     * Rechercher des projets
     */
    public function searchProjects($searchTerm, $status = null) {
        $sql = "SELECT p.*, c.nom_client 
                FROM {$this->table} p 
                JOIN clients c ON p.id_client = c.id 
                WHERE (p.titre_projet LIKE ? OR p.description LIKE ? OR c.nom_client LIKE ?)";
        
        $params = ["%{$searchTerm}%", "%{$searchTerm}%", "%{$searchTerm}%"];
        
        if ($status) {
            $sql .= " AND p.statut = ?";
            $params[] = $status;
        }
        
        $sql .= " ORDER BY p.date_creation DESC";
        
        $stmt = $this->db->query($sql, $params);
        return $stmt->fetchAll();
    }
    
    /**
     * Récupérer l'équipe d'un projet
     */
    public function getProjectTeam($projectId) {
        $sql = "SELECT u.id, u.nom, u.email, ep.role_projet, ep.date_assignation 
                FROM equipe_projet ep 
                JOIN users u ON ep.id_user = u.id 
                WHERE ep.id_projet = ? AND ep.actif = 1 
                ORDER BY ep.role_projet, u.nom";
        
        $stmt = $this->db->query($sql, [$projectId]);
        return $stmt->fetchAll();
    }
    
    /**
     * Récupérer les factures d'un projet
     */
    public function getProjectInvoices($projectId) {
        $sql = "SELECT * FROM factures WHERE id_projet = ? ORDER BY date_facture DESC";
        $stmt = $this->db->query($sql, [$projectId]);
        return $stmt->fetchAll();
    }
    
    /**
     * Calculer le chiffre d'affaires d'un projet
     */
    public function getProjectRevenue($projectId) {
        $sql = "SELECT SUM(montant) as total_revenue 
                FROM factures 
                WHERE id_projet = ? AND statut = 'payée'";
        
        $stmt = $this->db->query($sql, [$projectId]);
        $result = $stmt->fetch();
        return $result['total_revenue'] ?? 0;
    }
    
    /**
     * Mettre à jour le statut d'un projet
     */
    public function updateStatus($id, $status) {
        $data = ['statut' => $status];
        
        // Si le projet est terminé, mettre la date de fin réelle
        if ($status === 'terminé') {
            $data['date_fin_reelle'] = date('Y-m-d');
        }
        
        return $this->update($id, $data);
    }
    
    /**
     * Récupérer les statistiques des projets
     */
    public function getProjectStats() {
        $sql = "SELECT 
                    COUNT(*) as total_projects,
                    SUM(CASE WHEN statut = 'en cours' THEN 1 ELSE 0 END) as active_projects,
                    SUM(CASE WHEN statut = 'terminé' THEN 1 ELSE 0 END) as completed_projects,
                    SUM(CASE WHEN statut = 'annulé' THEN 1 ELSE 0 END) as cancelled_projects,
                    AVG(budget) as average_budget,
                    SUM(budget) as total_budget
                FROM {$this->table}";
        
        $stmt = $this->db->query($sql);
        return $stmt->fetch();
    }
    
    /**
     * Récupérer les projets en retard
     */
    public function getOverdueProjects() {
        $sql = "SELECT p.*, c.nom_client 
                FROM {$this->table} p 
                JOIN clients c ON p.id_client = c.id 
                WHERE p.statut = 'en cours' 
                AND p.date_fin_prevue < CURDATE() 
                ORDER BY p.date_fin_prevue ASC";
        
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
    
    /**
     * Récupérer les projets qui se terminent bientôt
     */
    public function getUpcomingDeadlines($days = 7) {
        $sql = "SELECT p.*, c.nom_client 
                FROM {$this->table} p 
                JOIN clients c ON p.id_client = c.id 
                WHERE p.statut = 'en cours' 
                AND p.date_fin_prevue BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL ? DAY) 
                ORDER BY p.date_fin_prevue ASC";
        
        $stmt = $this->db->query($sql, [$days]);
        return $stmt->fetchAll();
    }
}
?>
