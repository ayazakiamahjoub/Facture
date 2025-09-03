<?php
/**
 * Contrôleur du tableau de bord
 */

require_once 'BaseController.php';

class DashboardController extends BaseController {
    private $userModel;
    private $clientModel;
    private $projetModel;
    private $factureModel;
    
    public function __construct() {
        parent::__construct();
        $this->requireAuth();
        
        $this->userModel = new User();
        $this->clientModel = new Client();
        $this->projetModel = new Projet();
        $this->factureModel = new Facture();
    }
    
    /**
     * Page d'accueil du dashboard
     */
    public function index() {
        // Statistiques générales
        $stats = [
            'total_projects' => $this->projetModel->count(),
            'active_projects' => $this->projetModel->count('statut = ?', ['en cours']),
            'total_clients' => $this->clientModel->count('actif = 1'),
            'total_users' => $this->userModel->count('actif = 1'),
            'total_revenue' => $this->factureModel->getTotalRevenue(),
            'unpaid_amount' => $this->factureModel->getTotalUnpaid()
        ];
        
        // Projets récents
        $recentProjects = $this->projetModel->getAllWithClient(5, 0, 'p.date_creation DESC');
        
        // Factures récentes
        $recentInvoices = $this->factureModel->getAllWithDetails(5, 0, 'f.date_facture DESC');
        
        // Projets en retard
        $overdueProjects = $this->projetModel->getOverdueProjects();
        
        // Factures en retard
        $overdueInvoices = $this->factureModel->getOverdueInvoices();
        
        // Projets qui se terminent bientôt
        $upcomingDeadlines = $this->projetModel->getUpcomingDeadlines(7);
        
        // Statistiques par statut de projet
        $projectStats = $this->projetModel->getProjectStats();
        
        // Statistiques des factures
        $invoiceStats = $this->factureModel->getInvoiceStats();
        
        // Si l'utilisateur n'est pas admin, filtrer ses projets
        $userProjects = [];
        if (!$this->isAdmin()) {
            $equipeModel = new EquipeProjet();
            $userProjects = $equipeModel->getUserProjects($this->user['id']);
        }
        
        $this->render('dashboard/index', [
            'stats' => $stats,
            'recentProjects' => $recentProjects,
            'recentInvoices' => $recentInvoices,
            'overdueProjects' => $overdueProjects,
            'overdueInvoices' => $overdueInvoices,
            'upcomingDeadlines' => $upcomingDeadlines,
            'projectStats' => $projectStats,
            'invoiceStats' => $invoiceStats,
            'userProjects' => $userProjects
        ]);
    }
    
    /**
     * Statistiques détaillées
     */
    public function stats() {
        // Pas besoin d'être admin, mais les données seront filtrées selon le rôle
        
        // Période par défaut : 12 derniers mois
        $startDate = $_GET['start_date'] ?? date('Y-m-d', strtotime('-12 months'));
        $endDate = $_GET['end_date'] ?? date('Y-m-d');
        
        // Chiffre d'affaires par mois
        $monthlyRevenue = $this->getMonthlyRevenue($startDate, $endDate);
        
        // Projets par statut
        $projectsByStatus = $this->getProjectsByStatus();
        
        // Top clients par chiffre d'affaires
        $topClients = $this->getTopClients(10);
        
        // Utilisateurs les plus actifs
        $activeUsers = $this->getMostActiveUsers(10);
        
        $this->render('dashboard/stats', [
            'startDate' => $startDate,
            'endDate' => $endDate,
            'monthlyRevenue' => $monthlyRevenue,
            'projectsByStatus' => $projectsByStatus,
            'topClients' => $topClients,
            'activeUsers' => $activeUsers
        ]);
    }
    
    /**
     * Récupérer le chiffre d'affaires par mois
     */
    private function getMonthlyRevenue($startDate, $endDate) {
        $db = getDB();
        $sql = "SELECT
                    DATE_FORMAT(date_facture, '%Y-%m') as month,
                    SUM(montant) as revenue
                FROM factures
                WHERE statut = 'payée'
                AND date_facture BETWEEN ? AND ?
                GROUP BY DATE_FORMAT(date_facture, '%Y-%m')
                ORDER BY month";

        $stmt = $db->query($sql, [$startDate, $endDate]);
        return $stmt->fetchAll();
    }
    
