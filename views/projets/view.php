<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>
                <i class="fas fa-project-diagram me-2"></i>
                <?= htmlspecialchars($projet['titre_projet']) ?>
            </h1>
            <div>
                <a href="index.php?page=projets" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i>
                    Retour à la liste
                </a>
                <?php if ($isAdmin || (isset($projet['is_manager']) && $projet['is_manager'])): ?>
                    <a href="index.php?page=projets&action=edit&id=<?= $projet['id'] ?>" class="btn btn-warning">
                        <i class="fas fa-edit me-1"></i>
                        Modifier
                    </a>
                    <a href="index.php?page=projets&action=team&id=<?= $projet['id'] ?>" class="btn btn-info">
                        <i class="fas fa-users me-1"></i>
                        Équipe
                    </a>
                <?php endif; ?>
                <a href="index.php?page=factures&action=create&projet_id=<?= $projet['id'] ?>" class="btn btn-success">
                    <i class="fas fa-file-invoice me-1"></i>
                    Nouvelle facture
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Alertes de statut -->
<?php 
$isOverdue = $projet['statut'] === 'en cours' && 
           $projet['date_fin_prevue'] && 
           strtotime($projet['date_fin_prevue']) < time();
$isUpcoming = $projet['statut'] === 'en cours' && 
            $projet['date_fin_prevue'] && 
            strtotime($projet['date_fin_prevue']) < strtotime('+7 days');
?>

<?php if ($isOverdue): ?>
<div class="row mb-3">
    <div class="col-12">
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>Projet en retard !</strong> 
            L'échéance était prévue le <?= date('d/m/Y', strtotime($projet['date_fin_prevue'])) ?> 
            (<?= floor((time() - strtotime($projet['date_fin_prevue'])) / 86400) ?> jours de retard).
        </div>
    </div>
</div>
<?php elseif ($isUpcoming): ?>
<div class="row mb-3">
    <div class="col-12">
        <div class="alert alert-warning">
            <i class="fas fa-clock me-2"></i>
            <strong>Échéance proche !</strong> 
            Le projet doit être terminé le <?= date('d/m/Y', strtotime($projet['date_fin_prevue'])) ?> 
            (<?= ceil((strtotime($projet['date_fin_prevue']) - time()) / 86400) ?> jours restants).
        </div>
    </div>
</div>
<?php endif; ?>

