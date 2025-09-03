<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>
                <i class="fas fa-user me-2"></i>
                <?= htmlspecialchars($user['nom']) ?>
            </h1>
            <div>
                <a href="index.php?page=users" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i>
                    Retour à la liste
                </a>
                <a href="index.php?page=users&action=edit&id=<?= $user['id'] ?>" class="btn btn-warning">
                    <i class="fas fa-edit me-1"></i>
                    Modifier
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Informations utilisateur -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    Informations personnelles
                </h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <div class="avatar-lg mx-auto mb-3">
                        <div class="avatar-initial bg-primary rounded-circle">
                            <?= strtoupper(substr($user['nom'], 0, 2)) ?>
                        </div>
                    </div>
                    <h4><?= htmlspecialchars($user['nom']) ?></h4>
                    <span class="badge bg-<?= $user['role'] === 'admin' ? 'danger' : 'primary' ?> mb-2">
                        <?= ucfirst($user['role']) ?>
                    </span>
                    <br>
                    <?php if ($user['actif']): ?>
                        <span class="badge bg-success">
                            <i class="fas fa-check me-1"></i>
                            Compte actif
                        </span>
                    <?php else: ?>
                        <span class="badge bg-secondary">
                            <i class="fas fa-times me-1"></i>
                            Compte inactif
                        </span>
                    <?php endif; ?>
                </div>
                
                <table class="table table-borderless">
                    <tr>
                        <td><strong>Email :</strong></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                    </tr>
                    <tr>
                        <td><strong>Rôle :</strong></td>
                        <td>
                            <span class="badge bg-<?= $user['role'] === 'admin' ? 'danger' : 'primary' ?>">
                                <?= ucfirst($user['role']) ?>
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Membre depuis :</strong></td>
                        <td><?= date('d/m/Y', strtotime($user['date_creation'])) ?></td>
                    </tr>
                    <tr>
                        <td><strong>Dernière connexion :</strong></td>
                        <td>
                            <?php if (isset($user['derniere_connexion']) && $user['derniere_connexion']): ?>
                                <?= date('d/m/Y H:i', strtotime($user['derniere_connexion'])) ?>
                            <?php else: ?>
                                <span class="text-muted">Jamais connecté</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                </table>
                
                <div class="d-grid gap-2">
                    <?php if ($user['actif']): ?>
                        <a href="index.php?page=users&action=deactivate&id=<?= $user['id'] ?>" 
                           class="btn btn-outline-danger btn-delete"
                           data-item-name="cet utilisateur">
                            <i class="fas fa-ban me-1"></i>
                            Désactiver le compte
                        </a>
                    <?php else: ?>
                        <a href="index.php?page=users&action=activate&id=<?= $user['id'] ?>" 
                           class="btn btn-outline-success">
                            <i class="fas fa-check me-1"></i>
                            Réactiver le compte
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Projets de l'utilisateur -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-project-diagram me-2"></i>
                    Projets assignés
                    <span class="badge bg-primary ms-2"><?= count($projects) ?></span>
                </h5>
            </div>
            <div class="card-body">
                <?php if (empty($projects)): ?>
                    <div class="text-center py-4">
                        <i class="fas fa-project-diagram fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Aucun projet assigné à cet utilisateur.</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Projet</th>
                                    <th>Client</th>
                                    <th>Rôle</th>
                                    <th>Statut</th>
                                    <th>Date d'assignation</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($projects as $project): ?>
                                <tr>
                                    <td>
                                        <a href="index.php?page=projets&action=view&id=<?= $project['id_projet'] ?>">
                                            <?= htmlspecialchars($project['titre_projet']) ?>
                                        </a>
                                    </td>
                                    <td><?= htmlspecialchars($project['nom_client']) ?></td>
                                    <td>
                                        <span class="badge bg-<?= $project['role_projet'] === 'chef' ? 'warning' : 'info' ?>">
                                            <?= ucfirst($project['role_projet']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?= $project['statut'] === 'en cours' ? 'primary' : ($project['statut'] === 'terminé' ? 'success' : 'secondary') ?>">
                                            <?= ucfirst($project['statut']) ?>
                                        </span>
                                    </td>
                                    <td><?= date('d/m/Y', strtotime($project['date_assignation'])) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Statistiques -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-bar me-2"></i>
                    Statistiques
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="text-center">
                            <h3 class="text-primary"><?= count($projects) ?></h3>
                            <p class="text-muted mb-0">Total projets</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <h3 class="text-warning"><?= $activeProjects ?></h3>
                            <p class="text-muted mb-0">Projets actifs</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <h3 class="text-success">
                                <?= count(array_filter($projects, function($p) { return $p['statut'] === 'terminé'; })) ?>
                            </h3>
                            <p class="text-muted mb-0">Projets terminés</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <h3 class="text-info">
                                <?= count(array_filter($projects, function($p) { return $p['role_projet'] === 'chef'; })) ?>
                            </h3>
                            <p class="text-muted mb-0">Projets dirigés</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.avatar-lg {
    width: 80px;
    height: 80px;
}

.avatar-initial {
    width: 80px;
    height: 80px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    color: white;
    font-size: 1.5rem;
}
</style>
