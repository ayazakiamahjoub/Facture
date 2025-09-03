<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>
                <i class="fas fa-user-plus me-2"></i>
                Nouveau Client
            </h1>
            <div>
                <a href="index.php?page=clients" class="btn btn-secondary">
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
                    Informations du client
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="" data-validate="true">
                    <div class="row">
                        <!-- Nom du client -->
                        <div class="col-md-6 mb-3">
                            <label for="nom_client" class="form-label">
                                <i class="fas fa-user me-1"></i>
                                Nom du client <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   id="nom_client" 
                                   name="nom_client" 
                                   value="<?= htmlspecialchars($formData['nom_client'] ?? '') ?>"
                                   required
                                   placeholder="Ex: Entreprise ABC, Jean Dupont...">
                            <div class="form-text">
                                Nom de l'entreprise ou nom complet de la personne
                            </div>
                        </div>
                        
                        <!-- Email -->
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">
                                <i class="fas fa-envelope me-1"></i>
                                Adresse email <span class="text-danger">*</span>
                            </label>
                            <input type="email" 
                                   class="form-control" 
                                   id="email" 
                                   name="email" 
                                   value="<?= htmlspecialchars($formData['email'] ?? '') ?>"
                                   required
                                   placeholder="contact@entreprise.com">
                            <div class="form-text">
                                Adresse email principale pour les communications
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <!-- Téléphone -->
                        <div class="col-md-6 mb-3">
                            <label for="telephone" class="form-label">
                                <i class="fas fa-phone me-1"></i>
                                Numéro de téléphone
                            </label>
                            <input type="tel" 
                                   class="form-control" 
                                   id="telephone" 
                                   name="telephone" 
                                   value="<?= htmlspecialchars($formData['telephone'] ?? '') ?>"
                                   placeholder="Ex: 01 23 45 67 89">
                            <div class="form-text">
                                Numéro de téléphone principal (optionnel)
                            </div>
                        </div>
                        
                        <!-- Statut -->
                        <div class="col-md-6 mb-3">
                            <label for="actif" class="form-label">
                                <i class="fas fa-toggle-on me-1"></i>
                                Statut du client
                            </label>
                            <select class="form-select" id="actif" name="actif">
                                <option value="1" <?= (isset($formData['actif']) && $formData['actif'] == '1') ? 'selected' : 'selected' ?>>
                                    Actif
                                </option>
                                <option value="0" <?= (isset($formData['actif']) && $formData['actif'] == '0') ? 'selected' : '' ?>>
                                    Inactif
                                </option>
                            </select>
                            <div class="form-text">
                                Les clients inactifs n'apparaissent pas dans les listes par défaut
                            </div>
                        </div>
                    </div>
                    
                    <!-- Adresse -->
                    <div class="mb-3">
                        <label for="adresse" class="form-label">
                            <i class="fas fa-map-marker-alt me-1"></i>
                            Adresse complète
                        </label>
                        <textarea class="form-control" 
                                  id="adresse" 
                                  name="adresse" 
                                  rows="3"
                                  placeholder="Adresse complète du client (rue, ville, code postal, pays)..."><?= htmlspecialchars($formData['adresse'] ?? '') ?></textarea>
                        <div class="form-text">
                            Adresse postale complète (optionnel mais recommandé pour la facturation)
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
                                        <li><i class="fas fa-check text-success me-2"></i>L'email doit être unique dans le système</li>
                                        <li><i class="fas fa-check text-success me-2"></i>Vous pourrez créer des projets pour ce client</li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <ul class="list-unstyled mb-0">
                                        <li><i class="fas fa-check text-success me-2"></i>Les informations peuvent être modifiées ultérieurement</li>
                                        <li><i class="fas fa-check text-success me-2"></i>L'adresse sera utilisée pour la facturation</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Boutons d'action -->
                    <div class="d-flex justify-content-between">
                        <a href="index.php?page=clients" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-1"></i>
                            Annuler
                        </a>
                        <div>
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="fas fa-save me-1"></i>
                                Créer le client
                            </button>
                            <button type="submit" name="create_and_project" value="1" class="btn btn-success">
                                <i class="fas fa-plus me-1"></i>
                                Créer et ajouter un projet
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
                    Conseils pour créer un client
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>👤 Nom du client</h6>
                        <p class="small text-muted">
                            Utilisez le nom officiel de l'entreprise ou le nom complet de la personne. 
                            Ce nom apparaîtra sur les factures et documents.
                        </p>
                        
                        <h6>📧 Adresse email</h6>
                        <p class="small text-muted">
                            L'email doit être unique et valide. Il sera utilisé pour l'envoi des factures 
                            et communications importantes.
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h6>📞 Téléphone</h6>
                        <p class="small text-muted">
                            Le numéro de téléphone est optionnel mais recommandé pour faciliter 
                            les communications urgentes.
                        </p>
                        
                        <h6>🏠 Adresse</h6>
                        <p class="small text-muted">
                            L'adresse complète est importante pour la facturation et l'envoi 
                            de documents officiels.
                        </p>
                    </div>
                </div>
                
                <div class="alert alert-info mt-3 mb-0">
                    <i class="fas fa-rocket me-2"></i>
                    <strong>Astuce :</strong> Utilisez le bouton "Créer et ajouter un projet" pour créer directement 
                    un projet après la création du client !
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Validation de l'email en temps réel
    const emailInput = document.getElementById('email');
    emailInput.addEventListener('blur', function() {
        const email = this.value.trim();
        if (email) {
            // Validation basique du format email
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                this.setCustomValidity('Format d\'email invalide');
                this.classList.add('is-invalid');
            } else {
                this.setCustomValidity('');
                this.classList.remove('is-invalid');
            }
        }
    });
    
    // Formatage automatique du téléphone
    const phoneInput = document.getElementById('telephone');
    phoneInput.addEventListener('input', function() {
        let value = this.value.replace(/\D/g, ''); // Supprimer tout sauf les chiffres
        
        // Formatage français (01 23 45 67 89)
        if (value.length >= 2) {
            value = value.replace(/(\d{2})(?=\d)/g, '$1 ');
        }
        
        this.value = value;
    });
    
    // Validation du nom du client
    const nomInput = document.getElementById('nom_client');
    nomInput.addEventListener('input', function() {
        const nom = this.value.trim();
        if (nom.length < 2) {
            this.setCustomValidity('Le nom doit contenir au moins 2 caractères');
        } else {
            this.setCustomValidity('');
        }
    });
    
    // Auto-capitalisation du nom
    nomInput.addEventListener('blur', function() {
        const words = this.value.split(' ');
        const capitalizedWords = words.map(word => {
            if (word.length > 0) {
                return word.charAt(0).toUpperCase() + word.slice(1).toLowerCase();
            }
            return word;
        });
        this.value = capitalizedWords.join(' ');
    });
    
    // Validation du formulaire avant soumission
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const nom = document.getElementById('nom_client').value.trim();
        const email = document.getElementById('email').value.trim();
        
        if (!nom || nom.length < 2) {
            e.preventDefault();
            alert('Veuillez saisir un nom de client valide (au moins 2 caractères).');
            document.getElementById('nom_client').focus();
            return;
        }
        
        if (!email) {
            e.preventDefault();
            alert('Veuillez saisir une adresse email.');
            document.getElementById('email').focus();
            return;
        }
        
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            e.preventDefault();
            alert('Veuillez saisir une adresse email valide.');
            document.getElementById('email').focus();
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

.form-text {
    color: #6c757d;
    font-size: 0.875em;
}

.is-invalid {
    border-color: #dc3545;
}

.alert-info {
    background-color: #d1ecf1;
    border-color: #bee5eb;
    color: #0c5460;
}
</style>
