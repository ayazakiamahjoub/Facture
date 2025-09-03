<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>
                <i class="fas fa-project-diagram me-2"></i>
                Gestion des Projets
            </h1>
            <div>
                <?php if ($isAdmin): ?>
                <a href="index.php?page=projets&action=create" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i>
                    Nouveau Projet
                </a>
                <?php endif; ?>
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
                    <input type="hidden" name="page" value="projets">
                    <div class="col-md-5">
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text" class="form-control" name="search" 
                                   placeholder="Rechercher par titre ou client..." 
                                   value="<?= htmlspecialchars($search) ?>">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" name="status">
                            <option value="">Tous les statuts</option>
                            <option value="en cours" <?= $status === 'en cours' ? 'selected' : '' ?>>En cours</option>
                            <option value="terminé" <?= $status === 'terminé' ? 'selected' : '' ?>>Terminés</option>
                            <option value="annulé" <?= $status === 'annulé' ? 'selected' : '' ?>>Annulés</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-outline-primary w-100">
                            <i class="fas fa-search me-1"></i>
                            Filtrer
                        </button>
                    </div>
                    <div class="col-md-2">
                        <a href="index.php?page=projets" class="btn btn-outline-secondary w-100">
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
                            Total Projets
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?= $totalProjets ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-project-diagram fa-2x text-gray-300"></i>
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
                            En Cours
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?= count(array_filter($projets, function($p) { return $p['statut'] === 'en cours'; })) ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clock fa-2x text-gray-300"></i>
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
                            Terminés
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?= count(array_filter($projets, function($p) { return $p['statut'] === 'terminé'; })) ?>
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
        <div class="card border-left-danger">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                            En Retard
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?= count(array_filter($projets, function($p) { 
                                return $p['statut'] === 'en cours' && 
                                       $p['date_fin_prevue'] && 
                                       strtotime($p['date_fin_prevue']) < time(); 
                            })) ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Liste des projets -->
