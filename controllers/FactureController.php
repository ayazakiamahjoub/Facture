<?php
/**
 * Contrôleur Facture - Gestion des factures
 */

require_once 'BaseController.php';

class FactureController extends BaseController {
    private $factureModel;
    private $clientModel;
    private $projetModel;
    
    public function __construct() {
        parent::__construct();
        $this->requireAuth();
        $this->factureModel = new Facture();
        $this->clientModel = new Client();
        $this->projetModel = new Projet();
    }
    
    /**
     * Liste des factures
     */
    public function index() {
        $page = $_GET['p'] ?? 1;
        $search = $_GET['search'] ?? '';
        $status = $_GET['status'] ?? '';
        
        if (!empty($search)) {
            $factures = $this->factureModel->searchInvoices($search, $status);
            $totalFactures = count($factures);
            $pagination = null;
        } else if (!empty($status)) {
            $factures = $this->factureModel->getByStatus($status);
            $totalFactures = count($factures);
            $pagination = null;
        } else {
            $totalFactures = $this->factureModel->count();
            $pagination = $this->paginate($totalFactures, $page);
            $factures = $this->factureModel->getAllWithDetails();
        }
        
        $this->render('factures/index', [
            'factures' => $factures,
            'pagination' => $pagination,
            'search' => $search,
            'status' => $status,
            'totalFactures' => $totalFactures
        ]);
    }
    
    /**
     * Afficher une facture
     */
    public function view() {
        $id = $_GET['id'] ?? null;
        
        if (!$id || !$this->factureModel->exists($id)) {
            $this->setFlashMessage('Facture non trouvée.', 'error');
            $this->redirect('index.php?page=factures');
        }
        
        $facture = $this->factureModel->getByIdWithDetails($id);
        
        $this->render('factures/view', [
            'facture' => $facture
        ]);
    }
    
    /**
     * Créer une nouvelle facture
     */
    public function create() {
        $errors = [];
        $formData = [];
        $clients = $this->clientModel->getActiveClients();
        $projets = [];
        
        // Si un client est pré-sélectionné, récupérer ses projets
        $selectedClientId = $_GET['client_id'] ?? null;
        if ($selectedClientId) {
            $projets = $this->clientModel->getClientProjects($selectedClientId);
            $formData['id_client'] = $selectedClientId;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $formData = $this->sanitizeInput($_POST);
            
            // Validation
            $requiredFields = ['id_client', 'montant', 'date_facture'];
            $errors = $this->validateRequired($requiredFields);
            
            if (empty($errors)) {
                // Validations spécifiques
                if (!$this->clientModel->exists($formData['id_client'])) {
                    $errors[] = 'Client sélectionné invalide.';
                }
                
                if (!empty($formData['id_projet']) && !$this->projetModel->exists($formData['id_projet'])) {
                    $errors[] = 'Projet sélectionné invalide.';
                }
                
                if (!is_numeric($formData['montant']) || floatval($formData['montant']) <= 0) {
                    $errors[] = 'Le montant doit être un nombre positif.';
                }
                
                if (!$this->isValidDate($formData['date_facture'])) {
                    $errors[] = 'Format de date de facture invalide.';
                }
                
                if (!empty($formData['date_echeance']) && !$this->isValidDate($formData['date_echeance'])) {
                    $errors[] = 'Format de date d\'échéance invalide.';
                }
                
                // Vérifier l'unicité du numéro de facture si fourni
                if (!empty($formData['numero_facture']) && $this->factureModel->invoiceNumberExists($formData['numero_facture'])) {
                    $errors[] = 'Ce numéro de facture existe déjà.';
                }
            }
            
            if (empty($errors)) {
                try {
                    $factureData = [
                        'id_client' => $formData['id_client'],
                        'id_projet' => !empty($formData['id_projet']) ? $formData['id_projet'] : null,
                        'montant' => floatval($formData['montant']),
                        'date_facture' => $formData['date_facture'],
                        'date_echeance' => $formData['date_echeance'] ?: null,
                        'statut' => $formData['statut'] ?? 'impayée',
                        'description' => $formData['description'] ?? ''
                    ];
                    
                    // Ajouter le numéro de facture si fourni
                    if (!empty($formData['numero_facture'])) {
                        $factureData['numero_facture'] = $formData['numero_facture'];
                    }
                    
                    $factureId = $this->factureModel->createInvoice($factureData);
                    
                    $this->setFlashMessage('Facture créée avec succès.', 'success');
                    $this->redirect('index.php?page=factures&action=view&id=' . $factureId);
                } catch (Exception $e) {
                    $errors[] = 'Erreur lors de la création de la facture.';
                }
            }
        }
        
        $this->render('factures/create', [
            'errors' => $errors,
            'formData' => $formData,
            'clients' => $clients,
            'projets' => $projets
        ]);
    }
    
