<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

html, body {
    height: 100%;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

body {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    background-attachment: fixed;
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 100vh;
    padding: 20px;
    position: relative;
    overflow-x: hidden;
}

body::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background:
        radial-gradient(circle at 20% 80%, rgba(120, 119, 198, 0.3) 0%, transparent 50%),
        radial-gradient(circle at 80% 20%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
        radial-gradient(circle at 40% 40%, rgba(120, 119, 198, 0.2) 0%, transparent 50%);
    pointer-events: none;
}

.container {
    position: relative;
    z-index: 1;
    width: 100%;
    max-width: 480px;
}

.login-container {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 24px;
    box-shadow:
        0 32px 64px rgba(0, 0, 0, 0.12),
        0 0 0 1px rgba(255, 255, 255, 0.2);
    overflow: hidden;
    position: relative;
    animation: slideUp 0.6s ease-out;
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.login-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 3rem 2rem 2rem;
    text-align: center;
    position: relative;
}

.login-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, transparent 100%);
    pointer-events: none;
}

.login-header .logo-icon {
    width: 80px;
    height: 80px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.3);
}

.login-header h2 {
    font-weight: 700;
    font-size: 1.75rem;
    margin-bottom: 0.5rem;
    letter-spacing: -0.025em;
}

.login-header p {
    font-weight: 400;
    opacity: 0.9;
    font-size: 1rem;
}

.login-form {
    padding: 2.5rem;
}

.form-floating {
    margin-bottom: 1.5rem;
}

.form-floating > .form-control {
    height: 58px;
    border: 2px solid #e5e7eb;
    border-radius: 16px;
    background: #fafbfc;
    font-size: 1rem;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    padding: 1rem 1.25rem 0.25rem;
}

.form-floating > .form-control:focus {
    border-color: #667eea;
    background: #ffffff;
    box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
    transform: translateY(-1px);
}

.form-floating > label {
    padding: 1rem 1.25rem;
    color: #6b7280;
    font-weight: 500;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    border-radius: 16px;
    padding: 1rem 2rem;
    font-weight: 600;
    font-size: 1rem;
    height: 58px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}

.btn-primary::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: left 0.5s;
}

.btn-primary:hover::before {
    left: 100%;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 20px 40px rgba(102, 126, 234, 0.4);
}

.btn-primary:active {
    transform: translateY(0);
}

.alert {
    border-radius: 16px;
    border: none;
    padding: 1rem 1.25rem;
    margin-bottom: 1.5rem;
    font-weight: 500;
}

.alert-danger {
    background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
    color: #dc2626;
}

.demo-accounts {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border-radius: 20px;
    padding: 1.5rem;
    margin-top: 2rem;
    border: 1px solid #e2e8f0;
}

.demo-accounts h6 {
    color: #475569;
    font-weight: 600;
    margin-bottom: 1rem;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.demo-account {
    background: white;
    border-radius: 16px;
    padding: 1rem;
    margin-bottom: 0.75rem;
    border: 2px solid #e2e8f0;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}

.demo-account::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(102, 126, 234, 0.05), transparent);
    transition: left 0.3s;
}

.demo-account:hover::before {
    left: 100%;
}

.demo-account:hover {
    border-color: #667eea;
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(102, 126, 234, 0.15);
}

.demo-account:last-child {
    margin-bottom: 0;
}

.demo-account strong {
    color: #1e293b;
    font-weight: 600;
}

.demo-account small {
    color: #64748b;
    font-weight: 500;
}

.badge {
    font-weight: 600;
    padding: 0.375rem 0.75rem;
    border-radius: 8px;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.025em;
}

.form-check {
    margin: 1.5rem 0;
}

.form-check-input:checked {
    background-color: #667eea;
    border-color: #667eea;
}

.form-check-label {
    color: #6b7280;
    font-weight: 500;
}

.text-center a {
    color: #667eea;
    text-decoration: none;
    font-weight: 600;
    transition: color 0.3s ease;
}

.text-center a:hover {
    color: #764ba2;
}
</style>

