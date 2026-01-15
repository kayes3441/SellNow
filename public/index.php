<?php

require_once __DIR__ . '/../vendor/autoload.php';

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

use SellNow\Config\Router;

// Start session
session_start();

// Initialize Router
$router = new Router();

// Load routes
$routeLoader = require __DIR__ . '/../route/web.php';
$routeLoader($router);

// Dispatch the request
$router->dispatch();