    /**
     * Modifier une facture
     */
    public function edit() {
        $id = $_GET['id'] ?? null;
        
        if (!$id || !$this->factureModel->exists($id)) {
            $this->setFlashMessage('Facture non trouvée.', 'error');
            $this->redirect('index.php?page=factures');
        }
        
        $facture = $this->factureModel->getById($id);
        $clients = $this->clientModel->getActiveClients();
        $projets = $this->clientModel->getClientProjects($facture['id_client']);
        $errors = [];
        $formData = $facture;
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $formData = $this->sanitizeInput($_POST);
            
            // Validation
            $requiredFields = ['id_client', 'montant', 'date_facture', 'numero_facture'];
            $errors = $this->validateRequired($requiredFields);
            
            if (empty($errors)) {
                // Validations spécifiques
                if (!$this->clientModel->exists($formData['id_client'])) {
                    $errors[] = 'Client sélectionné invalide.';
                }
                
                if (!empty($formData['id_projet']) && !$this->projetModel->exists($formData['id_projet'])) {
                    $errors[] = 'Projet sélectionné invalide.';
                }
                
                if (!is_numeric($formData['montant']) || floatval($formData['montant']) <= 0) {
                    $errors[] = 'Le montant doit être un nombre positif.';
                }
                
                if (!$this->isValidDate($formData['date_facture'])) {
                    $errors[] = 'Format de date de facture invalide.';
                }
                
                if ($this->factureModel->invoiceNumberExists($formData['numero_facture'], $id)) {
                    $errors[] = 'Ce numéro de facture existe déjà.';
                }
            }
            
            if (empty($errors)) {
                try {
                    $factureData = [
                        'numero_facture' => $formData['numero_facture'],
                        'id_client' => $formData['id_client'],
                        'id_projet' => !empty($formData['id_projet']) ? $formData['id_projet'] : null,
                        'montant' => floatval($formData['montant']),
                        'date_facture' => $formData['date_facture'],
                        'date_echeance' => $formData['date_echeance'] ?: null,
                        'statut' => $formData['statut'],
                        'description' => $formData['description'] ?? ''
                    ];
                    
                    $this->factureModel->update($id, $factureData);
                    
                    $this->setFlashMessage('Facture modifiée avec succès.', 'success');
                    $this->redirect('index.php?page=factures&action=view&id=' . $id);
                } catch (Exception $e) {
                    $errors[] = 'Erreur lors de la modification de la facture.';
                }
            }
        }
        
        $this->render('factures/edit', [
            'facture' => $facture,
            'errors' => $errors,
            'formData' => $formData,
            'clients' => $clients,
            'projets' => $projets
        ]);
    }
    
    /**
     * Marquer une facture comme payée
     */
    public function markPaid() {
        $id = $_GET['id'] ?? null;
        
        if (!$id || !$this->factureModel->exists($id)) {
            $this->setFlashMessage('Facture non trouvée.', 'error');
            $this->redirect('index.php?page=factures');
        }
        
        try {
            $this->factureModel->markAsPaid($id);
            $this->setFlashMessage('Facture marquée comme payée.', 'success');
        } catch (Exception $e) {
            $this->setFlashMessage('Erreur lors de la mise à jour du statut.', 'error');
        }
        
        $this->redirect('index.php?page=factures&action=view&id=' . $id);
    }
    
    /**
     * Marquer une facture comme impayée
     */
    public function markUnpaid() {
        $id = $_GET['id'] ?? null;
        
        if (!$id || !$this->factureModel->exists($id)) {
            $this->setFlashMessage('Facture non trouvée.', 'error');
            $this->redirect('index.php?page=factures');
        }
        
        try {
            $this->factureModel->markAsUnpaid($id);
            $this->setFlashMessage('Facture marquée comme impayée.', 'success');
        } catch (Exception $e) {
            $this->setFlashMessage('Erreur lors de la mise à jour du statut.', 'error');
        }
        
        $this->redirect('index.php?page=factures&action=view&id=' . $id);
    }
    
    /**
     * Supprimer une facture
     */
    public function delete() {
        $this->requireAdmin();
        
        $id = $_GET['id'] ?? null;
        
        if (!$id || !$this->factureModel->exists($id)) {
            $this->setFlashMessage('Facture non trouvée.', 'error');
            $this->redirect('index.php?page=factures');
        }
        
        try {
            $this->factureModel->delete($id);
            $this->setFlashMessage('Facture supprimée avec succès.', 'success');
        } catch (Exception $e) {
            $this->setFlashMessage('Erreur lors de la suppression de la facture.', 'error');
        }
        
        $this->redirect('index.php?page=factures');
    }
    
    /**
     * Récupérer les projets d'un client (AJAX)
     */
    public function getClientProjects() {
        $clientId = $_GET['client_id'] ?? null;
        
        if (!$clientId || !$this->clientModel->exists($clientId)) {
            $this->jsonResponse([]);
        }
        
        $projets = $this->clientModel->getClientProjects($clientId);
        
        $results = array_map(function($projet) {
            return [
                'id' => $projet['id'],
                'titre_projet' => $projet['titre_projet'],
                'statut' => $projet['statut']
            ];
        }, $projets);
        
        $this->jsonResponse($results);
    }
    
    /**
     * Statistiques des factures
     */
    public function stats() {
        $this->requireAdmin();
        
        // Statistiques générales
        $stats = $this->factureModel->getInvoiceStats();
        
        // Factures en retard
        $overdueInvoices = $this->factureModel->getOverdueInvoices();
        
        // Chiffre d'affaires par mois (12 derniers mois)
        $monthlyRevenue = $this->getMonthlyRevenue();
        
        $this->render('factures/stats', [
            'stats' => $stats,
            'overdueInvoices' => $overdueInvoices,
            'monthlyRevenue' => $monthlyRevenue
        ]);
    }
    
    /**
     * Récupérer le chiffre d'affaires par mois
     */
    private function getMonthlyRevenue() {
        $sql = "SELECT 
                    DATE_FORMAT(date_facture, '%Y-%m') as month,
                    SUM(CASE WHEN statut = 'payée' THEN montant ELSE 0 END) as paid_amount,
                    SUM(CASE WHEN statut = 'impayée' THEN montant ELSE 0 END) as unpaid_amount,
                    COUNT(*) as total_invoices
                FROM factures 
                WHERE date_facture >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
                GROUP BY DATE_FORMAT(date_facture, '%Y-%m')
                ORDER BY month";
        
        $db = getDB();
        $stmt = $db->query($sql);
        return $stmt->fetchAll();
    }
}
?>
