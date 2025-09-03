<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>
                <i class="fas fa-chart-bar me-2"></i>
                Statistiques Détaillées
            </h1>
            <div>
                <a href="index.php?page=dashboard" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i>
                    Retour au Dashboard
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Filtres de période -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-filter me-2"></i>
                    Filtres de période
                </h5>
            </div>
            <div class="card-body">
                <form method="GET" action="">
                    <input type="hidden" name="page" value="dashboard">
                    <input type="hidden" name="action" value="stats">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="start_date" class="form-label">Date de début</label>
                            <input type="date" class="form-control" id="start_date" name="start_date" 
                                   value="<?= htmlspecialchars($startDate) ?>">
                        </div>
                        <div class="col-md-4">
                            <label for="end_date" class="form-label">Date de fin</label>
                            <input type="date" class="form-control" id="end_date" name="end_date" 
                                   value="<?= htmlspecialchars($endDate) ?>">
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search me-1"></i>
                                Filtrer
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Chiffre d'affaires par mois -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-line me-2"></i>
                    Évolution du chiffre d'affaires
                </h5>
            </div>
            <div class="card-body">
                <?php if (!empty($monthlyRevenue)): ?>
                    <canvas id="revenueChart" width="400" height="100"></canvas>
                    
                    <div class="table-responsive mt-4">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Mois</th>
                                    <th>Chiffre d'affaires</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($monthlyRevenue as $month): ?>
                                <tr>
                                    <td><?= date('F Y', strtotime($month['month'] . '-01')) ?></td>
                                    <td><?= number_format($month['revenue'], 2, ',', ' ') ?> €</td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-muted text-center">Aucune donnée de chiffre d'affaires pour cette période.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Projets par statut -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-project-diagram me-2"></i>
                    Répartition des projets par statut
                </h5>
            </div>
            <div class="card-body">
                <?php if (!empty($projectsByStatus)): ?>
                    <canvas id="projectsChart" width="400" height="200"></canvas>
                    
                    <div class="mt-3">
                        <?php foreach ($projectsByStatus as $status): ?>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge bg-<?= $status['statut'] === 'en cours' ? 'primary' : ($status['statut'] === 'terminé' ? 'success' : 'secondary') ?>">
                                <?= ucfirst($status['statut']) ?>
                            </span>
                            <strong><?= $status['count'] ?> projets</strong>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="text-muted text-center">Aucun projet trouvé.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Top clients -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-trophy me-2"></i>
                    Top 10 clients
                </h5>
            </div>
            <div class="card-body">
                <?php if (!empty($topClients)): ?>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Client</th>
                                    <th>CA Total</th>
                                    <th>Factures</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($topClients as $client): ?>
                                <tr>
                                    <td>
                                        <a href="index.php?page=clients&action=view&id=<?= $client['id'] ?>">
                                            <?= htmlspecialchars($client['nom_client']) ?>
                                        </a>
                                    </td>
                                    <td><?= number_format($client['total_revenue'], 2, ',', ' ') ?> €</td>
                                    <td><?= $client['total_invoices'] ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-muted text-center">Aucun client trouvé.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Utilisateurs les plus actifs -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-users me-2"></i>
                    Utilisateurs les plus actifs
                </h5>
            </div>
            <div class="card-body">
                <?php if (!empty($activeUsers)): ?>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Utilisateur</th>
                                    <th>Total Projets</th>
                                    <th>Projets Actifs</th>
                                    <th>Projets Terminés</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($activeUsers as $user): ?>
                                <tr>
                                    <td>
                                        <a href="index.php?page=users&action=view&id=<?= $user['id'] ?>">
                                            <?= htmlspecialchars($user['nom']) ?>
                                        </a>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary"><?= $user['total_projects'] ?></span>
                                    </td>
                                    <td>
                                        <span class="badge bg-warning"><?= $user['active_projects'] ?></span>
                                    </td>
                                    <td>
                                        <span class="badge bg-success"><?= $user['completed_projects'] ?></span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-muted text-center">Aucun utilisateur actif trouvé.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Scripts pour les graphiques -->
<script>
// Graphique du chiffre d'affaires
<?php if (!empty($monthlyRevenue)): ?>
const revenueCtx = document.getElementById('revenueChart').getContext('2d');
const revenueChart = new Chart(revenueCtx, {
    type: 'line',
    data: {
        labels: [<?php echo implode(',', array_map(function($m) { return '"' . date('M Y', strtotime($m['month'] . '-01')) . '"'; }, $monthlyRevenue)); ?>],
        datasets: [{
            label: 'Chiffre d\'affaires (€)',
            data: [<?php echo implode(',', array_column($monthlyRevenue, 'revenue')); ?>],
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            tension: 0.1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return value.toLocaleString() + ' €';
                    }
                }
            }
        }
    }
});
<?php endif; ?>

// Graphique des projets par statut
<?php if (!empty($projectsByStatus)): ?>
const projectsCtx = document.getElementById('projectsChart').getContext('2d');
const projectsChart = new Chart(projectsCtx, {
    type: 'doughnut',
    data: {
        labels: [<?php echo implode(',', array_map(function($p) { return '"' . ucfirst($p['statut']) . '"'; }, $projectsByStatus)); ?>],
        datasets: [{
            data: [<?php echo implode(',', array_column($projectsByStatus, 'count')); ?>],
            backgroundColor: [
                'rgba(54, 162, 235, 0.8)',
                'rgba(75, 192, 192, 0.8)',
                'rgba(255, 99, 132, 0.8)',
                'rgba(255, 205, 86, 0.8)'
            ]
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});
<?php endif; ?>
</script>
