<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>
                <i class="fas fa-file-invoice-dollar me-2"></i>
                Nouvelle Facture
            </h1>
            <div>
                <a href="index.php?page=factures" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i>
                    Retour à la liste
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
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    Informations de la facture
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="" data-validate="true">
                    <div class="row">
                        <!-- Numéro de facture -->
                        <div class="col-md-4 mb-3">
                            <label for="numero_facture" class="form-label">
                                <i class="fas fa-hashtag me-1"></i>
                                Numéro de facture
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   id="numero_facture" 
                                   name="numero_facture" 
                                   value="<?= htmlspecialchars($formData['numero_facture'] ?? 'FAC-' . date('Y') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT)) ?>"
                                   placeholder="Ex: FAC-2025-0001">
                            <div class="form-text">
                                Laissez vide pour génération automatique
                            </div>
                        </div>
                        
                        <!-- Client -->
                        <div class="col-md-4 mb-3">
                            <label for="id_client" class="form-label">
                                <i class="fas fa-user me-1"></i>
                                Client <span class="text-danger">*</span>
                            </label>
                            <select class="form-select" id="id_client" name="id_client" required>
                                <option value="">Sélectionner un client</option>
                                <?php foreach ($clients as $client): ?>
                                    <option value="<?= $client['id'] ?>" 
                                            <?= (isset($formData['id_client']) && $formData['id_client'] == $client['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($client['nom_client']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="form-text">
                                <a href="index.php?page=clients&action=create" target="_blank">
                                    <i class="fas fa-plus me-1"></i>
                                    Créer un nouveau client
                                </a>
                            </div>
                        </div>
                        
                        <!-- Projet -->
                        <div class="col-md-4 mb-3">
                            <label for="id_projet" class="form-label">
                                <i class="fas fa-project-diagram me-1"></i>
                                Projet (optionnel)
                            </label>
                            <select class="form-select" id="id_projet" name="id_projet">
                                <option value="">Aucun projet spécifique</option>
                                <?php foreach ($projets as $projet): ?>
                                    <option value="<?= $projet['id'] ?>" 
                                            <?= (isset($formData['id_projet']) && $formData['id_projet'] == $projet['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($projet['titre_projet']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="form-text">
                                Associer à un projet spécifique
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <!-- Montant -->
                        <div class="col-md-3 mb-3">
                            <label for="montant" class="form-label">
                                <i class="fas fa-euro-sign me-1"></i>
                                Montant (€) <span class="text-danger">*</span>
                            </label>
                            <input type="number" 
                                   class="form-control" 
                                   id="montant" 
                                   name="montant" 
                                   min="0" 
                                   step="0.01"
                                   value="<?= htmlspecialchars($formData['montant'] ?? '') ?>"
                                   required
                                   placeholder="Ex: 1500.00">
                        </div>
                        
                        <!-- Date de facture -->
                        <div class="col-md-3 mb-3">
                            <label for="date_facture" class="form-label">
                                <i class="fas fa-calendar-alt me-1"></i>
                                Date de facture <span class="text-danger">*</span>
                            </label>
                            <input type="date" 
                                   class="form-control" 
                                   id="date_facture" 
                                   name="date_facture" 
                                   value="<?= htmlspecialchars($formData['date_facture'] ?? date('Y-m-d')) ?>"
                                   required>
                        </div>
                        
                        <!-- Date d'échéance -->
                        <div class="col-md-3 mb-3">
                            <label for="date_echeance" class="form-label">
                                <i class="fas fa-calendar-check me-1"></i>
                                Date d'échéance
                            </label>
                            <input type="date" 
                                   class="form-control" 
                                   id="date_echeance" 
                                   name="date_echeance" 
                                   value="<?= htmlspecialchars($formData['date_echeance'] ?? '') ?>">
                            <div class="form-text">
                                <a href="#" id="add-30-days">+30 jours</a> | 
                                <a href="#" id="add-60-days">+60 jours</a>
                            </div>
                        </div>
                        
                        <!-- Statut -->
                        <div class="col-md-3 mb-3">
                            <label for="statut" class="form-label">
                                <i class="fas fa-flag me-1"></i>
                                Statut
                            </label>
                            <select class="form-select" id="statut" name="statut">
                                <option value="impayée" <?= (isset($formData['statut']) && $formData['statut'] === 'impayée') ? 'selected' : 'selected' ?>>
                                    Impayée
                                </option>
                                <option value="payée" <?= (isset($formData['statut']) && $formData['statut'] === 'payée') ? 'selected' : '' ?>>
                                    Payée
                                </option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Description -->
                    <div class="mb-3">
                        <label for="description" class="form-label">
                            <i class="fas fa-align-left me-1"></i>
                            Description / Objet de la facture
                        </label>
                        <textarea class="form-control" 
                                  id="description" 
                                  name="description" 
                                  rows="4"
                                  placeholder="Décrivez les prestations facturées, les produits ou services..."><?= htmlspecialchars($formData['description'] ?? '') ?></textarea>
                        <div class="form-text">
                            Cette description apparaîtra sur la facture
                        </div>
                    </div>
                    
                    <!-- Aperçu du montant -->
                    <div class="card bg-light mb-4">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <h6 class="card-title mb-0">
                                        <i class="fas fa-calculator me-2"></i>
                                        Récapitulatif
                                    </h6>
                                </div>
                                <div class="col-md-6 text-end">
                                    <div class="h4 mb-0" id="montant-display">
                                        <span class="text-muted">Montant : </span>
                                        <span class="text-primary">0,00 €</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Boutons d'action -->
                    <div class="d-flex justify-content-between">
                        <a href="index.php?page=factures" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-1"></i>
                            Annuler
                        </a>
                        <div>
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="fas fa-save me-1"></i>
                                Créer la facture
                            </button>
                            <button type="submit" name="create_and_send" value="1" class="btn btn-success">
                                <i class="fas fa-paper-plane me-1"></i>
                                Créer et envoyer
                            </button>
                        </div>
                    </div>
                </form>
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
                    Conseils pour créer une facture
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>🔢 Numérotation</h6>
                        <p class="small text-muted">
                            Le numéro de facture est généré automatiquement si vous le laissez vide. 
                            Il doit être unique pour chaque facture.
                        </p>
                        
                        <h6>👤 Client et Projet</h6>
                        <p class="small text-muted">
                            Sélectionnez le client concerné. Le projet est optionnel mais recommandé 
                            pour un meilleur suivi.
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h6>💰 Montant et Dates</h6>
                        <p class="small text-muted">
                            Saisissez le montant TTC. La date d'échéance est optionnelle mais aide 
                            au suivi des paiements.
                        </p>
                        
                        <h6>📝 Description</h6>
                        <p class="small text-muted">
                            Décrivez clairement les prestations ou produits facturés. Cette information 
                            apparaîtra sur le document final.
                        </p>
                    </div>
                </div>
                
                <div class="alert alert-info mt-3 mb-0">
                    <i class="fas fa-rocket me-2"></i>
                    <strong>Astuce :</strong> Utilisez "Créer et envoyer" pour créer la facture et l'envoyer 
                    automatiquement par email au client !
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mise à jour de l'affichage du montant
    const montantInput = document.getElementById('montant');
    const montantDisplay = document.getElementById('montant-display');
    
    function updateMontantDisplay() {
        const montant = parseFloat(montantInput.value) || 0;
        const formatted = new Intl.NumberFormat('fr-FR', {
            style: 'currency',
            currency: 'EUR'
        }).format(montant);
        
        montantDisplay.innerHTML = `<span class="text-muted">Montant : </span><span class="text-primary">${formatted}</span>`;
    }
    
    montantInput.addEventListener('input', updateMontantDisplay);
    updateMontantDisplay(); // Initial call
    
    // Gestion des projets selon le client sélectionné
    const clientSelect = document.getElementById('id_client');
    const projetSelect = document.getElementById('id_projet');
    
    clientSelect.addEventListener('change', function() {
        const clientId = this.value;
        
        if (clientId) {
            // Charger les projets du client via AJAX
            fetch(`index.php?page=factures&action=getClientProjects&client_id=${clientId}`)
                .then(response => response.json())
                .then(projets => {
                    projetSelect.innerHTML = '<option value="">Aucun projet spécifique</option>';
                    projets.forEach(projet => {
                        const option = document.createElement('option');
                        option.value = projet.id;
                        option.textContent = projet.titre_projet;
                        projetSelect.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Erreur lors du chargement des projets:', error);
                });
        } else {
            projetSelect.innerHTML = '<option value="">Aucun projet spécifique</option>';
        }
    });
    
    // Raccourcis pour les dates d'échéance
    const dateFacture = document.getElementById('date_facture');
    const dateEcheance = document.getElementById('date_echeance');
    
    document.getElementById('add-30-days').addEventListener('click', function(e) {
        e.preventDefault();
        if (dateFacture.value) {
            const date = new Date(dateFacture.value);
            date.setDate(date.getDate() + 30);
            dateEcheance.value = date.toISOString().split('T')[0];
        }
    });
    
    document.getElementById('add-60-days').addEventListener('click', function(e) {
        e.preventDefault();
        if (dateFacture.value) {
            const date = new Date(dateFacture.value);
            date.setDate(date.getDate() + 60);
            dateEcheance.value = date.toISOString().split('T')[0];
        }
    });
    
    // Auto-suggestion d'échéance (+30 jours par défaut)
    dateFacture.addEventListener('change', function() {
        if (this.value && !dateEcheance.value) {
            const date = new Date(this.value);
            date.setDate(date.getDate() + 30);
            dateEcheance.value = date.toISOString().split('T')[0];
        }
    });
    
    // Validation du formulaire
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const montant = parseFloat(montantInput.value);
        const client = clientSelect.value;
        const dateFactureVal = dateFacture.value;
        
        if (!client) {
            e.preventDefault();
            alert('Veuillez sélectionner un client.');
            clientSelect.focus();
            return;
        }
        
        if (!montant || montant <= 0) {
            e.preventDefault();
            alert('Veuillez saisir un montant valide.');
            montantInput.focus();
            return;
        }
        
        if (!dateFactureVal) {
            e.preventDefault();
            alert('Veuillez saisir une date de facture.');
            dateFacture.focus();
            return;
        }
        
        // Validation de la date d'échéance
        if (dateEcheance.value && new Date(dateEcheance.value) <= new Date(dateFactureVal)) {
            e.preventDefault();
            alert('La date d\'échéance doit être postérieure à la date de facture.');
            dateEcheance.focus();
            return;
        }
    });
});
</script>

<style>
.card-header.bg-info {
    background-color: #17a2b8 !important;
}

.form-label {
    font-weight: 600;
}

.text-danger {
    color: #dc3545 !important;
}

.bg-light {
    background-color: #f8f9fa !important;
}

.border-info {
    border-color: #17a2b8 !important;
}

.form-text a {
    color: #0d6efd;
    text-decoration: none;
}

.form-text a:hover {
    text-decoration: underline;
}

.alert-info {
    background-color: #d1ecf1;
    border-color: #bee5eb;
    color: #0c5460;
}

#montant-display {
    font-size: 1.25rem;
    font-weight: bold;
}
</style>
