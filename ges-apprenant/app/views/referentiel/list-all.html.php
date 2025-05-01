<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tous les référentiels</title>
    <link rel="stylesheet" href="/assets/css/referentiels.css">
    <style>
        /* Conteneur principal */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Section de recherche */
        .search-section {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            gap: 15px;
        }

        /* Barre de recherche */
        .search-bar {
            display: flex;
            align-items: center;
            gap: 10px;
            flex: 1;
        }

        .search-bar input[type="text"] {
            width: 100%;
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        /* Boutons de recherche et création */
        .search-buttons {
            display: flex;
            gap: 10px;
        }

        .btn {
            padding: 10px 15px;
            font-size: 14px;
            font-weight: bold;
            text-align: center;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            text-decoration: none;
        }

        .btn-teal {
            background-color: #0E8F7E;
        }

        .btn-teal:hover {
            background-color:rgb(12, 116, 35);
        }

        .btn-primary {
            background-color:rgb(7, 81, 23);
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .btn-back {
            background-color: #6c757d;
        }

        .btn-back:hover {
            background-color: #5a6268;
        }

        /* Flèche de retour */
        .back-arrow {
            margin-bottom: 20px;
        }

        .back-arrow a {
            background-color: #ccc;

            display: inline-block;
            font-size: 14px;
            font-weight: bold;
            color:rgb(233, 237, 240);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            margin-top: 20px;
        }

        .pagination-link {
            padding: 8px 12px;
            font-size: 14px;
            color: #007bff;
            text-decoration: none;
            border: 1px solid #ddd;
            border-radius: 5px;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .pagination-link:hover {
            background-color: #007bff;
            color: #fff;
        }

        .pagination-link.active {
            background-color: #007bff;
            color: #fff;
            pointer-events: none;
        }
            
    </style>
</head>
<body>
    
    <div class="container">
        <!-- Flèche de retour -->
        <div class="back-arrow">
            <a href="?page=referentiels" class="btn-back">
                ← Retour aux référentiels actifs
            </a>
        </div>

        <!-- Section de recherche -->
        <div class="search-section">
            <div class="search-bar">
                <input type="text" id="search" name="search" placeholder="Rechercher un référentiel...">
            </div>
            <div class="search-buttons">
                <!-- Bouton de recherche -->
                <button type="submit" class="btn btn-teal">Rechercher</button>

                <!-- Bouton pour créer un nouveau référentiel -->
                <a href="?page=create-referentiel" class="btn btn-primary">Créer un nouveau référentiel</a>
            </div>
        </div>

        <div class="header">
            <h1>Liste de tous les référentiels</h1>
        </div>

       

        <div class="cards-container">
            <?php if (empty($referentiels)): ?>
                <div class="no-data">Aucun référentiel trouvé</div>
            <?php else: ?>
                <?php foreach ($referentiels as $ref): ?>
                    <div class="card">
                        <div class="card-image">
                            <img src="<?= htmlspecialchars($ref['image'] ?? 'assets/images/referentiels/default.jpg') ?>" 
                                 alt="<?= htmlspecialchars($ref['name']) ?>">
                        </div>
                        <div class="card-content">
                            <h3 class="card-title"><?= htmlspecialchars($ref['name']) ?></h3>
                            <p class="card-description"><?= htmlspecialchars($ref['description']) ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div class="pagination">
            <?php if ($current_page > 1): ?>
                <a href="?page=all-referentiels&current_page=<?= $current_page - 1 ?>" class="pagination-link">Précédent</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?page=all-referentiels&current_page=<?= $i ?>" class="pagination-link <?= $i === $current_page ? 'active' : '' ?>">
                    <?= $i ?>
                </a>
            <?php endfor; ?>

            <?php if ($current_page < $total_pages): ?>
                <a href="?page=all-referentiels&current_page=<?= $current_page + 1 ?>" class="pagination-link">Suivant</a>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>