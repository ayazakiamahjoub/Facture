<?php
/**
 * Contrôleur Client - Gestion des clients
 */

require_once 'BaseController.php';

class ClientController extends BaseController {
    private $clientModel;
    
    public function __construct() {
        parent::__construct();
        $this->requireAuth();
        $this->clientModel = new Client();
    }
    
    /**
     * Liste des clients
     */
    public function index() {
        $page = $_GET['p'] ?? 1;
        $search = $_GET['search'] ?? '';
        
        if (!empty($search)) {
            $clients = $this->clientModel->searchClients($search);
            $totalClients = count($clients);
            $pagination = null;
        } else {
            $totalClients = $this->clientModel->count('actif = 1');
            $pagination = $this->paginate($totalClients, $page);
            $clients = $this->clientModel->getActiveClients();
        }
        
        $this->render('clients/index', [
            'clients' => $clients,
            'pagination' => $pagination,
            'search' => $search,
            'totalClients' => $totalClients
        ]);
    }
    
    /**
     * Afficher un client
     */
    public function view() {
        $id = $_GET['id'] ?? null;
        
        if (!$id || !$this->clientModel->exists($id)) {
            $this->setFlashMessage('Client non trouvé.', 'error');
            $this->redirect('index.php?page=clients');
        }
        
        $client = $this->clientModel->getById($id);
        $projects = $this->clientModel->getClientProjects($id);
        $invoices = $this->clientModel->getClientInvoices($id);
        $stats = $this->clientModel->getClientStats($id);
        
        $this->render('clients/view', [
            'client' => $client,
            'projects' => $projects,
            'invoices' => $invoices,
            'stats' => $stats
        ]);
    }
    
    /**
     * Créer un nouveau client
     */
    public function create() {
        $errors = [];
        $formData = [];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $formData = $this->sanitizeInput($_POST);
            
            // Validation
            $requiredFields = ['nom_client', 'email'];
            $errors = $this->validateRequired($requiredFields);
            
            if (empty($errors)) {
                // Validations spécifiques
                if (!$this->isValidEmail($formData['email'])) {
                    $errors[] = 'Format d\'email invalide.';
                }
                
                if ($this->clientModel->emailExists($formData['email'])) {
                    $errors[] = 'Cet email est déjà utilisé par un autre client.';
                }
            }
            
            if (empty($errors)) {
                try {
                    $clientId = $this->clientModel->create([
                        'nom_client' => $formData['nom_client'],
                        'email' => $formData['email'],
                        'telephone' => $formData['telephone'] ?? '',
                        'adresse' => $formData['adresse'] ?? ''
                    ]);
                    
                    $this->setFlashMessage('Client créé avec succès.', 'success');
                    $this->redirect('index.php?page=clients&action=view&id=' . $clientId);
                } catch (Exception $e) {
                    $errors[] = 'Erreur lors de la création du client.';
                }
            }
        }
        
