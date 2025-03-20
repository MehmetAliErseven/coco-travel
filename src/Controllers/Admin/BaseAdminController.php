<?php
namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Middleware\AuthMiddleware;

abstract class BaseAdminController extends BaseController
{
    protected function __construct()
    {
        // Skip auth check for login-related actions
        if (!$this->isAuthExempt()) {
            AuthMiddleware::requireAuth();
        }
    }

    protected function isAuthExempt()
    {
        $currentAction = $_GET['url'] ?? '';
        return in_array($currentAction, [
            'admin/login',
            'admin/authenticate',
            'admin/unauthorized'
        ]);
    }

    protected function setSuccessMessage($message)
    {
        $_SESSION['success_message'] = $message;
    }

    protected function setErrorMessage($message)
    {
        $_SESSION['error_message'] = $message;
    }

    protected function handleImageUpload($file, $uploadDir)
    {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return false;
        }

        $fileInfo = pathinfo($file['name']);
        $extension = strtolower($fileInfo['extension']);
        
        // Sadece belirli dosya türlerine izin ver
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($extension, $allowedTypes)) {
            return false;
        }

        // Benzersiz dosya adı oluştur
        $newFileName = uniqid() . '.' . $extension;
        $targetPath = rtrim($uploadDir, '/') . '/' . $newFileName;

        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            return 'assets/uploads/' . $newFileName;
        }

        return false;
    }
}
