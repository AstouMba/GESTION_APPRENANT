<?php
// Récupérer les erreurs et les anciennes valeurs depuis la session
$errors = $_SESSION['errors'] ?? [];
$old_inputs = $_SESSION['old_inputs'] ?? [];

// Supprimer les erreurs et les anciennes valeurs de la session après affichage
unset($_SESSION['errors'], $_SESSION['old_inputs']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
    /* Conteneur principal du formulaire */
.add-promotion-form {
    margin-top: 20px;
    padding: 20px;
    background-color: #f9f9f9;
    border: 1px solid #ddd;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

/* Titre principal */
.add-promotion-form h1 {
    font-size: 24px;
    font-weight: bold;
    color: #333;
    margin-bottom: 20px;
    text-align: center;
}

/* Groupes de champs */
.add-promotion-form .form-group {
    margin-bottom: 15px;
}

/* Labels */
.add-promotion-form label {
    display: block;
    font-size: 14px;
    font-weight: bold;
    color: #333;
    margin-bottom: 5px;
}

/* Champs de saisie */
.add-promotion-form input[type="text"],
.add-promotion-form input[type="date"],
.add-promotion-form input[type="file"],
.add-promotion-form select {
    width: 100%;
    padding: 10px;
    font-size: 14px;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-sizing: border-box;
}

/* Champs de saisie au focus */
.add-promotion-form input[type="text"]:focus,
.add-promotion-form input[type="date"]:focus,
.add-promotion-form input[type="file"]:focus,
.add-promotion-form select:focus {
    border-color: #007bff;
    outline: none;
    box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
}

/* Messages d'erreur */
.add-promotion-form .error-message {
    color: #dc3545;
    font-size: 12px;
    margin-top: 5px;
}

/* Référentiels */
.add-promotion-form #referentiels-list {
    flex-wrap: wrap;
    gap: 10px;
}

.add-promotion-form .referentiel-item {
    display: flex;
    align-items: center;
    gap: 5px;
}

.add-promotion-form .referentiel-item input[type="checkbox"] {
    margin: 0;
}

/* Boutons */
.add-promotion-form .form-buttons {
    text-align: center;
    margin-top: 20px;
}

.add-promotion-form .form-buttons .submit-button {
    padding: 10px 20px;
    font-size: 14px;
    font-weight: bold;
    color: #fff;
    background-color:rgb(9, 141, 64);
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.add-promotion-form .form-buttons .submit-button:hover {
    background-color: #0056b3;
}

/* Bouton Retour */
.back-button {
    margin-bottom: 20px;
}

.back-button a {
    display: inline-block;
    padding: 10px 20px;
    font-size: 14px;
    font-weight: bold;
    color: #fff;
    background-color: #6c757d;
    border: none;
    border-radius: 5px;
    text-decoration: none;
    transition: background-color 0.3s ease;
}

.back-button a:hover {
    background-color: #5a6268;
}

/* Formulaire en ligne pour les dates */
.add-promotion-form .form-row {
    display: flex;
    gap: 20px;
}

.add-promotion-form .form-row .form-group {
    flex: 1;
}
</style>
</head>
<body>
<div class="dashboard">
    <!-- Carte des apprenants -->
    <div class="stat-card">
        <div class="card-icon">
            <span></span>
        </div>
        <div class="card-content">
            <div class="card-value">0</div>
            <div class="card-label">Apprenants</div>
        </div>
    </div>
    
    <!-- Carte des référentiels -->
    <div class="stat-card">
        <div class="card-icon">
            <span></span>
        </div>
        <div class="card-content">
            <div class="card-value">0</div>
            <div class="card-label">Référentiels</div>
        </div>
    </div>
    
    <!-- Carte des stagiaires -->
    <div class="stat-card">
        <div class="card-icon">
            <span></span>
        </div>
        <div class="card-content">
            <div class="card-value">0</div>
            <div class="card-label">Stagiaires</div>
        </div>
    </div>
    
    <!-- Carte des permanents -->
    <div class="stat-card">
        <div class="card-icon">
            <span></span>
        </div>
        <div class="card-content">
            <div class="card-value">0</div>
            <div class="card-label">Permanent</div>
        </div>
    </div>
</div>

<!-- Note: Les autres parties du dashboard (graphiques, widgets, etc.) seront chargées 
           par d'autres pages se
<div class="container">
        <!- Bouton Retour -->
<div class="back-button">
        <a href="?page=promotions" class="btn btn-secondary">Retour à la liste</a>
    </div>
    <h1>Créer une nouvelle promotion</h1>
    
    
    <!-- Formulaire de création de promotion -->
    <div class="add-promotion-form">
        <form class="promotion-form" action="?page=add-promotion-process" method="POST" enctype="multipart/form-data">
            <!-- Nom de la promotion -->
            <div class="form-group">
                <label for="promotion-name">Nom de la promotion</label>
                <input type="text" id="promotion-name" name="name" placeholder="Ex: Promotion 2025" value="<?= htmlspecialchars($name ?? '') ?>">
                <?php if (!empty($errors['name'])): ?>
                    <div class="error-message"><?= htmlspecialchars($errors['name']) ?></div>
                <?php endif; ?>
            </div>
            
            <!-- Dates de début et de fin -->
            <div class="form-row">
                <div class="form-group">
                    <label for="start-date">Date de début</label>
                    <input type id="start-date" name="date_debut" value="<?= htmlspecialchars($date_debut ?? '') ?>">
                    <?php if (!empty($errors['date_debut'])): ?>
                        <div class="error-message"><?= htmlspecialchars($errors['date_debut']) ?></div>
                    <?php endif; ?>
                </div>
                
                <div class="form-group">
                    <label for="end-date">Date de fin</label>
                    <input type id="end-date" name="date_fin" value="<?= htmlspecialchars($date_fin ?? '') ?>">
                    <?php if (!empty($errors['date_fin'])): ?>
                        <div class="error-message"><?= htmlspecialchars($errors['date_fin']) ?></div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Image de la promotion -->
            <div class="form-group">
                <label for="promotion-image">Photo de la promotion</label>
                <input type="file" id="promotion-image" name="image" accept="image/png,image/jpeg">
                <?php if (!empty($errors['image'])): ?>
                    <div class="error-message"><?= htmlspecialchars($errors['image']) ?></div>
                <?php endif; ?>
            </div>
            
            <!-- Référentiels -->
            <div class="form-group">
                <label>Référentiels</label>
                <div id="referentiels-list">
                    <?php if (!empty($referentiels)): ?>
                        <?php foreach ($referentiels as $referentiel): ?>
                            <div class="referentiel-item">
                                <input type="checkbox" id="referentiel-<?= htmlspecialchars($referentiel['id']) ?>" 
                                       name="referentiels[]" 
                                       value="<?= htmlspecialchars($referentiel['id']) ?>">
                                <label for="referentiel-<?= htmlspecialchars($referentiel['id']) ?>">
                                    <?= htmlspecialchars($referentiel['name']) ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>Aucun référentiel disponible.</p>
                    <?php endif; ?>
                </div>
                <?php if (!empty($errors['referentiels'])): ?>
                    <div class="error-message"><?= htmlspecialchars($errors['referentiels']) ?></div>
                <?php endif; ?>
            </div>
            
            <!-- Boutons -->
            <div class="form-buttons">
                <button type="submit" class="submit-button">Créer la promotion</button>
            </div>
        </form>
    </div>
</div>
</body>
</html><!-- Dashboard - Cartes statistiques -->
