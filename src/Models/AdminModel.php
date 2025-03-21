<?php
namespace App\Models;

class AdminModel extends BaseModel {
    protected $table = 'users';

    public function validateLogin($username, $password) {
        if (empty($username) || empty($password)) {
            return false;
        }

        $sql = "SELECT * FROM {$this->table} WHERE username = :username AND is_active = 1 AND is_admin = 1";
        $admin = $this->db->fetch($sql, ['username' => $username]);
        
        if (!$admin) {
            return false;
        }

        if (!isset($admin['password']) || !password_verify($password, $admin['password'])) {
            return false;
        }

        $this->updateLastLogin($admin['id']);
        return $admin;
    }

    private function updateLastLogin($adminId) {
        $sql = "UPDATE {$this->table} SET last_login = CURRENT_TIMESTAMP WHERE id = :id";
        return $this->db->query($sql, ['id' => $adminId]);
    }
}
