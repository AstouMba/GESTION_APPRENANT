<?php

namespace App\Models;

$model = [
    'read_data' => function () {
        // Example implementation: return an array of data
        return ['apprenants' => []];
    },
    'write_data' => function ($data) {
        // Example implementation: save data and return true
        return true;
    }
];

$apprenant_model = [
    'get_all_apprenants' => function () use (&$model) {
        $data = $model['read_data']();
        return $data['apprenants'] ?? [];
    },
    
    'get_apprenant_by_id' => function ($id) use (&$model) {
        $data = $model['read_data']();
        $filtered_apprenants = array_filter($data['apprenants'] ?? [], function ($apprenant) use ($id) {
            return $apprenant['id'] === $id;
        });
        return !empty($filtered_apprenants) ? reset($filtered_apprenants) : null;
    },

    'generate_matricule' => function () use (&$model) {
        $data = $model['read_data']();
        $year = date('Y');
        $count = count($data['apprenants'] ?? []) + 1;
        return 'ODC-' . $year . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
    },

    'add_apprenant' => function ($apprenant_data) use (&$model) {
        $data = $model['read_data']();
        $apprenant_data['id'] = uniqid();
        $apprenant_data['matricule'] = $model['generate_matricule']();
        $data['apprenants'][] = $apprenant_data;
        return $model['write_data']($data);
    }
];