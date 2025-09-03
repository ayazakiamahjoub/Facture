<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>
                <i class="fas fa-table me-2"></i>
                Tableau Récapitulatif
            </h1>
            <div>
                <a href="index.php?page=dashboard" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i>
                    Retour au dashboard
                </a>
                <a href="index.php?page=dashboard&action=stats" class="btn btn-info">
                    <i class="fas fa-chart-line me-1"></i>
                    Statistiques
                </a>
                <button class="btn btn-success" onclick="exportToExcel()">
                    <i class="fas fa-file-excel me-1"></i>
                    Exporter Excel
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Statistiques rapides -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h3 class="mb-0"><?= $recapStats['total_clients'] ?></h3>
                        <p class="mb-0">Clients actifs</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-users fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h3 class="mb-0"><?= $recapStats['total_projets'] ?></h3>
                        <p class="mb-0">Projets total</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-project-diagram fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h3 class="mb-0"><?= $recapStats['projets_actifs'] ?></h3>
                        <p class="mb-0">Projets en cours</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-clock fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h3 class="mb-0"><?= $recapStats['total_membres'] ?></h3>
                        <p class="mb-0">Membres impliqués</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-user-friends fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filtres et recherche -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="searchClient" class="form-label">
                                <i class="fas fa-search me-1"></i>
                                Rechercher un client
                            </label>
                            <input type="text" class="form-control" id="searchClient" placeholder="Nom du client...">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="filterStatus" class="form-label">
                                <i class="fas fa-filter me-1"></i>
                                Statut projet
                            </label>
                            <select class="form-select" id="filterStatus">
                                <option value="">Tous les statuts</option>
                                <option value="en cours">En cours</option>
                                <option value="terminé">Terminé</option>
                                <option value="annulé">Annulé</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="filterTeamSize" class="form-label">
                                <i class="fas fa-users me-1"></i>
                                Taille équipe
                            </label>
                            <select class="form-select" id="filterTeamSize">
                                <option value="">Toutes les tailles</option>
                                <option value="1">1 membre</option>
                                <option value="2-3">2-3 membres</option>
                                <option value="4+">4+ membres</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-grid">
                                <button class="btn btn-outline-secondary" onclick="resetFilters()">
                                    <i class="fas fa-undo me-1"></i>
                                    Reset
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tableau récapitulatif -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-table me-2"></i>
                    Récapitulatif Clients - Projets - Équipes
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="recapTable">
                        <thead class="table-dark">
                            <tr>
                                <th>Client</th>
                                <th>Contact</th>
                                <th>Projets</th>
                                <th>Statut</th>
                                <th>Équipe Responsable</th>
                                <th>Chef de Projet</th>
                                <th>Budget</th>
                                <th>CA Réalisé</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($clientsData as $client): ?>
                                <?php if (empty($client['projets'])): ?>
                                    <!-- Client sans projet -->
                                    <tr class="client-row" data-client="<?= strtolower($client['nom_client']) ?>" data-status="" data-team-size="0">
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar me-2">
                                                    <div class="avatar-initial bg-secondary rounded-circle">
                                                        <?= strtoupper(substr($client['nom_client'], 0, 2)) ?>
                                                    </div>
                                                </div>
                                                <div>
                                                    <strong>
                                                        <a href="index.php?page=clients&action=view&id=<?= $client['client_id'] ?>">
                                                            <?= htmlspecialchars($client['nom_client']) ?>
                                                        </a>
                                                    </strong>
                                                    <br><small class="text-muted">Client depuis <?= date('d/m/Y', strtotime($client['client_date_creation'])) ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <?php if (!empty($client['client_email'])): ?>
                                                <a href="mailto:<?= htmlspecialchars($client['client_email']) ?>">
                                                    <i class="fas fa-envelope me-1"></i>
                                                    <?= htmlspecialchars($client['client_email']) ?>
                                                </a>
                                            <?php endif; ?>
                                            <?php if (!empty($client['client_telephone'])): ?>
                                                <br><a href="tel:<?= htmlspecialchars($client['client_telephone']) ?>">
                                                    <i class="fas fa-phone me-1"></i>
                                                    <?= htmlspecialchars($client['client_telephone']) ?>
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark">
                                                <i class="fas fa-exclamation-triangle me-1"></i>
                                                Aucun projet
                                            </span>
                                        </td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>
                                            <a href="index.php?page=projets&action=create&client_id=<?= $client['client_id'] ?>" 
                                               class="btn btn-sm btn-primary" title="Créer un projet">
                                                <i class="fas fa-plus"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <!-- Client avec projets -->
                                    <?php foreach ($client['projets'] as $index => $projet): ?>
                                        <tr class="client-row" 
                                            data-client="<?= strtolower($client['nom_client']) ?>" 
                                            data-status="<?= $projet['statut'] ?>" 
                                            data-team-size="<?= $projet['nb_membres'] ?>">
                                            
                                            <?php if ($index === 0): ?>
                                                <!-- Première ligne : afficher les infos client -->
                                                <td rowspan="<?= count($client['projets']) ?>">
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar me-2">
                                                            <div class="avatar-initial bg-primary rounded-circle">
                                                                <?= strtoupper(substr($client['nom_client'], 0, 2)) ?>
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <strong>
                                                                <a href="index.php?page=clients&action=view&id=<?= $client['client_id'] ?>">
                                                                    <?= htmlspecialchars($client['nom_client']) ?>
                                                                </a>
                                                            </strong>
                                                            <br><small class="text-muted">
                                                                <?= $client['nb_projets'] ?> projet(s)
                                                            </small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td rowspan="<?= count($client['projets']) ?>">
                                                    <?php if (!empty($client['client_email'])): ?>
                                                        <a href="mailto:<?= htmlspecialchars($client['client_email']) ?>">
                                                            <i class="fas fa-envelope me-1"></i>
                                                            <?= htmlspecialchars($client['client_email']) ?>
                                                        </a>
                                                    <?php endif; ?>
                                                    <?php if (!empty($client['client_telephone'])): ?>
                                                        <br><a href="tel:<?= htmlspecialchars($client['client_telephone']) ?>">
                                                            <i class="fas fa-phone me-1"></i>
                                                            <?= htmlspecialchars($client['client_telephone']) ?>
                                                        </a>
                                                    <?php endif; ?>
                                                </td>
                                            <?php endif; ?>
                                            
                                            <!-- Informations du projet -->
                                            <td>
                                                <strong>
                                                    <a href="index.php?page=projets&action=view&id=<?= $projet['id'] ?>">
                                                        <?= htmlspecialchars($projet['titre_projet']) ?>
                                                    </a>
                                                </strong>
                                                <?php if (!empty($projet['description'])): ?>
                                                    <br><small class="text-muted">
                                                        <?= htmlspecialchars(substr($projet['description'], 0, 50)) ?>
                                                        <?= strlen($projet['description']) > 50 ? '...' : '' ?>
                                                    </small>
                                                <?php endif; ?>
                                            </td>
                                            
                                            <!-- Statut du projet -->
                                            <td>
                                                <span class="badge bg-<?= $projet['statut'] === 'en cours' ? 'primary' : ($projet['statut'] === 'terminé' ? 'success' : 'secondary') ?>">
                                                    <?= ucfirst($projet['statut']) ?>
                                                </span>
                                                <?php if ($projet['date_fin_prevue']): ?>
                                                    <br><small class="text-muted">
                                                        Échéance: <?= date('d/m/Y', strtotime($projet['date_fin_prevue'])) ?>
                                                    </small>
                                                <?php endif; ?>
                                            </td>
                                            
                                            <!-- Équipe -->
                                            <td>
                                                <?php if (!empty($projet['equipe'])): ?>
                                                    <div class="team-members">
                                                        <?php foreach (array_slice($projet['equipe'], 0, 3) as $membre): ?>
                                                            <div class="d-flex align-items-center mb-1">
                                                                <div class="avatar-sm me-2">
                                                                    <div class="avatar-initial bg-<?= $membre['role_projet'] === 'chef' ? 'warning' : 'info' ?> rounded-circle">
                                                                        <?= strtoupper(substr($membre['nom'], 0, 1)) ?>
                                                                    </div>
                                                                </div>
                                                                <small>
                                                                    <a href="index.php?page=users&action=view&id=<?= $membre['id_utilisateur'] ?>">
                                                                        <?= htmlspecialchars($membre['nom']) ?>
                                                                    </a>
                                                                    <?php if ($membre['role_projet'] === 'chef'): ?>
                                                                        <span class="badge badge-sm bg-warning">Chef</span>
                                                                    <?php endif; ?>
                                                                </small>
                                                            </div>
                                                        <?php endforeach; ?>
                                                        <?php if (count($projet['equipe']) > 3): ?>
                                                            <small class="text-muted">
                                                                +<?= count($projet['equipe']) - 3 ?> autre(s)
                                                            </small>
                                                        <?php endif; ?>
                                                    </div>
                                                <?php else: ?>
                                                    <span class="text-muted">
                                                        <i class="fas fa-exclamation-triangle me-1"></i>
                                                        Aucune équipe
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            
                                            <!-- Chef de projet -->
                                            <td>
                                                <?php 
                                                $chefs = array_filter($projet['equipe'], function($m) { 
                                                    return $m['role_projet'] === 'chef'; 
                                                });
                                                ?>
                                                <?php if (!empty($chefs)): ?>
                                                    <?php foreach ($chefs as $chef): ?>
                                                        <div class="d-flex align-items-center mb-1">
                                                            <div class="avatar-sm me-2">
                                                                <div class="avatar-initial bg-warning rounded-circle">
                                                                    <?= strtoupper(substr($chef['nom'], 0, 1)) ?>
                                                                </div>
                                                            </div>
                                                            <small>
                                                                <strong><?= htmlspecialchars($chef['nom']) ?></strong>
                                                            </small>
                                                        </div>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <span class="text-warning">
                                                        <i class="fas fa-exclamation-triangle me-1"></i>
                                                        Pas de chef
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            
                                            <!-- Budget -->
                                            <td>
                                                <?php if ($projet['budget']): ?>
                                                    <strong><?= number_format($projet['budget'], 0, ',', ' ') ?> €</strong>
                                                <?php else: ?>
                                                    <span class="text-muted">Non défini</span>
                                                <?php endif; ?>
                                            </td>
                                            
                                            <?php if ($index === 0): ?>
                                                <!-- CA réalisé (pour tout le client) -->
                                                <td rowspan="<?= count($client['projets']) ?>">
                                                    <strong class="text-success">
                                                        <?= number_format($client['chiffre_affaires'], 2, ',', ' ') ?> €
                                                    </strong>
                                                </td>
                                            <?php endif; ?>
                                            
                                            <!-- Actions -->
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="index.php?page=projets&action=view&id=<?= $projet['id'] ?>" 
                                                       class="btn btn-sm btn-outline-primary" title="Voir projet">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="index.php?page=projets&action=team&id=<?= $projet['id'] ?>" 
                                                       class="btn btn-sm btn-outline-info" title="Gérer équipe">
                                                        <i class="fas fa-users"></i>
                                                    </a>
                                                    <a href="index.php?page=factures&action=create&projet_id=<?= $projet['id'] ?>" 
                                                       class="btn btn-sm btn-outline-success" title="Nouvelle facture">
                                                        <i class="fas fa-file-invoice"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.avatar {
    width: 40px;
    height: 40px;
}

