<?php

use Illuminate\Foundation\Application;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function () {
        // aucun code pour l’instant
    })
    ->withExceptions(function () {
        // aucun code pour l’instant
    })
    ->create();
