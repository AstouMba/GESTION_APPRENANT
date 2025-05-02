<?php

namespace App\Models;

$model = [
    'read_data' => function () {
        // Example structure, replace with actual implementation
        return ['users' => []];
    },
    'write_data' => function ($data) {
        // Example implementation, replace with actual logic
        return true;
    }
];

$user_model = [
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
];