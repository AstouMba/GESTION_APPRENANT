<?php

namespace App\Controllers;

require_once __DIR__ . '/controller.php';
require_once __DIR__ . '/../models/model.php';
require_once __DIR__ . '/../services/validator.service.php';
require_once __DIR__ . '/../services/session.service.php';
require_once __DIR__ . '/../translate/fr/error.fr.php';
require_once __DIR__ . '/../translate/fr/message.fr.php';
require_once __DIR__ . '/../enums/profile.enum.php';

use App\Models;
use App\Services;
use App\Translate\fr;
use App\Enums;
use Exception; // Ajoutez cette ligne

// Affichage de la liste des référentiels de la promotion en cours
function list_referentiels() {
    global $model;

    // Récupérer la promotion active
    $current_promotion = $model['get_current_promotion']();

    // Vérifiez si une promotion active existe
    if (!$current_promotion) {
        $current_promotion = ['name' => 'Promotion inconnue'];
    }

    // Récupérer les référentiels associés à la promotion active
    $referentiels = $model['get_referentiels_by_promotion']($current_promotion['id'] ?? null);

    // Afficher la vue
    render('admin.layout.php', 'referentiel/list.html.php', [
        'current_promotion' => $current_promotion,
        'referentiels' => $referentiels,
    ]);
}

// Affichage de la liste de tous les référentiels
function list_all_referentiels() {
    global $model;

    // Récupérer tous les référentiels
    $referentiels = $model['get_all_referentiels']();

    // Paramètres de pagination
    $items_per_page = 2; // Nombre de référentiels par page
    $current_page = isset($_GET['current_page']) ? max(1, intval($_GET['current_page'])) : 1;
    $total_items = count($referentiels);
    $total_pages = ceil($total_items / $items_per_page);

    // Calculer l'offset pour la pagination
    $offset = ($current_page - 1) * $items_per_page;

    // Récupérer les référentiels pour la page courante
    $paginated_referentiels = array_slice($referentiels, $offset, $items_per_page);
    // Récupérer la promotion active
    $current_promotion = $model['get_current_promotion']();

    // Rendu de la vue
    render('admin.layout.php', 'referentiel/list-all.html.php', [
        'current_promotion' => $current_promotion,
        'referentiels' => $paginated_referentiels,
        'current_page' => $current_page,
        'total_pages' => $total_pages,
        'total_items' => $total_items,
    ]);
}

// Affichage du formulaire d'ajout d'un référentiel
function add_referentiel_form() {
    global $model;

    // Vérification des droits d'accès (Admin uniquement)
    $user = check_profile(Enums\ADMIN);

    // Récupérer la promotion active
    $current_promotion = $model['get_current_promotion']();

    // Affichage de la vue
    render('admin.layout.php', 'referentiel/add.html.php', [
        'current_promotion' => $current_promotion,
        'user' => $user
    ]);
}

