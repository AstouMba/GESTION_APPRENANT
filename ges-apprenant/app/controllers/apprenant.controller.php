<?php
namespace App\Controllers;

use App\Enums\ErrorMessages;

function list_apprenants() {
    global $model;

    // Récupérer tous les apprenants
    $apprenants = $model['get_all_apprenants']();

    // Filtrage par statut
    $statut = $_GET['statut'] ?? '';
    if (!empty($statut)) {
        $apprenants = array_filter($apprenants, function ($apprenant) use ($statut) {
            return $apprenant['statut'] === $statut;
        });
    }

    // Filtrage par référentiel
    $referentiel = $_GET['referentiel'] ?? '';
    if (!empty($referentiel)) {
        $apprenants = array_filter($apprenants, function ($apprenant) use ($referentiel) {
            return $apprenant['referentiel'] === $referentiel;
        });
    }

    // Liste des référentiels pour le filtre
    $referentiels = array_unique(array_column($apprenants, 'referentiel'));
     // Récupérer la promotion active
     $current_promotion = $model['get_current_promotion']();

    // Afficher la vue avec les données des apprenants
    render('admin.layout.php', 'apprenants/list-app.html.php', [
        'current_promotion' => $current_promotion,
        'apprenants' => $apprenants,
        'referentiels' => $referentiels,
    ]);
}

function add_apprenant() {
    global $model;

    // Exemple de données pour un nouvel apprenant
    $new_apprenant = [
        'nom' => 'Nouveau',
        'prenom' => 'Apprenant',
        'email' => 'nouveau.apprenant@example.com',
        'password' => password_hash('password123', PASSWORD_DEFAULT),
        'telephone' => '770000000',
        'photo' => 'assets/images/default-avatar.png',
        'adresse' => 'Adresse inconnue',
        'referentiel' => 'Référentiel inconnu',
        'statut' => 'active'
    ];

    // Ajouter l'apprenant
    $model['add_apprenant']($new_apprenant);

    // Rediriger vers la liste des apprenants
    redirect_to_route('apprenants');
    exit;
}

function add_apprenant_form() {
    global $model;

    // Récupérer la promotion active
    $current_promotion = $model['get_current_promotion']();

    // Afficher la vue du formulaire
    render('admin.layout.php', 'apprenants/add-app.html.php', [
        'current_promotion' => $current_promotion,
    ]);
}

