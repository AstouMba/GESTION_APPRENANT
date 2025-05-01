<?php
// Récupérer les données passées à la vue
$model = $model ?? null;
$current_promotion = $current_promotion ?? null;
$assigned_referentiels = $assigned_referentiels ?? [];
$unassigned_referentiels = $unassigned_referentiels ?? [];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assigner des référentiels</title>
    <style>
        /* Reset et styles généraux */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Arial, sans-serif;
        }

        body {
            background-color: #f5f7fa;
            color: #333;
            line-height: 1.6;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Header */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .header h1 {
            font-size: 24px;
            font-weight: 600;
            color: #2c3e50;
        }

        /* Boutons */
        .btn {
            padding: 10px 20px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
        }

        .btn-back {
            background-color: #6c757d;
            color: white;
        }

        .btn-back:hover {
            background-color: #5a6268;
        }

        .btn-teal {
            background-color: #0E8F7E;
            color: white;
        }

        .btn-teal:hover {
            background-color: #0c745f;
        }

        .btn-primary {
            background-color: #007bff;
            color: white;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        /* Section de recherche */
        .search-section {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 30px;
        }

        /* Barre de recherche */
        .search-bar {
            position: relative;
            width: 100%;
            max-width: 400px;
        }

        .search-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
        }

        .search-bar input {
            width: 100%;
            padding: 12px 15px 12px 45px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .search-bar input:focus {
            outline: none;
            border-color: #0E8F7E;
            box-shadow: 0 0 0 3px rgba(14, 143, 126, 0.1);
        }

        .search-buttons {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }

        /* Cards container */
        .cards-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .card {
            background-color: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
        }

        .card-image {
            width: 100%;
            height: 160px;
            overflow: hidden;
        }

        .card-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .card-content {
            padding: 20px;
        }

        .card-title {
            font-size: 18px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 8px;
        }

        .card-subtitle {
            font-size: 14px;
            color: #0E8F7E;
            margin-bottom: 10px;
        }

        .card-description {
            font-size: 14px;
            color: #666;
            margin-bottom: 15px;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* Checkbox styling */
        .form-check {
            display: flex;
            align-items: center;
            margin-top: 15px;
        }

        .form-check input[type="checkbox"] {
            width: 18px;
            height: 18px;
            margin-right: 10px;
            accent-color: #0E8F7E;
            cursor: pointer;
        }

        .form-check label {
            font-size: 14px;
            cursor: pointer;
            user-select: none;
        }

        /* Action buttons */
        .action-buttons {
            display: flex;
            justify-content: flex-end;
            margin-top: 30px;
        }

        /* No data message */
        .no-data {
            text-align: center;
            padding: 40px;
            font-size: 16px;
            color: #666;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }

            .search-bar {
                max-width: 100%;
            }

            .cards-container {
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            }
        }

        @media (max-width: 480px) {
            .container {
                padding: 10px;
            }

            .header h1 {
                font-size: 20px;
            }

            .cards-container {
                grid-template-columns: 1fr;
            }
        }

        .referentiels-section {
            display: flex;
            gap: 20px;
            margin-top: 20px;
        }

        .referentiels-column {
            flex: 1;
            background-color: #f9f9f9;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .referentiels-column h3 {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 15px;
            text-align: center;
        }

        .referentiels-column ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .referentiels-column li {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            margin-bottom: 10px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }

        .referentiels-column li button {
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 5px 10px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .referentiels-column li button:hover {
            background-color: #0056b3;
        }

        .error-message {
            color: #dc3545;
            font-size: 12px;
            margin-left: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Affecter/Désaffecter des Référentiels</h1>
        <h2>Promotion : <?= htmlspecialchars($current_promotion['name']) ?></h2>

        <!-- Bouton Retour -->
        <div class="action-buttons">
            <a href="?page=list-referentiels" class="btn btn-back">↩ Retour aux référentiels</a>
        </div>

        <form action="?page=update-referentiels" method="POST">
            <div class="referentiels-section">
                <div class="referentiels-column">
                    <h3>Référentiels affectés</h3>
                    <ul>
                        <?php foreach ($assigned_referentiels as $referentiel): ?>
                            <?php
                            // Vérifier si le référentiel a des apprenants
                            $referentiel_apprenants = $model['get_apprenants_by_referentiel']($referentiel['id'], $current_promotion['id']);
                            $has_apprenants = !empty($referentiel_apprenants);
                            ?>
                            <li>
                                <input type="hidden" name="assigned_referentiels[]" value="<?= htmlspecialchars($referentiel['id']) ?>">
                                <?= htmlspecialchars($referentiel['name']) ?>
                                <button type="submit" name="move_to_unassigned" value="<?= htmlspecialchars($referentiel['id']) ?>" <?= $has_apprenants ? 'disabled' : '' ?>>
                                    →
                                </button>
                                <?php if ($has_apprenants): ?>
                                    <span class="error-message">Apprenants associés</span>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <div class="referentiels-column">
                    <h3>Référentiels non affectés</h3>
                    <ul>
                        <?php foreach ($unassigned_referentiels as $referentiel): ?>
                            <li>
                                <input type="hidden" name="unassigned_referentiels[]" value="<?= htmlspecialchars($referentiel['id']) ?>">
                                <?= htmlspecialchars($referentiel['name']) ?>
                                <button type="submit" name="move_to_assigned" value="<?= htmlspecialchars($referentiel['id']) ?>">←</button>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </form>
    </div>
</body>
</html>