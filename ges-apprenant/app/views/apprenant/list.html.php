<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Apprenants</title>
    <link rel="stylesheet" href="/assets/css/apprenants.css">
</head>
<body>

<div class="container">
    <div class="header">
        <h1>
            Apprenants de 
            <?= !empty($current_promotion['name']) ? htmlspecialchars($current_promotion['name']) : 'Promotion inconnue' ?>
        </h1>
        <p>Gérer les apprenants de la promotion</p>
    </div>
    
    <div class="search-section">
        <div class="search-bar">
            <div class="search-icon"></div>
            <input type="text" name="search" placeholder="Rechercher un apprenant..." 
                   value="<?= htmlspecialchars($search ?? '') ?>">
        </div>
        
        <button class="btn btn-teal" onclick="window.location.href='?page=add-apprenant'">
            <span>+</span> Ajouter un apprenant
        </button>
    </div>
    
    <div class="cards-container">
        <?php if (empty($apprenants)): ?>
            <div class="no-data">Aucun apprenant n'est inscrit dans cette promotion</div>
        <?php else: ?>
            <?php foreach ($apprenants as $apprenant): ?>
                <div class="card">
                    <div class="card-image">
                        <img src="<?= $apprenant['photo'] ?? 'assets/images/apprenants/default.jpg' ?>" 
                             alt="<?= htmlspecialchars($apprenant['nom'] . ' ' . $apprenant['prenom']) ?>">
                    </div>
                    <div class="card-content">
                        <h3 class="card-title"><?= htmlspecialchars($apprenant['prenom'] . ' ' . $apprenant['nom']) ?></h3>
                        <p class="card-subtitle"><?= htmlspecialchars($apprenant['email']) ?></p>
                        <p class="card-description">
                            <?= !empty($apprenant['telephone']) ? htmlspecialchars($apprenant['telephone']) : 'Aucun téléphone renseigné' ?>
                        </p>
                    </div>
                    <div class="card-footer">
                        <button class="btn btn-small btn-blue" onclick="window.location.href='?page=view-apprenant&id=<?= $apprenant['id'] ?>'">
                            Voir
                        </button>
                        <button class="btn btn-small btn-orange" onclick="window.location.href='?page=edit-apprenant&id=<?= $apprenant['id'] ?>'">
                            Modifier
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

</body>
</html>