<div class="row">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-list me-2"></i>
                    Liste des Projets
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
                <?php if (!$isAdmin): ?>
                    <small class="text-muted">
                        <i class="fas fa-info-circle me-1"></i>
                        Vous ne voyez que vos projets assignés
                    </small>
                <?php endif; ?>
            </div>
            <div class="card-body">
                <?php if (empty($projets)): ?>
                    <div class="text-center py-4">
                        <i class="fas fa-project-diagram fa-3x text-muted mb-3"></i>
                        <p class="text-muted">
                            <?php if (!empty($search) || !empty($status)): ?>
                                Aucun projet trouvé avec ces critères.
                            <?php elseif (!$isAdmin): ?>
                                Aucun projet ne vous est assigné pour le moment.
                            <?php else: ?>
                                Aucun projet trouvé.
                            <?php endif; ?>
                        </p>
                        <?php if ($isAdmin): ?>
                        <a href="index.php?page=projets&action=create" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i>
                            Créer le premier projet
                        </a>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Projet</th>
                                    <th>Client</th>
                                    <th>Statut</th>
                                    <th>Progression</th>
                                    <th>Échéance</th>
                                    <th>Budget</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($projets as $projet): ?>
                                <?php 
                                $isOverdue = $projet['statut'] === 'en cours' && 
                                           $projet['date_fin_prevue'] && 
                                           strtotime($projet['date_fin_prevue']) < time();
                                $isUpcoming = $projet['statut'] === 'en cours' && 
                                            $projet['date_fin_prevue'] && 
                                            strtotime($projet['date_fin_prevue']) < strtotime('+7 days');
                                ?>
                                <tr class="<?= $isOverdue ? 'table-danger' : ($isUpcoming ? 'table-warning' : '') ?>">
                                    <td>
                                        <div>
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
                                        </div>
                                    </td>
                                    <td>
                                        <a href="index.php?page=clients&action=view&id=<?= $projet['id_client'] ?>">
                                            <?= htmlspecialchars($projet['nom_client']) ?>
                                        </a>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?= $projet['statut'] === 'en cours' ? 'primary' : ($projet['statut'] === 'terminé' ? 'success' : 'secondary') ?>">
                                            <?= ucfirst($projet['statut']) ?>
                                        </span>
                                        <?php if ($isOverdue): ?>
                                            <br><small class="text-danger">
                                                <i class="fas fa-exclamation-triangle"></i> En retard
                                            </small>
                                        <?php elseif ($isUpcoming): ?>
                                            <br><small class="text-warning">
                                                <i class="fas fa-clock"></i> Échéance proche
                                            </small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php 
                                        $progression = 0;
                                        if ($projet['statut'] === 'terminé') $progression = 100;
                                        elseif ($projet['statut'] === 'en cours') $progression = 50;
                                        elseif ($projet['statut'] === 'annulé') $progression = 0;
                                        ?>
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar bg-<?= $projet['statut'] === 'terminé' ? 'success' : 'primary' ?>" 
                                                 role="progressbar" 
                                                 style="width: <?= $progression ?>%"
                                                 aria-valuenow="<?= $progression ?>" 
                                                 aria-valuemin="0" 
                                                 aria-valuemax="100">
                                                <?= $progression ?>%
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <?php if ($projet['date_fin_prevue']): ?>
                                            <?= date('d/m/Y', strtotime($projet['date_fin_prevue'])) ?>
                                            <?php if ($isOverdue): ?>
                                                <br><small class="text-danger">
                                                    <?= floor((time() - strtotime($projet['date_fin_prevue'])) / 86400) ?> jours de retard
                                                </small>
                                            <?php elseif ($isUpcoming && $projet['statut'] === 'en cours'): ?>
                                                <br><small class="text-warning">
                                                    <?= ceil((strtotime($projet['date_fin_prevue']) - time()) / 86400) ?> jours restants
                                                </small>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span class="text-muted">Non définie</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($projet['budget']): ?>
                                            <?= number_format($projet['budget'], 0, ',', ' ') ?> €
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="index.php?page=projets&action=view&id=<?= $projet['id'] ?>" 
                                               class="btn btn-sm btn-outline-primary" title="Voir">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <?php if ($isAdmin || (isset($projet['is_manager']) && $projet['is_manager'])): ?>
                                                <a href="index.php?page=projets&action=edit&id=<?= $projet['id'] ?>" 
                                                   class="btn btn-sm btn-outline-warning" title="Modifier">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="index.php?page=projets&action=team&id=<?= $projet['id'] ?>" 
                                                   class="btn btn-sm btn-outline-info" title="Équipe">
                                                    <i class="fas fa-users"></i>
                                                </a>
                                            <?php endif; ?>
                                            <a href="index.php?page=factures&action=create&projet_id=<?= $projet['id'] ?>" 
                                               class="btn btn-sm btn-outline-success" title="Nouvelle facture">
                                                <i class="fas fa-file-invoice"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <?php if ($pagination && $pagination['total_pages'] > 1): ?>
                    <nav aria-label="Navigation des projets">
                        <ul class="pagination justify-content-center">
                            <?php if ($pagination['has_previous']): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=projets&p=<?= $pagination['previous_page'] ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?><?= !empty($status) ? '&status=' . urlencode($status) : '' ?>">
                                        <i class="fas fa-chevron-left"></i>
                                    </a>
                                </li>
                            <?php endif; ?>
                            
                            <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
                                <li class="page-item <?= $i == $pagination['current_page'] ? 'active' : '' ?>">
                                    <a class="page-link" href="?page=projets&p=<?= $i ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?><?= !empty($status) ? '&status=' . urlencode($status) : '' ?>">
                                        <?= $i ?>
                                    </a>
                                </li>
                            <?php endfor; ?>
                            
                            <?php if ($pagination['has_next']): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=projets&p=<?= $pagination['next_page'] ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?><?= !empty($status) ? '&status=' . urlencode($status) : '' ?>">
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

.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}

.border-left-danger {
    border-left: 0.25rem solid #e74a3b !important;
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

.table-danger {
    background-color: rgba(231, 74, 59, 0.1) !important;
}

.table-warning {
    background-color: rgba(255, 193, 7, 0.1) !important;
}

.progress {
    background-color: #e9ecef;
}
</style>
