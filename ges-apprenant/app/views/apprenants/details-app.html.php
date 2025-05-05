<div class="apd-container">
    <div class="apd-main-content">
        <a href="?page=apprenants" class="btn btn-primary">Retour à la liste</a>

        <!-- Sidebar avec informations de l'apprenant -->
        <div class="apd-sidebar">
            <div class="apd-profile-card">
                <!-- Photo de profil -->
                <div class="apd-profile-img">
                    <img src="<?= htmlspecialchars($apprenant['photo'] ?? 'assets/images/default-avatar.png') ?>" alt="Photo de profil">
                </div>

                <?php
                $qrData = json_encode([
                    'matricule' => $apprenant['matricule'] ?? '',
                    'nom' => $apprenant['nom'] ?? '',
                    'prenom' => $apprenant['prenom'] ?? '',
                    'email' => $apprenant['email'] ?? '',
                    'telephone' => $apprenant['telephone'] ?? '',
                    'adresse' => $apprenant['adresse'] ?? '',
                ]);

                $qrCodeUrl = "https://api.qrserver.com/v1/create-qr-code/?data=" . urlencode($qrData) . "&size=150x150";
                ?>
                <div class="qr-code-profile">
                    <img src="<?= $qrCodeUrl ?>" alt="QR Code de présence">
                    <p class="qr-info">Scannez pour marquer votre présence</p>
                </div>

                <!-- Informations principales -->
                <div class="apd-profile-info">
                    <h2 class="apd-profile-name">
                        <?= htmlspecialchars($apprenant['prenom'] ?? 'Prénom inconnu') . ' ' . htmlspecialchars($apprenant['nom'] ?? 'Nom inconnu') ?>
                    </h2>
                    <div class="apd-profile-matricule">
                        <?= htmlspecialchars($apprenant['matricule'] ?? 'Non spécifié') ?>
                    </div>
                    <div class="apd-status-badge <?= htmlspecialchars($apprenant['status'] ?? 'inconnu') ?>">
                        <?= ucfirst($apprenant['status'] ?? 'Inconnu') ?>
                    </div>
                </div>

                <!-- Informations de contact -->
                <div class="apd-contact-info">
                    <div class="apd-contact-item">
                        <i class="fas fa-envelope"></i>
                        <span><?= htmlspecialchars($apprenant['email'] ?? 'Non spécifié') ?></span>
                    </div>
                    <div class="apd-contact-item">
                        <i class="fas fa-phone"></i>
                        <span><?= htmlspecialchars($apprenant['telephone'] ?? 'Non spécifié') ?></span>
                    </div>
                    <div class="apd-contact-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span><?= htmlspecialchars($apprenant['adresse'] ?? 'Non spécifiée') ?></span>
                    </div>
                    <div class="apd-contact-item">
                        <i class="fas fa-graduation-cap"></i>
                        <span><?= htmlspecialchars($apprenant['referentiel_name'] ?? 'Non spécifié') ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contenu principal -->
        <div class="apd-dashboard">
            <!-- En-tête avec les statistiques -->
            <div class="apd-stats">
                <div class="apd-stat-card" >
                    <div class="apd-stat-icon green">
                        <i class="fas fa-book"></i>
                    </div>
                    <div class="apd-stat-info">
                        <div class="apd-number">20</div>
                        <div class="apd-label">En progression</div>
                    </div>
                </div>

                <div class="apd-stat-card">
                    <div class="apd-stat-icon orange">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="apd-stat-info">
                        <div class="apd-number">5</div>
                        <div class="apd-label">En attente</div>
                    </div>
                </div>

                <div class="apd-stat-card">
                    <div class="apd-stat-icon red">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="apd-stat-info">
                        <div class="apd-number">1</div>
                        <div class="apd-label">Terminé</div>
                    </div>
                </div>
            </div>

            <!-- Liste des modules -->
            <div class="apd-modules-section">
                <h3 class="apd-section-title">Suivi opérationel par étudiant</h3>
                <div class="apd-modules-grid">
                    <?php foreach ($modules ?? [] as $module): ?>
                    <div class="apd-module-card">
                        <div class="apd-module-header">
                            <span class="apd-module-label">Online</span>
                        </div>
                        <h4 class="apd-module-title"><?= htmlspecialchars($module['nom']) ?></h4>
                        <div class="apd-module-formateur">
                            Formateur: <strong><?= htmlspecialchars($module['formateur']) ?></strong>
                        </div>
                        <div class="apd-progress-bar">
                            <div class="apd-progress-fill" style="width: <?= $module['progression'] ?>%"></div>
                        </div>
                        <div class="apd-module-meta">
                            <div class="apd-meta-item">
                                <i class="far fa-calendar"></i>
                                <span><?= htmlspecialchars($module['date_debut']) ?></span>
                            </div>
                            <div class="apd-meta-item">
                                <i class="far fa-clock"></i>
                                <span>10:45 pm</span>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>



 <style>
.apd-container {
    display: flex;
    gap: 2rem;
    padding: 2rem;
}

.apd-profile-card {
    width: 280px;
    background-color: #f9f9f9;
    padding: 1.5rem;
    border-radius: 12px;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
    text-align: center;
}

.apd-profile-img img {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    object-fit: cover;
    margin-bottom: 1rem;
}

.apd-name {
    font-size: 1.2rem;
    font-weight: bold;
    margin: 0.5rem 0;
}

.apd-role {
    color: #777;
    font-size: 0.95rem;
    margin-bottom: 1rem;
}

.apd-contact-info {
    font-size: 0.9rem;
    text-align: left;
    margin-bottom: 1.5rem;
}

.qr-code-profile {
    text-align: center;
    margin-top: 1.5rem;
    padding: 1rem;
    background: #fff;
    border: 2px solid #ff9800;
    border-radius: 10px;
}

.qr-code-profile img {
    width: 130px;
    height: 130px;
    margin: 0 auto;
}

.qr-info {
    font-size: 0.85rem;
    color: #555;
    margin-top: 0.5rem;
}
.apd-container {
    display: flex;
    gap: 2rem;
    padding: 2rem;
}

.apd-profile-card {
    width: 280px;
    background-color: #f9f9f9;
    padding: 1.5rem;
    border-radius: 12px;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
    text-align: center;
}

.apd-profile-img img {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    object-fit: cover;
    margin-bottom: 1rem;
}

.apd-contact-info {
    font-size: 0.9rem;
    text-align: left;
    margin-bottom: 1.5rem;
}

.qr-code-profile {
    text-align: center;
    margin-top: 1.5rem;
    padding: 1rem;
    background: #fff;
    border: 2px solid #ff9800; /* Orange */
    border-radius: 10px;
}

.qr-code-profile img {
    width: 130px;
    height: 130px;
    margin: 0 auto;
}

.qr-info {
    font-size: 0.85rem;
    color: #555;
    margin-top: 0.5rem;
}

</style>