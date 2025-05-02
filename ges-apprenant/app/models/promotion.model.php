<?php

namespace App\Models;

use App\Enums\Status;

$model = [
    'read_data' => function () {
        return ['promotions' => []]; // Initialize with default data
    },
    'write_data' => function ($data) {
        // Simulate writing data (e.g., to a file or database)
        return true;
    }
];

$promotion_model = [
    'get_all_promotions' => function () use (&$model) {
        $data = $model['read_data']();
        return $data['promotions'] ?? [];
    },
    
    'get_promotion_by_id' => function ($id) use (&$model) {
        $data = $model['read_data']();
        $filtered_promotions = array_filter($data['promotions'] ?? [], function ($promotion) use ($id) {
            return $promotion['id'] === $id;
        });
        return !empty($filtered_promotions) ? reset($filtered_promotions) : null;
    },

    'get_current_promotion' => function () use (&$model) {
        $data = $model['read_data']();
        $promotions = $data['promotions'] ?? [];
        
        if (empty($promotions)) {
            return null;
        }
        
        foreach ($promotions as $promotion) {
            if ($promotion['status'] === 'active') {
                return $promotion;
            }
        }
        return null;
    },

    'create_promotion' => function(array $promotion_data) use (&$model) {
        $data = $model['read_data']();
        
        if (!isset($data['promotions'])) {
            $data['promotions'] = [];
        }
        
        $promotion_data['id'] = uniqid();
        $promotion_data['status'] = 'inactive';
        $data['promotions'][] = $promotion_data;
        return $model['write_data']($data);
    }
];