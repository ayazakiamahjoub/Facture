/**
 * JavaScript principal pour ProjectManager
 */

// Configuration globale
const ProjectManager = {
    config: {
        baseUrl: window.location.origin + window.location.pathname.replace('index.php', ''),
        ajaxTimeout: 30000,
        dateFormat: 'dd/mm/yyyy'
    },
    
    // Initialisation
    init: function() {
        this.setupEventListeners();
        this.initializeComponents();
        this.setupAjaxDefaults();
    },
    
    // Configuration des événements
    setupEventListeners: function() {
        // Confirmation de suppression
        $(document).on('click', '.btn-delete', function(e) {
            e.preventDefault();
            const url = $(this).attr('href');
            const itemName = $(this).data('item-name') || 'cet élément';
            
            ProjectManager.confirmDelete(url, itemName);
        });
        
        // Auto-hide des alertes
        $('.alert').each(function() {
            const alert = $(this);
            if (!alert.hasClass('alert-permanent')) {
                setTimeout(() => {
                    alert.fadeOut();
                }, 5000);
            }
        });
        
        // Recherche en temps réel
        $('.search-input').on('input', ProjectManager.debounce(function() {
            const query = $(this).val();
            const target = $(this).data('target');
            if (query.length >= 2) {
                ProjectManager.performSearch(query, target);
            }
        }, 300));
        
        // Validation des formulaires
        $('form[data-validate="true"]').on('submit', function(e) {
            if (!ProjectManager.validateForm($(this))) {
                e.preventDefault();
            }
        });
        
        // Tooltips Bootstrap
        $('[data-bs-toggle="tooltip"]').tooltip();
        
        // Popovers Bootstrap
        $('[data-bs-toggle="popover"]').popover();
    },
    
    // Initialisation des composants
    initializeComponents: function() {
        // Datepickers
        if (typeof flatpickr !== 'undefined') {
            flatpickr('.datepicker', {
                dateFormat: 'd/m/Y',
                locale: 'fr'
            });
        }
        
        // Select2 pour les sélecteurs avancés
        if (typeof $.fn.select2 !== 'undefined') {
            $('.select2').select2({
                theme: 'bootstrap-5',
                placeholder: 'Sélectionner...'
            });
        }
        
        // DataTables
        if (typeof $.fn.DataTable !== 'undefined') {
            $('.data-table').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/fr-FR.json'
                },
                responsive: true,
                pageLength: 25,
                order: [[0, 'desc']]
            });
        }
    },
    
    // Configuration AJAX par défaut
    setupAjaxDefaults: function() {
        $.ajaxSetup({
            timeout: this.config.ajaxTimeout,
            beforeSend: function() {
                ProjectManager.showLoading();
            },
            complete: function() {
                ProjectManager.hideLoading();
            },
            error: function(xhr, status, error) {
                ProjectManager.handleAjaxError(xhr, status, error);
            }
        });
    },
    
    // Confirmation de suppression
    confirmDelete: function(url, itemName) {
        if (confirm(`Êtes-vous sûr de vouloir supprimer ${itemName} ? Cette action est irréversible.`)) {
            window.location.href = url;
        }
    },
    
    // Recherche
    performSearch: function(query, target) {
        $.ajax({
            url: 'index.php',
            method: 'GET',
            data: {
                page: 'search',
                action: 'ajax',
                q: query,
                target: target
            },
            success: function(response) {
                $(target).html(response);
            }
        });
    },
    
    // Validation de formulaire
    validateForm: function(form) {
        let isValid = true;
        
        // Supprimer les messages d'erreur précédents
        form.find('.is-invalid').removeClass('is-invalid');
        form.find('.invalid-feedback').remove();
        
        // Validation des champs requis
        form.find('[required]').each(function() {
            const field = $(this);
            if (!field.val().trim()) {
                ProjectManager.showFieldError(field, 'Ce champ est requis.');
                isValid = false;
            }
        });
        
        // Validation des emails
        form.find('input[type="email"]').each(function() {
            const field = $(this);
            const email = field.val().trim();
            if (email && !ProjectManager.isValidEmail(email)) {
                ProjectManager.showFieldError(field, 'Format d\'email invalide.');
                isValid = false;
            }
        });
        
        // Validation des mots de passe
        const password = form.find('input[name="mot_de_passe"]');
        const confirmPassword = form.find('input[name="confirm_password"]');
        
        if (password.length && confirmPassword.length) {
            if (password.val() !== confirmPassword.val()) {
                ProjectManager.showFieldError(confirmPassword, 'Les mots de passe ne correspondent pas.');
                isValid = false;
            }
        }
        
        return isValid;
    },
    
    // Afficher une erreur de champ
    showFieldError: function(field, message) {
        field.addClass('is-invalid');
        field.after(`<div class="invalid-feedback">${message}</div>`);
    },
    
    // Validation d'email
    isValidEmail: function(email) {
        const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return regex.test(email);
    },
    
    // Afficher le loading
    showLoading: function() {
        if (!$('#loading-overlay').length) {
            $('body').append(`
                <div id="loading-overlay" class="position-fixed top-0 start-0 w-100 h-100 d-flex justify-content-center align-items-center" style="background: rgba(0,0,0,0.5); z-index: 9999;">
                    <div class="spinner-border text-light" role="status">
                        <span class="visually-hidden">Chargement...</span>
                    </div>
                </div>
            `);
        }
    },
    
    // Masquer le loading
    hideLoading: function() {
        $('#loading-overlay').remove();
    },
    
    // Gestion des erreurs AJAX
    handleAjaxError: function(xhr, status, error) {
        let message = 'Une erreur est survenue.';
        
        if (xhr.status === 404) {
            message = 'Ressource non trouvée.';
        } else if (xhr.status === 403) {
            message = 'Accès refusé.';
        } else if (xhr.status === 500) {
            message = 'Erreur serveur.';
        } else if (status === 'timeout') {
            message = 'Délai d\'attente dépassé.';
        }
        
        ProjectManager.showAlert(message, 'danger');
    },
    
    // Afficher une alerte
    showAlert: function(message, type = 'info') {
        const alertHtml = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        
        $('.container-fluid').first().prepend(alertHtml);
        
        // Auto-hide après 5 secondes
        setTimeout(() => {
            $('.alert').first().fadeOut();
        }, 5000);
    },
    
    // Debounce function
    debounce: function(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    },
    
    // Formater un montant
    formatAmount: function(amount) {
        return new Intl.NumberFormat('fr-FR', {
            style: 'currency',
            currency: 'EUR'
        }).format(amount);
    },
    
    // Formater une date
    formatDate: function(date) {
        return new Intl.DateTimeFormat('fr-FR').format(new Date(date));
    },
    
    // Copier dans le presse-papiers
    copyToClipboard: function(text) {
        navigator.clipboard.writeText(text).then(() => {
            ProjectManager.showAlert('Copié dans le presse-papiers !', 'success');
        }).catch(() => {
            ProjectManager.showAlert('Erreur lors de la copie.', 'danger');
        });
    },
    
    // Exporter des données
    exportData: function(format, data, filename) {
        if (format === 'csv') {
            ProjectManager.exportToCSV(data, filename);
        } else if (format === 'json') {
            ProjectManager.exportToJSON(data, filename);
        }
    },
    
    // Exporter en CSV
    exportToCSV: function(data, filename) {
        const csv = ProjectManager.arrayToCSV(data);
        const blob = new Blob([csv], { type: 'text/csv' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = filename + '.csv';
        a.click();
        window.URL.revokeObjectURL(url);
    },
    
    // Exporter en JSON
    exportToJSON: function(data, filename) {
        const json = JSON.stringify(data, null, 2);
        const blob = new Blob([json], { type: 'application/json' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = filename + '.json';
        a.click();
        window.URL.revokeObjectURL(url);
    },
    
    // Convertir un tableau en CSV
    arrayToCSV: function(data) {
        if (!data.length) return '';
        
        const headers = Object.keys(data[0]);
        const csvContent = [
            headers.join(','),
            ...data.map(row => headers.map(header => `"${row[header] || ''}"`).join(','))
        ].join('\n');
        
        return csvContent;
    }
};

// Initialisation au chargement de la page
$(document).ready(function() {
    ProjectManager.init();
});

// Fonctions globales utilitaires
window.showAlert = ProjectManager.showAlert;
window.formatAmount = ProjectManager.formatAmount;
window.formatDate = ProjectManager.formatDate;
window.copyToClipboard = ProjectManager.copyToClipboard;
