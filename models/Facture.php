<?php
/**
 * Modèle Facture - Gestion des factures
 */

require_once 'BaseModel.php';

class Facture extends BaseModel {
    protected $table = 'factures';
    protected $primaryKey = 'id_facture';
    
    /**
     * Créer une nouvelle facture avec numéro automatique
     */
    public function createInvoice($data) {
        // Générer un numéro de facture automatique si non fourni
        if (!isset($data['numero_facture']) || empty($data['numero_facture'])) {
            $data['numero_facture'] = $this->generateInvoiceNumber();
        }
        
        // Calculer la date d'échéance si non fournie (30 jours par défaut)
        if (!isset($data['date_echeance']) || empty($data['date_echeance'])) {
            $data['date_echeance'] = date('Y-m-d', strtotime($data['date_facture'] . ' +30 days'));
        }
        
        return $this->create($data);
    }
    
    /**
     * Générer un numéro de facture automatique
     */
    private function generateInvoiceNumber() {
        $year = date('Y');
        $sql = "SELECT COUNT(*) + 1 as next_number 
                FROM {$this->table} 
                WHERE YEAR(date_facture) = ?";
        
        $stmt = $this->db->query($sql, [$year]);
        $result = $stmt->fetch();
        $nextNumber = str_pad($result['next_number'], 3, '0', STR_PAD_LEFT);
        
        return "FAC-{$year}-{$nextNumber}";
    }
    
    /**
     * Récupérer toutes les factures avec les informations client et projet
     */
    public function getAllWithDetails($limit = null, $offset = 0, $orderBy = 'f.date_facture DESC') {
        $sql = "SELECT f.*, c.nom_client, p.titre_projet 
                FROM {$this->table} f 
                JOIN clients c ON f.id_client = c.id 
                LEFT JOIN projets p ON f.id_projet = p.id";
        
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
     * Récupérer une facture avec les détails
     */
    public function getByIdWithDetails($id) {
        $sql = "SELECT f.*, c.nom_client, c.email as client_email, c.telephone as client_telephone, 
                       c.adresse as client_adresse, p.titre_projet 
                FROM {$this->table} f 
                JOIN clients c ON f.id_client = c.id 
                LEFT JOIN projets p ON f.id_projet = p.id 
                WHERE f.{$this->primaryKey} = ?";
        
        $stmt = $this->db->query($sql, [$id]);
        return $stmt->fetch();
    }
    
    /**
     * Récupérer les factures par statut
     */
    public function getByStatus($status) {
        $sql = "SELECT f.*, c.nom_client, p.titre_projet 
                FROM {$this->table} f 
                JOIN clients c ON f.id_client = c.id 
                LEFT JOIN projets p ON f.id_projet = p.id 
                WHERE f.statut = ? 
                ORDER BY f.date_facture DESC";
        
        $stmt = $this->db->query($sql, [$status]);
        return $stmt->fetchAll();
    }
    
    /**
     * Récupérer les factures d'un client
     */
    public function getByClient($clientId) {
        $sql = "SELECT f.*, p.titre_projet 
                FROM {$this->table} f 
                LEFT JOIN projets p ON f.id_projet = p.id 
                WHERE f.id_client = ? 
                ORDER BY f.date_facture DESC";
        
        $stmt = $this->db->query($sql, [$clientId]);
        return $stmt->fetchAll();
    }
    
    /**
     * Récupérer les factures d'un projet
     */
    public function getByProject($projectId) {
        $sql = "SELECT f.*, c.nom_client 
                FROM {$this->table} f 
                JOIN clients c ON f.id_client = c.id 
                WHERE f.id_projet = ? 
                ORDER BY f.date_facture DESC";
        
        $stmt = $this->db->query($sql, [$projectId]);
        return $stmt->fetchAll();
    }
    
    /**
     * Marquer une facture comme payée
     */
    public function markAsPaid($id) {
        return $this->update($id, ['statut' => 'payée']);
    }
    
    /**
     * Marquer une facture comme impayée
     */
    public function markAsUnpaid($id) {
        return $this->update($id, ['statut' => 'impayée']);
    }
    
    /**
     * Récupérer les factures en retard
     */
    public function getOverdueInvoices() {
        $sql = "SELECT f.*, c.nom_client, p.titre_projet 
                FROM {$this->table} f 
                JOIN clients c ON f.id_client = c.id 
                LEFT JOIN projets p ON f.id_projet = p.id 
                WHERE f.statut = 'impayée' 
                AND f.date_echeance < CURDATE() 
                ORDER BY f.date_echeance ASC";
        
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
    
    /**
     * Calculer le chiffre d'affaires total
     */
    public function getTotalRevenue($startDate = null, $endDate = null) {
        $sql = "SELECT SUM(montant) as total_revenue 
                FROM {$this->table} 
                WHERE statut = 'payée'";
        
        $params = [];
        
        if ($startDate && $endDate) {
            $sql .= " AND date_facture BETWEEN ? AND ?";
            $params = [$startDate, $endDate];
        }
        
        $stmt = $this->db->query($sql, $params);
        $result = $stmt->fetch();
        return $result['total_revenue'] ?? 0;
    }
    
    /**
     * Calculer le montant total impayé
     */
    public function getTotalUnpaid() {
        $sql = "SELECT SUM(montant) as total_unpaid 
                FROM {$this->table} 
                WHERE statut = 'impayée'";
        
        $stmt = $this->db->query($sql);
        $result = $stmt->fetch();
        return $result['total_unpaid'] ?? 0;
    }
    
    /**
     * Récupérer les statistiques des factures
     */
    public function getInvoiceStats() {
        $sql = "SELECT 
                    COUNT(*) as total_invoices,
                    SUM(CASE WHEN statut = 'payée' THEN 1 ELSE 0 END) as paid_invoices,
                    SUM(CASE WHEN statut = 'impayée' THEN 1 ELSE 0 END) as unpaid_invoices,
                    SUM(CASE WHEN statut = 'payée' THEN montant ELSE 0 END) as total_revenue,
                    SUM(CASE WHEN statut = 'impayée' THEN montant ELSE 0 END) as total_unpaid,
                    AVG(montant) as average_amount
                FROM {$this->table}";
        
        $stmt = $this->db->query($sql);
        return $stmt->fetch();
    }
    
    /**
     * Rechercher des factures
     */
    public function searchInvoices($searchTerm, $status = null) {
        $sql = "SELECT f.*, c.nom_client, p.titre_projet 
                FROM {$this->table} f 
                JOIN clients c ON f.id_client = c.id 
                LEFT JOIN projets p ON f.id_projet = p.id 
                WHERE (f.numero_facture LIKE ? OR c.nom_client LIKE ? OR p.titre_projet LIKE ?)";
        
        $params = ["%{$searchTerm}%", "%{$searchTerm}%", "%{$searchTerm}%"];
        
        if ($status) {
            $sql .= " AND f.statut = ?";
            $params[] = $status;
        }
        
        $sql .= " ORDER BY f.date_facture DESC";
        
        $stmt = $this->db->query($sql, $params);
        return $stmt->fetchAll();
    }
    
    /**
     * Vérifier si un numéro de facture existe
     */
    public function invoiceNumberExists($invoiceNumber, $excludeId = null) {
        $sql = "SELECT {$this->primaryKey} FROM {$this->table} WHERE numero_facture = ?";
        $params = [$invoiceNumber];
        
        if ($excludeId) {
            $sql .= " AND {$this->primaryKey} != ?";
            $params[] = $excludeId;
        }
        
        $stmt = $this->db->query($sql, $params);
        return $stmt->fetch() !== false;
    }
}
?>
