<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/config/config.php';

session_name(SESSION_NAME);
session_start();

require_once __DIR__ . '/core/Autoloader.php';
Autoloader::register();

require_once __DIR__ . '/app/controllers/AuthController.php';
require_once __DIR__ . '/app/controllers/DashboardController.php';
require_once __DIR__ . '/app/controllers/AdminController.php';
require_once __DIR__ . '/app/controllers/CommentaireController.php';
require_once __DIR__ . '/app/controllers/SignalementController.php';

require_once __DIR__ . '/core/Router.php';
$router = new Router();

$router->get( '/login',            'AuthController', 'login');
$router->post('/login/traiter',    'AuthController', 'traiterLogin');
$router->get( '/register',         'AuthController', 'register');
$router->post('/register/traiter', 'AuthController', 'traiterRegister');
$router->get( '/logout',           'AuthController', 'logout');

$router->get('/citoyen/dashboard',  'DashboardController', 'citoyen');
$router->get('/agent/dashboard',    'DashboardController', 'agent');
$router->get('/admin/dashboard',    'DashboardController', 'admin');
$router->get('/admin/utilisateurs', 'AdminController',     'utilisateurs');

$router->get( '/signalements',           'SignalementController', 'liste');
$router->get( '/signalements/ajouter',   'SignalementController', 'ajouter');
$router->post('/signalements/ajouter',   'SignalementController', 'traiterAjout');
$router->get( '/signalements/detail',    'SignalementController', 'detail');
$router->get( '/signalements/modifier',  'SignalementController', 'modifier');
$router->post('/signalements/modifier',  'SignalementController', 'traiterModifier');
$router->get( '/signalements/supprimer', 'SignalementController', 'supprimer');
$router->post('/signalements/statut',    'SignalementController', 'changerStatut');

$router->post('/commentaires/ajouter', 'CommentaireController', 'ajouter');

$router->dispatch();