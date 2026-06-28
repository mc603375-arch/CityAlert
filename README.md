# 🏙️ CityAlert — Plateforme de Signalement Citoyen

> Projet de Programmation Orientée Objet en PHP — L2 IAGEA Groupe 2  
> ISM — Institut Supérieur de Management | Technologie Web 3 : PHP/MySQL  
> Enseignant : M. Cheikh Tidiane NDIAYE

---

## 👥 Équipe

| Membre | Branche | Responsabilités |
|---|---|---|
| Mariama Lansana CAMARA | `auth-mariama` | Config, Core, Auth, Entités, Repositories, Interfaces, Traits, Exceptions |
| Pape Moussa BA | `backend-moussa` | Router, SignalementController, DashboardController, API JSON, Export CSV |
| Tady Aaron AYOUMA | `crud-aaron` | CommentaireController, AdminController, Vues Dashboard et Signalements |
| Adjo Constancia ASSIMADI | `design-constancia` | CSS, Design responsive, Logo, JavaScript front-end |

---

## 📋 Présentation du projet

CityAlert est une application web de signalement citoyen géolocalisé développée en **PHP 8.3 natif** (sans framework), suivant une **architecture MVC construite à la main**. Elle permet aux citoyens de signaler des problèmes urbains, aux agents municipaux spécialisés de les traiter, et à l'administrateur de superviser l'ensemble.

### Fonctionnalités principales
- ✅ Authentification complète (inscription, connexion, déconnexion, rôles)
- ✅ CRUD des signalements avec upload photo et géolocalisation Leaflet
- ✅ Cycle de vie : Nouveau → En cours → Résolu / Rejeté (avec historique)
- ✅ 4 catégories polymorphes : Voirie, Éclairage, Déchets, Eau & Assainissement
- ✅ Tableau de bord différencié par rôle (Citoyen / Agent / Admin)
- ✅ Commentaires citoyen ↔ agent sur chaque signalement
- ✅ Filtres, pagination et statistiques
- ✅ Dashboard admin avec graphiques Chart.js et carte Leaflet

### Fonctionnalités bonus
- ⭐ Upload et redimensionnement d'images (bibliothèque GD)
- ⭐ Carte interactive Leaflet avec géocodage d'adresse
- ⭐ Export CSV des signalements
- ⭐ API REST JSON lecture seule
- ⭐ Notification email au changement de statut
- ⭐ Gestion des agents avec détection automatique de spécialité

---

## 🛠️ Technologies utilisées

| Technologie | Usage |
|---|---|
| PHP 8.3 | Backend — POO, MVC maison, PDO |
| MySQL 8 | Base de données relationnelle |
| HTML5 / CSS3 | Interface utilisateur responsive |
| JavaScript | Interactions front-end |
| Leaflet.js | Carte interactive et géolocalisation |
| Chart.js | Graphiques du dashboard admin |
| Git / GitHub | Versioning et collaboration en équipe |
| WAMP / WebStorm | Environnement de développement |

---

## 📦 Installation

### Prérequis
- WAMP (ou XAMPP / MAMP)
- PHP >= 8.0
- MySQL >= 8.0
- Git

### Étapes

**1. Cloner le repository**
```bash
cd C:/wamp64/www
git clone https://github.com/mc603375-arch/CityAlert.git
cd CityAlert
```

**2. Créer la base de données**

Ouvrir phpMyAdmin (`http://localhost/phpmyadmin`) et exécuter le script SQL :
```
Importer le fichier : sql/cityalert.sql
```

Ou via le terminal MySQL :
```bash
mysql -u root -p < sql/cityalert.sql
```

**3. Configurer la connexion**

Ouvrir `config/config.php` et vérifier les paramètres :
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'cityalert');
define('DB_USER', 'root');
define('DB_PASS', '');        // Votre mot de passe MySQL
define('DB_PORT', '3306');
```

**4. Accéder à l'application**

Ouvrir le navigateur et aller sur :
```
http://localhost/CityAlert/index.php/login
```

---

## 🔑 Comptes de test

| Rôle | Email | Mot de passe |
|---|---|---|
| **Administrateur** | admin@cityalert.com | admin123 |
| **Agent Voirie** | agent.voirie@cityalert.com | agent123 |
| **Agent Éclairage** | agent.eclairage@cityalert.com | agent123 |
| **Agent Déchets** | agent.dechets@cityalert.com | agent123 |
| **Agent Eau** | agent.eau@cityalert.com | agent123 |
| **Citoyen** | mc60375@gmail.com | 123456|

> Pour créer un compte citoyen : cliquer sur **"S'inscrire gratuitement"** sur la page de connexion.

---

## 🗂️ Structure du projet

```
CityAlert/
├── app/
│   ├── controllers/        # AuthController, SignalementController, etc.
│   ├── models/
│   │   ├── entities/       # User, Signalement, Commentaire, categories/
│   │   └── repositories/   # UserRepository, SignalementRepository, etc.
│   └── views/              # auth/, dashboard/, signalements/, layout/
├── config/
│   ├── config.php          # Constantes et configuration
│   └── Database.php        # Connexion PDO (Pattern Singleton)
├── core/
│   ├── Router.php          # Routeur maison
│   └── Autoloader.php      # Autoloader PSR-4
├── interfaces/             # RepositoryInterface, NotifiableInterface
├── traits/                 # Timestampable
├── exceptions/             # EntityNotFoundException, ValidationException
├── public/
│   └── assets/             # CSS, JS, images, uploads
├── sql/
│   └── cityalert.sql       # Script de création et peuplement BDD
├── index.php               # Point d'entrée unique
└── README.md
```

---

## 🎯 Concepts POO mis en œuvre

| Concept | Implémentation |
|---|---|
| **Classe abstraite** | `Categorie` → Voirie, Eclairage, Dechets, EauAssainissement |
| **Héritage** | `User` → Citoyen / Agent / Administrateur |
| **Interfaces** | `RepositoryInterface`, `NotifiableInterface` |
| **Trait** | `Timestampable` (created_at / updated_at) |
| **Polymorphisme** | `getPriorite()` retourne une valeur différente selon la catégorie |
| **Pattern Singleton** | `Database::getInstance()` — une seule connexion PDO |
| **Pattern Factory** | `Categorie::fromArray()` — instancie la bonne sous-classe |
| **Encapsulation** | Attributs privés/protégés avec getters/setters |

---

## 🔒 Sécurité

- Hashage des mots de passe avec `password_hash(PASSWORD_BCRYPT)`
- Requêtes PDO préparées avec paramètres nommés (protection injections SQL)
- `htmlspecialchars()` sur toutes les sorties (protection XSS)
- Contrôle d'accès par rôle sur chaque route
- Validation des uploads (MIME type, taille max 2Mo)

---S

## 🔗 Liens utiles

- **Repository GitHub** : https://github.com/mc603375-arch/CityAlert
- **Application locale** : http://localhost/CityAlert/index.php/login