        $this->render('clients/create', [
            'errors' => $errors,
            'formData' => $formData
        ]);
    }
    
    /**
     * Modifier un client
     */
    public function edit() {
        $id = $_GET['id'] ?? null;
        
        if (!$id || !$this->clientModel->exists($id)) {
            $this->setFlashMessage('Client non trouvé.', 'error');
            $this->redirect('index.php?page=clients');
        }
        
        $client = $this->clientModel->getById($id);
        $errors = [];
        $formData = $client;
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $formData = $this->sanitizeInput($_POST);
            
            // Validation
            $requiredFields = ['nom_client', 'email'];
            $errors = $this->validateRequired($requiredFields);
            
            if (empty($errors)) {
                // Validations spécifiques
                if (!$this->isValidEmail($formData['email'])) {
                    $errors[] = 'Format d\'email invalide.';
                }
                
                if ($this->clientModel->emailExists($formData['email'], $id)) {
                    $errors[] = 'Cet email est déjà utilisé par un autre client.';
                }
            }
            
            if (empty($errors)) {
                try {
                    $this->clientModel->update($id, [
                        'nom_client' => $formData['nom_client'],
                        'email' => $formData['email'],
                        'telephone' => $formData['telephone'] ?? '',
                        'adresse' => $formData['adresse'] ?? ''
                    ]);
                    
                    $this->setFlashMessage('Client modifié avec succès.', 'success');
                    $this->redirect('index.php?page=clients&action=view&id=' . $id);
                } catch (Exception $e) {
                    $errors[] = 'Erreur lors de la modification du client.';
                }
            }
        }
        
        $this->render('clients/edit', [
            'client' => $client,
            'errors' => $errors,
            'formData' => $formData
        ]);
    }
    
    /**
     * Supprimer un client (désactivation)
     */
    // public function delete() {
    //     $id = $_GET['id'] ?? null;
        
    //     if (!$id || !$this->clientModel->exists($id)) {
    //         $this->setFlashMessage('Client non trouvé.', 'error');
    //         $this->redirect('index.php?page=clients');
    //     }
        
    //     // Vérifier s'il y a des projets actifs
    //     $activeProjects = $this->clientModel->countActiveProjects($id);
    //     if ($activeProjects > 0) {
    //         $this->setFlashMessage('Impossible de supprimer ce client car il a des projets actifs.', 'error');
    //         $this->redirect('index.php?page=clients&action=view&id=' . $id);
    //     }
        
    //     try {
    //         $this->clientModel->deactivate($id);
    //         $this->setFlashMessage('Client désactivé avec succès.', 'success');
    //     } catch (Exception $e) {
    //         $this->setFlashMessage('Erreur lors de la suppression du client.', 'error');
    //     }
        
    //     $this->redirect('index.php?page=clients');
    // }
    

    public function delete() {
    $id = $_GET['id'] ?? null;
    
    if (!$id || !$this->clientModel->exists($id)) {
        $this->setFlashMessage('Client non trouvé.', 'error');
        $this->redirect('index.php?page=clients');
    }

    // Vérifier projets actifs
    $activeProjects = $this->clientModel->countActiveProjects($id);
    if ($activeProjects > 0) {
        $this->setFlashMessage('Impossible de supprimer ce client car il a des projets actifs.', 'error');
        $this->redirect('index.php?page=clients&action=view&id=' . $id);
    }

    try {
        // 1) Supprimer ses factures
        $factureModel = new Facture();
        $factures = $factureModel->getByClient($id);
        foreach ($factures as $facture) {
            $factureModel->delete($facture['id_facture']);
        }

        // 2) Supprimer le client
        $this->clientModel->delete($id);

        $this->setFlashMessage('Client et ses factures supprimés avec succès.', 'success');
    } catch (Exception $e) {
        $this->setFlashMessage('Erreur lors de la suppression du client.', 'error');
    }

    $this->redirect('index.php?page=clients');
}

    /**
     * Réactiver un client
     */
    public function activate() {
        $this->requireAdmin();
        
        $id = $_GET['id'] ?? null;
        
        if (!$id || !$this->clientModel->exists($id)) {
            $this->setFlashMessage('Client non trouvé.', 'error');
            $this->redirect('index.php?page=clients');
        }
        
        try {
            $this->clientModel->activate($id);
            $this->setFlashMessage('Client réactivé avec succès.', 'success');
        } catch (Exception $e) {
            $this->setFlashMessage('Erreur lors de la réactivation du client.', 'error');
        }
        
        $this->redirect('index.php?page=clients&action=view&id=' . $id);
    }
    
    /**
     * Recherche AJAX
     */
    public function search() {
        $query = $_GET['q'] ?? '';
        
        if (strlen($query) < 2) {
            $this->jsonResponse([]);
        }
        
        $clients = $this->clientModel->searchClients($query);
        
        $results = array_map(function($client) {
            return [
                'id' => $client['id'],
                'nom_client' => $client['nom_client'],
                'email' => $client['email'],
                'telephone' => $client['telephone']
            ];
        }, $clients);
        
        $this->jsonResponse($results);
    }
    
    /**
     * Exporter les clients
     */
    public function export() {
        $this->requireAdmin();
        
        $format = $_GET['format'] ?? 'csv';
        $clients = $this->clientModel->getActiveClients();
        
        if ($format === 'csv') {
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="clients_' . date('Y-m-d') . '.csv"');
            
            $output = fopen('php://output', 'w');
            
            // En-têtes
            fputcsv($output, ['ID', 'Nom Client', 'Email', 'Téléphone', 'Adresse', 'Date Création']);
            
            // Données
            foreach ($clients as $client) {
                fputcsv($output, [
                    $client['id'],
                    $client['nom_client'],
                    $client['email'],
                    $client['telephone'],
                    $client['adresse'],
                    $client['date_creation']
                ]);
            }
            
            fclose($output);
            exit;
        }
        
        $this->setFlashMessage('Format d\'export non supporté.', 'error');
        $this->redirect('index.php?page=clients');
    }
    
    /**
     * Statistiques des clients
     */
    public function stats() {
        $this->requireAdmin();
        
        // Statistiques générales
        $totalClients = $this->clientModel->count('actif = 1');
        $inactiveClients = $this->clientModel->count('actif = 0');
        
        // Top clients par chiffre d'affaires
        $topClients = $this->getTopClientsByRevenue(10);
        
        // Clients récents
        $recentClients = $this->clientModel->findWhere(
            'actif = 1', 
            [], 
            10, 
            0, 
            'date_creation DESC'
        );
        
        $this->render('clients/stats', [
            'totalClients' => $totalClients,
            'inactiveClients' => $inactiveClients,
            'topClients' => $topClients,
            'recentClients' => $recentClients
        ]);
    }
    
    /**
     * Récupérer les meilleurs clients par chiffre d'affaires
     */
    private function getTopClientsByRevenue($limit = 10) {
        $sql = "SELECT 
                    c.id,
                    c.nom_client,
                    c.email,
                    SUM(f.montant) as total_revenue,
                    COUNT(f.id_facture) as total_invoices,
                    COUNT(DISTINCT p.id) as total_projects
                FROM clients c
                LEFT JOIN factures f ON c.id = f.id_client AND f.statut = 'payée'
                LEFT JOIN projets p ON c.id = p.id_client
                WHERE c.actif = 1
                GROUP BY c.id, c.nom_client, c.email
                ORDER BY total_revenue DESC
                LIMIT ?";
        
        $db = getDB();
        $stmt = $db->query($sql, [$limit]);
        return $stmt->fetchAll();
    }
}
?>
