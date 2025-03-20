<?php
namespace App\Middleware;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;

/**
 * Authentication Middleware
 * 
 * Handles JWT authentication for protected routes
 */
class AuthMiddleware
{
    /**
     * Check if the user is authenticated
     * 
     * @return bool Whether the user is authenticated
     */
    public static function isAuthenticated()
    {
        // Check if JWT token exists in session
        if (!isset($_SESSION['jwt_token'])) {
            return false;
        }
        
        try {
            // Decode and verify the token
            $token = $_SESSION['jwt_token'];
            $decoded = JWT::decode($token, new Key($_ENV['JWT_SECRET'], 'HS256'));
            
            // Check if token is valid and not expired
            $now = time();
            if ($decoded->exp < $now) {
                // Token expired, clear it from session
                unset($_SESSION['jwt_token']);
                return false;
            }
            
            // Store user data in session
            $_SESSION['user'] = [
                'id' => $decoded->user_id,
                'username' => $decoded->username,
                'is_admin' => $decoded->is_admin
            ];
            
            return true;
        } catch (ExpiredException $e) {
            // Token expired
            unset($_SESSION['jwt_token']);
            return false;
        } catch (\Exception $e) {
            // Invalid token
            unset($_SESSION['jwt_token']);
            return false;
        }
    }
    
    /**
     * Generate a JWT token for a user
     * 
     * @param array $user User data
     * @return string The JWT token
     */
    public static function generateToken($user)
    {
        $issuedAt = time();
        $expirationTime = $issuedAt + (int)$_ENV['JWT_EXPIRE'];
        
        $payload = [
            'iat' => $issuedAt,
            'exp' => $expirationTime,
            'user_id' => $user['id'],
            'username' => $user['username'],
            'is_admin' => $user['is_admin'] ?? 0
        ];
        
        return JWT::encode($payload, $_ENV['JWT_SECRET'], 'HS256');
    }
    
    /**
     * Require authentication to access a route
     * Redirects to login page if not authenticated
     * 
     * @return bool Whether the user is authenticated
     */
    public static function requireAuth()
    {
        if (!self::isAuthenticated()) {
            header('Location: ' . \App\Helpers\url('admin/login'));
            exit;
        }
        
        return true;
    }
    
    /**
     * Require admin privileges to access a route
     * 
     * @return bool Whether the user is an admin
     */
    public static function requireAdmin()
    {
        self::requireAuth();
        
        if (!isset($_SESSION['user']) || $_SESSION['user']['is_admin'] != 1) {
            header('Location: ' . \App\Helpers\url('admin/unauthorized'));
            exit;
        }
        
        return true;
    }
}