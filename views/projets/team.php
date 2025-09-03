<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>
                <i class="fas fa-users me-2"></i>
                Équipe du projet : <?= htmlspecialchars($projet['titre_projet']) ?>
            </h1>
            <div>
                <a href="index.php?page=projets&action=view&id=<?= $projet['id'] ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i>
                    Retour au projet
                </a>
                <a href="index.php?page=projets" class="btn btn-outline-secondary">
                    <i class="fas fa-list me-1"></i>
                    Liste des projets
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
            <h6><i class="fas fa-exclamation-triangle me-2"></i>Erreurs :</h6>
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
    <!-- Ajouter un membre -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-user-plus me-2"></i>
                    Ajouter un membre
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="">
                    <input type="hidden" name="action" value="add_member">
                    
                    <div class="mb-3">
                        <label for="user_id" class="form-label">
                            <i class="fas fa-user me-1"></i>
                            Utilisateur
                        </label>
                        <select class="form-select" id="user_id" name="user_id" required>
                            <option value="">Sélectionner un utilisateur</option>
                            <?php foreach (($availableUsers ?? []) as $user): ?>
                                <option value="<?= $user['id'] ?>">
                                    <?= htmlspecialchars($user['nom']) ?> 
                                    <small>(<?= htmlspecialchars($user['email']) ?>)</small>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (empty($availableUsers ?? [])): ?>
                            <div class="form-text text-muted">
                                Tous les utilisateurs sont déjà assignés à ce projet.
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="mb-3">
                        <label for="role" class="form-label">
                            <i class="fas fa-user-tag me-1"></i>
                            Rôle dans le projet
                        </label>
                        <select class="form-select" id="role" name="role" required>
                            <option value="">Sélectionner un rôle</option>
                            <option value="chef">Chef de projet</option>
                            <option value="membre">Membre de l'équipe</option>
                        </select>
                        <div class="form-text">
                            Le chef de projet peut modifier le projet et gérer l'équipe.
                        </div>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary" <?= empty($availableUsers ?? []) ? 'disabled' : '' ?>>
                            <i class="fas fa-plus me-1"></i>
                            Ajouter à l'équipe
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Informations du projet -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    Informations du projet
                </h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless table-sm">
                    <tr>
                        <td><strong>Client :</strong></td>
                        <td>
                            <?php if (isset($projet['nom_client'])): ?>
                                <a href="index.php?page=clients&action=view&id=<?= $projet['id_client'] ?>">
                                    <?= htmlspecialchars($projet['nom_client']) ?>
                                </a>
                            <?php else: ?>
                                <a href="index.php?page=clients&action=view&id=<?= $projet['id_client'] ?>">
                                    Client ID: <?= $projet['id_client'] ?>
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Statut :</strong></td>
                        <td>
                            <span class="badge bg-<?= $projet['statut'] === 'en cours' ? 'primary' : ($projet['statut'] === 'terminé' ? 'success' : 'secondary') ?>">
                                <?= ucfirst($projet['statut']) ?>
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Début :</strong></td>
                        <td><?= $projet['date_debut'] ? date('d/m/Y', strtotime($projet['date_debut'])) : 'Non défini' ?></td>
                    </tr>
                    <tr>
                        <td><strong>Échéance :</strong></td>
                        <td><?= $projet['date_fin_prevue'] ? date('d/m/Y', strtotime($projet['date_fin_prevue'])) : 'Non définie' ?></td>
                    </tr>
                    <?php if ($projet['budget']): ?>
                    <tr>
                        <td><strong>Budget :</strong></td>
                        <td><?= number_format($projet['budget'], 0, ',', ' ') ?> €</td>
                    </tr>
                    <?php endif; ?>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Équipe actuelle -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-users me-2"></i>
                    Équipe actuelle
                    <span class="badge bg-primary ms-2"><?= count($equipe ?? []) ?></span>
                </h5>
                <?php if (!empty($equipe)): ?>
                    <small class="text-muted">
                        <i class="fas fa-info-circle me-1"></i>
                        Cliquez sur les actions pour modifier les rôles
                    </small>
                <?php endif; ?>
            </div>
            <div class="card-body">
                <?php if (empty($equipe ?? [])): ?>
                    <div class="text-center py-5">
                        <i class="fas fa-users fa-4x text-muted mb-3"></i>
                        <h5 class="text-muted">Aucun membre dans l'équipe</h5>
                        <p class="text-muted">
                            Commencez par ajouter des membres à ce projet en utilisant le formulaire ci-contre.
                        </p>
                        <?php if (!empty($availableUsers ?? [])): ?>
                            <button class="btn btn-primary" onclick="document.getElementById('user_id').focus()">
                                <i class="fas fa-plus me-1"></i>
                                Ajouter le premier membre
                            </button>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <div class="row">
                        <?php foreach (($equipe ?? []) as $membre): ?>
                        <div class="col-md-6 mb-4">
                            <div class="card border-<?= $membre['role_projet'] === 'chef' ? 'warning' : 'info' ?>">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="avatar me-3">
                                            <div class="avatar-initial bg-<?= ($membre['role_projet'] ?? 'membre') === 'chef' ? 'warning' : 'info' ?> rounded-circle">
                                                <?= strtoupper(substr(($membre['nom'] ?? 'U'), 0, 1)) ?>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">
                                                <?php if (isset($membre['id_utilisateur']) && isset($membre['nom'])): ?>
                                                    <a href="index.php?page=users&action=view&id=<?= $membre['id_utilisateur'] ?>">
                                                        <?= htmlspecialchars($membre['nom']) ?>
                                                    </a>
                                                <?php else: ?>
                                                    Utilisateur inconnu
                                                <?php endif; ?>
                                            </h6>
                                            <small class="text-muted"><?= htmlspecialchars($membre['email'] ?? 'Email non disponible') ?></small>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <span class="badge bg-<?= ($membre['role_projet'] ?? 'membre') === 'chef' ? 'warning' : 'info' ?> me-2">
                                            <?= ($membre['role_projet'] ?? 'membre') === 'chef' ? 'Chef de projet' : 'Membre' ?>
                                        </span>
                                        <?php if (isset($membre['date_assignation'])): ?>
                                            <small class="text-muted">
                                                depuis le <?= date('d/m/Y', strtotime($membre['date_assignation'])) ?>
                                            </small>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <?php if (isset($membre['id_utilisateur'])): ?>
                                    <div class="btn-group w-100" role="group">
                                        <?php if (($membre['role_projet'] ?? 'membre') === 'membre'): ?>
                                            <form method="POST" action="" class="flex-fill">
                                                <input type="hidden" name="action" value="promote">
                                                <input type="hidden" name="user_id" value="<?= $membre['id_utilisateur'] ?>">
                                                <button type="submit" class="btn btn-sm btn-outline-warning w-100" title="Promouvoir chef">
                                                    <i class="fas fa-arrow-up me-1"></i>
                                                    Promouvoir chef
                                                </button>
                                            </form>
                                        <?php else: ?>
                                            <form method="POST" action="" class="flex-fill">
                                                <input type="hidden" name="action" value="demote">
                                                <input type="hidden" name="user_id" value="<?= $membre['id_utilisateur'] ?>">
                                                <button type="submit" class="btn btn-sm btn-outline-info w-100" title="Rétrograder membre">
                                                    <i class="fas fa-arrow-down me-1"></i>
                                                    Rétrograder membre
                                                </button>
                                            </form>
                                        <?php endif; ?>

                                        <form method="POST" action="" class="flex-fill ms-1">
                                            <input type="hidden" name="action" value="remove">
                                            <input type="hidden" name="user_id" value="<?= $membre['id_utilisateur'] ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-danger w-100 btn-delete"
                                                    title="Retirer de l'équipe"
                                                    data-item-name="<?= htmlspecialchars($membre['nom'] ?? 'cet utilisateur') ?> de l'équipe">
                                                <i class="fas fa-times me-1"></i>
                                                Retirer
                                            </button>
                                        </form>
                                    </div>
                                    <?php else: ?>
                                        <div class="text-muted small">
                                            <i class="fas fa-exclamation-triangle me-1"></i>
                                            Données utilisateur incomplètes
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <!-- Statistiques de l'équipe -->
                    <div class="row mt-4">
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h4 class="text-primary"><?= count($equipe ?? []) ?></h4>
                                    <small class="text-muted">Total membres</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h4 class="text-warning">
                                        <?= count(array_filter(($equipe ?? []), function($m) { return $m['role_projet'] === 'chef'; })) ?>
                                    </h4>
                                    <small class="text-muted">Chefs de projet</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h4 class="text-info">
                                        <?= count(array_filter(($equipe ?? []), function($m) { return $m['role_projet'] === 'membre'; })) ?>
                                    </h4>
                                    <small class="text-muted">Membres</small>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
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
                    Gestion de l'équipe
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>👑 Chef de projet</h6>
                        <p class="small text-muted">
                            Le chef de projet peut modifier les informations du projet, gérer l'équipe 
                            et accéder à toutes les fonctionnalités. Un projet peut avoir plusieurs chefs.
                        </p>
                        
                        <h6>👤 Membre de l'équipe</h6>
                        <p class="small text-muted">
                            Les membres peuvent consulter le projet et ses informations, mais ne peuvent 
                            pas le modifier ni gérer l'équipe.
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h6>🔄 Actions disponibles</h6>
                        <ul class="small text-muted">
                            <li><strong>Promouvoir :</strong> Transformer un membre en chef de projet</li>
                            <li><strong>Rétrograder :</strong> Transformer un chef en membre</li>
                            <li><strong>Retirer :</strong> Supprimer complètement de l'équipe</li>
                        </ul>
                        
                        <div class="alert alert-warning alert-sm mt-3">
                            <i class="fas fa-exclamation-triangle me-1"></i>
                            <strong>Attention :</strong> Retirer un membre supprime définitivement son accès au projet.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.avatar {
    width: 50px;
    height: 50px;
}

.avatar-initial {
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    color: white;
    font-size: 1.2rem;
}

.alert-sm {
    padding: 0.5rem 0.75rem;
    font-size: 0.875rem;
}

.card.border-warning {
    border-width: 2px;
}

.card.border-info {
    border-width: 2px;
}
</style>
