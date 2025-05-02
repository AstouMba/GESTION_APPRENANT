<?php

namespace App\Models;

require_once __DIR__ . '/../enums/path.enum.php';

$core_model = [
    'read_data' => function () {
        return json_to_array('Enums\DATA_PATH');
    },
    
    'write_data' => function ($data) {
        return array_to_json(\App\Enums\DATA_PATH, $data);
    },
    
    'generate_id' => function () {
        return uniqid();
    }
];