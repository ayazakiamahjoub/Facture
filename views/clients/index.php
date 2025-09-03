<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>
                <i class="fas fa-users me-2"></i>
                Gestion des Clients
            </h1>
            <div>
                <a href="index.php?page=clients&action=create" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i>
                    Nouveau Client
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Barre de recherche -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="GET" action="" class="row g-3">
                    <input type="hidden" name="page" value="clients">
                    <div class="col-md-8">
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text" class="form-control" name="search" 
                                   placeholder="Rechercher par nom, email ou téléphone..." 
                                   value="<?= htmlspecialchars($search) ?>">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-outline-primary w-100">
                            <i class="fas fa-search me-1"></i>
                            Rechercher
                        </button>
                    </div>
                    <div class="col-md-2">
                        <a href="index.php?page=clients" class="btn btn-outline-secondary w-100">
                            <i class="fas fa-times me-1"></i>
                            Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Statistiques rapides -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card border-left-primary">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Clients
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?= $totalClients ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card border-left-success">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Clients Actifs
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?= count(array_filter($clients, function($c) { return $c['actif'] == 1; })) ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card border-left-info">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Nouveaux ce mois
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?= count(array_filter($clients, function($c) { 
                                return date('Y-m', strtotime($c['date_creation'])) === date('Y-m'); 
                            })) ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-user-plus fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card border-left-warning">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Avec Projets
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?= count(array_filter($clients, function($c) { 
                                return isset($c['nb_projets']) && $c['nb_projets'] > 0; 
                            })) ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-project-diagram fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Liste des clients -->
<div class="row">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-list me-2"></i>
                    Liste des Clients
                    <?php if (!empty($search)): ?>
                        <small class="text-muted">- Résultats pour "<?= htmlspecialchars($search) ?>"</small>
                    <?php endif; ?>
                </h6>
                <?php if ($isAdmin): ?>
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-cog me-1"></i>
                        Actions
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="index.php?page=clients&action=stats">
                            <i class="fas fa-chart-bar me-2"></i>Statistiques
                        </a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="index.php?page=clients&action=export&format=csv">
                            <i class="fas fa-download me-2"></i>Exporter CSV
                        </a></li>
                    </ul>
                </div>
                <?php endif; ?>
            </div>
            <div class="card-body">
                <?php if (empty($clients)): ?>
                    <div class="text-center py-4">
                        <i class="fas fa-users fa-3x text-muted mb-3"></i>
                        <p class="text-muted">
                            <?php if (!empty($search)): ?>
                                Aucun client trouvé pour "<?= htmlspecialchars($search) ?>".
                            <?php else: ?>
                                Aucun client trouvé.
                            <?php endif; ?>
                        </p>
                        <a href="index.php?page=clients&action=create" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i>
                            Créer le premier client
                        </a>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Client</th>
                                    <th>Email</th>
                                    <th>Téléphone</th>
                                    <th>Projets</th>
                                    <th>Statut</th>
                                    <th>Date création</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($clients as $client): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar me-3">
                                                <div class="avatar-initial bg-info rounded-circle">
                                                    <?= strtoupper(substr($client['nom_client'], 0, 1)) ?>
                                                </div>
                                            </div>
                                            <div>
                                                <strong><?= htmlspecialchars($client['nom_client']) ?></strong>
                                                <?php if (!empty($client['adresse'])): ?>
                                                    <br><small class="text-muted"><?= htmlspecialchars($client['adresse']) ?></small>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="mailto:<?= htmlspecialchars($client['email']) ?>">
                                            <?= htmlspecialchars($client['email']) ?>
                                        </a>
                                    </td>
                                    <td>
                                        <?php if (!empty($client['telephone'])): ?>
                                            <a href="tel:<?= htmlspecialchars($client['telephone']) ?>">
                                                <?= htmlspecialchars($client['telephone']) ?>
                                            </a>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (isset($client['nb_projets']) && $client['nb_projets'] > 0): ?>
                                            <span class="badge bg-primary"><?= $client['nb_projets'] ?> projets</span>
                                        <?php else: ?>
                                            <span class="text-muted">Aucun projet</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($client['actif']): ?>
                                            <span class="badge bg-success">
                                                <i class="fas fa-check me-1"></i>
                                                Actif
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">
                                                <i class="fas fa-times me-1"></i>
                                                Inactif
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= date('d/m/Y', strtotime($client['date_creation'])) ?></td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="index.php?page=clients&action=view&id=<?= $client['id'] ?>" 
                                               class="btn btn-sm btn-outline-primary" title="Voir">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="index.php?page=clients&action=edit&id=<?= $client['id'] ?>" 
                                               class="btn btn-sm btn-outline-warning" title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="index.php?page=factures&action=create&client_id=<?= $client['id'] ?>" 
                                               class="btn btn-sm btn-outline-success" title="Nouvelle facture">
                                                <i class="fas fa-file-invoice"></i>
                                            </a>
                                            <?php if ($client['actif']): ?>
                                                <a href="index.php?page=clients&action=delete&id=<?= $client['id'] ?>" 
                                                   class="btn btn-sm btn-outline-danger btn-delete" 
                                                   title="Désactiver"
                                                   data-item-name="ce client">
                                                    <i class="fas fa-ban"></i>
                                                </a>
                                            <?php else: ?>
                                                <a href="index.php?page=clients&action=activate&id=<?= $client['id'] ?>" 
                                                   class="btn btn-sm btn-outline-success" title="Réactiver">
                                                    <i class="fas fa-check"></i>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <?php if ($pagination && $pagination['total_pages'] > 1): ?>
                    <nav aria-label="Navigation des clients">
                        <ul class="pagination justify-content-center">
                            <?php if ($pagination['has_previous']): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=clients&p=<?= $pagination['previous_page'] ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?>">
                                        <i class="fas fa-chevron-left"></i>
                                    </a>
                                </li>
                            <?php endif; ?>
                            
                            <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
                                <li class="page-item <?= $i == $pagination['current_page'] ? 'active' : '' ?>">
                                    <a class="page-link" href="?page=clients&p=<?= $i ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?>">
                                        <?= $i ?>
                                    </a>
                                </li>
                            <?php endfor; ?>
                            
                            <?php if ($pagination['has_next']): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=clients&p=<?= $pagination['next_page'] ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?>">
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<style>
.avatar {
    width: 40px;
    height: 40px;
}

.avatar-initial {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    color: white;
}

.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}

.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}

.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}

.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}

.text-xs {
    font-size: 0.7rem;
}

.text-gray-800 {
    color: #5a5c69 !important;
}

.text-gray-300 {
    color: #dddfeb !important;
}
</style>
