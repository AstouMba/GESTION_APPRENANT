<div class="container">
    <h1>Liste des Apprenants</h1>

    <!-- Filtres et actions alignés -->
    <div class="filters-actions">
        <form method="GET" action="" class="filters-form">
            <input type="hidden" name="page" value="apprenants">
            <label for="statut">Statut :</label>
            <select name="statut" id="statut">
                <option value="">Tous</option>
                <option value="active" <?= isset($_GET['statut']) && $_GET['statut'] === 'active' ? 'selected' : '' ?>>Actif</option>
                <option value="inactive" <?= isset($_GET['statut']) && $_GET['statut'] === 'inactive' ? 'selected' : '' ?>>Inactif</option>
            </select>

            <label for="referentiel">Référentiel :</label>
            <select name="referentiel" id="referentiel">
                <option value="">Tous</option>
                <?php foreach ($referentiels as $referentiel): ?>
                    <option value="<?= htmlspecialchars($referentiel) ?>" <?= isset($_GET['referentiel']) && $_GET['referentiel'] === $referentiel ? 'selected' : '' ?>>
                        <?= htmlspecialchars($referentiel) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <button type="submit" class="btn btn-filter">Filtrer</button>
        </form>

        <div class="actions">
            <a href="?page=add-apprenant" class="btn btn-success">Ajouter un apprenant</a>

            <!-- Bouton Télécharger avec options -->
            <div class="dropdown">
                <button class="btn btn-download">Télécharger ▼</button>
                <div class="dropdown-content">
                    <a href="?page=download-pdf" class="dropdown-item">Télécharger PDF</a>
                    <a href="?page=download-excel" class="dropdown-item">Télécharger Excel</a>
                </div>
            </div>
        </div>
    </div>

    
    <!-- Tableau des apprenants -->
    <!-- <div class="tabs">
        <a href="?page=apprenants&filter=retenus" class="tab-link <?= (!isset($filter) || $filter === 'retenus') ? 'active' : '' ?>">
            Apprenants Retenus
        </a>
        <a href="?page=apprenants&filter=attente" class="tab-link <?= (isset($filter) && $filter === 'attente') ? 'active' : '' ?>">
            Liste d'Attente
        </a>
    </div> -->
    <table class="table">
        <thead>
            <tr>
                <th>Photo</th>
                <th>Matricule</th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Email</th>
                <th>Téléphone</th>
                <th>Adresse</th>
                <th>Référentiel</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($apprenants)): ?>
                <?php foreach ($apprenants as $apprenant): ?>
                    <tr>
                        <td>
                            <img src="<?= htmlspecialchars($apprenant['photo'] ?? 'assets/images/default-avatar.png') ?>" 
                                 alt="Photo de <?= htmlspecialchars($apprenant['prenom'] . ' ' . $apprenant['nom']) ?>" 
                                 class="avatar">
                        </td>
                        <td><?= htmlspecialchars($apprenant['matricule']) ?></td>
                        <td><?= htmlspecialchars($apprenant['nom']) ?></td>
                        <td><?= htmlspecialchars($apprenant['prenom']) ?></td>
                        <td><?= htmlspecialchars($apprenant['email']) ?></td>
                        <td><?= htmlspecialchars($apprenant['telephone']) ?></td>
                        <td><?= htmlspecialchars($apprenant['adresse']) ?></td>
                        <td><?= htmlspecialchars($apprenant['referentiel']) ?></td>
                        <td>
                            <span class="status <?= $apprenant['statut'] === 'active' ? 'active' : 'inactive' ?>">
                                <?= htmlspecialchars($apprenant['statut']) ?>
                            </span>
                        </td>
                        <td>
                            <a href="?page=details-apprenant&id=<?= htmlspecialchars($apprenant['id']) ?>" class="btn btn-info">Détails</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="10">Aucun apprenant trouvé.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<style>
.table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

.table th, .table td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: left;
}

.table th {
    background-color:rgba(250, 135, 64, 0.96);
    font-weight: bold;
}

.avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
}

.status.active {
    color: green;
    font-weight: bold;
}

.status.inactive {
    color: red;
    font-weight: bold;
}

.filters-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    gap: 10px;
}

.filters-form {
    display: flex;
    align-items: center;
    gap: 10px;
}

.filters-form label {
    margin-right: 5px;
}

.actions {
    display: flex;
    gap: 10px;
}

.btn {
    padding: 8px 12px;
    text-decoration: none;
    border-radius: 5px;
    color: #fff;
    font-size: 14px;
}

.btn-success {
    background-color: #28a745;
}

.btn-success:hover {
    background-color:rgb(13, 110, 34);
}

.btn-pdf {
    background-color: #dc3545;
}

.btn-pdf:hover {
    background-color: #c82333;
}

.btn-excel {
    background-color:rgb(23, 25, 26);
}

.btn-excel:hover {
    background-color: #0056b3;
}

.btn-filter {
    background-color: #ffc107;
    color: #000;
}

.btn-filter:hover {
    background-color: #e0a800;
}

.btn-info {
    background-color:rgb(237, 8, 43);
}

.btn-info:hover {
    background-color: #0056b3;
}

.dropdown {
    position: relative;
    display: inline-block;
}

.btn-download {
    background-color:rgb(21, 22, 24);
    color: white;
    padding: 8px 12px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 14px;
}

.btn-download:hover {
    background-color: #0056b3;
}

.dropdown-content {
    display: none;
    position: absolute;
    background-color: white;
    min-width: 150px;
    box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
    z-index: 1;
    border-radius: 5px;
    overflow: hidden;
}

.dropdown-content a {
    color: black;
    padding: 10px 15px;
    text-decoration: none;
    display: block;
    font-size: 14px;
}

.dropdown-content a:hover {
    background-color: #f1f1f1;
}

.dropdown:hover .dropdown-content {
    display: block;
}
</style>

