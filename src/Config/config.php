<?php
namespace App\Config;

class Config {
    // Database Configuration
    public static function getDbHost() {
        return $_ENV['DB_HOST'] ?? 'localhost';
    }
    
    public static function getDbName() {
        return $_ENV['DB_NAME'] ?? 'coco_travel';
    }
    
    public static function getDbUser() {
        return $_ENV['DB_USER'] ?? 'root';
    }
    
    public static function getDbPass() {
        return $_ENV['DB_PASS'] ?? '';
    }
    
    // JWT Configuration
    public static function getJwtSecret() {
        return $_ENV['JWT_SECRET'] ?? 'your-secret-key';
    }
    
    public static function getJwtExpiration() {
        return (int)($_ENV['JWT_EXPIRE'] ?? 21600); // 6 hour
    }
    
    // Application Configuration
    public static function getSiteName() {
        return $_ENV['APP_NAME'] ?? 'Coco Travel';
    }
    
    public static function getAppUrl() {
        return rtrim($_ENV['APP_URL'] ?? '', '/');
    }
    
    public static function getUploadPath() {
        return BASE_PATH . '/public/assets/uploads/';
    }
    
    public static function getAllowedImageTypes() {
        return ['image/jpeg', 'image/png', 'image/webp'];
    }
    
    public static function getMaxFileSize() {
        return 5 * 1024 * 1024; // 5MB
    }
    
    // Email Configuration
    public static function getSmtpHost() {
        return $_ENV['MAIL_HOST'] ?? 'smtp.gmail.com';
    }
    
    public static function getSmtpPort() {
        return (int)($_ENV['MAIL_PORT'] ?? 587);
    }
    
    public static function getSmtpUser() {
        return $_ENV['MAIL_USERNAME'] ?? '';
    }
    
    public static function getSmtpPass() {
        return $_ENV['MAIL_PASSWORD'] ?? '';
    }
    
    public static function getMailFrom() {
        return $_ENV['MAIL_FROM_ADDRESS'] ?? 'noreply@cocotravel.com';
    }
    
    public static function getMailFromName() {
        return $_ENV['MAIL_FROM_NAME'] ?? 'Coco Travel';
    }
}