function add_apprenant_process() {
    global $model;

    $errors = [];
    $nom = $_POST['nom'] ?? '';
    $prenom = $_POST['prenom'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $telephone = $_POST['telephone'] ?? '';
    $adresse = $_POST['adresse'] ?? '';
    $referentiel = $_POST['referentiel'] ?? '';
    $statut = $_POST['statut'] ?? '';

    $date_naissance = $_POST['date_naissance'] ?? '';
    $lieu_naissance = $_POST['lieu_naissance'] ?? '';
    $photo = $_FILES['photo'] ?? null;

    // Validation des champs
    if (empty($nom)) {
        $errors['nom'] = ErrorMessages::NOM_OBLIGATOIRE->value;
    }

    if (empty($prenom)) {
        $errors['prenom'] = ErrorMessages::PRENOM_OBLIGATOIRE->value;
    }

    if (empty($email)) {
        $errors['email'] = ErrorMessages::EMAIL_OBLIGATOIRE->value;
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = ErrorMessages::EMAIL_INVALIDE->value;
    }

    if (empty($password)) {
        $errors['password'] = ErrorMessages::PASSWORD_OBLIGATOIRE->value;
    }

    if (empty($referentiel)) {
        $errors['referentiel'] = ErrorMessages::REFERENTIEL_OBLIGATOIRE->value;
    }

    if (empty($statut)) {
        $errors['statut'] = ErrorMessages::STATUT_OBLIGATOIRE->value;
    }

    if (empty($date_naissance)) {
        $errors['date_naissance'] = ErrorMessages::DATE_NAISSANCE_OBLIGATOIRE->value;
    }

    if (empty($lieu_naissance)) {
        $errors['lieu_naissance'] = ErrorMessages::LIEU_NAISSANCE_OBLIGATOIRE->value;
    }

    if (empty($adresse)) {
        $errors['adresse'] = ErrorMessages::ADRESSE_OBLIGATOIRE->value;
    }

    if (empty($telephone)) {
        $errors['telephone'] = ErrorMessages::TELEPHONE_OBLIGATOIRE->value;
    } elseif (!preg_match('/^\d{9}$/', $telephone)) {
        $errors['telephone'] = ErrorMessages::TELEPHONE_INVALIDE->value;
    }

    if (empty($photo) || $photo['error'] === UPLOAD_ERR_NO_FILE) {
        $errors['photo'] = ErrorMessages::PHOTO_OBLIGATOIRE->value;
    } elseif (!in_array($photo['type'], ['image/jpeg', 'image/png'])) {
        $errors['photo'] = ErrorMessages::PHOTO_FORMAT_INVALIDE->value;
    }

    $tuteur_nom = $_POST['tuteur_nom'] ?? '';
    $tuteur_prenom = $_POST['tuteur_prenom'] ?? '';
    $tuteur_email = $_POST['tuteur_email'] ?? '';
    $tuteur_telephone = $_POST['tuteur_telephone'] ?? '';

    // Validation des champs du tuteur
    if (empty($tuteur_nom)) {
        $errors['tuteur_nom'] = ErrorMessages::NOM_OBLIGATOIRE->value;
    }

    if (empty($tuteur_prenom)) {
        $errors['tuteur_prenom'] = ErrorMessages::PRENOM_OBLIGATOIRE->value;
    }

    if (empty($tuteur_email)) {
        $errors['tuteur_email'] = ErrorMessages::EMAIL_OBLIGATOIRE->value;
    } elseif (!filter_var($tuteur_email, FILTER_VALIDATE_EMAIL)) {
        $errors['tuteur_email'] = ErrorMessages::EMAIL_INVALIDE->value;
    }

    if (empty($tuteur_telephone)) {
        $errors['tuteur_telephone'] = ErrorMessages::TUTEUR_TELEPHONE_OBLIGATOIRE->value;
    }
    
 // Récupérer la promotion active
 $current_promotion = $model['get_current_promotion']();

    // Si des erreurs existent, réafficher le formulaire avec les erreurs
    if (!empty($errors)) {
        render('admin.layout.php', 'apprenants/add-app.html.php', [
            'current_promotion' => $current_promotion,

            'errors' => $errors,
        ]);
        return;
    }

    // Ajouter l'apprenant si tout est valide
    $new_apprenant = [
        'nom' => $nom,
        'prenom' => $prenom,
        'email' => $email,
        'password' => password_hash($password, PASSWORD_DEFAULT),
        'telephone' => $telephone,
        'adresse' => $adresse,
        'referentiel' => $referentiel,
        'statut' => $statut,
        'photo' => 'assets/images/default-avatar.png',
    ];

    // Gérer l'upload de la photo
    if (!empty($_FILES['photo']['name'])) {
        $upload_dir = 'assets/images/apprenants/';
        $photo_name = uniqid() . '-' . $_FILES['photo']['name'];
        $upload_path = $upload_dir . $photo_name;

        if (move_uploaded_file($_FILES['photo']['tmp_name'], $upload_path)) {
            $new_apprenant['photo'] = $upload_path;
        }
    }

    $model['add_apprenant']($new_apprenant);

    // Rediriger vers la liste des apprenants
    redirect_to_route('apprenants');
    exit;
}

function show_apprenant_details() {
    $json_file = __DIR__ . '/../data/data.json';

    // Vérifiez si le fichier JSON existe
    if (!file_exists($json_file)) {
        render('admin.layout.php', 'errors/404.html.php', [
            'message' => 'Fichier des apprenants introuvable.'
        ]);
        return;
    }

    // Récupérez les apprenants depuis le fichier JSON
    $data = json_decode(file_get_contents($json_file), true);
    $apprenants = $data['apprenants'] ?? [];

    // Récupérez l'ID de l'apprenant depuis les paramètres GET
    $apprenant_id = $_GET['id'] ?? null;

    if (!$apprenant_id) {
        render('admin.layout.php', 'errors/404.html.php', [
            'message' => 'ID de l’apprenant manquant.'
        ]);
        return;
    }

    // Trouvez l'apprenant correspondant
    $apprenant = null;
    foreach ($apprenants as $item) {
        if ($item['id'] == $apprenant_id) {
            $apprenant = $item;
            break;
        }
    }

    if (!$apprenant) {
        render('admin.layout.php', 'errors/404.html.php', [
            'message' => 'Apprenant introuvable.'
        ]);
        return;
    }

    // Affichez la vue des détails de l'apprenant
    render('admin.layout.php', 'apprenants/details-app.html.php', [
        'apprenant' => $apprenant
    ]);
}

