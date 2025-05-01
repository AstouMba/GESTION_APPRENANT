<?php

namespace App\Models;

require_once __DIR__ . '/../enums/path.enum.php';
require_once __DIR__ . '/../enums/status.enum.php';
require_once __DIR__ . '/../enums/profile.enum.php';
require_once __DIR__ . '/../helpers/functions.php';

use App\Enums;
use App\Enums\Status; // Ajout de cette ligne

/**
 * @var array<string, callable> $model
 */
$model = [
    // Fonctions de base pour manipuler les données
    'read_data' => function () {
        return json_to_array(Enums\DATA_PATH);
    },
    
    'write_data' => function ($data) {
        return array_to_json(Enums\DATA_PATH, $data);
    },
    
    'generate_id' => function () {
        return uniqid();
    },
    
    // Fonctions d'authentification
    'authenticate' => function ($email, $password) use (&$model) {
        $data = $model['read_data']();
        
        // Utiliser array_filter au lieu de foreach
        $filtered_users = array_filter($data['users'], function ($user) use ($email, $password) {
            return $user['email'] === $email && $user['password'] === $password;
        });
        
        // Si aucun utilisateur ne correspond
        if (empty($filtered_users)) {
            return null;
        }
        
        // Récupérer le premier utilisateur qui correspond
        return reset($filtered_users);
    },
    
    'get_user_by_email' => function ($email) use (&$model) {
        $data = $model['read_data']();
        
        // Utiliser array_filter au lieu de foreach
        $filtered_users = array_filter($data['users'], function ($user) use ($email) {
            return $user['email'] === $email;
        });
        
        // Si aucun utilisateur ne correspond
        if (empty($filtered_users)) {
            return null;
        }
        
        // Récupérer le premier utilisateur qui correspond
        return reset($filtered_users);
    },
    
    'get_user_by_id' => function ($user_id) use (&$model) {
        $data = $model['read_data']();
        
        // Utiliser array_filter au lieu de foreach
        $filtered_users = array_filter($data['users'], function ($user) use ($user_id) {
            return $user['id'] === $user_id;
        });
        
        // Si aucun utilisateur ne correspond
        if (empty($filtered_users)) {
            return null;
        }
        
        // Récupérer le premier utilisateur qui correspond
        return reset($filtered_users);
    },
    
    'change_password' => function ($user_id, $new_password) use (&$model) {
        $data = $model['read_data']();
        
        $user_indices = array_keys(array_filter($data['users'], function($user) use ($user_id) {
            return $user['id'] === $user_id;
        }));
        
        if (empty($user_indices)) {
            return false;
        }
        
        $user_index = reset($user_indices);
        
        // Mettre à jour le mot de passe (sans cryptage)
        $data['users'][$user_index]['password'] = $new_password;
        
        // Sauvegarder les modifications
        return $model['write_data']($data);
    },
    
    // Fonctions pour les promotions
    'get_all_promotions' => function () use (&$model) {
        $data = $model['read_data']();
        return $data['promotions'] ?? [];
    },
    
    'get_promotion_by_id' => function ($id) use (&$model) {
        $data = $model['read_data']();
        
        // Utiliser array_filter au lieu de foreach
        $filtered_promotions = array_filter($data['promotions'] ?? [], function ($promotion) use ($id) {
            return $promotion['id'] === $id;
        });
        
        return !empty($filtered_promotions) ? reset($filtered_promotions) : null;
    },
    
    'promotion_name_exists' => function(string $name) use (&$model): bool {
        $data = $model['read_data']();
        
        foreach ($data['promotions'] as $promotion) {
            if (strtolower($promotion['name']) === strtolower($name)) {
                return true;
            }
        }
        
        return false;
    },
    
    'create_promotion' => function(array $promotion_data) use (&$model) {
        $data = $model['read_data']();
        
        // Générer un nouvel ID
        $max_id = 0;
        foreach ($data['promotions'] as $promotion) {
            $max_id = max($max_id, (int)$promotion['id']);
        }
        
        $promotion_data['id'] = $max_id + 1;
        $promotion_data['status'] = 'inactive'; // Statut inactif par défaut
        
        // Ajouter la promotion
        $data['promotions'][] = $promotion_data;
        
        // Sauvegarder les données
        return $model['write_data']($data);
    },
    
    'update_promotion' => function ($id, $promotion_data) use (&$model) {
        $data = $model['read_data']();
        
        // Trouver l'index de la promotion
        $promotion_indices = array_keys(array_filter($data['promotions'], function($promotion) use ($id) {
            return $promotion['id'] === $id;
        }));
        
        if (empty($promotion_indices)) {
            return false;
        }
        
        $promotion_index = reset($promotion_indices);
        
        // Mettre à jour les données de la promotion
        $data['promotions'][$promotion_index] = array_merge(
            $data['promotions'][$promotion_index],
            $promotion_data
        );
        
        if ($model['write_data']($data)) {
            return $data['promotions'][$promotion_index];
        }
        
        return null;
    },
    
    'toggle_promotion_status' => function(int $promotion_id) use (&$model) {
        $data = $model['read_data']();
        
        // Trouver la promotion à modifier
        $target_promotion = null;
        $target_index = null;
        
        foreach ($data['promotions'] as $index => $promotion) {
            if ((int)$promotion['id'] === $promotion_id) {
                $target_promotion = $promotion;
                $target_index = $index;
                break;
            }
        }
        
        if ($target_index === null) {
            return false;
        }
        
        // Si la promotion est inactive
        if ($target_promotion['status'] === Status::INACTIVE->value) {
            // Désactiver toutes les promotions
            $data['promotions'] = array_map(function($p) {
                $p['status'] = Status::INACTIVE->value;
                return $p;
            }, $data['promotions']);
            
            // Activer la promotion ciblée
            $data['promotions'][$target_index]['status'] = Status::ACTIVE->value;
        } else {
            // Si la promotion est active, la désactiver
            $data['promotions'][$target_index]['status'] = Status::INACTIVE->value;
        }
        
        // Sauvegarder les modifications
        if ($model['write_data']($data)) {
            return $data['promotions'][$target_index];
        }
        
        return null;
    },
    
    'search_promotions' => function($search_term) use (&$model) {
        $promotions = $model['get_all_promotions']();
        
        if (empty($search_term)) {
            return $promotions;
        }
        
        return array_values(array_filter($promotions, function($promotion) use ($search_term) {
            return stripos($promotion['name'], $search_term) !== false;
        }));
    },
    
    // Fonctions pour les référentiels
    'get_all_referentiels' => function () use (&$model) {
        $data = $model['read_data'](); 
        return $data['referentiels'] ?? [];
    },
    
    'get_referentiel_by_id' => function ($id) use (&$model) {
        $data = $model['read_data']();
        
        // Utiliser array_filter au lieu de foreach
        $filtered_referentiels = array_filter($data['referentiels'] ?? [], function ($referentiel) use ($id) {
            return $referentiel['id'] === $id;
        });
        
        return !empty($filtered_referentiels) ? reset($filtered_referentiels) : null;
    },
    
    'referentiel_name_exists' => function ($name, $exclude_id = null) use (&$model) {
        $data = $model['read_data']();
        
        // Utiliser array_filter au lieu de foreach
        $filtered_referentiels = array_filter($data['referentiels'] ?? [], function ($referentiel) use ($name, $exclude_id) {
            return strtolower($referentiel['name']) === strtolower($name) && ($exclude_id === null || $referentiel['id'] !== $exclude_id);
        });
        
        return !empty($filtered_referentiels);
    },
    
    'create_referentiel' => function ($referentiel_data) use (&$model) {
        $data = $model['read_data']();
        
        $new_referentiel = [
            'id' => $model['generate_id'](),
            'name' => $referentiel_data['name'],
            'description' => $referentiel_data['description'],
            'image' => $referentiel_data['image'],
            'capacite' => $referentiel_data['capacite'],
            'sessions' => $referentiel_data['sessions'],
            'modules' => []
        ];
        
        $data['referentiels'][] = $new_referentiel;
        
        if ($model['write_data']($data)) {
            return $new_referentiel;
        }
        
        return null;
    },
    
    'get_referentiels_by_promotion' => function($promotion_id) use (&$model) {
        $data = $model['read_data']();
        
        // Trouver la promotion
        $promotion = null;
        foreach ($data['promotions'] as $p) {
            if ($p['id'] == $promotion_id) {
                $promotion = $p;
                break;
            }
        }
        
        if (!$promotion || empty($promotion['referentiels'])) {
            return [];
        }
        
        // Récupérer les référentiels associés
        return array_filter($data['referentiels'], function($ref) use ($promotion) {
            return in_array($ref['id'], $promotion['referentiels']);
        });
    },
    
    'assign_referentiels_to_promotion' => function ($promotion_id, $referentiel_ids) use (&$model) {
        $data = $model['read_data']();
        
        // Trouver l'index de la promotion
        $promotion_indices = array_keys(array_filter($data['promotions'], function($promotion) use ($promotion_id) {
            return $promotion['id'] === $promotion_id;
        }));
        
        if (empty($promotion_indices)) {
            return false;
        }
        
        $promotion_index = reset($promotion_indices);
        
        // Ajouter les référentiels à la promotion
        if (!isset($data['promotions'][$promotion_index]['referentiels'])) {
            $data['promotions'][$promotion_index]['referentiels'] = [];
        }
        
        $data['promotions'][$promotion_index]['referentiels'] = array_unique(
            array_merge($data['promotions'][$promotion_index]['referentiels'], $referentiel_ids)
        );
        
        return $model['write_data']($data);
    },
    
    'unassign_referentiels_from_promotion' => function ($promotion_id, $referentiel_ids) use (&$model) {
        $data = $model['read_data']();
        
        // Trouver la promotion
        foreach ($data['promotions'] as &$promotion) {
            if ($promotion['id'] === $promotion_id) {
                // Supprimer les référentiels spécifiés
                $promotion['referentiels'] = array_diff($promotion['referentiels'], $referentiel_ids);
                break;
            }
        }
        
        // Sauvegarder les modifications
        return $model['write_data']($data);
    },
    
    'search_referentiels' => function(string $query) use (&$model) {
        $referentiels = $model['get_all_referentiels']();
        if (empty($query)) {
            return $referentiels;
        }
        
        return array_filter($referentiels, function($ref) use ($query) {
            return stripos($ref['name'], $query) !== false || 
                   stripos($ref['description'], $query) !== false;
        });
    },
    
    // Fonction pour récupérer la promotion active courante
    'get_current_promotion' => function () use (&$model) {
        $data = $model['read_data']();
        foreach ($data['promotions'] as $promotion) {
            if ($promotion['status'] === 'active') {
                return $promotion;
            }
        }
        return null; // Aucune promotion active
    },
    
    // Statistiques diverses pour le tableau de bord
    'get_promotions_stats' => function () use (&$model) {
        $data = $model['read_data']();
        
        // Nombre total de promotions
        $total_promotions = count($data['promotions'] ?? []);
        
        // Nombre de promotions actives
        $active_promotions = count(array_filter($data['promotions'] ?? [], function ($promotion) {
            return $promotion['status'] === Enums\ACTIVE;
        }));
        
        // Récupérer la promotion courante
        $current_promotion = $model['get_current_promotion']();
        
        // Nombre d'apprenants dans la promotion courante
        $current_promotion_apprenants = 0;
        if ($current_promotion) {
            $current_promotion_apprenants = count(array_filter($data['apprenants'] ?? [], function ($apprenant) use ($current_promotion) {
                return $apprenant['promotion_id'] === $current_promotion['id'];
            }));
        }
        
        // Nombre de référentiels dans la promotion courante
        $current_promotion_referentiels = 0;
        if ($current_promotion) {
            $current_promotion_referentiels = count($current_promotion['referentiels'] ?? []);
        }
        
        return [
            'total_promotions' => $total_promotions,
            'active_promotions' => $active_promotions,
            'current_promotion_apprenants' => $current_promotion_apprenants,
            'current_promotion_referentiels' => $current_promotion_referentiels
        ];
    },
    
    // Fonctions pour les apprenants
    'get_all_apprenants' => function () use (&$model) {
        $data = $model['read_data']();
        return $data['apprenants'] ?? [];
    },
    
    'get_apprenants_by_promotion' => function ($promotion_id) use (&$model) {
        $data = $model['read_data']();
        
        // Filtrer les apprenants par promotion
        $apprenants = array_filter($data['apprenants'] ?? [], function ($apprenant) use ($promotion_id) {
            return $apprenant['promotion_id'] === $promotion_id;
        });
        
        return array_values($apprenants);
    },
    
    'get_apprenant_by_id' => function ($id) use (&$model) {
        $data = $model['read_data']();
        
        // Filtrer les apprenants par ID
        $filtered_apprenants = array_filter($data['apprenants'] ?? [], function ($apprenant) use ($id) {
            return $apprenant['id'] === $id;
        });
        
        return !empty($filtered_apprenants) ? reset($filtered_apprenants) : null;
    },
    
    'get_apprenant_by_matricule' => function ($matricule) use (&$model) {
        $data = $model['read_data']();
        
        // Filtrer les apprenants par matricule
        $filtered_apprenants = array_filter($data['apprenants'] ?? [], function ($apprenant) use ($matricule) {
            return $apprenant['matricule'] === $matricule;
        });
        
        return !empty($filtered_apprenants) ? reset($filtered_apprenants) : null;
    },
    
    'generate_matricule' => function () use (&$model) {
        $data = $model['read_data']();
        $year = date('Y');
        $count = count($data['apprenants'] ?? []) + 1;
        
        return 'ODC-' . $year . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
    },
    
    'get_statistics' => function() use (&$model) {
        $data = $model['read_data']();
        
        // Trouver la promotion active
        $active_promotions = array_filter($data['promotions'], function($promotion) {
            return $promotion['status'] === 'active';
        });
        $active_promotion = reset($active_promotions);
        
        // Calculer les statistiques
        $stats = [
            'active_learners' => 0,
            'total_referentials' => count($data['referentiels'] ?? []),
            'active_promotions' => count($active_promotions),
            'total_promotions' => count($data['promotions'] ?? [])
        ];
        
        // Ajouter le nombre d'apprenants de la promotion active
        if ($active_promotion) {
            $stats['active_learners'] = count($active_promotion['apprenants'] ?? []);
        }
        
        return $stats;
    },
    
    'get_apprenants_by_referentiel' => function ($referentiel_id, $promotion_id) use (&$model) {
        $data = $model['read_data']();
    
        // Trouver la promotion
        $promotion = $model['get_promotion_by_id']($promotion_id);
        if (!$promotion || empty($promotion['apprenants'])) {
            return [];
        }
    
        // Filtrer les apprenants associés au référentiel
        return array_filter($promotion['apprenants'], function ($apprenant) use ($referentiel_id) {
            return isset($apprenant['referentiel_id']) && $apprenant['referentiel_id'] === $referentiel_id;
        });
    },
    
    'add_apprenant' => function ($apprenant_data) use (&$model) {
        $data = $model['read_data']();

        // Générer un ID unique et un matricule
        $apprenant_data['id'] = uniqid();
        $apprenant_data['matricule'] = $model['generate_matricule']();

        // Ajouter l'apprenant
        $data['apprenants'][] = $apprenant_data;

        // Sauvegarder les données
        return $model['write_data']($data);
    }
];

function create_referentiel($data) {
    $file_path = __DIR__ . '/../data/data.json';
    $referentiels = json_to_array($file_path);

    $data['id'] = uniqid(); // Générer un ID unique
    $referentiels[] = $data;

    array_to_json($file_path, $referentiels);
}

function referentiel_exists($name) {
    $file_path = __DIR__ . '/../data/data.json';
    $referentiels = json_to_array($file_path);

    foreach ($referentiels as $ref) {
        if (strtolower($ref['name']) === strtolower($name)) {
            return true;
        }
    }

    return false;
}