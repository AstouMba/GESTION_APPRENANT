<?php

namespace App\Route;

require_once __DIR__ . '/../services/session.service.php';
require_once __DIR__ . '/../controllers/auth.controller.php';
require_once __DIR__ . '/../controllers/promotion.controller.php';
require_once __DIR__ . '/../controllers/referentiel.controller.php';
require_once __DIR__ . '/../controllers/dashboard.controller.php';
require_once __DIR__ . '/../controllers/apprenant.controller.php';

use App\Controllers;

// Définition de toutes les routes disponibles avec leur fonction controller associée
$routes = [
    // Routes pour l'authentification
    'login' => 'App\Controllers\login_page',
    'login-process' => 'App\Controllers\login_process',
    'logout' => 'App\Controllers\logout',
    'change-password' => 'App\Controllers\change_password_page',
    'change-password-process' => 'App\Controllers\change_password_process',
    'forgot-password' => 'App\Controllers\forgot_password_page',
    'forgot-password-process' => 'App\Controllers\forgot_password_process',
    'reset-password' => 'App\Controllers\reset_password_page',
    'reset-password-process' => 'App\Controllers\reset_password_process',
    
    // Routes pour les promotions
    'promotions' => 'App\Controllers\list_promotions',
    'add-promotion' => 'App\Controllers\add_promotion_form',
    'add-promotion-form' => 'App\Controllers\add_promotion_form',
    'add-promotion-process' => 'App\Controllers\add_promotion_process',
    'toggle-promotion-status' => 'App\Controllers\toggle_promotion_status',
    'promotion' => 'App\Controllers\promotion_page',

    // Routes pour les référentiels
    'referentiels' => 'App\Controllers\list_referentiels',
    'all-referentiels' => 'App\Controllers\list_all_referentiels',
    'add-referentiel' => 'App\Controllers\add_referentiel_form',
    'add-referentiel-process' => 'App\Controllers\add_referentiel_process',
    'assign-referentiels' => 'App\Controllers\assign_referentiels_form',
    'assign-referentiels-process' => 'App\Controllers\assign_referentiels_process',
    'update-referentiels' => 'App\Controllers\update_referentiels_assignment',
    'create-referentiel' => 'App\Controllers\create_referentiel_form',
    'create-referentiel-process' => 'App\Controllers\create_referentiel_process',
    'list-referentiels' => 'App\Controllers\list_referentiels',
    
    // Route par défaut pour le tableau de bord
    'dashboard' => 'App\Controllers\dashboard',
    
    // Route pour les erreurs
    'forbidden' => 'App\Controllers\forbidden',
    
    // Route par défaut (page non trouvée)
    '404' => 'App\Controllers\not_found',

    // Route pour les apprenants
    'apprenants' => 'App\Controllers\list_apprenants',
    'add-apprenant' => 'App\Controllers\add_apprenant_form',
    'add-apprenant-process' => 'App\Controllers\add_apprenant_process',
    'apprenant-details' => 'App\Controllers\show_apprenant_details',

    // Routes pour les téléchargements
    'download-pdf' => 'App\Controllers\download_pdf',
    'download-excel' => 'App\Controllers\download_excel'
];

/**
 * Fonction pour gérer la requête entrante
 */
function handle_request() {
    global $routes, $session_services, $model;

    // Démarrer la session
    $session_services['start_session']();

    // Liste des pages qui ne nécessitent pas d'authentification
    $public_pages = ['login', 'login-process', 'forgot-password', 'forgot-password-process', 'reset-password', 'reset-password-process'];

    // Récupération de la page demandée
    $page = isset($_GET['page']) ? $_GET['page'] : 'login';

    // Journalisation de la page demandée
    error_log("Page demandée : $page, Utilisateur connecté : " . ($session_services['is_logged_in']() ? 'Oui' : 'Non'));

    // Si l'utilisateur est connecté et qu'il essaie d'accéder à la page de connexion, rediriger vers le dashboard
    if ($session_services['is_logged_in']() && in_array($page, $public_pages)) {
        header('Location: ?page=dashboard');
        exit;
    }

    // Routage vers le contrôleur approprié avec `match`
    match ($page) {
        'promotions' => \App\Controllers\list_promotions(),
        'add_promotion' => \App\Controllers\add_promotion(),
        'toggle_promotion' => \App\Controllers\toggle_promotion_status(),
        'search_referentiels' => \App\Controllers\search_referentiels(),
        'apprenants' => \App\Controllers\list_apprenants(),
        default => array_key_exists($page, $routes)
            ? call_user_func($routes[$page])
            : call_user_func($routes['404']),
    };

    // Récupérer la promotion active
    $current_promotion = $model['get_current_promotion']();

    // Ajoutez la promotion active à une variable globale pour toutes les vues
    if ($current_promotion) {
        global $current_promotion_global;
        $current_promotion_global = $current_promotion;
    }

    // Si la page est "all-referentiels", exécuter directement la fonction correspondante
    if ($page === 'all-referentiels') {
        \App\Controllers\list_all_referentiels();
        return;
    }
}