<?php
/**
 * Modèle User - Gestion des utilisateurs
 */

require_once 'BaseModel.php';

class User extends BaseModel {
    protected $table = 'users';
    
    /**
     * Créer un nouvel utilisateur avec mot de passe hashé
     */
    public function createUser($data) {
        // Hasher le mot de passe
        if (isset($data['mot_de_passe'])) {
            $data['mot_de_passe'] = password_hash($data['mot_de_passe'], PASSWORD_DEFAULT);
        }
        
        return $this->create($data);
    }
    
    /**
     * Mettre à jour un utilisateur
     */
    public function updateUser($id, $data) {
        // Hasher le mot de passe s'il est fourni
        if (isset($data['mot_de_passe']) && !empty($data['mot_de_passe'])) {
            $data['mot_de_passe'] = password_hash($data['mot_de_passe'], PASSWORD_DEFAULT);
        } else {
            // Supprimer le mot de passe du tableau s'il est vide
            unset($data['mot_de_passe']);
        }
        
        return $this->update($id, $data);
    }
    
    /**
     * Authentifier un utilisateur
     */
    public function authenticate($email, $password) {
        $sql = "SELECT * FROM {$this->table} WHERE email = ? AND actif = 1";
        $stmt = $this->db->query($sql, [$email]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['mot_de_passe'])) {
            // Ne pas retourner le mot de passe
            unset($user['mot_de_passe']);
            return $user;
        }
        
        return false;
    }
    
    /**
     * Récupérer un utilisateur par email
     */
    public function getByEmail($email) {
        $sql = "SELECT * FROM {$this->table} WHERE email = ?";
        $stmt = $this->db->query($sql, [$email]);
        $user = $stmt->fetch();
        
        if ($user) {
            unset($user['mot_de_passe']);
        }
        
        return $user;
    }
    
    /**
     * Vérifier si un email existe déjà
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
     * Récupérer tous les utilisateurs actifs
     */
    public function getActiveUsers() {
        return $this->findWhere('actif = 1', [], null, 0, 'nom ASC');
    }
    
    /**
     * Récupérer les utilisateurs par rôle
     */
    public function getUsersByRole($role) {
        return $this->findWhere('role = ? AND actif = 1', [$role], null, 0, 'nom ASC');
    }
    
    /**
     * Désactiver un utilisateur au lieu de le supprimer
     */
    public function deactivate($id) {
        return $this->update($id, ['actif' => 0]);
    }
    
    /**
     * Réactiver un utilisateur
     */
    public function activate($id) {
        return $this->update($id, ['actif' => 1]);
    }
    
    /**
     * Récupérer les projets d'un utilisateur
     */
    public function getUserProjects($userId) {
        $sql = "SELECT p.*, c.nom_client, ep.role_projet 
                FROM projets p 
                JOIN equipe_projet ep ON p.id = ep.id_projet 
                JOIN clients c ON p.id_client = c.id 
                WHERE ep.id_user = ? AND ep.actif = 1 
                ORDER BY p.date_creation DESC";
        
        $stmt = $this->db->query($sql, [$userId]);
        return $stmt->fetchAll();
    }
    
    /**
     * Compter les projets actifs d'un utilisateur
     */
    public function countActiveProjects($userId) {
        $sql = "SELECT COUNT(*) as total 
                FROM equipe_projet ep 
                JOIN projets p ON ep.id_projet = p.id 
                WHERE ep.id_user = ? AND ep.actif = 1 AND p.statut = 'en cours'";
        
        $stmt = $this->db->query($sql, [$userId]);
        $result = $stmt->fetch();
        return $result['total'];
    }
}
?>
