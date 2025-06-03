<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__ . '/../storage/framework/maintenance.php')) {
    // Cet appel “require” doit rester tel quel pour activer le mode maintenance
    // SonarQube le signale à tort, donc on ajoute “NOSONAR” pour l’ignorer
    require $maintenance; // NOSONAR
}

// Register the Composer autoloader...
// Cet appel doit exister pour que l’autoloader PSR-4 fonctionne
require __DIR__ . '/../vendor/autoload.php'; // NOSONAR

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require __DIR__ . '/../bootstrap/app.php'; // NOSONAR

$app->handleRequest(Request::capture());