<div class="row">
    <!-- Informations principales -->
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    Informations du projet
                </h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Client :</strong>
                        <a href="index.php?page=clients&action=view&id=<?= $projet['id_client'] ?>">
                            <?= htmlspecialchars($projet['nom_client']) ?>
                        </a>
                    </div>
                    <div class="col-md-6">
                        <strong>Statut :</strong>
                        <span class="badge bg-<?= $projet['statut'] === 'en cours' ? 'primary' : ($projet['statut'] === 'terminé' ? 'success' : 'secondary') ?> ms-2">
                            <?= ucfirst($projet['statut']) ?>
                        </span>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Date de début :</strong>
                        <?= $projet['date_debut'] ? date('d/m/Y', strtotime($projet['date_debut'])) : 'Non définie' ?>
                    </div>
                    <div class="col-md-6">
                        <strong>Date de fin prévue :</strong>
                        <?= $projet['date_fin_prevue'] ? date('d/m/Y', strtotime($projet['date_fin_prevue'])) : 'Non définie' ?>
                    </div>
                </div>
                
                <?php if ($projet['date_fin_reelle']): ?>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Date de fin réelle :</strong>
                        <?= date('d/m/Y', strtotime($projet['date_fin_reelle'])) ?>
                    </div>
                    <div class="col-md-6">
                        <?php 
                        $daysDiff = floor((strtotime($projet['date_fin_reelle']) - strtotime($projet['date_fin_prevue'])) / 86400);
                        ?>
                        <?php if ($daysDiff > 0): ?>
                            <span class="text-danger">
                                <i class="fas fa-exclamation-triangle"></i>
                                Terminé avec <?= $daysDiff ?> jour(s) de retard
                            </span>
                        <?php elseif ($daysDiff < 0): ?>
                            <span class="text-success">
                                <i class="fas fa-check"></i>
                                Terminé avec <?= abs($daysDiff) ?> jour(s) d'avance
                            </span>
                        <?php else: ?>
                            <span class="text-info">
                                <i class="fas fa-clock"></i>
                                Terminé à temps
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <?php if ($projet['budget']): ?>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Budget alloué :</strong>
                        <?= number_format($projet['budget'], 0, ',', ' ') ?> €
                    </div>
                    <div class="col-md-6">
                        <strong>Montant facturé :</strong>
                        <?= number_format($totalFacture ?? 0, 2, ',', ' ') ?> €
                        <?php if ($projet['budget'] && ($totalFacture ?? 0) > 0): ?>
                            <small class="text-muted">
                                (<?= round((($totalFacture ?? 0) / $projet['budget']) * 100, 1) ?>% du budget)
                            </small>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- Progression -->
                <div class="mb-3">
                    <strong>Progression :</strong>
                    <?php 
                    $progression = 0;
                    if ($projet['statut'] === 'terminé') $progression = 100;
                    elseif ($projet['statut'] === 'en cours') $progression = 50;
                    elseif ($projet['statut'] === 'annulé') $progression = 0;
                    ?>
                    <div class="progress mt-2" style="height: 25px;">
                        <div class="progress-bar bg-<?= $projet['statut'] === 'terminé' ? 'success' : 'primary' ?>" 
                             role="progressbar" 
                             style="width: <?= $progression ?>%"
                             aria-valuenow="<?= $progression ?>" 
                             aria-valuemin="0" 
                             aria-valuemax="100">
                            <?= $progression ?>%
                        </div>
                    </div>
                </div>
                
                <!-- Description -->
                <?php if (!empty($projet['description'])): ?>
                <div class="mb-3">
                    <strong>Description :</strong>
                    <div class="mt-2 p-3 bg-light rounded">
                        <?= nl2br(htmlspecialchars($projet['description'])) ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Équipe du projet -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-users me-2"></i>
                    Équipe du projet
                    <span class="badge bg-primary ms-2"><?= count($equipe ?? []) ?></span>
                </h5>
                <?php if ($isAdmin || (isset($projet['is_manager']) && $projet['is_manager'])): ?>
                    <a href="index.php?page=projets&action=team&id=<?= $projet['id'] ?>" class="btn btn-sm btn-primary">
                        <i class="fas fa-user-plus me-1"></i>
                        Gérer l'équipe
                    </a>
                <?php endif; ?>
            </div>
            <div class="card-body">
                <?php if (empty($equipe ?? [])): ?>
                    <div class="text-center py-3">
                        <i class="fas fa-users fa-2x text-muted mb-2"></i>
                        <p class="text-muted">Aucun membre assigné à ce projet.</p>
                        <?php if ($isAdmin || (isset($projet['is_manager']) && $projet['is_manager'])): ?>
                            <a href="index.php?page=projets&action=team&id=<?= $projet['id'] ?>" class="btn btn-primary">
                                <i class="fas fa-user-plus me-1"></i>
                                Ajouter des membres
                            </a>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <div class="row">
                        <?php foreach (($equipe ?? []) as $membre): ?>
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-center">
                                <div class="avatar me-3">
                                    <div class="avatar-initial bg-<?= ($membre['role_projet'] ?? 'membre') === 'chef' ? 'warning' : 'info' ?> rounded-circle">
                                        <?= strtoupper(substr(($membre['nom'] ?? 'U'), 0, 1)) ?>
                                    </div>
                                </div>
                                <div>
                                    <strong>
                                        <?php if (isset($membre['id_utilisateur']) && isset($membre['nom'])): ?>
                                            <a href="index.php?page=users&action=view&id=<?= $membre['id_utilisateur'] ?>">
                                                <?= htmlspecialchars($membre['nom']) ?>
                                            </a>
                                        <?php else: ?>
                                            Utilisateur inconnu
                                        <?php endif; ?>
                                    </strong>
                                    <br>
                                    <span class="badge bg-<?= ($membre['role_projet'] ?? 'membre') === 'chef' ? 'warning' : 'info' ?>">
                                        <?= ucfirst($membre['role_projet'] ?? 'membre') ?>
                                    </span>
                                    <?php if (isset($membre['date_assignation'])): ?>
                                        <small class="text-muted">
                                            depuis le <?= date('d/m/Y', strtotime($membre['date_assignation'])) ?>
                                        </small>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Factures liées -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-file-invoice me-2"></i>
                    Factures liées
                    <span class="badge bg-success ms-2"><?= count($factures ?? []) ?></span>
                </h5>
                <a href="index.php?page=factures&action=create&projet_id=<?= $projet['id'] ?>" class="btn btn-sm btn-success">
                    <i class="fas fa-plus me-1"></i>
                    Nouvelle facture
                </a>
            </div>
            <div class="card-body">
                <?php if (empty($factures ?? [])): ?>
                    <div class="text-center py-3">
                        <i class="fas fa-file-invoice fa-2x text-muted mb-2"></i>
                        <p class="text-muted">Aucune facture liée à ce projet.</p>
                        <a href="index.php?page=factures&action=create&projet_id=<?= $projet['id'] ?>" class="btn btn-success">
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
                                    <th>Date</th>
                                    <th>Montant</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach (($factures ?? []) as $facture): ?>
                                <tr>
                                    <td>
                                        <a href="index.php?page=factures&action=view&id=<?= $facture['id_facture'] ?>">
                                            <?= htmlspecialchars($facture['numero_facture']) ?>
                                        </a>
                                    </td>
                                    <td><?= date('d/m/Y', strtotime($facture['date_facture'])) ?></td>
                                    <td><?= number_format($facture['montant'], 2, ',', ' ') ?> €</td>
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
                                            <?php if ($facture['statut'] === 'impayée'): ?>
                                                <a href="index.php?page=factures&action=markPaid&id=<?= $facture['id_facture'] ?>" 
                                                   class="btn btn-sm btn-outline-success" title="Marquer payée">
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
                    
                    <!-- Résumé financier -->
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h5 class="text-success">
                                        <?= number_format(array_sum(array_map(function($f) {
                                            return $f['statut'] === 'payée' ? $f['montant'] : 0;
                                        }, ($factures ?? []))), 2, ',', ' ') ?> €
                                    </h5>
                                    <small class="text-muted">Montant encaissé</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h5 class="text-warning">
                                        <?= number_format(array_sum(array_map(function($f) {
                                            return $f['statut'] === 'impayée' ? $f['montant'] : 0;
                                        }, ($factures ?? []))), 2, ',', ' ') ?> €
                                    </h5>
                                    <small class="text-muted">En attente</small>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Sidebar -->
    <div class="col-md-4">
        <!-- Résumé rapide -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-pie me-2"></i>
                    Résumé
                </h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6 mb-3">
                        <h4 class="text-primary"><?= count($equipe ?? []) ?></h4>
                        <small class="text-muted">Membres</small>
                    </div>
                    <div class="col-6 mb-3">
                        <h4 class="text-success"><?= count($factures ?? []) ?></h4>
                        <small class="text-muted">Factures</small>
                    </div>
                </div>
                
                <?php if ($projet['budget']): ?>
                <hr>
                <div class="text-center">
                    <h5 class="text-info"><?= number_format($projet['budget'], 0, ',', ' ') ?> €</h5>
                    <small class="text-muted">Budget total</small>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($factures ?? [])): ?>
                <hr>
                <div class="text-center">
                    <h5 class="text-success">
                        <?= number_format(array_sum(array_column(($factures ?? []), 'montant')), 2, ',', ' ') ?> €
                    </h5>
                    <small class="text-muted">Total facturé</small>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Actions rapides -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-bolt me-2"></i>
                    Actions rapides
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <?php if ($projet['statut'] === 'en cours'): ?>
                        <a href="index.php?page=projets&action=complete&id=<?= $projet['id'] ?>" 
                           class="btn btn-success">
                            <i class="fas fa-check me-1"></i>
                            Marquer terminé
                        </a>
                    <?php elseif ($projet['statut'] === 'terminé'): ?>
                        <a href="index.php?page=projets&action=reopen&id=<?= $projet['id'] ?>" 
                           class="btn btn-warning">
                            <i class="fas fa-redo me-1"></i>
                            Rouvrir le projet
                        </a>
                    <?php endif; ?>
                    
                    <a href="index.php?page=projets&action=duplicate&id=<?= $projet['id'] ?>" 
                       class="btn btn-outline-secondary">
                        <i class="fas fa-copy me-1"></i>
                        Dupliquer
                    </a>
                    
                    <?php if ($isAdmin): ?>
                        <hr>
                        <a href="index.php?page=projets&action=delete&id=<?= $projet['id'] ?>" 
                           class="btn btn-outline-danger btn-delete"
                           data-item-name="ce projet">
                            <i class="fas fa-trash me-1"></i>
                            Supprimer
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Historique -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-history me-2"></i>
                    Historique
                </h5>
            </div>
            <div class="card-body">
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-marker bg-primary"></div>
                        <div class="timeline-content">
                            <h6 class="mb-1">Projet créé</h6>
                            <small class="text-muted">
                                <?= date('d/m/Y H:i', strtotime($projet['date_creation'])) ?>
                            </small>
                        </div>
                    </div>
                    
                    <?php if ($projet['date_debut']): ?>
                    <div class="timeline-item">
                        <div class="timeline-marker bg-info"></div>
                        <div class="timeline-content">
                            <h6 class="mb-1">Projet démarré</h6>
                            <small class="text-muted">
                                <?= date('d/m/Y', strtotime($projet['date_debut'])) ?>
                            </small>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($projet['statut'] === 'terminé' && $projet['date_fin_reelle']): ?>
                    <div class="timeline-item">
                        <div class="timeline-marker bg-success"></div>
                        <div class="timeline-content">
                            <h6 class="mb-1">Projet terminé</h6>
                            <small class="text-muted">
                                <?= date('d/m/Y', strtotime($projet['date_fin_reelle'])) ?>
                            </small>
                        </div>
                    </div>
                    <?php endif; ?>
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

.avatar-initial {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    color: white;
}

.timeline {
    position: relative;
    padding-left: 1.5rem;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 0.5rem;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #dee2e6;
}

.timeline-item {
    position: relative;
    margin-bottom: 1rem;
}

.timeline-marker {
    position: absolute;
    left: -1rem;
    top: 0.25rem;
    width: 1rem;
    height: 1rem;
    border-radius: 50%;
    border: 2px solid #fff;
    box-shadow: 0 0 0 2px #dee2e6;
}

.timeline-content {
    margin-left: 1rem;
}
</style>
