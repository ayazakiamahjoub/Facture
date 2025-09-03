<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-lg">
                <div class="card-body text-center p-5">
                    <div class="mb-4">
                        <i class="fas fa-exclamation-triangle fa-4x text-danger mb-3"></i>
                        <h2 class="card-title text-danger">Erreur</h2>
                    </div>
                    
                    <div class="alert alert-danger" role="alert">
                        <?= htmlspecialchars($message) ?>
                    </div>
                    
                    <div class="mt-4">
                        <a href="javascript:history.back()" class="btn btn-secondary me-2">
                            <i class="fas fa-arrow-left me-1"></i>
                            Retour
                        </a>
                        
                        <a href="index.php" class="btn btn-primary">
                            <i class="fas fa-home me-1"></i>
                            Accueil
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
body {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
    display: flex;
    align-items: center;
}

.card {
    border: none;
    border-radius: 15px;
}

.card-body {
    border-radius: 15px;
}
</style>
