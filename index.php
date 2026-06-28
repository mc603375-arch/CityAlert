<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// =====================================================
// CONFIGURATION
// =====================================================
require_once __DIR__ . '/config/config.php';

// =====================================================
// SESSION
// =====================================================
session_name(SESSION_NAME);
session_start();

// =====================================================
// AUTOLOADER
// =====================================================
require_once __DIR__ . '/core/Autoloader.php';
Autoloader::register();

// =====================================================
// CONTROLLERS
// =====================================================
require_once __DIR__ . '/app/controllers/AuthController.php';
require_once __DIR__ . '/app/controllers/DashboardController.php';
require_once __DIR__ . '/app/controllers/AdminController.php';
require_once __DIR__ . '/app/controllers/CommentaireController.php';
require_once __DIR__ . '/app/controllers/SignalementController.php';

if (file_exists(__DIR__ . '/app/controllers/ApiController.php')) {
    require_once __DIR__ . '/app/controllers/ApiController.php';
}
if (file_exists(__DIR__ . '/app/controllers/ExportController.php')) {
    require_once __DIR__ . '/app/controllers/ExportController.php';
}

// =====================================================
// ROUTER
// =====================================================
require_once __DIR__ . '/core/Router.php';
$router = new Router();

// --- Auth ---
$router->get( '/login',            'AuthController', 'login');
$router->post('/login/traiter',    'AuthController', 'traiterLogin');
$router->get( '/register',         'AuthController', 'register');
$router->post('/register/traiter', 'AuthController', 'traiterRegister');
$router->get( '/logout',           'AuthController', 'logout');

// --- Dashboards ---
$router->get('/citoyen/dashboard',  'DashboardController', 'citoyen');
$router->get('/agent/dashboard',    'DashboardController', 'agent');
$router->get('/admin/dashboard',    'DashboardController', 'admin');

// --- Admin ---
$router->get( '/admin/utilisateurs',       'AdminController', 'utilisateurs');
$router->get( '/admin/agents',             'AdminController', 'agents');
$router->post('/admin/agents/creer',       'AdminController', 'creerAgent');
$router->get( '/admin/agents/supprimer',   'AdminController', 'supprimerAgent');
$router->post('/admin/changer-role',       'AdminController', 'changerRole');

// --- Signalements ---
$router->get( '/signalements',           'SignalementController', 'liste');
$router->get('/admin/citoyens', 'AdminController', 'citoyens');
$router->get( '/signalements/ajouter',   'SignalementController', 'ajouter');
$router->post('/signalements/ajouter',   'SignalementController', 'traiterAjout');
$router->get( '/signalements/detail',    'SignalementController', 'detail');
$router->get( '/signalements/modifier',  'SignalementController', 'modifier');
$router->post('/signalements/modifier',  'SignalementController', 'traiterModifier');
$router->get( '/signalements/supprimer', 'SignalementController', 'supprimer');
$router->post('/signalements/statut',    'SignalementController', 'changerStatut');
$router->post('/signalements/assigner',  'SignalementController', 'assigner');

// --- Commentaires ---
$router->post('/commentaires/ajouter', 'CommentaireController', 'ajouter');

// --- API & Export (bonus) ---
if (class_exists('ApiController')) {
    $router->get('/api/signalements', 'ApiController', 'signalements');
    $router->get('/api/signalement',  'ApiController', 'signalement');
    $router->get('/api/stats',        'ApiController', 'stats');
}
if (class_exists('ExportController')) {
    $router->get('/export/csv', 'ExportController', 'csv');
}

// =====================================================
// DISPATCH
// =====================================================
$router->dispatch();