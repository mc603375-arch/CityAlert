<?php

// =====================================================
// CONFIGURATION GÉNÉRALE DU PROJET
// =====================================================

// URL de base du projet
define('BASE_URL', 'http://localhost/CityAlert');

// Chemins
define('ROOT_PATH',  __DIR__ . '/..');
define('APP_PATH',   ROOT_PATH . '/app');
define('VIEW_PATH',  APP_PATH . '/views');

// Base de données
define('DB_HOST',    'localhost');
define('DB_NAME',    'cityalert');
define('DB_USER',    'root');
define('DB_PASS',    '');
define('DB_CHARSET', 'utf8');

// Session
define('SESSION_NAME', 'cityalert_session');

// Rôles
define('ROLE_CITOYEN', 'citoyen');
define('ROLE_AGENT',   'agent');
define('ROLE_ADMIN',   'admin');

// Statuts des signalements
define('STATUT_NOUVEAU',   'nouveau');
define('STATUT_EN_COURS',  'en_cours');
define('STATUT_RESOLU',    'resolu');
define('STATUT_REJETE',    'rejete');

// Upload photos
define('UPLOAD_PATH', ROOT_PATH . '/public/assets/images/uploads');
define('UPLOAD_URL',  BASE_URL  . '/public/assets/images/uploads');
define('MAX_FILE_SIZE', 2 * 1024 * 1024); // 2MB