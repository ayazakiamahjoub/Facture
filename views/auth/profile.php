<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>
                <i class="fas fa-user-edit me-2"></i>
                Mon Profil
            </h1>
            <div>
                <a href="index.php?page=dashboard" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i>
                    Retour au dashboard
                </a>
                <a href="index.php?page=auth&action=changePassword" class="btn btn-warning">
                    <i class="fas fa-key me-1"></i>
                    Changer le mot de passe
                </a>
            </div>
        </div>
    </div>
</div>

<?php if (!empty($success)): ?>
<div class="row mb-4">
    <div class="col-12">
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle me-2"></i>
            <?= htmlspecialchars($success) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
</div>
<?php endif; ?>

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
                    Modifier mes informations
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nom" class="form-label">
                                    <i class="fas fa-user me-1"></i>
                                    Nom complet <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control <?= isset($errors['nom']) ? 'is-invalid' : '' ?>" 
                                       id="nom" 
                                       name="nom" 
                                       value="<?= htmlspecialchars($formData['nom'] ?? $currentUser['nom']) ?>"
                                       required
                                       placeholder="Votre nom complet">
                                <?php if (isset($errors['nom'])): ?>
                                    <div class="invalid-feedback">
                                        <?= htmlspecialchars($errors['nom']) ?>
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
                                       value="<?= htmlspecialchars($formData['email'] ?? $currentUser['email']) ?>"
                                       required
                                       placeholder="votre.email@exemple.com">
                                <?php if (isset($errors['email'])): ?>
                                    <div class="invalid-feedback">
                                        <?= htmlspecialchars($errors['email']) ?>
                                    </div>
                                <?php endif; ?>
                                <div class="form-text">
                                    Cet email sera utilisé pour vous connecter.
                                </div>
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
                                       class="form-control" 
                                       id="telephone" 
                                       name="telephone" 
                                       value="<?= htmlspecialchars($formData['telephone'] ?? $currentUser['telephone'] ?? '') ?>"
                                       placeholder="+33 1 23 45 67 89">
                                <div class="form-text">
                                    Numéro de téléphone professionnel (optionnel).
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="poste" class="form-label">
                                    <i class="fas fa-briefcase me-1"></i>
                                    Poste / Fonction
                                </label>
                                <input type="text" 
                                       class="form-control" 
                                       id="poste" 
                                       name="poste" 
                                       value="<?= htmlspecialchars($formData['poste'] ?? $currentUser['poste'] ?? '') ?>"
                                       placeholder="Développeur, Chef de projet, etc.">
                                <div class="form-text">
                                    Votre fonction dans l'entreprise (optionnel).
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="bio" class="form-label">
                            <i class="fas fa-user-circle me-1"></i>
                            Présentation
                        </label>
                        <textarea class="form-control" 
                                  id="bio" 
                                  name="bio" 
                                  rows="3"
                                  placeholder="Quelques mots sur vous, vos compétences, votre expérience..."><?= htmlspecialchars($formData['bio'] ?? $currentUser['bio'] ?? '') ?></textarea>
                        <div class="form-text">
                            Cette information sera visible par les autres membres de l'équipe.
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>
                                Enregistrer les modifications
                            </button>
                            <a href="index.php?page=dashboard" class="btn btn-secondary ms-2">
                                <i class="fas fa-times me-1"></i>
                                Annuler
                            </a>
                        </div>
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
                    <div class="avatar-xl mx-auto mb-3">
                        <div class="avatar-initial bg-<?= $isAdmin ? 'warning' : 'primary' ?> rounded-circle">
                            <?= strtoupper(substr($currentUser['nom'], 0, 2)) ?>
                        </div>
                    </div>
                    <h5><?= htmlspecialchars($currentUser['nom']) ?></h5>
                    <span class="badge bg-<?= $isAdmin ? 'warning' : 'primary' ?> mb-2">
                        <?= $isAdmin ? 'Administrateur' : 'Employé' ?>
                    </span>
                    <?php if ($currentUser['actif']): ?>
                        <br><span class="badge bg-success">
                            <i class="fas fa-check me-1"></i>
                            Compte actif
                        </span>
                    <?php else: ?>
                        <br><span class="badge bg-secondary">
                            <i class="fas fa-times me-1"></i>
                            Compte inactif
                        </span>
                    <?php endif; ?>
                </div>
                
                <table class="table table-borderless table-sm">
                    <tr>
                        <td><strong>Email :</strong></td>
                        <td><?= htmlspecialchars($currentUser['email']) ?></td>
                    </tr>
                    <tr>
                        <td><strong>Rôle :</strong></td>
                        <td><?= ucfirst($currentUser['role']) ?></td>
                    </tr>
                    <?php if (!empty($currentUser['telephone'])): ?>
                    <tr>
                        <td><strong>Téléphone :</strong></td>
                        <td><?= htmlspecialchars($currentUser['telephone']) ?></td>
                    </tr>
                    <?php endif; ?>
                    <?php if (!empty($currentUser['poste'])): ?>
                    <tr>
                        <td><strong>Poste :</strong></td>
                        <td><?= htmlspecialchars($currentUser['poste']) ?></td>
                    </tr>
                    <?php endif; ?>
                    <tr>
                        <td><strong>Membre depuis :</strong></td>
                        <td><?= date('d/m/Y', strtotime($currentUser['date_creation'])) ?></td>
                    </tr>
                    <?php if (!empty($currentUser['date_modification'])): ?>
                    <tr>
                        <td><strong>Dernière modif :</strong></td>
                        <td><?= date('d/m/Y H:i', strtotime($currentUser['date_modification'])) ?></td>
                    </tr>
                    <?php endif; ?>
                </table>
            </div>
        </div>
        
        <!-- Statistiques personnelles -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-bar me-2"></i>
                    Mes statistiques
                </h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6 mb-3">
                        <h4 class="text-primary"><?= $stats['projets_assignes'] ?? 0 ?></h4>
                        <small class="text-muted">Projets assignés</small>
                    </div>
                    <div class="col-6 mb-3">
                        <h4 class="text-success"><?= $stats['projets_termines'] ?? 0 ?></h4>
                        <small class="text-muted">Projets terminés</small>
                    </div>
                </div>
                
                <?php if ($isAdmin): ?>
                <hr>
                <div class="row text-center">
                    <div class="col-6 mb-3">
                        <h4 class="text-info"><?= $stats['total_clients'] ?? 0 ?></h4>
                        <small class="text-muted">Clients</small>
                    </div>
                    <div class="col-6 mb-3">
                        <h4 class="text-warning"><?= $stats['total_factures'] ?? 0 ?></h4>
                        <small class="text-muted">Factures</small>
                    </div>
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
                    <a href="index.php?page=auth&action=changePassword" class="btn btn-outline-warning">
                        <i class="fas fa-key me-1"></i>
                        Changer le mot de passe
                    </a>
                    
                    <a href="index.php?page=projets" class="btn btn-outline-primary">
                        <i class="fas fa-project-diagram me-1"></i>
                        Mes projets
                    </a>
                    
                    <?php if ($isAdmin): ?>
                    <a href="index.php?page=users" class="btn btn-outline-info">
                        <i class="fas fa-users me-1"></i>
                        Gérer les utilisateurs
                    </a>
                    
                    <a href="index.php?page=dashboard&action=stats" class="btn btn-outline-success">
                        <i class="fas fa-chart-line me-1"></i>
                        Statistiques globales
                    </a>
                    <?php endif; ?>
                    
                    <hr>
                    
                    <a href="index.php?page=auth&action=logout" class="btn btn-outline-danger">
                        <i class="fas fa-sign-out-alt me-1"></i>
                        Se déconnecter
                    </a>
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
                    Conseils pour votre profil
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>📝 Informations importantes</h6>
                        <ul class="small text-muted">
                            <li>Votre nom et email sont obligatoires</li>
                            <li>L'email doit être unique dans le système</li>
                            <li>Votre présentation est visible par l'équipe</li>
                            <li>Le téléphone et poste sont optionnels</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6>🔒 Sécurité</h6>
                        <ul class="small text-muted">
                            <li>Changez régulièrement votre mot de passe</li>
                            <li>Utilisez un mot de passe fort</li>
                            <li>Ne partagez jamais vos identifiants</li>
                            <li>Déconnectez-vous sur les postes partagés</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.avatar-xl {
    width: 100px;
    height: 100px;
}

.avatar-initial {
    width: 100px;
    height: 100px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    color: white;
    font-size: 2rem;
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
