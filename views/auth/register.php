<style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .container {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .register-container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            overflow: hidden;
            max-width: 650px;
            width: 100%;
            margin: 0 auto;
        }
        
        .register-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        
        .register-form {
            padding: 2rem;
        }
        
        .form-control {
            border-radius: 10px;
            border: 2px solid #e9ecef;
            padding: 12px 15px;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 10px;
            padding: 12px 30px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }
        
        .input-group-text {
            background: #f8f9fa;
            border: 2px solid #e9ecef;
            border-right: none;
            border-radius: 10px 0 0 10px;
        }
        
        .input-group .form-control {
            border-left: none;
            border-radius: 0 10px 10px 0;
        }
        
        .alert {
            border-radius: 10px;
            border: none;
        }
        
        .password-strength {
            height: 5px;
            border-radius: 3px;
            margin-top: 5px;
            transition: all 0.3s ease;
        }
        
        .strength-weak { background-color: #dc3545; }
        .strength-medium { background-color: #ffc107; }
        .strength-strong { background-color: #28a745; }
        
        .role-card {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .role-card:hover {
            border-color: #667eea;
            background-color: #f8f9fa;
        }
        
        .role-card.selected {
            border-color: #667eea;
            background-color: rgba(102, 126, 234, 0.1);
        }
    </style>

<div class="container">
    <div class="register-container">
                    <div class="register-header">
                        <h2 class="mb-0">
                            <i class="fas fa-user-plus me-2"></i>
                            Créer un compte
                        </h2>
                        <p class="mb-0 mt-2 opacity-75">Pioneer Tech ProjectManager</p>
                    </div>
                    
                    <div class="register-form">
                        <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <h6><i class="fas fa-exclamation-triangle me-2"></i>Erreurs de validation :</h6>
                            <ul class="mb-0">
                                <?php foreach ($errors as $error): ?>
                                    <li><?= htmlspecialchars($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <?php endif; ?>
                        
                        <form method="POST" action="" id="registerForm">
                            <!-- Nom complet -->
                            <div class="mb-3">
                                <label for="nom" class="form-label fw-bold">
                                    <i class="fas fa-user me-1"></i>
                                    Nom complet
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-user"></i>
                                    </span>
                                    <input type="text" 
                                           class="form-control" 
                                           id="nom" 
                                           name="nom" 
                                           value="<?= htmlspecialchars($formData['nom'] ?? '') ?>"
                                           required
                                           placeholder="Votre nom complet">
                                </div>
                            </div>
                            
                            <!-- Email -->
                            <div class="mb-3">
                                <label for="email" class="form-label fw-bold">
                                    <i class="fas fa-envelope me-1"></i>
                                    Adresse email
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-envelope"></i>
                                    </span>
                                    <input type="email" 
                                           class="form-control" 
                                           id="email" 
                                           name="email" 
                                           value="<?= htmlspecialchars($formData['email'] ?? '') ?>"
                                           required
                                           placeholder="votre.email@exemple.com">
                                </div>
                                <div class="form-text">
                                    Cette adresse sera utilisée pour vous connecter
                                </div>
                            </div>
                            
                            <!-- Mot de passe -->
                            <div class="mb-3">
                                <label for="mot_de_passe" class="form-label fw-bold">
                                    <i class="fas fa-lock me-1"></i>
                                    Mot de passe
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                    <input type="password" 
                                           class="form-control" 
                                           id="mot_de_passe" 
                                           name="mot_de_passe" 
                                           required
                                           placeholder="Choisissez un mot de passe sécurisé">
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <div class="password-strength" id="passwordStrength"></div>
                                <div class="form-text">
                                    Minimum 6 caractères, incluez des lettres et des chiffres
                                </div>
                            </div>
                            
                            <!-- Confirmation mot de passe -->
                            <div class="mb-3">
                                <label for="confirm_password" class="form-label fw-bold">
                                    <i class="fas fa-lock me-1"></i>
                                    Confirmer le mot de passe
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                    <input type="password" 
                                           class="form-control" 
                                           id="confirm_password" 
                                           name="confirm_password" 
                                           required
                                           placeholder="Répétez votre mot de passe">
                                    <button class="btn btn-outline-secondary" type="button" id="toggleConfirmPassword">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <div class="invalid-feedback" id="passwordMismatch">
                                    Les mots de passe ne correspondent pas
                                </div>
                            </div>
                            
                            <!-- Rôle -->
                            <div class="mb-4">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-user-tag me-1"></i>
                                    Rôle dans l'organisation
                                </label>
                                <div class="row">
                                    <div class="col-md-6 mb-2">
                                        <div class="role-card" data-role="admin">
                                            <input type="radio" name="role" value="admin" id="role_admin" class="d-none" 
                                                   <?= (isset($formData['role']) && $formData['role'] === 'admin') ? 'checked' : '' ?>>
                                            <div class="text-center">
                                                <i class="fas fa-user-shield fa-2x text-danger mb-2"></i>
                                                <h6>Administrateur</h6>
                                                <small class="text-muted">Accès complet au système</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <div class="role-card" data-role="employé">
                                            <input type="radio" name="role" value="employé" id="role_employe" class="d-none"
                                                   <?= (isset($formData['role']) && $formData['role'] === 'employé') ? 'checked' : 'checked' ?>>
                                            <div class="text-center">
                                                <i class="fas fa-user fa-2x text-primary mb-2"></i>
                                                <h6>Employé</h6>
                                                <small class="text-muted">Accès aux projets assignés</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Boutons -->
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-user-plus me-2"></i>
                                    Créer le compte
                                </button>
                            </div>
                            
                            <div class="text-center mt-3">
                                <p class="mb-0">
                                    Déjà un compte ? 
                                    <a href="index.php?page=auth&action=login" class="text-decoration-none">
                                        Se connecter
                                    </a>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

<script>
        document.addEventListener('DOMContentLoaded', function() {
            // Gestion de l'affichage des mots de passe
            const togglePassword = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('mot_de_passe');
            const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
            const confirmPasswordInput = document.getElementById('confirm_password');
            
            togglePassword.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                this.querySelector('i').classList.toggle('fa-eye');
                this.querySelector('i').classList.toggle('fa-eye-slash');
            });
            
            toggleConfirmPassword.addEventListener('click', function() {
                const type = confirmPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                confirmPasswordInput.setAttribute('type', type);
                this.querySelector('i').classList.toggle('fa-eye');
                this.querySelector('i').classList.toggle('fa-eye-slash');
            });
            
            // Indicateur de force du mot de passe
            const passwordStrength = document.getElementById('passwordStrength');
            
            passwordInput.addEventListener('input', function() {
                const password = this.value;
                let strength = 0;
                
                if (password.length >= 6) strength++;
                if (password.match(/[a-z]/)) strength++;
                if (password.match(/[A-Z]/)) strength++;
                if (password.match(/[0-9]/)) strength++;
                if (password.match(/[^a-zA-Z0-9]/)) strength++;
                
                passwordStrength.className = 'password-strength';
                if (strength < 2) {
                    passwordStrength.classList.add('strength-weak');
                } else if (strength < 4) {
                    passwordStrength.classList.add('strength-medium');
                } else {
                    passwordStrength.classList.add('strength-strong');
                }
            });
            
            // Vérification de la correspondance des mots de passe
            const passwordMismatch = document.getElementById('passwordMismatch');
            
            function checkPasswordMatch() {
                if (confirmPasswordInput.value && passwordInput.value !== confirmPasswordInput.value) {
                    confirmPasswordInput.classList.add('is-invalid');
                    passwordMismatch.style.display = 'block';
                } else {
                    confirmPasswordInput.classList.remove('is-invalid');
                    passwordMismatch.style.display = 'none';
                }
            }
            
            passwordInput.addEventListener('input', checkPasswordMatch);
            confirmPasswordInput.addEventListener('input', checkPasswordMatch);
            
            // Gestion de la sélection des rôles
            const roleCards = document.querySelectorAll('.role-card');
            
            roleCards.forEach(card => {
                card.addEventListener('click', function() {
                    roleCards.forEach(c => c.classList.remove('selected'));
                    this.classList.add('selected');
                    
                    const role = this.getAttribute('data-role');
                    document.querySelector(`input[value="${role}"]`).checked = true;
                });
            });
            
            // Sélection initiale
            const checkedRole = document.querySelector('input[name="role"]:checked');
            if (checkedRole) {
                document.querySelector(`[data-role="${checkedRole.value}"]`).classList.add('selected');
            }
            
            // Validation du formulaire
            const form = document.getElementById('registerForm');
            form.addEventListener('submit', function(e) {
                const nom = document.getElementById('nom').value.trim();
                const email = document.getElementById('email').value.trim();
                const password = passwordInput.value;
                const confirmPassword = confirmPasswordInput.value;
                
                if (!nom || nom.length < 2) {
                    e.preventDefault();
                    alert('Veuillez saisir un nom valide (au moins 2 caractères).');
                    document.getElementById('nom').focus();
                    return;
                }
                
                if (!email) {
                    e.preventDefault();
                    alert('Veuillez saisir une adresse email.');
                    document.getElementById('email').focus();
                    return;
                }
                
                if (password.length < 6) {
                    e.preventDefault();
                    alert('Le mot de passe doit contenir au moins 6 caractères.');
                    passwordInput.focus();
                    return;
                }
                
                if (password !== confirmPassword) {
                    e.preventDefault();
                    alert('Les mots de passe ne correspondent pas.');
                    confirmPasswordInput.focus();
                    return;
                }
                
                if (!document.querySelector('input[name="role"]:checked')) {
                    e.preventDefault();
                    alert('Veuillez sélectionner un rôle.');
                    return;
                }
            });
        });
</script>
