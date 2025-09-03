# Pioneer Tech ProjectManager - Application de Gestion de Projets

Une application web complète développée par Pioneer Tech pour la gestion de projets, clients, équipes et factures, développée en PHP avec une architecture MVC moderne.

## 🚀 Fonctionnalités

### Gestion des Utilisateurs
- Système d'authentification sécurisé
- Gestion des rôles (Admin/Employé)
- Profils utilisateurs
- Changement de mot de passe

### Gestion des Clients
- CRUD complet des clients
- Historique des projets par client
- Suivi des factures
- Statistiques par client

### Gestion des Projets
- Création et suivi de projets
- Assignation d'équipes
- Gestion des statuts (en cours, terminé, annulé)
- Suivi des échéances
- Alertes pour les projets en retard

### Gestion des Équipes
- Assignation d'utilisateurs aux projets
- Gestion des rôles dans les projets
- Historique des assignations

### Gestion des Factures
- Création automatique de numéros de facture
- Suivi des paiements
- Alertes pour les factures en retard
- Statistiques financières

### Tableau de Bord
- Vue d'ensemble des activités
- Statistiques en temps réel
- Alertes et notifications
- Graphiques et métriques

## 📋 Prérequis

- PHP 7.4 ou supérieur
- MySQL 5.7 ou supérieur
- Serveur web (Apache/Nginx)
- Extensions PHP : PDO, PDO_MySQL

## 🛠️ Installation

### 1. Cloner ou télécharger l'application
```bash
# Si vous utilisez Git
git clone [url-du-repo] ProjectManager
cd ProjectManager
```

### 2. Configuration de la base de données

1. Créer une base de données MySQL :
```sql
CREATE DATABASE project_manager CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

2. Importer le schéma de base de données :
```bash
mysql -u [username] -p project_manager < database/schema.sql
```

### 3. Configuration de l'application

1. Modifier le fichier `config/database.php` avec vos paramètres de base de données :
```php
private $host = 'localhost';
private $dbname = 'project_manager';
private $username = 'votre_username';
private $password = 'votre_password';
```

2. Modifier le fichier `config/config.php` si nécessaire :
```php
define('APP_URL', 'http://votre-domaine.com/ProjectManager');
define('SALT', 'votre_salt_unique_et_securise');
```

### 4. Configuration du serveur web

#### Apache
Créer un fichier `.htaccess` dans le répertoire racine :
```apache
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php [QSA,L]

# Sécurité
<Files "config/*">
    Order allow,deny
    Deny from all
</Files>
```

#### Nginx
Configuration pour Nginx :
```nginx
server {
    listen 80;
    server_name votre-domaine.com;
    root /path/to/ProjectManager;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php7.4-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /config/ {
        deny all;
    }
}
```

### 5. Permissions des fichiers
```bash
# Donner les bonnes permissions
chmod 755 ProjectManager/
chmod 644 ProjectManager/*.php
chmod 644 ProjectManager/config/*.php
chmod 755 ProjectManager/assets/
```

## 👤 Comptes par défaut

Après l'installation, vous pouvez vous connecter avec :

**Administrateur :**
- Email : `admin@projectmanager.com`
- Mot de passe : `password`

**Employé :**
- Email : `jean.dupont@example.com`
- Mot de passe : `password`

⚠️ **Important :** Changez ces mots de passe par défaut après la première connexion !

## 🏗️ Structure du projet

```
ProjectManager/
├── index.php                 # Point d'entrée principal
├── config/                   # Configuration
│   ├── config.php
│   └── database.php
├── controllers/              # Contrôleurs MVC
│   ├── BaseController.php
│   ├── AuthController.php
│   ├── DashboardController.php
│   └── ...
├── models/                   # Modèles de données
│   ├── BaseModel.php
│   ├── User.php
│   ├── Client.php
│   └── ...
├── views/                    # Vues et templates
│   ├── layouts/
│   ├── auth/
│   ├── dashboard/
│   └── ...
├── assets/                   # Ressources statiques
│   ├── css/
│   ├── js/
│   └── images/
├── database/                 # Scripts de base de données
│   └── schema.sql
└── README.md
```

## 🔧 Configuration avancée

### Variables d'environnement
Vous pouvez créer un fichier `.env` pour les configurations sensibles :
```env
DB_HOST=localhost
DB_NAME=project_manager
DB_USER=username
DB_PASS=password
APP_ENV=production
```

### Sauvegarde automatique
Script de sauvegarde de la base de données :
```bash
#!/bin/bash
mysqldump -u username -p project_manager > backup_$(date +%Y%m%d_%H%M%S).sql
```

## 🚀 Utilisation

1. Accédez à l'application via votre navigateur
2. Connectez-vous avec un compte administrateur
3. Créez vos utilisateurs, clients et projets
4. Assignez les équipes aux projets
5. Gérez les factures et suivez les paiements

## 🔒 Sécurité

- Mots de passe hashés avec `password_hash()`
- Protection CSRF sur les formulaires
- Validation et échappement des données
- Sessions sécurisées
- Contrôle d'accès basé sur les rôles

## 🐛 Dépannage

### Erreur de connexion à la base de données
- Vérifiez les paramètres dans `config/database.php`
- Assurez-vous que MySQL est démarré
- Vérifiez les permissions de l'utilisateur MySQL

### Page blanche
- Activez l'affichage des erreurs PHP
- Vérifiez les logs d'erreur du serveur web
- Vérifiez les permissions des fichiers

### Problèmes de session
- Vérifiez que `session_start()` fonctionne
- Vérifiez les permissions du répertoire de sessions

## 📝 Licence

Ce projet est sous licence MIT. Voir le fichier LICENSE pour plus de détails.

## 🤝 Contribution

Les contributions sont les bienvenues ! N'hésitez pas à :
1. Fork le projet
2. Créer une branche pour votre fonctionnalité
3. Commiter vos changements
4. Pousser vers la branche
5. Ouvrir une Pull Request

## 📞 Support

Pour toute question ou problème, n'hésitez pas à ouvrir une issue sur le repository.
