<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>
                <i class="fas fa-file-invoice me-2"></i>
                Gestion des Factures
            </h1>
            <div>
                <a href="index.php?page=factures&action=create" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i>
                    Nouvelle Facture
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Filtres et recherche -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="GET" action="" class="row g-3">
                    <input type="hidden" name="page" value="factures">
                    <div class="col-md-5">
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text" class="form-control" name="search" 
                                   placeholder="Rechercher par numéro, client..." 
                                   value="<?= htmlspecialchars($search) ?>">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" name="status">
                            <option value="">Tous les statuts</option>
                            <option value="payée" <?= $status === 'payée' ? 'selected' : '' ?>>Payées</option>
                            <option value="impayée" <?= $status === 'impayée' ? 'selected' : '' ?>>Impayées</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-outline-primary w-100">
                            <i class="fas fa-search me-1"></i>
                            Filtrer
                        </button>
                    </div>
                    <div class="col-md-2">
                        <a href="index.php?page=factures" class="btn btn-outline-secondary w-100">
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
                            Total Factures
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?= $totalFactures ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-file-invoice fa-2x text-gray-300"></i>
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
                            Factures Payées
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?= count(array_filter($factures, function($f) { return $f['statut'] === 'payée'; })) ?>
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
        <div class="card border-left-warning">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Factures Impayées
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?= count(array_filter($factures, function($f) { return $f['statut'] === 'impayée'; })) ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
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
                            Montant Total
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?= number_format(array_sum(array_column($factures, 'montant')), 0, ',', ' ') ?> €
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-euro-sign fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Liste des factures -->
<div class="row">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-list me-2"></i>
                    Liste des Factures
                    <?php if (!empty($search) || !empty($status)): ?>
                        <small class="text-muted">
                            - Filtré
                            <?php if (!empty($search)): ?>
                                par "<?= htmlspecialchars($search) ?>"
                            <?php endif; ?>
                            <?php if (!empty($status)): ?>
                                (<?= ucfirst($status) ?>)
                            <?php endif; ?>
                        </small>
                    <?php endif; ?>
                </h6>
                <?php if ($isAdmin): ?>
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-cog me-1"></i>
                        Actions
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="index.php?page=factures&action=stats">
                            <i class="fas fa-chart-bar me-2"></i>Statistiques
                        </a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="index.php?page=factures&action=export&format=csv">
                            <i class="fas fa-download me-2"></i>Exporter CSV
                        </a></li>
                    </ul>
                </div>
                <?php endif; ?>
            </div>
            <div class="card-body">
                <?php if (empty($factures)): ?>
                    <div class="text-center py-4">
                        <i class="fas fa-file-invoice fa-3x text-muted mb-3"></i>
                        <p class="text-muted">
                            <?php if (!empty($search) || !empty($status)): ?>
                                Aucune facture trouvée avec ces critères.
                            <?php else: ?>
                                Aucune facture trouvée.
                            <?php endif; ?>
                        </p>
                        <a href="index.php?page=factures&action=create" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i>
                            Créer la première facture
                        </a>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Numéro</th>
                                    <th>Client</th>
                                    <th>Projet</th>
                                    <th>Montant</th>
                                    <th>Date</th>
                                    <th>Échéance</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($factures as $facture): ?>
                                <tr class="<?= $facture['statut'] === 'impayée' && strtotime($facture['date_echeance']) < time() ? 'table-warning' : '' ?>">
                                    <td>
                                        <strong><?= htmlspecialchars($facture['numero_facture']) ?></strong>
                                    </td>
                                    <td>
                                        <a href="index.php?page=clients&action=view&id=<?= $facture['id_client'] ?>">
                                            <?= htmlspecialchars($facture['nom_client']) ?>
                                        </a>
                                    </td>
                                    <td>
                                        <?php if ($facture['titre_projet']): ?>
                                            <a href="index.php?page=projets&action=view&id=<?= $facture['id_projet'] ?>">
                                                <?= htmlspecialchars($facture['titre_projet']) ?>
                                            </a>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <strong><?= number_format($facture['montant'], 2, ',', ' ') ?> €</strong>
                                    </td>
                                    <td><?= date('d/m/Y', strtotime($facture['date_facture'])) ?></td>
                                    <td>
                                        <?php if ($facture['date_echeance']): ?>
                                            <?= date('d/m/Y', strtotime($facture['date_echeance'])) ?>
                                            <?php if ($facture['statut'] === 'impayée' && strtotime($facture['date_echeance']) < time()): ?>
                                                <i class="fas fa-exclamation-triangle text-danger ms-1" title="En retard"></i>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($facture['statut'] === 'payée'): ?>
                                            <span class="badge bg-success">
                                                <i class="fas fa-check me-1"></i>
                                                Payée
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-warning">
                                                <i class="fas fa-clock me-1"></i>
                                                Impayée
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="index.php?page=factures&action=view&id=<?= $facture['id_facture'] ?>" 
                                               class="btn btn-sm btn-outline-primary" title="Voir">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="index.php?page=factures&action=edit&id=<?= $facture['id_facture'] ?>" 
                                               class="btn btn-sm btn-outline-warning" title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <?php if ($facture['statut'] === 'impayée'): ?>
                                                <a href="index.php?page=factures&action=markPaid&id=<?= $facture['id_facture'] ?>" 
                                                   class="btn btn-sm btn-outline-success" title="Marquer comme payée">
                                                    <i class="fas fa-check"></i>
                                                </a>
                                            <?php else: ?>
                                                <a href="index.php?page=factures&action=markUnpaid&id=<?= $facture['id_facture'] ?>" 
                                                   class="btn btn-sm btn-outline-warning" title="Marquer comme impayée">
                                                    <i class="fas fa-undo"></i>
                                                </a>
                                            <?php endif; ?>
                                            <?php if ($isAdmin): ?>
                                                <a href="index.php?page=factures&action=delete&id=<?= $facture['id_facture'] ?>" 
                                                   class="btn btn-sm btn-outline-danger btn-delete" 
                                                   title="Supprimer"
                                                   data-item-name="cette facture">
                                                    <i class="fas fa-trash"></i>
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
                    <nav aria-label="Navigation des factures">
                        <ul class="pagination justify-content-center">
                            <?php if ($pagination['has_previous']): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=factures&p=<?= $pagination['previous_page'] ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?><?= !empty($status) ? '&status=' . urlencode($status) : '' ?>">
                                        <i class="fas fa-chevron-left"></i>
                                    </a>
                                </li>
                            <?php endif; ?>
                            
                            <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
                                <li class="page-item <?= $i == $pagination['current_page'] ? 'active' : '' ?>">
                                    <a class="page-link" href="?page=factures&p=<?= $i ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?><?= !empty($status) ? '&status=' . urlencode($status) : '' ?>">
                                        <?= $i ?>
                                    </a>
                                </li>
                            <?php endfor; ?>
                            
                            <?php if ($pagination['has_next']): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=factures&p=<?= $pagination['next_page'] ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?><?= !empty($status) ? '&status=' . urlencode($status) : '' ?>">
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

.table-warning {
    background-color: rgba(255, 193, 7, 0.1) !important;
}
</style>
