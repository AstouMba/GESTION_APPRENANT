<?php
$view = $_GET['view'] ?? 'grid'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
   <style>
   /* Conteneur principal */
.filter-form {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    margin-bottom: 20px;
    align-items: center;
}

/* Groupes de filtres */
.filter-group {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

/* Labels */
.filter-group label {
    font-size: 14px;
    font-weight: bold;
    color: #333;
}

/* Champs de formulaire */
.filter-group select,
.filter-group input[type="text"] {
    padding: 8px 10px;
    font-size: 14px;
    border: 1px solid #ccc;
    border-radius: 5px;
    width: 200px;
}

/* Bouton de recherche */
.filter-form .btn-primary {
    padding: 10px 20px;
    font-size: 14px;
    color: #fff;
    background-color: #0E8F7E;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.filter-form .btn-primary:hover {
    background-color: #0056b3;
}
</style>
</head>
<body>
<div class="container">
    <div class="header">
        <div class="header-title">
            <h1>Promotion</h1>
            <div class="header-subtitle">Gérer les promotions de l'école</div>
        </div>
        <button type="button" id="addPromotionBtn" class="add-button">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            <div class="add-promotion-button">
        <a href="?page=add-promotion-form" class="btn btn-primary">Ajouter une promotion</a>
    </div>

        </button>
    </div>

   
    <div class="stats-container">
        <div class="stat-card">
            <div>
                <div class="stat-number"><?= $stats['active_learners'] ?></div>
                <div class="stat-label">Apprenants actifs</div>
            </div>
            <div class="stat-icon">
                <svg viewBox="0 0 24 24" fill="white">
                    <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                </svg>
            </div>
        </div>
        
        <div class="stat-card">
            <div>
                <div class="stat-number"><?= $stats['total_referentials'] ?></div>
                <div class="stat-label">Référentiels</div>
            </div>
            <div class="stat-icon">
                <svg viewBox="0 0 24 24" fill="white">
                    <path d="M21 5c-1.11-.35-2.33-.5-3.5-.5-1.95 0-4.05.4-5.5 1.5-1.45-1.1-3.55-1.5-5.5-1.5-1.95 0-4.05.4-5.5 1.5v14.65c0 .25.25.5.5.5.1 0 .15-.05.25-.05C3.1 20.45 5.05 20 6.5 20c1.95 0 4.05.4 5.5 1.5 1.35-.85 3.8-1.5 5.5-1.5 1.65 0 3.35.3 4.75 1.05.1.05.15.05.25.05.25 0 .5-.25.5-.5V6c-.6-.45-1.25-.75-2-1z"/>
                </svg>
            </div>
        </div>
        
        <div class="stat-card">
            <div>
                <div class="stat-number"><?= $stats['active_promotions'] ?></div>
                <div class="stat-label">Promotions actives</div>
            </div>
            <div class="stat-icon">
                <svg viewBox="0 0 24 24" fill="white">
                    <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                </svg>
            </div>
        </div>
        
        <div class="stat-card">
            <div>
                <div class="stat-number"><?= $stats['total_promotions'] ?></div>
                <div class="stat-label">Total promotions</div>
            </div>
            <div class="stat-icon">
                <svg viewBox="0 0 24 24" fill="white">
                    <path d="M20 6h-8l-2-2H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2z"/>
                </svg>
            </div>
        </div>
    </div>

    <div class="search-filter">
        <div class="search-bar">
            <span class="search-icon">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
            </span>
           
        </div>
    </div>

    <div class="filter-container">

    <div class="formu">
            <form action="?page=promotions" method="GET" class="filter-form">
    <input type="hidden" name="page" value="promotions">

    <!-- Champ de recherche -->
    <div class="filter-group">
        <label for="search">Rechercher :</label>
        <input id="search" type="text" name="search" placeholder="Rechercher une promotion..." value="<?= htmlspecialchars($search ?? '') ?>">
    </div>

    <!-- Filtre par référentiel -->
    <div class="filter-group">
        <label for="referentiel">Référentiel :</label>
        <select name="referentiel" id="referentiel">
            <option value="">Tous</option>
            <option value="Developpement Web/Mobile" <?= isset($_GET['referentiel']) && $_GET['referentiel'] === 'Developpement Web/Mobile' ? 'selected' : '' ?>>Développement Web/Mobile</option>
            <option value="Developpement Data" <?= isset($_GET['referentiel']) && $_GET['referentiel'] === 'Developpement Data' ? 'selected' : '' ?>>Développement Data</option>
            <option value="AWS et DevOps" <?= isset($_GET['referentiel']) && $_GET['referentiel'] === 'AWS et DevOps' ? 'selected' : '' ?>>AWS et DevOps</option>
            <option value="Assistanat Digital(Hackeuse)" <?= isset($_GET['referentiel']) && $_GET['referentiel'] === 'Assistanat Digital(Hackeuse)' ? 'selected' : '' ?>>Assistanat Digital (Hackeuse)</option>
            <option value="Référent Digital" <?= isset($_GET['referentiel']) && $_GET['referentiel'] === 'Référent Digital' ? 'selected' : '' ?>>Référent Digital</option>
        </select>
    </div>

    <!-- Filtre par statut -->
    <div class="filter-group">
        <label for="status">Statut :</label>
        <select name="status" id="status">
            <option value="">Tous</option>
            <option value="active" <?= isset($_GET['status']) && $_GET['status'] === 'active' ? 'selected' : '' ?>>Active</option>
            <option value="inactive" <?= isset($_GET['status']) && $_GET['status'] === 'inactive' ? 'selected' : '' ?>>Inactive</option>
        </select>
    </div>

    <!-- Bouton de recherche -->
    <button type="submit" class="btn btn-primary">Rechercher</button>
</form>
    </div>
    <div class="view-toggle">
        <select class="filter-dropdown">
            <option>Tous</option>
        </select>
        <a href="?page=promotions&view=list<?= isset($_GET['page_num']) ? '&page_num='.$_GET['page_num'] : '' ?>" class="view-button <?= $view === 'list' ? 'active' : '' ?>">
    Liste
</a>
<a href="?page=promotions&view=grid<?= isset($_GET['page_num']) ? '&page_num='.$_GET['page_num'] : '' ?>" class="view-button <?= $view === 'grid' ? 'active' : '' ?>">
    Grille
</a>
        </div>
    </div>

    <div class="promotions-grid" style="display: <?= $view === 'grid' ? 'grid' : 'none' ?>">
    <?php if (empty($promotions)): ?>
        <div class="no-results">Aucune promotion trouvée</div>
    <?php else: ?>
        <?php foreach ($promotions as $promotion): ?>
                <div class="promotion-card">
                    <div class="status-container">
                        <div class="status-badge <?= $promotion['status'] === 'active' ? 'active' : 'inactive' ?>">
                            <?= ucfirst($promotion['status']) ?>
                        </div>
                        <form action="?page=toggle_promotion" method="POST" class="toggle-form">
                            <input type="hidden" name="promotion_id" value="<?= $promotion['id'] ?>">
                            <button type="submit" class="toggle-button <?= $promotion['status'] === 'active' ? 'active' : '' ?>">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M18.36 6.64a9 9 0 1 1-12.73 0"></path>
                                    <line x1="12" y1="2" x2="12" y2="12"></line>
                                </svg>
                            </button>
                        </form>
                    </div>
                    <img src="<?= htmlspecialchars($promotion['image']) ?>" 
                         alt="<?= htmlspecialchars($promotion['name']) ?>" 
                         class="promotion-avatar">
                    <div class="promotion-title"><?= htmlspecialchars($promotion['name']) ?></div>
                    <div class="promotion-date">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg>
                        <?= date('d/m/Y', strtotime($promotion['date_debut'])) ?> - 
                        <?= date('d/m/Y', strtotime($promotion['date_fin'])) ?>
                    </div>
                    <div class="promotion-students">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                        <?= count($promotion['apprenants'] ?? []) ?> apprenants
                    </div>
                    <a href="?page=promotion&id=<?= $promotion['id'] ?>&view=grid" class="view-details">
    Voir détails
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#f97316" stroke-width="2">
        <polyline points="9 18 15 12 9 6"></polyline>
    </svg>
</a>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    
    <div class="promotions-list" style="display: <?= $view === 'list' ? 'block' : 'none' ?>">
    <?php if (empty($promotions)): ?>
        <div class="no-results">Aucune promotion trouvée</div>
    <?php else: ?>
        <?php foreach ($promotions as $promotion): ?>
            <div class="promotion-card-list">
                <div class="promotion-list-left">
                    <img src="<?= htmlspecialchars($promotion['image']) ?>"
                         alt="<?= htmlspecialchars($promotion['name']) ?>"
                         class="promotion-avatar">
                    <div class="promotion-list-middle">
                        <div class="promotion-title"><?= htmlspecialchars($promotion['name']) ?></div>
                        <div class="promotion-date">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                <line x1="3" y1="10" x2="21" y2="10"></line>
                            </svg>
                            <?= date('d/m/Y', strtotime($promotion['date_debut'])) ?> -
                            <?= date('d/m/Y', strtotime($promotion['date_fin'])) ?>
                        </div>
                        <div class="promotion-students">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                            </svg>
                            <?= count($promotion['apprenants'] ?? []) ?> apprenants
                        </div>
                    </div>
                </div>
                <div class="promotion-list-right">
                    <div class="status-container">
                        <div class="status-badge <?= $promotion['status'] === 'active' ? 'active' : 'inactive' ?>">
                            <?= ucfirst($promotion['status']) ?>
                        </div>
                        <form action="?page=toggle_promotion" method="POST" class="toggle-form">
                            <input type="hidden" name="promotion_id" value="<?= $promotion['id'] ?>">
                            <button type="submit" class="toggle-button <?= $promotion['status'] === 'active' ? 'active' : '' ?>">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M18.36 6.64a9 9 0 1 1-12.73 0"></path>
                                    <line x1="12" y1="2" x2="12" y2="12"></line>
                                </svg>
                            </button>
                        </form>
                    </div>
                    <a href="?page=promotion&id=<?= $promotion['id'] ?>&view=list" class="view-details">
                        Voir détails
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#f97316" stroke-width="2">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Affichage des promotions en liste ou en grille (déjà en place) -->

<!-- Un seul système de pagination commun -->
<div class="pagination">
    <?php if ($total_pages > 1): ?>
        <!-- Bouton Précédent -->
        <?php if ($current_page > 1): ?>
            <a href="?page=promotions&view=<?= $view ?>&page_num=<?= $current_page - 1 ?>" class="pagination-button prev">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="15 18 9 12 15 6"></polyline>
                </svg>
                Précédent
            </a>
        <?php endif; ?>

        <!-- Pages numérotées -->
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <a href="?page=promotions&view=<?= $view ?>&page_num=<?= $i ?>" class="pagination-button <?= $i === $current_page ? 'active' : '' ?>">
                <?= $i ?>
            </a>
        <?php endfor; ?>

        <!-- Bouton Suivant -->
        <?php if ($current_page < $total_pages): ?>
            <a href="?page=promotions&view=<?= $view ?>&page_num=<?= $current_page + 1 ?>" class="pagination-button next">
                Suivant
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
            </a>
        <?php endif; ?>
    <?php endif; ?>
</div> 
</body>
</html>
