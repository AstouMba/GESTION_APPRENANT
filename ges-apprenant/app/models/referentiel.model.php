<?php

namespace App\Models;

$referentiel_model = [
    'get_all_referentiels' => function () use (&$model) {
        if (!isset($model) || !is_array($model) || !isset($model['read_data'])) {
            throw new \Exception("Model is not properly initialized.");
        }
        $data = $model['read_data']();
        return $data['referentiels'] ?? [];
    },
    
    'get_referentiel_by_id' => function ($id) use (&$model) {
        $data = $model['read_data']();
        $filtered_referentiels = array_filter($data['referentiels'] ?? [], function ($referentiel) use ($id) {
            return $referentiel['id'] === $id;
        });
        return !empty($filtered_referentiels) ? reset($filtered_referentiels) : null;
    },

    // 'add_referentiel' => function ($referentiel_data) use (&$model) {
    //     $data = $model['read_data']();
    //     $referentiel_data['id'] = uniqid();
    //     $data['referentiels'][] = $referentiel_data;
    //     return $model['write_data']($data);
    // },

    'update_referentiel' => function ($id, $referentiel_data) use (&$model) {
        $data = $model['read_data']();
        foreach ($data['referentiels'] as &$referentiel) {
            if ($referentiel['id'] === $id) {
                $referentiel = array_merge($referentiel, $referentiel_data);
                return $model['write_data']($data);
            }
        }
        return false;
    }
];