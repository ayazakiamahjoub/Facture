<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>
                <i class="fas fa-plus me-2"></i>
                Nouveau Projet
            </h1>
            <div>
                <a href="index.php?page=projets" class="btn btn-secondary">
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
                    Informations du projet
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="" data-validate="true">
                    <div class="row">
                        <!-- Titre du projet -->
                        <div class="col-md-8 mb-3">
                            <label for="titre_projet" class="form-label">
                                <i class="fas fa-heading me-1"></i>
                                Titre du projet <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   id="titre_projet" 
                                   name="titre_projet" 
                                   value="<?= htmlspecialchars($formData['titre_projet'] ?? '') ?>"
                                   required
                                   placeholder="Ex: Développement site web e-commerce">
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
                    </div>
                    
                    <!-- Description -->
                    <div class="mb-3">
                        <label for="description" class="form-label">
                            <i class="fas fa-align-left me-1"></i>
                            Description du projet
                        </label>
                        <textarea class="form-control" 
                                  id="description" 
                                  name="description" 
                                  rows="4"
                                  placeholder="Décrivez les objectifs, fonctionnalités et spécifications du projet..."><?= htmlspecialchars($formData['description'] ?? '') ?></textarea>
                    </div>
                    
                    <div class="row">
                        <!-- Statut -->
                        <div class="col-md-3 mb-3">
                            <label for="statut" class="form-label">
                                <i class="fas fa-flag me-1"></i>
                                Statut initial
                            </label>
                            <select class="form-select" id="statut" name="statut">
                                <option value="en cours" <?= (isset($formData['statut']) && $formData['statut'] === 'en cours') ? 'selected' : '' ?>>
                                    En cours
                                </option>
                                <option value="en attente" <?= (isset($formData['statut']) && $formData['statut'] === 'en attente') ? 'selected' : '' ?>>
                                    En attente
                                </option>
                            </select>
                        </div>
                        
                        <!-- Date de début -->
                        <div class="col-md-3 mb-3">
                            <label for="date_debut" class="form-label">
                                <i class="fas fa-calendar-alt me-1"></i>
                                Date de début
                            </label>
                            <input type="date" 
                                   class="form-control" 
                                   id="date_debut" 
                                   name="date_debut" 
                                   value="<?= htmlspecialchars($formData['date_debut'] ?? date('Y-m-d')) ?>">
                        </div>
                        
                        <!-- Date de fin prévue -->
                        <div class="col-md-3 mb-3">
                            <label for="date_fin_prevue" class="form-label">
                                <i class="fas fa-calendar-check me-1"></i>
                                Date de fin prévue
                            </label>
                            <input type="date" 
                                   class="form-control" 
                                   id="date_fin_prevue" 
                                   name="date_fin_prevue" 
                                   value="<?= htmlspecialchars($formData['date_fin_prevue'] ?? '') ?>">
                        </div>
                        
                        <!-- Budget -->
                        <div class="col-md-3 mb-3">
                            <label for="budget" class="form-label">
                                <i class="fas fa-euro-sign me-1"></i>
                                Budget (€)
                            </label>
                            <input type="number" 
                                   class="form-control" 
                                   id="budget" 
                                   name="budget" 
                                   min="0" 
                                   step="0.01"
                                   value="<?= htmlspecialchars($formData['budget'] ?? '') ?>"
                                   placeholder="Ex: 15000">
                        </div>
                    </div>
                    
                    <!-- Informations supplémentaires -->
                    <div class="card bg-light mb-4">
                        <div class="card-body">
                            <h6 class="card-title">
                                <i class="fas fa-info-circle me-2"></i>
                                Informations importantes
                            </h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <ul class="list-unstyled mb-0">
                                        <li><i class="fas fa-check text-success me-2"></i>Vous serez automatiquement assigné comme chef de projet</li>
                                        <li><i class="fas fa-check text-success me-2"></i>Vous pourrez ajouter des membres à l'équipe après création</li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <ul class="list-unstyled mb-0">
                                        <li><i class="fas fa-check text-success me-2"></i>Les dates peuvent être modifiées ultérieurement</li>
                                        <li><i class="fas fa-check text-success me-2"></i>Le budget est optionnel mais recommandé</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Boutons d'action -->
                    <div class="d-flex justify-content-between">
                        <a href="index.php?page=projets" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-1"></i>
                            Annuler
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>
                            Créer le projet
                        </button>
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
                    Conseils pour créer un projet
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>📝 Titre du projet</h6>
                        <p class="small text-muted">Choisissez un titre clair et descriptif qui résume l'objectif principal du projet.</p>
                        
                        <h6>👤 Client</h6>
                        <p class="small text-muted">Sélectionnez le client pour lequel ce projet est réalisé. Si le client n'existe pas, créez-le d'abord.</p>
                    </div>
                    <div class="col-md-6">
                        <h6>📅 Planification</h6>
                        <p class="small text-muted">Définissez des dates réalistes. La date de fin prévue vous aidera à suivre les échéances.</p>
                        
                        <h6>💰 Budget</h6>
                        <p class="small text-muted">Indiquez le budget alloué pour faciliter le suivi financier et la facturation.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Validation des dates
    const dateDebut = document.getElementById('date_debut');
    const dateFinPrevue = document.getElementById('date_fin_prevue');
    
    function validateDates() {
        if (dateDebut.value && dateFinPrevue.value) {
            if (new Date(dateFinPrevue.value) <= new Date(dateDebut.value)) {
                dateFinPrevue.setCustomValidity('La date de fin doit être postérieure à la date de début');
            } else {
                dateFinPrevue.setCustomValidity('');
            }
        }
    }
    
    dateDebut.addEventListener('change', validateDates);
    dateFinPrevue.addEventListener('change', validateDates);
    
    // Auto-suggestion de date de fin (30 jours après le début)
    dateDebut.addEventListener('change', function() {
        if (this.value && !dateFinPrevue.value) {
            const startDate = new Date(this.value);
            startDate.setDate(startDate.getDate() + 30);
            dateFinPrevue.value = startDate.toISOString().split('T')[0];
        }
    });
    
    // Validation du budget
    const budgetInput = document.getElementById('budget');
    budgetInput.addEventListener('input', function() {
        if (this.value < 0) {
            this.setCustomValidity('Le budget ne peut pas être négatif');
        } else {
            this.setCustomValidity('');
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
</style>
