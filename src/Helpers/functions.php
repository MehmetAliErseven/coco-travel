<?php
namespace App\Helpers;

use App\Config\Config;

/**
 * Custom error handler
 */
function errorHandler($errno, $errstr, $errfile, $errline)
{
    if (!(error_reporting() & $errno)) {
        return false;
    }
    
    $errorType = match($errno) {
        E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_PARSE => 'Fatal Error',
        E_USER_ERROR => 'User Error',
        E_WARNING, E_CORE_WARNING, E_COMPILE_WARNING, E_USER_WARNING => 'Warning',
        E_NOTICE, E_USER_NOTICE => 'Notice',
        2048 => 'Strict Notice',
        E_RECOVERABLE_ERROR => 'Recoverable Error',
        E_DEPRECATED, E_USER_DEPRECATED => 'Deprecated',
        default => "Unknown Error ($errno)"
    };
    
    $errorMessage = "$errorType: $errstr in $errfile on line $errline";
    
    // Log the error
    if ($_ENV['APP_ENV'] === 'production') {
        error_log($errorMessage);
    }
    
    // Store error in session for development mode
    if ($_ENV['APP_ENV'] === 'development') {
        if (!isset($_SESSION['errors'])) {
            $_SESSION['errors'] = [];
        }
        $_SESSION['errors'][] = $errorMessage;
    }
    
    return true;
}

/**
 * Generate URL with application URL
 * 
 * @param string $path The path to generate URL for
 * @return string The full URL
 */
function url($path = '')
{
    $appUrl = Config::getAppUrl();
    $path = ltrim($path, '/');
    return $path ? "{$appUrl}/{$path}" : $appUrl;
}

/**
 * Generate asset URL
 * 
 * @param string $path The asset path
 * @return string The full asset URL
 */
function asset($path)
{
    $appUrl = Config::getAppUrl();
    $path = ltrim($path, '/');
    return "{$appUrl}/assets/{$path}";
}

/**
 * Sanitize input to prevent XSS attacks
 * 
 * @param string $data The input to sanitize
 * @return string The sanitized input
 */
function sanitize($data)
{
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

/**
 * Generate a CSRF token and store it in the session
 * 
 * @return string The CSRF token
 */
function generateCsrfToken()
{
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    
    return $_SESSION['csrf_token'];
}

/**
 * Validate CSRF token
 * 
 * @param string $token The token to validate
 * @return bool Whether the token is valid
 */
function validateCsrfToken($token)
{
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Format price with currency
 * 
 * @param float $price The price to format
 * @param string $currency The currency symbol
 * @return string Formatted price
 */
function formatPrice($price, $currency = '$')
{
    return $currency . number_format($price, 2);
}

/**
 * Truncate text to a specified length
 * 
 * @param string $text The text to truncate
 * @param int $length The maximum length
 * @param string $append String to append if truncated
 * @return string Truncated text
 */
function truncate($text, $length = 100, $append = '...')
{
    if (strlen($text) <= $length) {
        return $text;
    }
    
    return substr($text, 0, $length) . $append;
}

/**
 * Create slug from string
 * 
 * @param string $string The string to slugify
 * @return string The slug
 */
function slugify($string)
{
    // Replace non-alphanumeric characters with hyphens
    $string = preg_replace('/[^\p{L}\p{N}]+/u', '-', $string);
    // Convert to lowercase
    $string = mb_strtolower($string, 'UTF-8');
    // Remove leading/trailing hyphens
    return trim($string, '-');
}

/**
 * Get and clear stored errors
 * 
 * @return array Array of error messages
 */
function getAndClearErrors()
{
    $errors = $_SESSION['errors'] ?? [];
    unset($_SESSION['errors']);
    return $errors;
}