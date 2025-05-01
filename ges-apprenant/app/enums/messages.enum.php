<?php

namespace App\Enums;

enum Messages: string {
    case INVALID_REQUEST = 'Requête invalide';
    case IMAGE_UPLOAD_ERROR = 'Erreur lors du téléchargement de l\'image';
    case PROMOTION_ERROR = 'Une erreur est survenue lors de la modification du statut de la promotion';
    case PROMOTION_ACTIVATED = 'La promotion a été activée avec succès';
    case PROMOTION_INACTIVE = 'La promotion a été désactivée avec succès';
    case PROMOTION_CREATE_ERROR = 'Erreur lors de la création de la promotion';
    case PROMOTION_CREATED = 'La promotion a été créée avec succès';
}

enum ErrorMessages: string {
    case NOM_OBLIGATOIRE = 'Le champ Nom est obligatoire.';
    case PRENOM_OBLIGATOIRE = 'Le champ Prénom est obligatoire.';
    case EMAIL_OBLIGATOIRE = 'Le champ Email est obligatoire.';
    case EMAIL_INVALIDE = 'Le format de l\'email est invalide.';
    case PASSWORD_OBLIGATOIRE = 'Le champ Mot de passe est obligatoire.';
    case REFERENTIEL_OBLIGATOIRE = 'Le champ Référentiel est obligatoire.';
    case STATUT_OBLIGATOIRE = 'Le champ Statut est obligatoire.';

    case DATE_NAISSANCE_OBLIGATOIRE = 'La date de naissance est obligatoire.';

    case LIEU_NAISSANCE_OBLIGATOIRE = 'Le lieu de naissance est obligatoire.';

    case ADRESSE_OBLIGATOIRE = 'L\'adresse est obligatoire.';

    case TELEPHONE_OBLIGATOIRE = 'Le numéro de téléphone est obligatoire.';

    case TELEPHONE_INVALIDE = 'Le numéro de téléphone doit contenir 9 chiffres.';

    case PHOTO_OBLIGATOIRE = 'La photo est obligatoire.';

    case PHOTO_FORMAT_INVALIDE = 'La photo doit être au format JPEG ou PNG.';
    case TUTEUR_TELEPHONE_OBLIGATOIRE = 'Le champ Téléphone du tuteur est obligatoire.';

}


enum ErrorsMessages: string {
    case PROMOTION_NAME_REQUIRED = 'Le nom de la promotion est obligatoire.';
    case PROMOTION_DATE_DEBUT_REQUIRED = 'La date de début est obligatoire.';
    case PROMOTION_DATE_FIN_REQUIRED = 'La date de fin est obligatoire.';
    case PROMOTION_REFERENTIELS_REQUIRED = 'Veuillez sélectionner au moins un référentiel.';
    case PROMOTION_IMAGE_REQUIRED = 'L\'image de la promotion est obligatoire.';
    case PROMOTION_DATE_RANGE_INVALID = 'La date de début doit être antérieure à la date de fin.';
}

