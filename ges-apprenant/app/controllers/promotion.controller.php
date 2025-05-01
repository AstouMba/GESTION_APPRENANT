<?php

namespace App\Controllers;

require_once __DIR__ . '/controller.php';
require_once __DIR__ . '/../models/model.php';
require_once __DIR__ . '/../services/validator.service.php';
require_once __DIR__ . '/../services/session.service.php';
require_once __DIR__ . '/../services/file.service.php';
require_once __DIR__ . '/../translate/fr/error.fr.php';
require_once __DIR__ . '/../translate/fr/message.fr.php';
require_once __DIR__ . '/../enums/profile.enum.php';
require_once __DIR__ . '/../enums/status.enum.php'; // Ajout de cette ligne
require_once __DIR__ . '/../enums/messages.enum.php';
require_once __DIR__ . '/../helpers/functions.php';

use App\Models;
use App\Services;
use App\Translate\fr;
use App\Enums;
use App\Enums\Status; // Ajout de cette ligne
use App\Enums\Messages;
use App\Enums\ErrorsMessages;

// Affichage de la liste des promotions
function list_promotions() {
    // $file_path = __DIR__ . '/../data/data.json';
    
    global $model, $session_services;
    
    // Vérification si l'utilisateur est connecté
    $user = check_auth();
    
    // Récupérer les statistiques
    $stats = $model['get_statistics']();
    
    // Récupérer le terme de recherche depuis GET
    $search = $_GET['search'] ?? '';
    $referentiel_filter = $_GET['referentiel'] ?? '';
    $status_filter = $_GET['status'] ?? '';

    // Récupérer la page courante et le nombre d'éléments par page
    $current_page = isset($_GET['page_num']) ? (int)$_GET['page_num'] : 1;
    $items_per_page = 3; // 3 éléments par page
    
    // Récupérer toutes les promotions
    $promotions = $model['get_all_promotions']();
    
    // Filtrer les promotions si un terme de recherche est présent
    if (!empty($search)) {
        $promotions = array_filter($promotions, function($promotion) use ($search) {
            return stripos($promotion['name'], $search) !== false;
        });
    }
    // Appliquer le filtre par référentiel
    if (!empty($referentiel_filter)) {
        $promotions = array_filter($promotions, function ($promotion) use ($referentiel_filter) {
            return in_array($referentiel_filter, $promotion['referentiels']);
        });
    }

    // Appliquer le filtre par statut
    if (!empty($status_filter)) {
        $promotions = array_filter($promotions, function ($promotion) use ($status_filter) {
            return $promotion['status'] === $status_filter;
        });
    }
    
    // Séparer les promotions actives et inactives
    $active_promotions = array_filter($promotions, function($promotion) {
        return $promotion['status'] === 'active';
    });
    
    $inactive_promotions = array_filter($promotions, function($promotion) {
        return $promotion['status'] !== 'active';
    });
    
    // Calculer le nombre d'éléments inactifs à afficher par page
    $active_count = count($active_promotions);
    
    // On n'applique la pagination que sur les promotions inactives
    $total_inactive = count($inactive_promotions);
    $total_pages = ceil($total_inactive / $items_per_page);
    
    // S'assurer que la page courante est valide
    $current_page = max(1, min($current_page, $total_pages > 0 ? $total_pages : 1));
    
    // Calculer l'offset pour la pagination des inactives
    $offset = ($current_page - 1) * $items_per_page;
    
    // Récupérer les promotions inactives pour la page courante
    $paginated_inactive = array_slice(array_values($inactive_promotions), $offset, $items_per_page);
    
    // Combiner les promotions actives (toujours en premier) avec les inactives paginées
    $paginated_promotions = array_merge(array_values($active_promotions), $paginated_inactive);
    
    // Récupérer la promotion active
    $current_promotion = $model['get_current_promotion']();


    // Rendu de la vue avec les statistiques
    render('admin.layout.php', 'promotion/list.html.php', [
        'user' => $user,
        'promotions' => $paginated_promotions,
        'current_promotion' => $current_promotion,
        'search' => $search,
        'referentiel_filter' => $referentiel_filter,
        'status_filter' => $status_filter,
        'active_menu' => 'promotions',
        'current_page' => $current_page,
        'total_pages' => $total_pages,
        'items_per_page' => $items_per_page,
        'total_items' => $total_inactive + $active_count, // Total des items
        'stats' => $stats
    ]);
}// Affichage du formulaire d'ajout d'une promotion
function add_promotion_form() {
    global $model;
    // Vérification des droits d'accès (Admin uniquement)
    $user = check_profile(Enums\ADMIN);
    
    // Récupérer les référentiels pour le formulaire
    $referentiels = $model['get_all_referentiels']();
     // Récupérer la promotion active
     $current_promotion = $model['get_current_promotion']();

    
    // Affichage de la vue
    render('admin.layout.php', 'promotion/add.html.php', [
        'current_promotion' => $current_promotion,

        'user' => $user,
        'referentiels' => $referentiels
    ]);
}

