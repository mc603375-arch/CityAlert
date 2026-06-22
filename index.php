<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

// =====================================================
// POINT D'ENTRÉE PRINCIPAL — CityAlert
// =====================================================

// Charge la configuration EN PREMIER
require_once __DIR__ . '/config/config.php';

// Démarre la session
session_name(SESSION_NAME);
session_start();

// Charge l'autoloader
require_once __DIR__ . '/core/Autoloader.php';
Autoloader::register();

// Charge le router
require_once __DIR__ . '/core/Router.php';

// =====================================================
// DÉFINITION DES ROUTES
// =====================================================
$router = new Router();

// --- Auth ---
$router->get( '/login',           'AuthController', 'login');
$router->post('/login/traiter',   'AuthController', 'traiterLogin');
$router->get( '/register',        'AuthController', 'register');
$router->post('/register/traiter','AuthController', 'traiterRegister');
$router->get( '/logout',          'AuthController', 'logout');

// --- Dashboard Citoyen ---
$router->get('/citoyen/dashboard', 'DashboardController', 'citoyen');

// --- Dashboard Agent ---
$router->get('/agent/dashboard',   'DashboardController', 'agent');

// --- Dashboard Admin ---
$router->get('/admin/dashboard',   'DashboardController', 'admin');
$router->get('/admin/utilisateurs','AdminController',     'utilisateurs');

// --- Signalements ---
$router->get( '/signalements',           'SignalementController', 'liste');
$router->get( '/signalements/ajouter',   'SignalementController', 'ajouter');
$router->post('/signalements/ajouter',   'SignalementController', 'traiterAjout');
$router->get( '/signalements/detail',    'SignalementController', 'detail');
$router->get( '/signalements/modifier',  'SignalementController', 'modifier');
$router->post('/signalements/modifier',  'SignalementController', 'traiterModifier');
$router->get( '/signalements/supprimer', 'SignalementController', 'supprimer');

// --- Commentaires ---
$router->post('/commentaires/ajouter',   'CommentaireController', 'ajouter');

// =====================================================
// LANCE LE ROUTER
// =====================================================
$router->dispatch();