<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>
                <i class="fas fa-file-invoice me-2"></i>
                Facture <?= htmlspecialchars($facture['numero_facture']) ?>
            </h1>
            <div>
                <a href="index.php?page=factures" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i>
                    Retour à la liste
                </a>
                <a href="index.php?page=factures&action=edit&id=<?= $facture['id_facture'] ?>" class="btn btn-warning">
                    <i class="fas fa-edit me-1"></i>
                    Modifier
                </a>
                <button class="btn btn-info" onclick="window.print()">
                    <i class="fas fa-print me-1"></i>
                    Imprimer
                </button>
                <?php if ($facture['statut'] === 'impayée'): ?>
                    <a href="index.php?page=factures&action=markPaid&id=<?= $facture['id_facture'] ?>" class="btn btn-success">
                        <i class="fas fa-check me-1"></i>
                        Marquer payée
                    </a>
                <?php else: ?>
                    <a href="index.php?page=factures&action=markUnpaid&id=<?= $facture['id_facture'] ?>" class="btn btn-outline-warning">
                        <i class="fas fa-undo me-1"></i>
                        Marquer impayée
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Informations facture -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-file-invoice-dollar me-2"></i>
                    Détails de la facture
                </h5>
            </div>
            <div class="card-body">
                <!-- En-tête facture -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h3 class="text-primary">Pioneer Tech</h3>
                        <p class="text-muted mb-0">
                            123 Rue de l'Innovation<br>
                            75001 Paris, France<br>
                            Tél: +33 1 23 45 67 89<br>
                            Email: contact@pioneertech.com
                        </p>
                    </div>
                    <div class="col-md-6 text-end">
                        <h4>FACTURE</h4>
                        <p class="mb-1"><strong>N° <?= htmlspecialchars($facture['numero_facture']) ?></strong></p>
                        <p class="mb-1">Date: <?= date('d/m/Y', strtotime($facture['date_facture'])) ?></p>
                        <?php if ($facture['date_echeance']): ?>
                            <p class="mb-0">Échéance: <?= date('d/m/Y', strtotime($facture['date_echeance'])) ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <hr>

                <!-- Informations client -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6>Facturé à :</h6>
                        <div class="border p-3 bg-light">
                            <strong><?= htmlspecialchars($facture['nom_client']) ?></strong><br>
                            <?php if (!empty($facture['adresse_client'] ?? '')): ?>
                                <?= nl2br(htmlspecialchars($facture['adresse_client'])) ?><br>
                            <?php endif; ?>
                            <?php if (!empty($facture['email_client'] ?? '')): ?>
                                Email: <?= htmlspecialchars($facture['email_client']) ?><br>
                            <?php endif; ?>
                            <?php if (!empty($facture['telephone_client'] ?? '')): ?>
                                Tél: <?= htmlspecialchars($facture['telephone_client']) ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <?php if (!empty($facture['titre_projet'] ?? '')): ?>
                            <h6>Projet associé :</h6>
                            <div class="border p-3 bg-light">
                                <strong>
                                    <a href="index.php?page=projets&action=view&id=<?= $facture['id_projet'] ?>">
                                        <?= htmlspecialchars($facture['titre_projet']) ?>
                                    </a>
                                </strong>
                                <?php if (!empty($facture['description_projet'] ?? '')): ?>
                                    <br><small class="text-muted">
                                        <?= htmlspecialchars(substr($facture['description_projet'], 0, 100)) ?>
                                        <?= strlen($facture['description_projet']) > 100 ? '...' : '' ?>
                                    </small>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Description des prestations -->
                <div class="mb-4">
                    <h6>Description des prestations :</h6>
                    <div class="border p-3">
                        <?php if (!empty($facture['description'] ?? '')): ?>
                            <?= nl2br(htmlspecialchars($facture['description'])) ?>
                        <?php else: ?>
                            <em class="text-muted">Aucune description fournie</em>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Détail du montant -->
                <div class="row">
                    <div class="col-md-8"></div>
                    <div class="col-md-4">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Sous-total HT :</strong></td>
                                <td class="text-end">
                                    <?= number_format($facture['montant'] / 1.2, 2, ',', ' ') ?> €
                                </td>
                            </tr>
                            <tr>
                                <td><strong>TVA (20%) :</strong></td>
                                <td class="text-end">
                                    <?= number_format($facture['montant'] - ($facture['montant'] / 1.2), 2, ',', ' ') ?> €
                                </td>
                            </tr>
                            <tr class="table-primary">
                                <td><strong>Total TTC :</strong></td>
                                <td class="text-end">
                                    <strong><?= number_format($facture['montant'], 2, ',', ' ') ?> €</strong>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Conditions de paiement -->
                <div class="mt-4 pt-3 border-top">
                    <h6>Conditions de paiement :</h6>
                    <p class="small text-muted mb-0">
                        Paiement à réception de facture. En cas de retard de paiement, des pénalités de 3 fois le taux d'intérêt légal seront appliquées.
                        Une indemnité forfaitaire de 40€ sera due pour frais de recouvrement.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Informations complémentaires -->
    <div class="col-md-4">
        <!-- Statut de la facture -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    Statut de la facture
                </h5>
            </div>
            <div class="card-body text-center">
                <?php if ($facture['statut'] === 'payée'): ?>
                    <div class="mb-3">
                        <i class="fas fa-check-circle fa-3x text-success"></i>
                    </div>
                    <h5 class="text-success">Facture payée</h5>
                    <?php if (!empty($facture['date_paiement'] ?? '')): ?>
                        <p class="text-muted">
                            Payée le <?= date('d/m/Y', strtotime($facture['date_paiement'])) ?>
                        </p>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="mb-3">
                        <i class="fas fa-clock fa-3x text-warning"></i>
                    </div>
                    <h5 class="text-warning">En attente de paiement</h5>
                    <?php if ($facture['date_echeance']): ?>
                        <?php 
                        $isOverdue = strtotime($facture['date_echeance']) < time();
                        $daysLeft = ceil((strtotime($facture['date_echeance']) - time()) / 86400);
                        ?>
                        <?php if ($isOverdue): ?>
                            <p class="text-danger">
                                <i class="fas fa-exclamation-triangle"></i>
                                En retard de <?= abs($daysLeft) ?> jour(s)
                            </p>
                        <?php else: ?>
                            <p class="text-info">
                                Échéance dans <?= $daysLeft ?> jour(s)
                            </p>
                        <?php endif; ?>
                    <?php endif; ?>
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
                    <?php if (!empty($facture['email_client'])): ?>
                    <a href="mailto:<?= htmlspecialchars($facture['email_client']) ?>?subject=<?= urlencode('Facture ' . $facture['numero_facture']) ?>"
                       class="btn btn-outline-primary">
                        <i class="fas fa-envelope me-1"></i>
                        Envoyer par email
                    </a>
                    <?php endif; ?>
                    
                    <a href="index.php?page=factures&action=duplicate&id=<?= $facture['id_facture'] ?>" 
                       class="btn btn-outline-secondary">
                        <i class="fas fa-copy me-1"></i>
                        Dupliquer
                    </a>
                    
                    <a href="index.php?page=factures&action=pdf&id=<?= $facture['id_facture'] ?>" 
                       class="btn btn-outline-info" target="_blank">
                        <i class="fas fa-file-pdf me-1"></i>
                        Télécharger PDF
                    </a>
                    
                    <?php if ($isAdmin): ?>
                        <hr>
                        <a href="index.php?page=factures&action=delete&id=<?= $facture['id_facture'] ?>" 
                           class="btn btn-outline-danger btn-delete"
                           data-item-name="cette facture">
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
                            <h6 class="mb-1">Facture créée</h6>
                            <small class="text-muted">
                                <?= date('d/m/Y H:i', strtotime($facture['date_creation'])) ?>
                            </small>
                        </div>
                    </div>
                    
                    <?php if ($facture['statut'] === 'payée' && !empty($facture['date_paiement'] ?? '')): ?>
                    <div class="timeline-item">
                        <div class="timeline-marker bg-success"></div>
                        <div class="timeline-content">
                            <h6 class="mb-1">Facture payée</h6>
                            <small class="text-muted">
                                <?= date('d/m/Y H:i', strtotime($facture['date_paiement'])) ?>
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

@media print {
    .btn, .card-header, .timeline {
        display: none !important;
    }
    
    .card {
        border: none !important;
        box-shadow: none !important;
    }
    
    .col-md-4:last-child {
        display: none !important;
    }
    
    .col-md-8 {
        width: 100% !important;
    }
}
</style>
