<?php
/**
 * Bootstrap file for Coco Travel Application
 * 
 * This file initializes the application, sets up error handling,
 * and loads any global configurations
 */

// Error reporting based on environment
if ($_ENV['APP_ENV'] === 'development') {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Set default timezone
date_default_timezone_set('UTC');

// Initialize session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Load helper functions
require_once BASE_PATH . '/src/Helpers/functions.php';

// Setup custom error handler
set_error_handler('App\Helpers\errorHandler');

// Initialize TranslationService
$translationService = new \App\Core\TranslationService();
$translator = $translationService->getTranslator();
$lang = $translationService->getCurrentLocale();