// Traitement de l'ajout d'une promotion
function add_promotion_process() {
    global $session_services;

    // Charger les messages d'erreur
    require_once __DIR__ . '/../translate/fr/error.fr.php';

    $errors = [];
    $name = $_POST['name'] ?? '';
    $date_debut = $_POST['date_debut'] ?? '';
    $date_fin = $_POST['date_fin'] ?? '';
    $referentiels = $_POST['referentiels'] ?? [];
    $image = $_FILES['image'] ?? null;

    // Validation des champs
    if (empty($name)) {
        $errors['name'] = ErrorsMessages::PROMOTION_NAME_REQUIRED->value;
    }

    if (empty($date_debut)) {
        $errors['date_debut'] = ErrorsMessages::PROMOTION_DATE_DEBUT_REQUIRED->value;
    }

    if (empty($date_fin)) {
        $errors['date_fin'] = ErrorsMessages::PROMOTION_DATE_FIN_REQUIRED->value;
    }

    if (empty($referentiels)) {
        $errors['referentiels'] = ErrorsMessages::PROMOTION_REFERENTIELS_REQUIRED->value;
    }

    if ($image && $image['error'] === UPLOAD_ERR_NO_FILE) {
        $errors['image'] = ErrorsMessages::PROMOTION_IMAGE_REQUIRED->value;
    }

    if (!empty($date_debut) && !empty($date_fin) && strtotime($date_debut) > strtotime($date_fin)) {
        $errors['date_range'] = ErrorsMessages::PROMOTION_DATE_RANGE_INVALID->value;
    }

    // Si des erreurs existent, les stocker dans la session
    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        $_SESSION['old_inputs'] = $_POST; // Stocker les anciennes valeurs pour les réafficher
        header('Location: ?page=add-promotion');
        exit;
    }

    // Si aucune erreur, traiter la création de la promotion
    // (Ajout dans le fichier JSON ou autre logique)
    $file_path = __DIR__ . '/../data/data.json';
    $promotions = json_to_array($file_path);
    $new_promotion = [
        'id' => count($promotions) + 1,
        'name' => $name,
        'date_debut' => $date_debut,
        'date_fin' => $date_fin,
        'referentiels' => $referentiels,
        'image' => $image['name'] ?? '',
    ];
    $promotions[] = $new_promotion;
    array_to_json($file_path, $promotions);

    // Rediriger vers la page des promotions après succès
    header('Location: ?page=promotions');
    exit;
}

// Modification du statut d'une promotion (activation/désactivation)
function toggle_promotion_status() {
    global $model, $session_services;
    
    // Vérification de l'authentification
    check_auth();
    
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        redirect('?page=promotions');
        return;
    }
    
    $promotion_id = filter_input(INPUT_POST, 'promotion_id', FILTER_VALIDATE_INT);
    if (!$promotion_id) {
        $session_services['set_flash_message']('error', Messages::PROMOTION_ERROR->value);
        redirect('?page=promotions');
        return;
    }
    
    $result = $model['toggle_promotion_status']($promotion_id);
    
    if ($result) {
        $message = $result['status'] === Status::ACTIVE->value ? 
                  Messages::PROMOTION_ACTIVATED->value : 
                  Messages::PROMOTION_INACTIVE->value;
        $session_services['set_flash_message']('success', $message);
    } else {
        $session_services['set_flash_message']('error', Messages::PROMOTION_ERROR->value);
    }
    
    // Redirection vers la liste des promotions
    redirect('?page=promotions');
}

// Ajout d'une promotion
function add_promotion() {
    global $model, $session_services, $validator_services, $file_services;
    
    // Vérification de l'authentification
    $user = check_auth();
    
    // Vérification de la méthode POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        $session_services['set_flash_message']('error', Messages::INVALID_REQUEST->value);
        redirect('?page=promotions');
        return;
    }
    
    // Validation des données
    $validation = $validator_services['validate_promotion']($_POST, $_FILES);
    
    if (!$validation['valid']) {
        $session_services['set_flash_message']('error', $validation['errors'][0]);
        redirect('?page=promotions');
        return;
    }
    
    // Traitement de l'image avec le service
    $image_path = $file_services['handle_promotion_image']($_FILES['image']);
    if (!$image_path) {
        $session_services['set_flash_message']('error', Messages::IMAGE_UPLOAD_ERROR->value);
        redirect('?page=promotions');
        return;
    }
    
    // Préparation des données
    $promotion_data = [
        'name' => htmlspecialchars($_POST['name']),
        'date_debut' => $_POST['date_debut'],
        'date_fin' => $_POST['date_fin'],
        'image' => $image_path,
        'status' => 'inactive',
        'apprenants' => []
    ];
    
    // Création de la promotion
    $result = $model['create_promotion']($promotion_data);
    
    if (!$result) {
        $session_services['set_flash_message']('error', Messages::PROMOTION_CREATE_ERROR->value);
        redirect('?page=promotions');
        return;
    }

    $session_services['set_flash_message']('success', Messages::PROMOTION_CREATED->value);
    redirect('?page=promotions');
}

// Recherche des référentiels
function search_referentiels() {
    global $model;
    
    // Vérification si l'utilisateur est connecté
    check_auth();
    
    $query = $_GET['q'] ?? '';
    $referentiels = $model['search_referentiels']($query);
    
    // Retourner les résultats en JSON
    header('Content-Type: application/json');
    echo json_encode(array_values($referentiels));
    exit;
}

// Affichage de la page de promotion
