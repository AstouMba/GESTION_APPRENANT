<?php

namespace App\Models;

require_once __DIR__ . '/core.model.php';
require_once __DIR__ . '/user.model.php';
require_once __DIR__ . '/promotion.model.php';
require_once __DIR__ . '/apprenant.model.php';
require_once __DIR__ . '/referentiel.model.php';

$model = array_merge(
    $core_model,
    $user_model, 
    $promotion_model,
    $apprenant_model,
    $referentiel_model
);