// Traitement de l'ajout d'un référentiel
function add_referentiel_process() {
    global $model, $validator_services, $session_services;
    
    // Vérification des droits d'accès (Admin uniquement)
    $user = check_profile(Enums\ADMIN);
    
    // Récupération des données du formulaire
    $name = $_POST['referentiel_name'] ?? '';
    $description = $_POST['referentiel_details'] ?? '';
    $promotion = $_POST['promotion'] ?? '';
    // Récupérer la promotion active
    $current_promotion = $model['get_current_promotion']();
    
    // Validation des données essentielles
    $errors = [];
    
    if ($validator_services['is_empty']($name)) {
        $errors['name'] = 'Le nom du référentiel est obligatoire';
    } elseif ($model['referentiel_name_exists']($name)) {
        $errors['name'] = 'Un référentiel avec ce nom existe déjà';
    }
    
    if ($validator_services['is_empty']($description)) {
        $errors['description'] = 'La description est obligatoire';
    }
    
    // S'il y a des erreurs, affichage du formulaire avec les erreurs
    if (!empty($errors)) {
        render('admin.layout.php', 'referentiel/list.html.php', [
            'current_promotion' => $current_promotion,
            'user' => $user,
            'errors' => $errors,
            'name' => $name,
            'description' => $description
        ]);
        return;
    }
    
    // Définir des valeurs par défaut pour les champs manquants
    $referentiel_data = [
        'name' => $name,
        'description' => $description,
        'capacite' => 30,  // Valeur par défaut
        'sessions' => 10,  // Valeur par défaut
        'image' => "assets/images/default-referentiel.jpg"  // Image par défaut
    ];
    
    // Création du référentiel
    $result = $model['create_referentiel']($referentiel_data);
    // Récupérer la promotion active
    $current_promotion = $model['get_current_promotion']();
    
    
    if (!$result) {
        $session_services['set_flash_message']('danger', 'Erreur lors de la création du référentiel');
        render('admin.layout.php', 'referentiel/list.html.php', [
            'current_promotion' => $current_promotion,
            'user' => $user,
            'name' => $name,
            'description' => $description
        ]);
        return;
    }
    
    // Si une promotion a été sélectionnée, affecter le référentiel
    if (!empty($promotion)) {
        $model['assign_referentiels_to_promotion']($promotion, [$result]);
    }
    
    // Redirection avec message de succès
    $session_services['set_flash_message']('success', 'Référentiel créé avec succès');
    redirect('?page=referentiels');
}

// Affichage du formulaire d'affectation de référentiels à une promotion
function assign_referentiels_form() {
    global $model;

    // Vérification des droits d'accès (Admin uniquement)
    check_profile(Enums\ADMIN);

    // Récupération de la promotion active
    $current_promotion = $model['get_current_promotion']();

    if (!$current_promotion) {
        redirect('?page=promotions');
        return;
    }

    // Récupération des référentiels affectés et non affectés
    $assigned_referentiels = $model['get_referentiels_by_promotion']($current_promotion['id']);
    $all_referentiels = $model['get_all_referentiels']();
    $unassigned_referentiels = array_filter($all_referentiels, function ($ref) use ($assigned_referentiels) {
        return !in_array($ref, $assigned_referentiels);
    });

    // Affichage de la vue
    render('admin.layout.php', 'referentiel/assign.html.php', [
        'model' => $model,
        'current_promotion' => $current_promotion,
        'assigned_referentiels' => $assigned_referentiels,
        'unassigned_referentiels' => $unassigned_referentiels,
    ]);
}

// Traitement de l'affectation de référentiels à une promotion
function assign_referentiels_process() {
    global $model, $session_services, $error_messages, $success_messages;
    
    // Vérification des droits d'accès (Admin uniquement)
    check_profile(Enums\ADMIN);
    
    // Récupération de la promotion courante
    $current_promotion = $model['get_current_promotion']();
    
    if (!$current_promotion) {
        $session_services['set_flash_message']('info', 'Aucune promotion active. Veuillez d\'abord activer une promotion.');
        redirect('?page=promotions');
        return;
    }
    
    // Récupération des référentiels sélectionnés
    $selected_referentiels = $_POST['referentiels'] ?? [];
    
    if (empty($selected_referentiels)) {
        $session_services['set_flash_message']('info', 'Aucun référentiel sélectionné.');
        redirect('?page=assign-referentiels');
        return;
    }
    
    // Affectation des référentiels à la promotion
    $result = $model['assign_referentiels_to_promotion']($current_promotion['id'], $selected_referentiels);
    
    if (!$result) {
        $session_services['set_flash_message']('danger', $error_messages['referentiel']['update_failed']);
        redirect('?page=assign-referentiels');
        return;
    }
    
    // Redirection vers la liste des référentiels de la promotion avec un message de succès
    $session_services['set_flash_message']('success', $success_messages['referentiel']['assigned']);
    redirect('?page=referentiels');
}

