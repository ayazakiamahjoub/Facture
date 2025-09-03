<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>
                <i class="fas fa-file-invoice-dollar me-2"></i>
                Modifier la facture <?= htmlspecialchars($facture['numero_facture']) ?>
            </h1>
            <div>
                <a href="index.php?page=factures&action=view&id=<?= $facture['id_facture'] ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i>
                    Retour à la facture
                </a>
                <a href="index.php?page=factures" class="btn btn-outline-secondary">
                    <i class="fas fa-list me-1"></i>
                    Liste des factures
                </a>
            </div>
        </div>
    </div>
</div>

<?php if (!empty($errors)): ?>
<div class="row mb-4">
    <div class="col-12">
        <div class="alert alert-danger">
            <h6><i class="fas fa-exclamation-triangle me-2"></i>Erreurs de validation :</h6>
            <ul class="mb-0">
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</div>
<?php endif; ?>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-edit me-2"></i>
                    Informations de la facture
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="numero_facture" class="form-label">
                                    <i class="fas fa-hashtag me-1"></i>
                                    Numéro de facture <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control <?= isset($errors['numero_facture']) ? 'is-invalid' : '' ?>" 
                                       id="numero_facture" 
                                       name="numero_facture" 
                                       value="<?= htmlspecialchars($formData['numero_facture'] ?? $facture['numero_facture']) ?>"
                                       required
                                       placeholder="FAC-2024-001">
                                <?php if (isset($errors['numero_facture'])): ?>
                                    <div class="invalid-feedback">
                                        <?= htmlspecialchars($errors['numero_facture']) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="date_facture" class="form-label">
                                    <i class="fas fa-calendar me-1"></i>
                                    Date de facture <span class="text-danger">*</span>
                                </label>
                                <input type="date" 
                                       class="form-control <?= isset($errors['date_facture']) ? 'is-invalid' : '' ?>" 
                                       id="date_facture" 
                                       name="date_facture" 
                                       value="<?= $formData['date_facture'] ?? (!empty($facture['date_facture']) ? date('Y-m-d', strtotime($facture['date_facture'])) : date('Y-m-d')) ?>"
                                       required>
                                <?php if (isset($errors['date_facture'])): ?>
                                    <div class="invalid-feedback">
                                        <?= htmlspecialchars($errors['date_facture']) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="id_client" class="form-label">
                                    <i class="fas fa-user me-1"></i>
                                    Client <span class="text-danger">*</span>
                                </label>
                                <select class="form-select <?= isset($errors['id_client']) ? 'is-invalid' : '' ?>" 
                                        id="id_client" 
                                        name="id_client" 
                                        required>
                                    <option value="">Sélectionner un client</option>
                                    <?php foreach (($clients ?? []) as $client): ?>
                                        <option value="<?= $client['id'] ?>" 
                                                <?= ($formData['id_client'] ?? $facture['id_client']) == $client['id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($client['nom_client']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <?php if (isset($errors['id_client'])): ?>
                                    <div class="invalid-feedback">
                                        <?= htmlspecialchars($errors['id_client']) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="id_projet" class="form-label">
                                    <i class="fas fa-project-diagram me-1"></i>
                                    Projet (optionnel)
                                </label>
                                <select class="form-select" id="id_projet" name="id_projet">
                                    <option value="">Aucun projet spécifique</option>
                                    <?php foreach (($projets ?? []) as $projet): ?>
                                        <option value="<?= $projet['id'] ?>" 
                                                <?= ($formData['id_projet'] ?? $facture['id_projet']) == $projet['id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($projet['titre_projet']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="form-text">
                                    Associer cette facture à un projet spécifique.
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="montant" class="form-label">
                                    <i class="fas fa-euro-sign me-1"></i>
                                    Montant TTC <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="number" 
                                           class="form-control <?= isset($errors['montant']) ? 'is-invalid' : '' ?>" 
                                           id="montant" 
                                           name="montant" 
                                           value="<?= $formData['montant'] ?? $facture['montant'] ?>"
                                           step="0.01" 
                                           min="0"
                                           required
                                           placeholder="0.00">
                                    <span class="input-group-text">€</span>
                                    <?php if (isset($errors['montant'])): ?>
                                        <div class="invalid-feedback">
                                            <?= htmlspecialchars($errors['montant']) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="form-text">
                                    Montant toutes taxes comprises.
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="date_echeance" class="form-label">
                                    <i class="fas fa-clock me-1"></i>
                                    Date d'échéance
                                </label>
                                <input type="date" 
                                       class="form-control" 
                                       id="date_echeance" 
                                       name="date_echeance" 
                                       value="<?= $formData['date_echeance'] ?? (!empty($facture['date_echeance']) ? date('Y-m-d', strtotime($facture['date_echeance'])) : '') ?>">
                                <div class="form-text">
                                    Date limite de paiement (optionnel).
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="statut" class="form-label">
                                    <i class="fas fa-flag me-1"></i>
                                    Statut
                                </label>
                                <select class="form-select" id="statut" name="statut">
                                    <option value="impayée" <?= ($formData['statut'] ?? $facture['statut']) === 'impayée' ? 'selected' : '' ?>>
                                        <i class="fas fa-clock"></i> Impayée
                                    </option>
                                    <option value="payée" <?= ($formData['statut'] ?? $facture['statut']) === 'payée' ? 'selected' : '' ?>>
                                        <i class="fas fa-check"></i> Payée
                                    </option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="date_paiement" class="form-label">
                                    <i class="fas fa-calendar-check me-1"></i>
                                    Date de paiement
                                </label>
                                <input type="date" 
                                       class="form-control" 
                                       id="date_paiement" 
                                       name="date_paiement" 
                                       value="<?= $formData['date_paiement'] ?? (!empty($facture['date_paiement']) ? date('Y-m-d', strtotime($facture['date_paiement'])) : '') ?>">
                                <div class="form-text">
                                    Date effective du paiement (si payée).
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="description" class="form-label">
                            <i class="fas fa-file-alt me-1"></i>
                            Description des prestations <span class="text-danger">*</span>
                        </label>
                        <textarea class="form-control <?= isset($errors['description']) ? 'is-invalid' : '' ?>" 
                                  id="description" 
                                  name="description" 
                                  rows="4"
                                  required
                                  placeholder="Décrivez les prestations facturées..."><?= htmlspecialchars($formData['description'] ?? $facture['description']) ?></textarea>
                        <?php if (isset($errors['description'])): ?>
                            <div class="invalid-feedback">
                                <?= htmlspecialchars($errors['description']) ?>
                            </div>
                        <?php endif; ?>
                        <div class="form-text">
                            Cette description apparaîtra sur la facture.
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>
                                Enregistrer les modifications
                            </button>
                            <a href="index.php?page=factures&action=view&id=<?= $facture['id_facture'] ?>" class="btn btn-secondary ms-2">
                                <i class="fas fa-times me-1"></i>
                                Annuler
                            </a>
                        </div>
                        
                        <?php if ($isAdmin): ?>
                        <div>
                            <a href="index.php?page=factures&action=delete&id=<?= $facture['id_facture'] ?>" 
                               class="btn btn-outline-danger btn-delete"
                               data-item-name="cette facture">
                                <i class="fas fa-trash me-1"></i>
                                Supprimer la facture
                            </a>
                        </div>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Sidebar avec informations -->
    <div class="col-md-4">
        <!-- Informations actuelles -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    Informations actuelles
                </h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <div class="mb-2">
                        <?php if ($facture['statut'] === 'payée'): ?>
                            <i class="fas fa-check-circle fa-3x text-success"></i>
                            <h6 class="text-success mt-2">Facture payée</h6>
                        <?php else: ?>
                            <i class="fas fa-clock fa-3x text-warning"></i>
                            <h6 class="text-warning mt-2">En attente de paiement</h6>
                        <?php endif; ?>
                    </div>
                    <h5><?= htmlspecialchars($facture['numero_facture']) ?></h5>
                </div>
                
                <table class="table table-borderless table-sm">
                    <tr>
                        <td><strong>Client :</strong></td>
                        <td><?= htmlspecialchars($facture['nom_client'] ?? 'Client inconnu') ?></td>
                    </tr>
                    <tr>
                        <td><strong>Montant :</strong></td>
                        <td><strong><?= number_format($facture['montant'], 2, ',', ' ') ?> €</strong></td>
                    </tr>
                    <tr>
                        <td><strong>Date :</strong></td>
                        <td><?= !empty($facture['date_facture']) ? date('d/m/Y', strtotime($facture['date_facture'])) : 'Non définie' ?></td>
                    </tr>
                    <?php if (!empty($facture['date_echeance'])): ?>
                    <tr>
                        <td><strong>Échéance :</strong></td>
                        <td><?= date('d/m/Y', strtotime($facture['date_echeance'])) ?></td>
                    </tr>
                    <?php endif; ?>
                    <tr>
                        <td><strong>Créée le :</strong></td>
                        <td><?= !empty($facture['date_creation']) ? date('d/m/Y H:i', strtotime($facture['date_creation'])) : 'Non définie' ?></td>
                    </tr>
                </table>
            </div>
        </div>
        
        <!-- Calculs automatiques -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-calculator me-2"></i>
                    Détail du montant
                </h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td>Montant HT :</td>
                        <td class="text-end">
                            <span id="montant-ht"><?= number_format($facture['montant'] / 1.2, 2, ',', ' ') ?></span> €
                        </td>
                    </tr>
                    <tr>
                        <td>TVA (20%) :</td>
                        <td class="text-end">
                            <span id="montant-tva"><?= number_format($facture['montant'] - ($facture['montant'] / 1.2), 2, ',', ' ') ?></span> €
                        </td>
                    </tr>
                    <tr class="table-primary">
                        <td><strong>Total TTC :</strong></td>
                        <td class="text-end">
                            <strong><span id="montant-ttc"><?= number_format($facture['montant'], 2, ',', ' ') ?></span> €</strong>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        
        <!-- Actions rapides -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-bolt me-2"></i>
                    Actions rapides
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <button class="btn btn-outline-info" onclick="window.print()">
                        <i class="fas fa-print me-1"></i>
                        Imprimer
                    </button>
                    
                    <a href="index.php?page=factures&action=duplicate&id=<?= $facture['id_facture'] ?>" class="btn btn-outline-secondary">
                        <i class="fas fa-copy me-1"></i>
                        Dupliquer
                    </a>
                    
                    <?php if (isset($facture['email_client'])): ?>
                    <a href="mailto:<?= htmlspecialchars($facture['email_client']) ?>?subject=Facture <?= urlencode($facture['numero_facture']) ?>" 
                       class="btn btn-outline-primary">
                        <i class="fas fa-envelope me-1"></i>
                        Envoyer par email
                    </a>
                    <?php endif; ?>
                    
                    <?php if ($facture['statut'] === 'impayée'): ?>
                        <a href="index.php?page=factures&action=markPaid&id=<?= $facture['id_facture'] ?>" 
                           class="btn btn-outline-success">
                            <i class="fas fa-check me-1"></i>
                            Marquer payée
                        </a>
                    <?php else: ?>
                        <a href="index.php?page=factures&action=markUnpaid&id=<?= $facture['id_facture'] ?>" 
                           class="btn btn-outline-warning">
                            <i class="fas fa-undo me-1"></i>
                            Marquer impayée
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Aide contextuelle -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card border-info">
            <div class="card-header bg-info text-white">
                <h6 class="mb-0">
                    <i class="fas fa-lightbulb me-2"></i>
                    Conseils pour la modification
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>📝 Informations importantes</h6>
                        <ul class="small text-muted">
                            <li>Le numéro de facture doit être unique</li>
                            <li>Le montant est en euros TTC</li>
                            <li>La description apparaît sur la facture</li>
                            <li>L'association à un projet est optionnelle</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6>⚠️ Attention</h6>
                        <ul class="small text-muted">
                            <li>Modifier une facture payée peut affecter la comptabilité</li>
                            <li>La date de paiement est automatiquement mise à jour</li>
                            <li>La suppression est définitive</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Calcul automatique des montants
document.getElementById('montant').addEventListener('input', function() {
    const montantTTC = parseFloat(this.value) || 0;
    const montantHT = montantTTC / 1.2;
    const montantTVA = montantTTC - montantHT;
    
    document.getElementById('montant-ht').textContent = montantHT.toLocaleString('fr-FR', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
    
    document.getElementById('montant-tva').textContent = montantTVA.toLocaleString('fr-FR', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
    
    document.getElementById('montant-ttc').textContent = montantTTC.toLocaleString('fr-FR', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
});

// Gestion automatique de la date de paiement
document.getElementById('statut').addEventListener('change', function() {
    const datePaiementField = document.getElementById('date_paiement');
    
    if (this.value === 'payée' && !datePaiementField.value) {
        // Si on marque comme payée et qu'il n'y a pas de date, mettre aujourd'hui
        datePaiementField.value = new Date().toISOString().split('T')[0];
    } else if (this.value === 'impayée') {
        // Si on marque comme impayée, vider la date de paiement
        datePaiementField.value = '';
    }
});
</script>

<style>
.form-control.is-invalid {
    border-color: #dc3545;
}

.form-control.is-invalid:focus {
    border-color: #dc3545;
    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
}

.invalid-feedback {
    display: block;
}

@media print {
    .btn, .card-header, .alert {
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
