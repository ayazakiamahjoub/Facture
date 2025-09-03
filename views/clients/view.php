<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>
                <i class="fas fa-user me-2"></i>
                <?= htmlspecialchars($client['nom_client']) ?>
            </h1>
            <div>
                <a href="index.php?page=clients" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i>
                    Retour à la liste
                </a>
                <a href="index.php?page=clients&action=edit&id=<?= $client['id'] ?>" class="btn btn-warning">
                    <i class="fas fa-edit me-1"></i>
                    Modifier
                </a>
                <a href="index.php?page=factures&action=create&client_id=<?= $client['id'] ?>" class="btn btn-success">
                    <i class="fas fa-file-invoice me-1"></i>
                    Nouvelle facture
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Informations client -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    Informations client
                </h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <div class="avatar-lg mx-auto mb-3">
                        <div class="avatar-initial bg-info rounded-circle">
                            <?= strtoupper(substr($client['nom_client'], 0, 2)) ?>
                        </div>
                    </div>
                    <h4><?= htmlspecialchars($client['nom_client']) ?></h4>
                    <?php if ($client['actif']): ?>
                        <span class="badge bg-success">
                            <i class="fas fa-check me-1"></i>
                            Client actif
                        </span>
                    <?php else: ?>
                        <span class="badge bg-secondary">
                            <i class="fas fa-times me-1"></i>
                            Client inactif
                        </span>
                    <?php endif; ?>
                </div>
                
                <table class="table table-borderless">
                    <tr>
                        <td><strong>Email :</strong></td>
                        <td>
                            <a href="mailto:<?= htmlspecialchars($client['email']) ?>">
                                <?= htmlspecialchars($client['email']) ?>
                            </a>
                        </td>
                    </tr>
                    <?php if (!empty($client['telephone'])): ?>
                    <tr>
                        <td><strong>Téléphone :</strong></td>
                        <td>
                            <a href="tel:<?= htmlspecialchars($client['telephone']) ?>">
                                <?= htmlspecialchars($client['telephone']) ?>
                            </a>
                        </td>
                    </tr>
                    <?php endif; ?>
                    <?php if (!empty($client['adresse'])): ?>
                    <tr>
                        <td><strong>Adresse :</strong></td>
                        <td><?= nl2br(htmlspecialchars($client['adresse'])) ?></td>
                    </tr>
                    <?php endif; ?>
                    <tr>
                        <td><strong>Client depuis :</strong></td>
                        <td><?= date('d/m/Y', strtotime($client['date_creation'])) ?></td>
                    </tr>
                </table>
                
                <div class="d-grid gap-2">
                    <?php if ($client['actif']): ?>
                        <a href="index.php?page=clients&action=deactivate&id=<?= $client['id'] ?>" 
                           class="btn btn-outline-danger btn-delete"
                           data-item-name="ce client">
                            <i class="fas fa-ban me-1"></i>
                            Désactiver le client
                        </a>
                    <?php else: ?>
                        <a href="index.php?page=clients&action=activate&id=<?= $client['id'] ?>" 
                           class="btn btn-outline-success">
                            <i class="fas fa-check me-1"></i>
                            Réactiver le client
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Statistiques rapides -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-bar me-2"></i>
                    Statistiques
                </h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <h3 class="text-primary"><?= count($projects ?? []) ?></h3>
                        <p class="text-muted mb-0">Projets</p>
                    </div>
                    <div class="col-6">
                        <h3 class="text-success"><?= count($invoices ?? []) ?></h3>
                        <p class="text-muted mb-0">Factures</p>
                    </div>
                </div>
                <hr>
                <div class="row text-center">
                    <div class="col-12">
                        <h4 class="text-info">
                            <?= number_format(array_sum(array_column($invoices ?? [], 'montant')), 2, ',', ' ') ?> €
                        </h4>
                        <p class="text-muted mb-0">Chiffre d'affaires total</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Projets du client -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-project-diagram me-2"></i>
                    Projets
                    <span class="badge bg-primary ms-2"><?= count($projects ?? []) ?></span>
                </h5>
                <a href="index.php?page=projets&action=create&client_id=<?= $client['id'] ?>" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus me-1"></i>
                    Nouveau projet
                </a>
            </div>
            <div class="card-body">
                <?php if (empty($projects)): ?>
                    <div class="text-center py-4">
                        <i class="fas fa-project-diagram fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Aucun projet pour ce client.</p>
                        <a href="index.php?page=projets&action=create&client_id=<?= $client['id'] ?>" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i>
                            Créer le premier projet
                        </a>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Projet</th>
                                    <th>Statut</th>
                                    <th>Échéance</th>
                                    <th>Budget</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($projects as $projet): ?>
                                <tr>
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
                                    <td>
                                        <span class="badge bg-<?= $projet['statut'] === 'en cours' ? 'primary' : ($projet['statut'] === 'terminé' ? 'success' : 'secondary') ?>">
                                            <?= ucfirst($projet['statut']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($projet['date_fin_prevue']): ?>
                                            <?= date('d/m/Y', strtotime($projet['date_fin_prevue'])) ?>
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
                                            <a href="index.php?page=factures&action=create&projet_id=<?= $projet['id'] ?>" 
                                               class="btn btn-sm btn-outline-success" title="Facturer">
                                                <i class="fas fa-file-invoice"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Factures du client -->
        <div class="card mt-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-file-invoice me-2"></i>
                    Factures
                    <span class="badge bg-success ms-2"><?= count($invoices ?? []) ?></span>
                </h5>
                <a href="index.php?page=factures&action=create&client_id=<?= $client['id'] ?>" class="btn btn-sm btn-success">
                    <i class="fas fa-plus me-1"></i>
                    Nouvelle facture
                </a>
            </div>
            <div class="card-body">
                <?php if (empty($invoices)): ?>
                    <div class="text-center py-4">
                        <i class="fas fa-file-invoice fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Aucune facture pour ce client.</p>
                        <a href="index.php?page=factures&action=create&client_id=<?= $client['id'] ?>" class="btn btn-success">
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
                                <?php foreach ($invoices as $facture): ?>
                                <tr>
                                    <td>
                                        <strong>
                                            <a href="index.php?page=factures&action=view&id=<?= $facture['id_facture'] ?>">
                                                <?= htmlspecialchars($facture['numero_facture']) ?>
                                            </a>
                                        </strong>
                                    </td>
                                    <td><?= date('d/m/Y', strtotime($facture['date_facture'])) ?></td>
                                    <td>
                                        <strong><?= number_format($facture['montant'], 2, ',', ' ') ?> €</strong>
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
                                        }, $invoices ?? [])), 2, ',', ' ') ?> €
                                    </h5>
                                    <small class="text-muted">Montant payé</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h5 class="text-warning">
                                        <?= number_format(array_sum(array_map(function($f) {
                                            return $f['statut'] === 'impayée' ? $f['montant'] : 0;
                                        }, $invoices ?? [])), 2, ',', ' ') ?> €
                                    </h5>
                                    <small class="text-muted">Montant en attente</small>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
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
