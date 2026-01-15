<?php

require_once __DIR__ . '/../vendor/autoload.php';

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

use SellNow\Config\Database;
use SellNow\Config\Router;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;

// Start session
session_start();

// Setup Twig
$loader = new FilesystemLoader(__DIR__ . '/../templates');
$twig = new Environment($loader, ['debug' => true]);
$twig->addGlobal('session', $_SESSION);

// Database Connection
$db = Database::getInstance()->getConnection();

// Initialize Router with dependencies
$router = new Router($twig, $db);

// Load routes
$routeLoader = require __DIR__ . '/../route/web.php';
$routeLoader($router);

// Dispatch the request
$router->dispatch();
