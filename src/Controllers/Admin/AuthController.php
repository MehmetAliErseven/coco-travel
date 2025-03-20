<?php
namespace App\Controllers\Admin;

use App\Models\AdminModel;
use App\Middleware\AuthMiddleware;

class AuthController extends BaseAdminController
{
    private $adminModel;

    public function __construct()
    {
        parent::__construct();
        $this->adminModel = new AdminModel();
    }

    public function loginAction()
    {
        if (AuthMiddleware::isAuthenticated()) {
            $this->redirect(\App\Helpers\url('admin'));
            return;
        }
        
        $this->render('admin/login', [
            'pageTitle' => 'Admin Login',
            'error' => null
        ]);
    }

    public function authenticateAction()
    {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        
        if (empty($username) || empty($password)) {
            return $this->renderLoginError('Please enter both username and password.');
        }

        $admin = $this->adminModel->validateLogin($username, $password);
        
        if ($admin) {
            $token = AuthMiddleware::generateToken([
                'id' => $admin['id'],
                'username' => $admin['username'],
                'is_admin' => 1
            ]);
            
            $_SESSION['jwt_token'] = $token;
            $this->redirect(\App\Helpers\url('admin'));
            return;
        }

        return $this->renderLoginError('Invalid admin credentials.');
    }

    public function logoutAction()
    {
        unset($_SESSION['jwt_token']);
        $this->redirect(\App\Helpers\url('admin/login'));
    }

    public function unauthorizedAction()
    {
        $this->render('admin/unauthorized', [
            'pageTitle' => 'Unauthorized Access'
        ]);
    }

    private function renderLoginError($error)
    {
        $this->render('admin/login', [
            'pageTitle' => 'Admin Login',
            'error' => $error
        ]);
    }
}