.avatar-sm {
    width: 24px;
    height: 24px;
}

.avatar-initial {
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    color: white;
    font-size: 0.875rem;
}

.avatar-sm .avatar-initial {
    font-size: 0.75rem;
}

.team-members {
    max-width: 200px;
}

.badge-sm {
    font-size: 0.65rem;
    padding: 0.2rem 0.4rem;
}

.table td {
    vertical-align: middle;
}

.client-row.filtered-out {
    display: none;
}

.opacity-75 {
    opacity: 0.75;
}

@media (max-width: 768px) {
    .table-responsive {
        font-size: 0.875rem;
    }

    .btn-group .btn {
        padding: 0.25rem 0.5rem;
    }

    .team-members {
        max-width: 150px;
    }
}
</style>

<script>
// Fonctions de filtrage et recherche
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchClient');
    const statusFilter = document.getElementById('filterStatus');
    const teamSizeFilter = document.getElementById('filterTeamSize');

    // Fonction de filtrage
    function filterTable() {
        const searchTerm = searchInput.value.toLowerCase();
        const statusValue = statusFilter.value;
        const teamSizeValue = teamSizeFilter.value;

        const rows = document.querySelectorAll('.client-row');

        rows.forEach(row => {
            const clientName = row.dataset.client;
            const projectStatus = row.dataset.status;
            const teamSize = parseInt(row.dataset.teamSize);

            let showRow = true;

            // Filtre par nom de client
            if (searchTerm && !clientName.includes(searchTerm)) {
                showRow = false;
            }

            // Filtre par statut
            if (statusValue && projectStatus !== statusValue) {
                showRow = false;
            }

            // Filtre par taille d'équipe
            if (teamSizeValue) {
                switch (teamSizeValue) {
                    case '1':
                        if (teamSize !== 1) showRow = false;
                        break;
                    case '2-3':
                        if (teamSize < 2 || teamSize > 3) showRow = false;
                        break;
                    case '4+':
                        if (teamSize < 4) showRow = false;
                        break;
                }
            }

            if (showRow) {
                row.classList.remove('filtered-out');
            } else {
                row.classList.add('filtered-out');
            }
        });

        updateStats();
    }

    // Mise à jour des statistiques après filtrage
    function updateStats() {
        const visibleRows = document.querySelectorAll('.client-row:not(.filtered-out)');
        const clients = new Set();
        let totalProjects = 0;
        let activeProjects = 0;
        let totalMembers = new Set();

        visibleRows.forEach(row => {
            const clientName = row.dataset.client;
            const projectStatus = row.dataset.status;
            const teamSize = parseInt(row.dataset.teamSize);

            clients.add(clientName);

            if (projectStatus) {
                totalProjects++;
                if (projectStatus === 'en cours') {
                    activeProjects++;
                }
            }

            // Compter les membres uniques (approximation)
            for (let i = 0; i < teamSize; i++) {
                totalMembers.add(`${clientName}-member-${i}`);
            }
        });

        // Mettre à jour l'affichage (optionnel)
        console.log(`Clients visibles: ${clients.size}, Projets: ${totalProjects}, Actifs: ${activeProjects}, Membres: ${totalMembers.size}`);
    }

    // Événements de filtrage
    searchInput.addEventListener('input', filterTable);
    statusFilter.addEventListener('change', filterTable);
    teamSizeFilter.addEventListener('change', filterTable);
});

