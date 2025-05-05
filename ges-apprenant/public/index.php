<?php

session_start();

// Afficher les erreurs pendant le développement
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Point d'entrée de l'application
require_once __DIR__ . '/../app/route/route.web.php';

// Gérer la requête entrante
\App\Route\handle_request(); 