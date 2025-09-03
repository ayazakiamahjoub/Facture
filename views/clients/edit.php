<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>
                <i class="fas fa-user-edit me-2"></i>
                Modifier le client
            </h1>
            <div>
                <a href="index.php?page=clients&action=view&id=<?= $client['id'] ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i>
                    Retour au client
                </a>
                <a href="index.php?page=clients" class="btn btn-outline-secondary">
                    <i class="fas fa-list me-1"></i>
                    Liste des clients
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
                    Informations du client
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nom_client" class="form-label">
                                    <i class="fas fa-user me-1"></i>
                                    Nom du client <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control <?= isset($errors['nom_client']) ? 'is-invalid' : '' ?>" 
                                       id="nom_client" 
                                       name="nom_client" 
                                       value="<?= htmlspecialchars($formData['nom_client'] ?? $client['nom_client']) ?>"
                                       required
                                       placeholder="Nom de l'entreprise ou de la personne">
                                <?php if (isset($errors['nom_client'])): ?>
                                    <div class="invalid-feedback">
                                        <?= htmlspecialchars($errors['nom_client']) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">
                                    <i class="fas fa-envelope me-1"></i>
                                    Email <span class="text-danger">*</span>
                                </label>
                                <input type="email" 
                                       class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>" 
                                       id="email" 
                                       name="email" 
                                       value="<?= htmlspecialchars($formData['email'] ?? $client['email']) ?>"
                                       required
                                       placeholder="contact@exemple.com">
                                <?php if (isset($errors['email'])): ?>
                                    <div class="invalid-feedback">
                                        <?= htmlspecialchars($errors['email']) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="telephone" class="form-label">
                                    <i class="fas fa-phone me-1"></i>
                                    Téléphone
                                </label>
                                <input type="tel" 
                                       class="form-control <?= isset($errors['telephone']) ? 'is-invalid' : '' ?>" 
                                       id="telephone" 
                                       name="telephone" 
                                       value="<?= htmlspecialchars($formData['telephone'] ?? $client['telephone'] ?? '') ?>"
                                       placeholder="+33 1 23 45 67 89">
                                <?php if (isset($errors['telephone'])): ?>
                                    <div class="invalid-feedback">
                                        <?= htmlspecialchars($errors['telephone']) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="actif" class="form-label">
                                    <i class="fas fa-toggle-on me-1"></i>
                                    Statut
                                </label>
                                <select class="form-select" id="actif" name="actif">
                                    <option value="1" <?= ($formData['actif'] ?? $client['actif']) == 1 ? 'selected' : '' ?>>
                                        <i class="fas fa-check"></i> Client actif
                                    </option>
                                    <option value="0" <?= ($formData['actif'] ?? $client['actif']) == 0 ? 'selected' : '' ?>>
                                        <i class="fas fa-times"></i> Client inactif
                                    </option>
                                </select>
                                <div class="form-text">
                                    Les clients inactifs n'apparaissent pas dans les listes de sélection.
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="adresse" class="form-label">
                            <i class="fas fa-map-marker-alt me-1"></i>
                            Adresse complète
                        </label>
                        <textarea class="form-control <?= isset($errors['adresse']) ? 'is-invalid' : '' ?>" 
                                  id="adresse" 
                                  name="adresse" 
                                  rows="3"
                                  placeholder="Adresse complète du client (rue, ville, code postal, pays)"><?= htmlspecialchars($formData['adresse'] ?? $client['adresse'] ?? '') ?></textarea>
                        <?php if (isset($errors['adresse'])): ?>
                            <div class="invalid-feedback">
                                <?= htmlspecialchars($errors['adresse']) ?>
                            </div>
                        <?php endif; ?>
                        <div class="form-text">
                            Cette adresse sera utilisée pour la facturation.
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="notes" class="form-label">
                            <i class="fas fa-sticky-note me-1"></i>
                            Notes internes
                        </label>
                        <textarea class="form-control" 
                                  id="notes" 
                                  name="notes" 
                                  rows="3"
                                  placeholder="Notes internes sur le client (non visibles sur les factures)"><?= htmlspecialchars($formData['notes'] ?? $client['notes'] ?? '') ?></textarea>
                        <div class="form-text">
                            Ces notes sont uniquement visibles par votre équipe.
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>
                                Enregistrer les modifications
                            </button>
                            <a href="index.php?page=clients&action=view&id=<?= $client['id'] ?>" class="btn btn-secondary ms-2">
                                <i class="fas fa-times me-1"></i>
                                Annuler
                            </a>
                        </div>
                        
                        <?php if ($isAdmin): ?>
                        <div>
                            <a href="index.php?page=clients&action=delete&id=<?= $client['id'] ?>" 
                               class="btn btn-outline-danger btn-delete"
                               data-item-name="ce client">
                                <i class="fas fa-trash me-1"></i>
                                Supprimer le client
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
                    <div class="avatar-lg mx-auto mb-2">
                        <div class="avatar-initial bg-info rounded-circle">
                            <?= strtoupper(substr($client['nom_client'], 0, 2)) ?>
                        </div>
                    </div>
                    <h6><?= htmlspecialchars($client['nom_client']) ?></h6>
                    <span class="badge bg-<?= $client['actif'] ? 'success' : 'secondary' ?>">
                        <?= $client['actif'] ? 'Actif' : 'Inactif' ?>
                    </span>
                </div>
                
                <table class="table table-borderless table-sm">
                    <tr>
                        <td><strong>Email :</strong></td>
                        <td><?= htmlspecialchars($client['email']) ?></td>
                    </tr>
                    <?php if (!empty($client['telephone'])): ?>
                    <tr>
                        <td><strong>Téléphone :</strong></td>
                        <td><?= htmlspecialchars($client['telephone']) ?></td>
                    </tr>
                    <?php endif; ?>
                    <tr>
                        <td><strong>Créé le :</strong></td>
                        <td><?= date('d/m/Y', strtotime($client['date_creation'])) ?></td>
                    </tr>
                    <?php if (!empty($client['date_modification'])): ?>
                    <tr>
                        <td><strong>Modifié le :</strong></td>
                        <td><?= date('d/m/Y H:i', strtotime($client['date_modification'])) ?></td>
                    </tr>
                    <?php endif; ?>
                </table>
            </div>
        </div>
        
        <!-- Statistiques -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-bar me-2"></i>
                    Statistiques
                </h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6 mb-3">
                        <h4 class="text-primary"><?= $stats['projets'] ?? 0 ?></h4>
                        <small class="text-muted">Projets</small>
                    </div>
                    <div class="col-6 mb-3">
                        <h4 class="text-success"><?= $stats['factures'] ?? 0 ?></h4>
                        <small class="text-muted">Factures</small>
                    </div>
                </div>
                
                <?php if (($stats['chiffre_affaires'] ?? 0) > 0): ?>
                <hr>
                <div class="text-center">
                    <h5 class="text-info"><?= number_format($stats['chiffre_affaires'], 2, ',', ' ') ?> €</h5>
                    <small class="text-muted">Chiffre d'affaires total</small>
                </div>
                <?php endif; ?>
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
                    <a href="index.php?page=projets&action=create&client_id=<?= $client['id'] ?>" class="btn btn-outline-primary">
                        <i class="fas fa-project-diagram me-1"></i>
                        Nouveau projet
                    </a>
                    
                    <a href="index.php?page=factures&action=create&client_id=<?= $client['id'] ?>" class="btn btn-outline-success">
                        <i class="fas fa-file-invoice me-1"></i>
                        Nouvelle facture
                    </a>
                    
                    <a href="mailto:<?= htmlspecialchars($client['email']) ?>" class="btn btn-outline-info">
                        <i class="fas fa-envelope me-1"></i>
                        Envoyer un email
                    </a>
                    
                    <?php if (!empty($client['telephone'])): ?>
                    <a href="tel:<?= htmlspecialchars($client['telephone']) ?>" class="btn btn-outline-warning">
                        <i class="fas fa-phone me-1"></i>
                        Appeler
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
                            <li>Le nom et l'email sont obligatoires</li>
                            <li>L'email doit être unique dans le système</li>
                            <li>L'adresse sera utilisée pour la facturation</li>
                            <li>Les notes internes ne sont pas visibles sur les factures</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6>⚠️ Attention</h6>
                        <ul class="small text-muted">
                            <li>Désactiver un client le masque des listes de sélection</li>
                            <li>Les projets et factures existants restent accessibles</li>
                            <li>La suppression est définitive et supprime tout l'historique</li>
                        </ul>
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
</style>
