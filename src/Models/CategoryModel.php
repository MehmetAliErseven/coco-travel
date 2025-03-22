<?php
namespace App\Models;

class CategoryModel extends BaseModel
{
    protected $table = 'categories';

    public function getActiveCategories()
    {
        return $this->db->fetchAll("
            SELECT c.*, COUNT(t.id) as tour_count
            FROM {$this->table} c
            LEFT JOIN tours t ON c.id = t.category_id AND t.is_active = 1
            WHERE c.is_active = 1
            GROUP BY c.id
            ORDER BY c.name ASC
        ");
    }

    public function findBySlug($slug)
    {
        return $this->db->fetch("
            SELECT *
            FROM {$this->table}
            WHERE slug = :slug AND is_active = 1
        ", ['slug' => $slug]);
    }

    public function getCategoryWithTourCount($id)
    {
        return $this->db->fetch("
            SELECT c.*, COUNT(t.id) as tour_count
            FROM {$this->table} c
            LEFT JOIN tours t ON c.id = t.category_id AND t.is_active = 1
            WHERE c.id = :id AND c.is_active = 1
            GROUP BY c.id
        ", ['id' => $id]);
    }

    public function validate($data, $id = null)
    {
        $errors = [];
        
        if (empty($data['name'])) {
            $errors['name'] = 'Name is required';
        }
        
        // Check if slug is unique
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE slug = :slug";
        $params = ['slug' => $data['slug']];
        
        if ($id) {
            $sql .= " AND id != :id";
            $params['id'] = $id;
        }
        
        $result = $this->db->fetch($sql, $params);
        if ((int)$result['count'] > 0) {
            $errors['name'] = 'A category with this name already exists';
        }
        
        return $errors;
    }

    public function hasTours($categoryId)
    {
        $sql = "SELECT COUNT(*) as count FROM tours WHERE category_id = :category_id";
        $result = $this->db->fetch($sql, ['category_id' => $categoryId]);
        return (int)$result['count'] > 0;
    }

    public function getAllCategories()
    {
        return $this->db->fetchAll("
            SELECT c.*, COUNT(t.id) as tour_count
            FROM {$this->table} c
            LEFT JOIN tours t ON c.id = t.category_id
            GROUP BY c.id
            ORDER BY c.name ASC
        ");
    }
}
