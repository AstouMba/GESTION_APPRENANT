<?php
function render($layout, $view, $data = []) {
    global $model;

    // Ajouter la promotion active aux données
    $data['current_promotion'] = $model['get_current_promotion']();

    extract($data); // Extrait les données pour les rendre disponibles dans la vue
    require __DIR__ . '/../views/' . $layout; // Inclut le layout principal
    require __DIR__ . '/../views/' . $view;   // Inclut la vue spécifique
}


function json_to_array(string $file_path): array {
    if (!file_exists($file_path)) {
        return []; // Retourne un tableau vide si le fichier n'existe pas
    }

    $json_content = file_get_contents($file_path);
    $data = json_decode($json_content, true);

    // Vérifie si le JSON est valide
    if (json_last_error() !== JSON_ERROR_NONE) {
        return []; // Retourne un tableau vide si le JSON est invalide
    }

    return $data;
}


   function array_to_json(string $file_path, array $data): bool {
    $json_content = json_encode($data, JSON_PRETTY_PRINT);

    // Vérifie si l'encodage JSON a réussi
    if ($json_content === false) {
        return false;
    }

    // Écrit le contenu JSON dans le fichier
    return file_put_contents($file_path, $json_content) !== false;
}


function redirect_to_route(string $route, array $params = []): void {
    // Construire l'URL avec les paramètres
    $url = '?page=' . $route;
    if (!empty($params)) {
        $url .= '&' . http_build_query($params);
    }

    // Rediriger vers l'URL
    header('Location: ' . $url);
    exit;
}