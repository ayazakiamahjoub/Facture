<?php
/**
 * Modèle Client - Gestion des clients
 */

require_once 'BaseModel.php';

class Client extends BaseModel {
    protected $table = 'clients';
    
    /**
     * Récupérer tous les clients actifs
     */
    public function getActiveClients() {
        return $this->findWhere('actif = 1', [], null, 0, 'nom_client ASC');
    }
    
    /**
     * Rechercher des clients par nom ou email
     */
    public function searchClients($searchTerm) {
        $sql = "SELECT * FROM {$this->table} 
                WHERE (nom_client LIKE ? OR email LIKE ?) AND actif = 1 
                ORDER BY nom_client ASC";
        
        $searchPattern = "%{$searchTerm}%";
        $stmt = $this->db->query($sql, [$searchPattern, $searchPattern]);
        return $stmt->fetchAll();
    }
    
    /**
     * Vérifier si un email client existe déjà
     */
    public function emailExists($email, $excludeId = null) {
        $sql = "SELECT id FROM {$this->table} WHERE email = ?";
        $params = [$email];
        
        if ($excludeId) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }
        
        $stmt = $this->db->query($sql, $params);
        return $stmt->fetch() !== false;
    }
    
    /**
     * Récupérer les projets d'un client
     */
    public function getClientProjects($clientId) {
        $sql = "SELECT * FROM projets WHERE id_client = ? ORDER BY date_creation DESC";
        $stmt = $this->db->query($sql, [$clientId]);
        return $stmt->fetchAll();
    }
    
    /**
     * Récupérer les factures d'un client
     */
    public function getClientInvoices($clientId) {
        $sql = "SELECT f.*, p.titre_projet 
                FROM factures f 
                LEFT JOIN projets p ON f.id_projet = p.id 
                WHERE f.id_client = ? 
                ORDER BY f.date_facture DESC";
        
        $stmt = $this->db->query($sql, [$clientId]);
        return $stmt->fetchAll();
    }
    
    /**
     * Calculer le chiffre d'affaires total d'un client
     */
    public function getTotalRevenue($clientId) {
        $sql = "SELECT SUM(montant) as total_revenue 
                FROM factures 
                WHERE id_client = ? AND statut = 'payée'";
        
        $stmt = $this->db->query($sql, [$clientId]);
        $result = $stmt->fetch();
        return $result['total_revenue'] ?? 0;
    }
    
    /**
     * Calculer le montant impayé d'un client
     */
    public function getUnpaidAmount($clientId) {
        $sql = "SELECT SUM(montant) as unpaid_amount 
                FROM factures 
                WHERE id_client = ? AND statut = 'impayée'";
        
        $stmt = $this->db->query($sql, [$clientId]);
        $result = $stmt->fetch();
        return $result['unpaid_amount'] ?? 0;
    }
    
    /**
     * Compter les projets actifs d'un client
     */
    public function countActiveProjects($clientId) {
        $sql = "SELECT COUNT(*) as total 
                FROM projets 
                WHERE id_client = ? AND statut = 'en cours'";
        
        $stmt = $this->db->query($sql, [$clientId]);
        $result = $stmt->fetch();
        return $result['total'];
    }
    
    /**
     * Désactiver un client au lieu de le supprimer
     */
    public function deactivate($id) {
        return $this->update($id, ['actif' => 0]);
    }
    
    /**
     * Réactiver un client
     */
    public function activate($id) {
        return $this->update($id, ['actif' => 1]);
    }
    
    /**
     * Récupérer les statistiques d'un client
     */
    public function getClientStats($clientId) {
        $stats = [
            'total_projects' => 0,
            'active_projects' => 0,
            'completed_projects' => 0,
            'total_revenue' => 0,
            'unpaid_amount' => 0,
            'total_invoices' => 0
        ];
        
        // Compter les projets
        $sql = "SELECT 
                    COUNT(*) as total_projects,
                    SUM(CASE WHEN statut = 'en cours' THEN 1 ELSE 0 END) as active_projects,
                    SUM(CASE WHEN statut = 'terminé' THEN 1 ELSE 0 END) as completed_projects
                FROM projets WHERE id_client = ?";
        
        $stmt = $this->db->query($sql, [$clientId]);
        $projectStats = $stmt->fetch();
        
        if ($projectStats) {
            $stats = array_merge($stats, $projectStats);
        }
        
        // Calculer les montants des factures
        $sql = "SELECT 
                    COUNT(*) as total_invoices,
                    SUM(CASE WHEN statut = 'payée' THEN montant ELSE 0 END) as total_revenue,
                    SUM(CASE WHEN statut = 'impayée' THEN montant ELSE 0 END) as unpaid_amount
                FROM factures WHERE id_client = ?";
        
        $stmt = $this->db->query($sql, [$clientId]);
        $invoiceStats = $stmt->fetch();
        
        if ($invoiceStats) {
            $stats = array_merge($stats, $invoiceStats);
        }
        
        return $stats;
    }
}
?>
