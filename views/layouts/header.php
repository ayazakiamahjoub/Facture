<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $appName ?> - Gestion de Projets</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link href="assets/css/style.css" rel="stylesheet">
    
    <!-- Chart.js pour les graphiques -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <?php if (isset($currentUser) && $currentUser): ?>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-project-diagram me-2"></i>
                <?= $appName ?>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?page=dashboard">
                            <i class="fas fa-tachometer-alt me-1"></i>
                            Dashboard
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?page=projets">
                            <i class="fas fa-project-diagram me-1"></i>
                            Projets
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?page=clients">
                            <i class="fas fa-users me-1"></i>
                            Clients
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?page=factures">
                            <i class="fas fa-file-invoice me-1"></i>
                            Factures
                        </a>
                    </li>
                    
                    <?php if ($isAdmin): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?page=users">
                            <i class="fas fa-user-cog me-1"></i>
                            Utilisateurs
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?page=dashboard&action=stats">
                            <i class="fas fa-chart-bar me-1"></i>
                            Statistiques
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="index.php?page=dashboard&action=recap">
                            <i class="fas fa-table me-1"></i>
                            Tableau Récapitulatif
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
                
                <!-- Menu utilisateur -->
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user me-1"></i>
                            <?= htmlspecialchars($currentUser['nom']) ?>
                            <?php if ($isAdmin): ?>
                                <span class="badge bg-warning text-dark ms-1">Admin</span>
                            <?php endif; ?>
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="index.php?page=auth&action=profile">
                                    <i class="fas fa-user-edit me-2"></i>
                                    Mon Profil
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="index.php?page=auth&action=changePassword">
                                    <i class="fas fa-key me-2"></i>
                                    Changer le mot de passe
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="index.php?page=auth&action=logout">
                                    <i class="fas fa-sign-out-alt me-2"></i>
                                    Déconnexion
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <?php endif; ?>
    
    <!-- Messages flash -->
    <?php 
    $flashMessage = null;
    if (isset($_SESSION['flash_message'])) {
        $flashMessage = $_SESSION['flash_message'];
        unset($_SESSION['flash_message']);
    }
    ?>
    
    <?php if ($flashMessage): ?>
    <div class="container-fluid mt-3">
        <div class="alert alert-<?= $flashMessage['type'] === 'error' ? 'danger' : $flashMessage['type'] ?> alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($flashMessage['message']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- Contenu principal -->
    <main class="<?= isset($currentUser) && $currentUser ? 'container-fluid mt-4' : '' ?>">
