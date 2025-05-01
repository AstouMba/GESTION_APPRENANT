<?php

namespace App\Route;

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
    'promotion' => 'App\Controllers\promotion_page', // Nouvelle route

    
    // Routes pour les référentiels
    'referentiels' => 'App\Controllers\List_referentiels',
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

    // Routes pour les téléchargements
    'download-pdf' => 'App\Controllers\download_pdf',
    'download-excel' => 'App\Controllers\download_excel'
];

/**
 * Fonction de routage qui exécute le contrôleur correspondant à la page demandée
 *
 * @param string $page La page demandée
 * @return mixed Le résultat de la fonction contrôleur
 */
function route($page) {
    global $routes, $model;

    // Vérifie si la route demandée existe
    $route_exists = array_key_exists($page, $routes);

    // Récupérer la promotion active
    $current_promotion = $model['get_current_promotion']();

    // Ajoutez la promotion active à une variable globale pour toutes les vues
    if ($current_promotion) {
        global $current_promotion_global;
        $current_promotion_global = $current_promotion;
    }

    if ($page === 'all-referentiels') {
        \App\Controllers\list_all_referentiels();
        return;
    }
    
    // Obtient la fonction à exécuter (route demandée ou 404 si non trouvée)
    $controller_function = $route_exists ? $routes[$page] : $routes['404'];
    
    // Exécute la fonction contrôleur
    return call_user_func($controller_function);
}