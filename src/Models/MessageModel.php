<?php
namespace App\Models;

class MessageModel extends BaseModel
{
    protected $table = 'messages';

    public function getUnreadCount()
    {
        return $this->db->fetch("
            SELECT COUNT(*) as count 
            FROM {$this->table} 
            WHERE is_read = 0
        ")['count'];
    }

    public function getRecentMessages($limit = 5)
    {
        return $this->db->fetchAll("
            SELECT * FROM {$this->table}
            ORDER BY created_at DESC
            LIMIT :limit
        ", ['limit' => $limit]);
    }

    public function getMessagesWithPagination($page = 1, $perPage = 10)
    {
        $offset = ($page - 1) * $perPage;
        return $this->db->fetchAll("
            SELECT * FROM {$this->table}
            ORDER BY created_at DESC
            LIMIT :limit OFFSET :offset
        ", ['limit' => $perPage, 'offset' => $offset]);
    }

    public function markAsRead($id)
    {
        return $this->update($id, ['is_read' => 1]);
    }

    public function getTotalCount()
    {
        $result = $this->db->fetch("SELECT COUNT(*) as count FROM {$this->table}");
        return (int)$result['count'];
    }

    public function validate($data)
    {
        $errors = [];
        
        if (empty($data['name'])) {
            $errors['name'] = 'Name is required';
        }
        
        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Valid email is required';
        }
        
        if (empty($data['message'])) {
            $errors['message'] = 'Message is required';
        }
        
        return $errors;
    }
}