    /**
     * Récupérer les projets par statut
     */
    private function getProjectsByStatus() {
        $db = getDB();
        $sql = "SELECT
                    statut,
                    COUNT(*) as count
                FROM projets
                GROUP BY statut";

        $stmt = $db->query($sql);
        return $stmt->fetchAll();
    }
    
    /**
     * Récupérer les meilleurs clients
     */
    private function getTopClients($limit = 10) {
        $db = getDB();
        $sql = "SELECT
                    c.id,
                    c.nom_client,
                    SUM(f.montant) as total_revenue,
                    COUNT(f.id_facture) as total_invoices
                FROM clients c
                JOIN factures f ON c.id = f.id_client
                WHERE f.statut = 'payée'
                GROUP BY c.id, c.nom_client
                ORDER BY total_revenue DESC
                LIMIT ?";

        $stmt = $db->query($sql, [$limit]);
        return $stmt->fetchAll();
    }
    
    /**
     * Récupérer les utilisateurs les plus actifs
     */
    private function getMostActiveUsers($limit = 10) {
        $db = getDB();
        $sql = "SELECT
                    u.id,
                    u.nom,
                    COUNT(DISTINCT ep.id_projet) as total_projects,
                    COUNT(CASE WHEN p.statut = 'en cours' THEN 1 END) as active_projects,
                    COUNT(CASE WHEN p.statut = 'terminé' THEN 1 END) as completed_projects
                FROM users u
                JOIN equipe_projet ep ON u.id = ep.id_user
                JOIN projets p ON ep.id_projet = p.id
                WHERE ep.actif = 1
                GROUP BY u.id, u.nom
                ORDER BY total_projects DESC
                LIMIT ?";

        $stmt = $db->query($sql, [$limit]);
        return $stmt->fetchAll();
    }
    
    /**
     * API pour les données du dashboard (AJAX)
     */
    public function api() {
        $action = $_GET['api_action'] ?? '';
        
        switch ($action) {
            case 'quick_stats':
                $this->jsonResponse([
                    'total_projects' => $this->projetModel->count(),
                    'active_projects' => $this->projetModel->count('statut = ?', ['en cours']),
                    'total_clients' => $this->clientModel->count('actif = 1'),
                    'total_revenue' => $this->factureModel->getTotalRevenue(),
                    'unpaid_amount' => $this->factureModel->getTotalUnpaid()
                ]);
                break;
                
            case 'recent_activities':
                // Activités récentes (projets créés, factures émises, etc.)
                $activities = [];
                
                // Projets récents
                $recentProjects = $this->projetModel->getAllWithClient(3);
                foreach ($recentProjects as $project) {
                    $activities[] = [
                        'type' => 'project',
                        'message' => "Nouveau projet : {$project['titre_projet']}",
                        'date' => $project['date_creation'],
                        'icon' => 'fas fa-project-diagram'
                    ];
                }
                
                // Factures récentes
                $recentInvoices = $this->factureModel->getAllWithDetails(3);
                foreach ($recentInvoices as $invoice) {
                    $activities[] = [
                        'type' => 'invoice',
                        'message' => "Facture {$invoice['numero_facture']} créée",
                        'date' => $invoice['date_creation'],
                        'icon' => 'fas fa-file-invoice'
                    ];
                }
                
                // Trier par date
                usort($activities, function($a, $b) {
                    return strtotime($b['date']) - strtotime($a['date']);
                });
                
                $this->jsonResponse(array_slice($activities, 0, 5));
                break;
                
            default:
                $this->jsonResponse(['error' => 'Action non reconnue'], 400);
        }
    }

