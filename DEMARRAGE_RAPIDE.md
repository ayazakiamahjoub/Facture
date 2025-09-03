# 🚀 Démarrage Rapide - Pioneer Tech ProjectManager

## ✅ Application lancée avec succès !

Votre application Pioneer Tech ProjectManager est maintenant opérationnelle et accessible.

## 🌐 Accès à l'application

**URL principale :** http://localhost/pioneer tech/

**Test de connexion :** http://localhost/pioneer tech/test_connection.php

**Vérification Apache :** http://localhost/pioneer tech/test_apache.php

**Statut de l'application :** http://localhost/pioneer tech/check_status.php

## 👤 Comptes de démonstration

### Administrateur
- **Email :** admin@projectmanager.com
- **Mot de passe :** password
- **Permissions :** Accès complet à toutes les fonctionnalités

### Employé
- **Email :** jean.dupont@example.com
- **Mot de passe :** password
- **Permissions :** Accès limité selon le rôle

### Employé 2
- **Email :** marie.martin@example.com
- **Mot de passe :** password
- **Permissions :** Accès limité selon le rôle

## 🎯 Fonctionnalités disponibles

### 📊 Dashboard
- Vue d'ensemble des statistiques
- Projets actifs et récents
- Factures et chiffre d'affaires
- Alertes et notifications

### 👥 Gestion des utilisateurs (Admin uniquement)
- Création/modification d'utilisateurs
- Gestion des rôles
- Activation/désactivation

### 🏢 Gestion des clients
- CRUD complet des clients
- Historique des projets
- Suivi des factures
- Statistiques par client

### 📋 Gestion des projets
- Création et suivi de projets
- Assignation d'équipes
- Gestion des statuts
- Suivi des échéances

### 👨‍💼 Gestion des équipes
- Assignation aux projets
- Gestion des rôles dans les projets
- Historique des assignations

### 🧾 Gestion des factures
- Création avec numérotation automatique
- Suivi des paiements
- Alertes pour les impayés
- Statistiques financières

## 🔧 Démarrage automatique

Pour démarrer rapidement l'application, double-cliquez sur :
```
start.bat
```

Ce script vérifie automatiquement :
- ✅ Services XAMPP (Apache/MySQL)
- ✅ Base de données
- ✅ Configuration
- 🌐 Ouverture automatique dans le navigateur

## 📱 Interface

L'application dispose d'une interface moderne et responsive :
- **Framework :** Bootstrap 5
- **Icons :** Font Awesome
- **Responsive :** Compatible mobile/tablette
- **Thème :** Design moderne avec animations

## 🔐 Sécurité

L'application intègre plusieurs mesures de sécurité :
- Mots de passe hashés (password_hash)
- Protection contre les injections SQL (PDO)
- Gestion des sessions sécurisées
- Contrôle d'accès basé sur les rôles
- Validation et échappement des données

## 📊 Base de données

**Nom :** project_manager
**Tables créées :**
- users (utilisateurs)
- clients (clients)
- projets (projets)
- equipe_projet (assignations)
- factures (factures)

## 🛠️ Dépannage

### Problème de connexion
1. Vérifiez que XAMPP est démarré
2. Testez : http://localhost/ProjectManager/test_connection.php
3. Vérifiez les paramètres dans `config/database.php`

### Page blanche
1. Vérifiez les logs d'erreur Apache
2. Activez l'affichage des erreurs PHP
3. Vérifiez les permissions des fichiers

### Erreur 404
1. Vérifiez que le fichier `.htaccess` est présent
2. Vérifiez que mod_rewrite est activé
3. Testez l'accès direct : http://localhost/ProjectManager/index.php

## 📞 Support

Pour toute question ou problème :
1. Consultez le fichier `README.md` complet
2. Vérifiez la configuration dans `config/`
3. Testez la connexion avec `test_connection.php`

## 🎉 Prochaines étapes

1. **Connectez-vous** avec un compte admin
2. **Créez vos premiers clients** dans la section Clients
3. **Ajoutez des projets** et assignez les équipes
4. **Générez des factures** pour vos projets
5. **Explorez le dashboard** pour voir les statistiques

---

**Félicitations ! Votre application ProjectManager est prête à être utilisée ! 🎊**