<div class="container">
    <div class="login-container">
        <div class="login-header">
            <div class="logo-icon">
                <i class="fas fa-rocket fa-2x"></i>
            </div>
            <h2>Pioneer Tech</h2>
            <p>Gestion de projets nouvelle génération</p>
        </div>

        <div class="login-form">
                        <?php if (!empty($error)): ?>
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <?= htmlspecialchars($error) ?>
                        </div>
                        <?php endif; ?>

                        <?php if (!empty($success)): ?>
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-2"></i>
                            <?= htmlspecialchars($success) ?>
                        </div>
                        <?php endif; ?>

                        <form method="POST" action="">
                            <div class="form-floating">
                                <input type="email"
                                       class="form-control"
                                       id="email"
                                       name="email"
                                       value="<?= htmlspecialchars($email ?? '') ?>"
                                       required
                                       placeholder="votre.email@exemple.com">
                                <label for="email">
                                    <i class="fas fa-envelope me-2"></i>
                                    Adresse email
                                </label>
                            </div>

                            <div class="form-floating">
                                <input type="password"
                                       class="form-control"
                                       id="password"
                                       name="password"
                                       required
                                       placeholder="Votre mot de passe">
                                <label for="password">
                                    <i class="fas fa-lock me-2"></i>
                                    Mot de passe
                                </label>
                            </div>

                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="remember_me" name="remember_me">
                                <label class="form-check-label" for="remember_me">
                                    Se souvenir de moi
                                </label>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-sign-in-alt me-2"></i>
                                    Se connecter
                                </button>
                            </div>
                        </form>

                        <!-- Comptes de démonstration -->
                        <div class="demo-accounts">
                            <h6>
                                <i class="fas fa-users me-2"></i>
                                Comptes de démonstration
                            </h6>

                            <div class="demo-account" onclick="fillLogin('admin@pioneertech.com', 'password')">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>Administrateur</strong>
                                        <br><small>admin@pioneertech.com</small>
                                    </div>
                                    <span class="badge bg-danger">Admin</span>
                                </div>
                            </div>

                            <div class="demo-account" onclick="fillLogin('jean.dupont@pioneertech.com', 'password')">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>Jean Dupont</strong>
                                        <br><small>jean.dupont@pioneertech.com</small>
                                    </div>
                                    <span class="badge bg-primary">Employé</span>
                                </div>
                            </div>
                        </div>

                        <div class="text-center mt-3">
                            <p class="mb-0">
                                Pas encore de compte ?
                                <a href="index.php?page=auth&action=register" class="text-decoration-none">
                                    S'inscrire
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

<script>
// Fill login form with demo account
function fillLogin(email, password) {
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');

    emailInput.value = email;
    passwordInput.value = password;

    // Add visual feedback with modern animation
    emailInput.style.transform = 'scale(1.02)';
    passwordInput.style.transform = 'scale(1.02)';
    emailInput.style.borderColor = '#10b981';
    passwordInput.style.borderColor = '#10b981';

    setTimeout(() => {
        emailInput.style.transform = '';
        passwordInput.style.transform = '';
        emailInput.style.borderColor = '';
        passwordInput.style.borderColor = '';
    }, 800);

    // Focus on submit button
    document.querySelector('.btn-primary').focus();
}

// Enhanced form interactions
document.addEventListener('DOMContentLoaded', function() {
    // Auto-focus on email field
    document.getElementById('email').focus();

    // Add floating label animations
    const inputs = document.querySelectorAll('.form-floating input');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.classList.add('focused');
        });

        input.addEventListener('blur', function() {
            if (!this.value) {
                this.parentElement.classList.remove('focused');
            }
        });

        // Check if input has value on load
        if (input.value) {
            input.parentElement.classList.add('focused');
        }
    });

    // Add ripple effect to demo accounts
    const demoAccounts = document.querySelectorAll('.demo-account');
    demoAccounts.forEach(account => {
        account.addEventListener('click', function(e) {
            const ripple = document.createElement('span');
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;

            ripple.style.width = ripple.style.height = size + 'px';
            ripple.style.left = x + 'px';
            ripple.style.top = y + 'px';
            ripple.classList.add('ripple');

            this.appendChild(ripple);

            setTimeout(() => {
                ripple.remove();
            }, 600);
        });
    });

    // Form submission animation
    const form = document.querySelector('form');
    const submitBtn = document.querySelector('.btn-primary');

    form.addEventListener('submit', function() {
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Connexion...';
        submitBtn.disabled = true;
    });
});
</script>

<style>
.ripple {
    position: absolute;
    border-radius: 50%;
    background: rgba(102, 126, 234, 0.3);
    transform: scale(0);
    animation: ripple-animation 0.6s linear;
    pointer-events: none;
}

@keyframes ripple-animation {
    to {
        transform: scale(4);
        opacity: 0;
    }
}

.form-floating.focused .form-control {
    border-color: #667eea !important;
    box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1) !important;
}

/* Loading animation */
@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.fa-spin {
    animation: spin 1s linear infinite;
}

/* Responsive improvements */
@media (max-width: 480px) {
    .login-container {
        margin: 10px;
        border-radius: 20px;
    }

    .login-header {
        padding: 2rem 1.5rem 1.5rem;
    }

    .login-form {
        padding: 2rem 1.5rem;
    }

    .demo-accounts {
        padding: 1rem;
    }
}
</style>
