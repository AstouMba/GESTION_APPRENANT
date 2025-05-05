<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un nouveau référentiel</title>
    <style>
        .form-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .form-container h1 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .form-group input[type="file"] {
            padding: 5px;
        }

        .form-group textarea {
            resize: vertical;
        }

        .error-message {
            color: #dc3545;
            font-size: 12px;
            margin-top: 5px;
        }

        .form-buttons {
            text-align: center;
        }

        .btn {
            padding: 10px 20px;
            font-size: 14px;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Créer un nouveau référentiel</h1>
        <form action="?page=create-referentiel-process" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Nom du référentiel</label>
                <input type="text" id="name" name="name" value="<?= htmlspecialchars($name ?? '') ?>">
                <?php if (!empty($errors['name'])): ?>
                    <div class="error-message"><?= htmlspecialchars($errors['name']) ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" rows="4"><?= htmlspecialchars($description ?? '') ?></textarea>
                <?php if (!empty($errors['description'])): ?>
                    <div class="error-message"><?= htmlspecialchars($errors['description']) ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="capacity">Capacité (nombre d'étudiants)</label>
                <input type="number" id="capacity" name="capacity" value="<?= htmlspecialchars($capacity ?? '') ?>">
                <?php if (!empty($errors['capacity'])): ?>
                    <div class="error-message"><?= htmlspecialchars($errors['capacity']) ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="sessions">Nombre de sessions par an</label>
                <input type="number" id="sessions" name="sessions" value="<?= htmlspecialchars($sessions ?? '') ?>">
                <?php if (!empty($errors['sessions'])): ?>
                    <div class="error-message"><?= htmlspecialchars($errors['sessions']) ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="image">Photo (JPG, PNG, max 2MB)</label>
                <input type="file" id="image" name="image" accept=".jpg,.jpeg,.png">
                <?php if (!empty($errors['image'])): ?>
                    <div class="error-message"><?= htmlspecialchars($errors['image']) ?></div>
                <?php endif; ?>
            </div>

            <div class="form-buttons">
                <button type="submit" class="btn">Créer le référentiel</button>
            </div>
        </form>
    </div>
</body>
</html>