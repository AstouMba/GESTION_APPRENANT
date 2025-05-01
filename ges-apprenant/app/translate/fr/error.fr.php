<?php

namespace App\Translate\fr;

$error_messages = [
    'form' => [
        'required' => 'Ce champ est obligatoire',
        'email' => 'Veuillez saisir une adresse email valide',
        'min_length' => 'Ce champ doit contenir au moins %d caractères',
        'max_length' => 'Ce champ ne doit pas dépasser %d caractères',
        'invalid_image' => 'Le fichier doit être une image valide (JPG ou PNG) de moins de 2MB'
    ],
    'auth' => [
        'invalid_credentials' => 'Email ou mot de passe incorrect',
        'not_logged_in' => 'Veuillez vous connecter pour accéder à cette page'
    ],
    'referentiel' => [
        'name_exists' => 'Un référentiel avec ce nom existe déjà',
        'create_failed' => 'Erreur lors de la création du référentiel',
        'update_failed' => 'Erreur lors de la mise à jour du référentiel'
    ],
    'promotion' => [
        'name_required' => 'Le champ "Nom de la promotion" est obligatoire.',
        'date_debut_required' => 'La date de début est obligatoire.',
        'date_fin_required' => 'La date de fin est obligatoire.',
        'referentiels_required' => 'Veuillez sélectionner au moins un référentiel.',
        'image_required' => 'Veuillez télécharger une image pour la promotion.',
        'invalid_date_range' => 'La date de fin doit être postérieure à la date de début.'
    ]
];

return $error_messages;