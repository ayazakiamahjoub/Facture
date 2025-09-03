<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>
                <i class="fas fa-tachometer-alt me-2"></i>
                Tableau de bord
            </h1>
            <div class="text-muted">
                Bienvenue, <?= htmlspecialchars($currentUser['nom']) ?>
            </div>
        </div>
    </div>
</div>

<!-- Statistiques principales -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Projets Actifs
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?= $stats['active_projects'] ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-project-diagram fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Chiffre d'affaires
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?= number_format($stats['total_revenue'], 2, ',', ' ') ?> €
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-euro-sign fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Clients
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?= $stats['total_clients'] ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Impayés
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?= number_format($stats['unpaid_amount'], 2, ',', ' ') ?> €
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

<div class="row">
    <!-- Projets récents -->
    <div class="col-lg-6 mb-4">
        <div class="card shadow">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-project-diagram me-2"></i>
                    Projets récents
                </h6>
                <a href="index.php?page=projets" class="btn btn-sm btn-primary">
                    Voir tout
                </a>
            </div>
            <div class="card-body">
                <?php if (empty($recentProjects)): ?>
                    <p class="text-muted text-center">Aucun projet trouvé.</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Projet</th>
                                    <th>Client</th>
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recentProjects as $project): ?>
                                <tr>
                                    <td>
                                        <a href="index.php?page=projets&action=view&id=<?= $project['id'] ?>">
                                            <?= htmlspecialchars($project['titre_projet']) ?>
                                        </a>
                                    </td>
                                    <td><?= htmlspecialchars($project['nom_client']) ?></td>
                                    <td>
                                        <span class="badge bg-<?= $project['statut'] === 'en cours' ? 'primary' : ($project['statut'] === 'terminé' ? 'success' : 'secondary') ?>">
                                            <?= ucfirst($project['statut']) ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Factures récentes -->
    <div class="col-lg-6 mb-4">
        <div class="card shadow">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-file-invoice me-2"></i>
                    Factures récentes
                </h6>
                <a href="index.php?page=factures" class="btn btn-sm btn-primary">
                    Voir tout
                </a>
            </div>
            <div class="card-body">
                <?php if (empty($recentInvoices)): ?>
                    <p class="text-muted text-center">Aucune facture trouvée.</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Numéro</th>
                                    <th>Client</th>
                                    <th>Montant</th>
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recentInvoices as $invoice): ?>
                                <tr>
                                    <td>
                                        <a href="index.php?page=factures&action=view&id=<?= $invoice['id_facture'] ?>">
                                            <?= htmlspecialchars($invoice['numero_facture']) ?>
                                        </a>
                                    </td>
                                    <td><?= htmlspecialchars($invoice['nom_client']) ?></td>
                                    <td><?= number_format($invoice['montant'], 2, ',', ' ') ?> €</td>
                                    <td>
                                        <span class="badge bg-<?= $invoice['statut'] === 'payée' ? 'success' : 'warning' ?>">
                                            <?= ucfirst($invoice['statut']) ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Alertes et notifications -->
<?php if (!empty($overdueProjects) || !empty($overdueInvoices) || !empty($upcomingDeadlines)): ?>
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Alertes et notifications
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <?php if (!empty($overdueProjects)): ?>
                    <div class="col-md-4">
                        <h6 class="text-danger">Projets en retard</h6>
                        <ul class="list-unstyled">
                            <?php foreach (array_slice($overdueProjects, 0, 3) as $project): ?>
                            <li class="mb-2">
                                <a href="index.php?page=projets&action=view&id=<?= $project['id'] ?>" class="text-decoration-none">
                                    <?= htmlspecialchars($project['titre_projet']) ?>
                                </a>
                                <br>
                                <small class="text-muted">
                                    Échéance : <?= date('d/m/Y', strtotime($project['date_fin_prevue'])) ?>
                                </small>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($overdueInvoices)): ?>
                    <div class="col-md-4">
                        <h6 class="text-warning">Factures en retard</h6>
                        <ul class="list-unstyled">
                            <?php foreach (array_slice($overdueInvoices, 0, 3) as $invoice): ?>
                            <li class="mb-2">
                                <a href="index.php?page=factures&action=view&id=<?= $invoice['id_facture'] ?>" class="text-decoration-none">
                                    <?= htmlspecialchars($invoice['numero_facture']) ?>
                                </a>
                                <br>
                                <small class="text-muted">
                                    <?= htmlspecialchars($invoice['nom_client']) ?> - 
                                    <?= number_format($invoice['montant'], 2, ',', ' ') ?> €
                                </small>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($upcomingDeadlines)): ?>
                    <div class="col-md-4">
                        <h6 class="text-info">Échéances prochaines</h6>
                        <ul class="list-unstyled">
                            <?php foreach (array_slice($upcomingDeadlines, 0, 3) as $project): ?>
                            <li class="mb-2">
                                <a href="index.php?page=projets&action=view&id=<?= $project['id'] ?>" class="text-decoration-none">
                                    <?= htmlspecialchars($project['titre_projet']) ?>
                                </a>
                                <br>
                                <small class="text-muted">
                                    Échéance : <?= date('d/m/Y', strtotime($project['date_fin_prevue'])) ?>
                                </small>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

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
</style>