function assign_referentiels_to_promotion() {
    global $model, $session_services;
    
    $promotion_id = $_POST['promotion_id'] ?? null;
    $referentiel_ids = $_POST['referentiel_ids'] ?? [];
    
    if (!$promotion_id || !is_array($referentiel_ids)) {
        $session_services['set_flash_message']('error', 'Données invalides');
        redirect('?page=referentiels');
        return;
    }
    
    $result = $model['assign_referentiels_to_promotion']($promotion_id, $referentiel_ids);
    
    if ($result) {
        $session_services['set_flash_message']('success', 'Référentiels assignés avec succès');
    } else {
        $session_services['set_flash_message']('error', 'Erreur lors de l\'assignation');
    }
    
    redirect('?page=referentiels');
}

function update_referentiels_assignment() {
    global $model, $session_services;

    // Vérification des droits d'accès (Admin uniquement)
    check_profile(Enums\ADMIN);

    // Récupération de la promotion active
    $current_promotion = $model['get_current_promotion']();

    if (!$current_promotion) {
        $session_services['set_flash_message']('info', 'Aucune promotion active. Veuillez d\'abord activer une promotion.');
        redirect('?page=promotions');
        return;
    }

    // Récupérer les données du formulaire
    $move_to_assigned = $_POST['move_to_assigned'] ?? null;
    $move_to_unassigned = $_POST['move_to_unassigned'] ?? null;

    // Affecter un référentiel à la promotion active
    if ($move_to_assigned) {
        $model['assign_referentiels_to_promotion']($current_promotion['id'], [$move_to_assigned]);
    }

    // Désaffecter un référentiel de la promotion active
    if ($move_to_unassigned) {
        // Vérifier si le référentiel a des apprenants
        $referentiel_apprenants = $model['get_apprenants_by_referentiel']($move_to_unassigned, $current_promotion['id']);
        if (empty($referentiel_apprenants)) {
            $model['unassign_referentiels_from_promotion']($current_promotion['id'], [$move_to_unassigned]);
        } else {
            $session_services['set_flash_message']('error', 'Impossible de désaffecter un référentiel ayant des apprenants.');
        }
    }

    // Rediriger vers la page d'assignation
    redirect('?page=assign-referentiels');
}

function create_referentiel_form() {
    render('admin.layout.php', 'referentiel/create.html.php', []);
}

function create_referentiel_process() {
    global $model, $session_services;

    // Récupérer les données du formulaire
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $capacity = (int)($_POST['capacity'] ?? 0);
    $sessions = (int)($_POST['sessions'] ?? 0);
    $image = $_FILES['image'] ?? null;

    // Validation des champs
    $errors = [];
    if (empty($name)) {
        $errors['name'] = 'Le nom du référentiel est obligatoire.';
    } elseif ($model['referentiel_exists']($name)) {
        $errors['name'] = 'Le nom du référentiel doit être unique.';
    }

    if (empty($description)) {
        $errors['description'] = 'La description est obligatoire.';
    }

    if ($capacity <= 0) {
        $errors['capacity'] = 'La capacité doit être un nombre positif.';
    }

    if ($sessions <= 0) {
        $errors['sessions'] = 'Le nombre de sessions doit être un nombre positif.';
    }

    if ($image && $image['size'] > 2 * 1024 * 1024) {
        $errors['image'] = 'La taille de l\'image ne doit pas dépasser 2MB.';
    } elseif ($image && !in_array(strtolower(pathinfo($image['name'], PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png'])) {
        $errors['image'] = 'Le format de l\'image doit être JPG ou PNG.';
    }

    // Si des erreurs existent, retourner au formulaire
    if (!empty($errors)) {
        render('admin.layout.php', 'referentiel/create.html.php', ['errors' => $errors]);
        return;
    }

    // Enregistrer l'image
    $image_path = 'assets/images/referentiels/' . uniqid() . '.' . pathinfo($image['name'], PATHINFO_EXTENSION);
    move_uploaded_file($image['tmp_name'], $image_path);

    // Ajouter le référentiel
    $model['create_referentiel']([
        'name' => $name,
        'description' => $description,
        'capacity' => $capacity,
        'sessions' => $sessions,
        'image' => $image_path,
        'status' => 'inactive', // Statut par défaut
    ]);

    // Redirection avec message de succès
    $session_services['set_flash_message']('success', 'Référentiel créé avec succès.');
    redirect('?page=referentiels');
}