    /**
     * Afficher le tableau récapitulatif clients/projets/équipes
     */
    public function recap() {
        $this->requireAuth();

        // Récupérer tous les clients avec leurs projets et équipes
        $clientsData = $this->getClientsRecapData();

        // Statistiques pour le récapitulatif
        $recapStats = [
            'total_clients' => count($clientsData),
            'total_projets' => array_sum(array_column($clientsData, 'nb_projets')),
            'projets_actifs' => array_sum(array_map(function($client) {
                return count(array_filter($client['projets'], function($projet) {
                    return $projet['statut'] === 'en cours';
                }));
            }, $clientsData)),
            'total_membres' => array_sum(array_map(function($client) {
                $membres = [];
                foreach ($client['projets'] as $projet) {
                    foreach ($projet['equipe'] as $membre) {
                        $membres[$membre['id_utilisateur']] = true;
                    }
                }
                return count($membres);
            }, $clientsData))
        ];

        $this->render('dashboard/recap', [
            'clientsData' => $clientsData,
            'recapStats' => $recapStats
        ]);
    }

    /**
     * Récupérer les données récapitulatives des clients
     */
    private function getClientsRecapData() {
        $clientModel = new Client();
        $projetModel = new Projet();
        $factureModel = new Facture();
        $db = Database::getInstance();

        $sql = "
            SELECT
                c.id as client_id,
                c.nom_client,
                c.email as client_email,
                c.telephone as client_telephone,
                c.actif as client_actif,
                c.date_creation as client_date_creation,
                COUNT(DISTINCT p.id) as nb_projets,
                SUM(CASE WHEN p.statut = 'en cours' THEN 1 ELSE 0 END) as projets_en_cours,
                SUM(CASE WHEN p.statut = 'terminé' THEN 1 ELSE 0 END) as projets_termines,
                COALESCE(SUM(p.budget), 0) as budget_total,
                COALESCE(SUM(f.montant), 0) as chiffre_affaires
            FROM clients c
            LEFT JOIN projets p ON c.id = p.id_client
            LEFT JOIN factures f ON c.id = f.id_client AND f.statut = 'payée'
            WHERE c.actif = 1
            GROUP BY c.id, c.nom_client, c.email, c.telephone, c.actif, c.date_creation
            ORDER BY c.nom_client ASC
        ";

        $clients = $db->query($sql)->fetchAll();

        // Pour chaque client, récupérer ses projets avec les équipes
        foreach ($clients as &$client) {
            $client['projets'] = $this->getClientProjects($client['client_id']);
        }

        return $clients;
    }

    /**
     * Récupérer les projets d'un client avec leurs équipes
     */
    private function getClientProjects($clientId) {
        $db = Database::getInstance();

        $sql = "
            SELECT
                p.id,
                p.titre_projet,
                p.description,
                p.statut,
                p.date_debut,
                p.date_fin_prevue,
                p.date_fin_reelle,
                p.budget,
                p.date_creation
            FROM projets p
            WHERE p.id_client = ?
            ORDER BY p.date_creation DESC
        ";

        $projets = $db->query($sql, [$clientId])->fetchAll();

        // Pour chaque projet, récupérer l'équipe
        foreach ($projets as &$projet) {
            $projet['equipe'] = $this->getProjectTeam($projet['id']);
            $projet['nb_membres'] = count($projet['equipe']);
            $projet['chef_projet'] = array_filter($projet['equipe'], function($membre) {
                return $membre['role_projet'] === 'chef';
            });
        }

        return $projets;
    }

    /**
     * Récupérer l'équipe d'un projet
     */
    private function getProjectTeam($projetId) {
        $db = Database::getInstance();

        $sql = "
            SELECT
                u.id as id_utilisateur,
                u.nom,
                u.email,
                u.role as role_utilisateur,
                ep.role_projet,
                ep.date_assignation
            FROM equipe_projet ep
            JOIN users u ON ep.id_user = u.id
            WHERE ep.id_projet = ? AND u.actif = 1 AND ep.actif = 1
            ORDER BY ep.role_projet DESC, u.nom ASC
        ";

        return $db->query($sql, [$projetId])->fetchAll();
    }
}
?>
