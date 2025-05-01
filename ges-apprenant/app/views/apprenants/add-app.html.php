<div class="container">
    <h1>Ajouter un Apprenant</h1>
    <form method="POST" action="?page=add-apprenant-process" enctype="multipart/form-data">
        <div class="form-section">
            <h2>Informations de l'Apprenant</h2>
            <div class="form-row">
                <div class="form-group">
                    <label for="nom">Nom :</label>
                    <input type="text" name="nom" id="nom" class="form-control">
                    <?php if (!empty($errors['nom'])): ?>
                        <span class="error"><?= htmlspecialchars($errors['nom']) ?></span>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="prenom">Prénom :</label>
                    <input type="text" name="prenom" id="prenom" class="form-control">
                    <?php if (!empty($errors['prenom'])): ?>
                        <span class="error"><?= htmlspecialchars($errors['prenom']) ?></span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="date_naissance">Date de Naissance</label>
                    <input type name="date_naissance" id="date_naissance" value="<?= htmlspecialchars($_POST['date_naissance'] ?? '') ?>">
                    <?php if (!empty($errors['date_naissance'])): ?>
                        <span class="error"><?= htmlspecialchars($errors['date_naissance']) ?></span>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="lieu_naissance">Lieu de Naissance</label>
                    <input type="text" name="lieu_naissance" id="lieu_naissance" value="<?= htmlspecialchars($_POST['lieu_naissance'] ?? '') ?>">
                    <?php if (!empty($errors['lieu_naissance'])): ?>
                        <span class="error"><?= htmlspecialchars($errors['lieu_naissance']) ?></span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="adresse">Adresse</label>
                    <input type="text" name="adresse" id="adresse" value="<?= htmlspecialchars($_POST['adresse'] ?? '') ?>">
                    <?php if (!empty($errors['adresse'])): ?>
                        <span class="error"><?= htmlspecialchars($errors['adresse']) ?></span>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="email">Email :</label>
                    <input type="email" name="email" id="email" class="form-control">
                    <?php if (!empty($errors['email'])): ?>
                        <span class="error"><?= htmlspecialchars($errors['email']) ?></span>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="photo">Photo</label>
                    <input type="file" name="photo" id="photo">
                    <?php if (!empty($errors['photo'])): ?>
                        <span class="error"><?= htmlspecialchars($errors['photo']) ?></span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="form-group">
                <label for="telephone">Téléphone</label>
                <input type="text" name="telephone" id="telephone" value="<?= htmlspecialchars($_POST['telephone'] ?? '') ?>">
                <?php if (!empty($errors['telephone'])): ?>
                    <span class="error"><?= htmlspecialchars($errors['telephone']) ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="referentiel">Référentiel :</label>
                <select name="referentiel" id="referentiel" class="form-control">
                    <option value="">-- Sélectionnez un référentiel --</option>
                    <option value="Développement Web">Développement Web</option>
                    <option value="Développement Data">Développement Data</option>
                    <option value="Référent Digital">AWS & DevOps</option>
                    <option value="Référent Digital">Hackeuse</option>
                    <option value="Référent Digital">Référent Digital</option>
                </select>
                <?php if (!empty($errors['referentiel'])): ?>
                    <span class="error"><?= htmlspecialchars($errors['referentiel']) ?></span>
                <?php endif; ?>
            </div>
        </div>

        <div class="form-section">
            <h2>Informations du Tuteur</h2>
            <div class="form-group">
                <label for="tuteur_nom">Nom du Tuteur</label>
                <input type="text" name="tuteur_nom" id="tuteur_nom" value="<?= htmlspecialchars($_POST['tuteur_nom'] ?? '') ?>">
                <?php if (!empty($errors['tuteur_nom'])): ?>
                    <span class="error"><?= htmlspecialchars($errors['tuteur_nom']) ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="tuteur_prenom">Prénom du Tuteur</label>
                <input type="text" name="tuteur_prenom" id="tuteur_prenom" value="<?= htmlspecialchars($_POST['tuteur_prenom'] ?? '') ?>">
                <?php if (!empty($errors['tuteur_prenom'])): ?>
                    <span class="error"><?= htmlspecialchars($errors['tuteur_prenom']) ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="tuteur_email">Email du Tuteur</label>
                <input type="email" name="tuteur_email" id="tuteur_email" value="<?= htmlspecialchars($_POST['tuteur_email'] ?? '') ?>">
                <?php if (!empty($errors['tuteur_email'])): ?>
                    <span class="error"><?= htmlspecialchars($errors['tuteur_email']) ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="tuteur_telephone">Téléphone du Tuteur</label>
                <input type="text" name="tuteur_telephone" id="tuteur_telephone" value="<?= htmlspecialchars($_POST['tuteur_telephone'] ?? '') ?>">
                <?php if (!empty($errors['tuteur_telephone'])): ?>
                    <span class="error"><?= htmlspecialchars($errors['tuteur_telephone']) ?></span>
                <?php endif; ?>
            </div>
        </div>

        <button type="submit" class="btn btn-success">Ajouter</button>
        <a href="?page=apprenants" class="btn btn-secondary">Annuler</a>
    </form>
</div>

<style>
/* Conteneur principal */
.container {
    max-width: 600px;
    margin: 0 auto;
    padding: 20px;
    background-color: #f9f9f9;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

/* Grille pour les sections */
.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

/* Ligne de champs */
.form-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
}

/* Sections */
.form-section {
    background-color: #fff;
    padding: 15px;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

/* Titre des sections */
.form-section h2 {
    margin-bottom: 15px;
    color: #333;
}

/* Titre */
.container h1 {
    text-align: center;
    margin-bottom: 20px;
    color: #333;
}

/* Formulaire */
form {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

/* Groupes de champs */
.form-group {
    display: flex;
    flex-direction: column;
}

/* Labels */
.form-group label {
    font-weight: bold;
    margin-bottom: 5px;
    color: #555;
}

/* Champs de saisie */
.form-control {
    padding: 10px;
    font-size: 14px;
    border: 1px solid #ccc;
    border-radius: 5px;
    transition: border-color 0.3s ease;
}

.form-control:focus {
    border-color: #007bff;
    outline: none;
}

/* Boutons */
.btn {
    padding: 10px 15px;
    font-size: 14px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    transition: background-color 0.3s ease, color 0.3s ease;
}

/* Bouton Ajouter */
.btn-success {
    background-color: #28a745;
    color: #fff;
}

.btn-success:hover {
    background-color: #218838;
}

/* Bouton Annuler */
.btn-secondary {
    background-color: #6c757d;
    color: #fff;
}

.btn-secondary:hover {
    background-color: #5a6268;
}

/* Espacement entre les boutons */
form .btn {
    margin-top: 10px;
}

/* Champ de fichier */
input[type="file"] {
    padding: 5px;
}

/* Responsive */
@media (max-width: 768px) {
    .container {
        padding: 15px;
    }

    .form-control {
        font-size: 13px;
    }

    .btn {
        font-size: 13px;
    }
}

.error {
    color: #dc3545;
    font-size: 13px;
    margin-top: 5px;
}
</style>