// Fonction de reset des filtres
function resetFilters() {
    document.getElementById('searchClient').value = '';
    document.getElementById('filterStatus').value = '';
    document.getElementById('filterTeamSize').value = '';

    // Réafficher toutes les lignes
    document.querySelectorAll('.client-row').forEach(row => {
        row.classList.remove('filtered-out');
    });
}

// Fonction d'export Excel
function exportToExcel() {
    // Récupérer les données visibles du tableau
    const table = document.getElementById('recapTable');
    const rows = table.querySelectorAll('tr:not(.filtered-out)');

    let csvContent = "data:text/csv;charset=utf-8,";

    // En-têtes
    const headers = ['Client', 'Email', 'Téléphone', 'Projet', 'Statut', 'Équipe', 'Chef de Projet', 'Budget', 'CA Réalisé'];
    csvContent += headers.join(',') + '\n';

    // Données
    rows.forEach((row, index) => {
        if (index === 0) return; // Ignorer l'en-tête

        const cells = row.querySelectorAll('td');
        if (cells.length === 0) return;

        const rowData = [];
        cells.forEach(cell => {
            // Nettoyer le texte de la cellule
            let text = cell.textContent.trim().replace(/\s+/g, ' ');
            text = text.replace(/"/g, '""'); // Échapper les guillemets
            rowData.push(`"${text}"`);
        });

        csvContent += rowData.join(',') + '\n';
    });

    // Télécharger le fichier
    const encodedUri = encodeURI(csvContent);
    const link = document.createElement('a');
    link.setAttribute('href', encodedUri);
    link.setAttribute('download', `recap_clients_projets_${new Date().toISOString().split('T')[0]}.csv`);
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

// Tooltips Bootstrap
document.addEventListener('DOMContentLoaded', function() {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
