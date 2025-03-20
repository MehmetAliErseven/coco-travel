<?php
/**
 * Coco Travel Agency - Main Entry Point
 */

// Define base path
define('BASE_PATH', dirname(__DIR__));

// Require the autoloader
require_once BASE_PATH . '/vendor/autoload.php';

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(BASE_PATH);
$dotenv->safeLoad();

// Set error reporting based on environment
if ($_ENV['APP_ENV'] === 'development') {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
}

// Include the functions.php directly to ensure errorHandler is available
require_once BASE_PATH . '/src/Helpers/functions.php';

// Register custom error handler
set_error_handler('App\Helpers\errorHandler');

// Include the bootstrap file if it exists
if (file_exists(BASE_PATH . '/src/bootstrap.php')) {
    require_once BASE_PATH . '/src/bootstrap.php';
}

// Handle CORS for API requests
if (strpos($_SERVER['REQUEST_URI'], '/api/') !== false) {
    // Get the app URL from environment
    $app_url = rtrim($_ENV['APP_URL'] ?? '', '/');
    
    $allowed_origins = [
        'http://coco-travel.local',    // Local development with virtual host
        'https://cocotravel.com',      // Production domain
        $app_url                       // Dynamic APP_URL from .env
    ];

    // Remove empty values
    $allowed_origins = array_filter($allowed_origins);

    if (isset($_SERVER['HTTP_ORIGIN']) && in_array($_SERVER['HTTP_ORIGIN'], $allowed_origins)) {
        header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
    }

    header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization');
    
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        exit(0);
    }
}

// Start the session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Initialize and run the Application
$app = new App\Core\Application();
$app->run();