<?php
namespace App\Models;

class TourModel extends BaseModel
{
    protected $table = 'tours';

    public function getToursByCategory($categoryId) {
        $sql = "SELECT t.*, c.name as category_name 
                FROM {$this->table} t 
                LEFT JOIN categories c ON t.category_id = c.id 
                WHERE t.is_active = 1";
        
        if ($categoryId !== 'all') {
            $sql .= " AND t.category_id = :category_id";
            return $this->db->fetchAll($sql, ['category_id' => $categoryId]);
        }
        
        $sql .= " ORDER BY t.is_featured DESC, t.created_at DESC";
        return $this->db->fetchAll($sql, []);
    }

    public function searchTours($query, $categoryId = null, $page = null, $perPage = null, $startDate = null)
    {
        $params = [];
        
        $sql = "SELECT t.*, c.name as category_name
                FROM {$this->table} t
                LEFT JOIN categories c ON t.category_id = c.id
                WHERE t.is_active = 1";
        
        // Add search condition if query exists
        if ($query) {
            $sql .= " AND (t.title LIKE :query OR t.description LIKE :query_desc OR t.location LIKE :query_loc)";
            $params['query'] = "%{$query}%";
            $params['query_desc'] = "%{$query}%";
            $params['query_loc'] = "%{$query}%";
        }

        // Add category filter if specified
        if ($categoryId) {
            $sql .= " AND t.category_id = :category_id";
            $params['category_id'] = $categoryId;
        }
        
        // Add date filter if specified
        if ($startDate) {
            $sql .= " AND t.start_date = :start_date";
            $params['start_date'] = $startDate;
        }

        // Add ordering
        $sql .= " ORDER BY t.is_featured DESC, t.created_at DESC";

        // Add pagination if specified
        if ($page !== null && $perPage !== null) {
            $offset = ($page - 1) * $perPage;
            $sql .= " LIMIT :limit OFFSET :offset";
            $params['limit'] = $perPage;
            $params['offset'] = $offset;
        }

        return $this->db->fetchAll($sql, $params);
    }

    public function getTotalSearchResults($query, $categoryId = null, $startDate = null)
    {
        $params = [];
        
        $sql = "SELECT COUNT(*) as count
                FROM {$this->table} t
                WHERE t.is_active = 1";
        
        if ($query) {
            $sql .= " AND (t.title LIKE :query OR t.description LIKE :query_desc OR t.location LIKE :query_loc)";
            $params['query'] = "%{$query}%";
            $params['query_desc'] = "%{$query}%";
            $params['query_loc'] = "%{$query}%";
        }

        if ($categoryId) {
            $sql .= " AND t.category_id = :category_id";
            $params['category_id'] = $categoryId;
        }
        
        if ($startDate) {
            $sql .= " AND t.start_date = :start_date";
            $params['start_date'] = $startDate;
        }

        return $this->db->fetch($sql, $params)['count'];
    }

    // Alias for searchTours with pagination
    public function searchToursWithPagination($query, $page = 1, $perPage = 12, $categoryId = null)
    {
        return $this->searchTours($query, $categoryId, $page, $perPage);
    }

    public function getToursWithPagination($page = 1, $perPage = 12, $categoryId = null)
    {
        return $this->searchTours(null, $categoryId, $page, $perPage);
    }

    public function getTotalTours($categoryId = null)
    {
        return $this->getTotalSearchResults(null, $categoryId);
    }

    public function getFeaturedTours($limit = 6)
    {
        return $this->db->fetchAll("
            SELECT t.*, c.name as category_name
            FROM {$this->table} t
            LEFT JOIN categories c ON t.category_id = c.id
            WHERE t.is_active = 1 AND t.is_featured = 1
            ORDER BY t.created_at DESC
            LIMIT :limit
        ", ['limit' => $limit]);
    }

    public function getActiveTours($limit = null)
    {
        $sql = "SELECT t.*, c.name as category_name 
                FROM {$this->table} t 
                LEFT JOIN categories c ON t.category_id = c.id 
                WHERE t.is_active = 1
                ORDER BY t.is_featured DESC, t.created_at DESC";
        
        if ($limit) {
            $sql .= " LIMIT :limit";
            return $this->db->fetchAll($sql, ['limit' => $limit]);
        }
        
        return $this->db->fetchAll($sql, []);
    }

    public function getRelatedTours($tourId, $categoryId, $limit = 3)
    {
        return $this->db->fetchAll("
            SELECT t.*, c.name as category_name
            FROM {$this->table} t
            LEFT JOIN categories c ON t.category_id = c.id
            WHERE t.category_id = :category_id 
            AND t.id != :tour_id
            AND t.is_active = 1
            ORDER BY RAND()
            LIMIT :limit
        ", [
            'category_id' => $categoryId,
            'tour_id' => $tourId,
            'limit' => $limit
        ]);
    }

    public function findBySlug($slug)
    {
        return $this->db->fetch("
            SELECT t.*, c.name as category_name, c.slug as category_slug
            FROM {$this->table} t
            LEFT JOIN categories c ON t.category_id = c.id
            WHERE t.slug = :slug AND t.is_active = 1
        ", ['slug' => $slug]);
    }

    // Utility methods for admin operations
    public function updateImage($id, $imageUrl) {
        return $this->update($id, ['image_url' => $imageUrl]);
    }

    private function slugExists($slug) {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE slug = :slug";
        $result = $this->db->fetch($sql, ['slug' => $slug]);
        return (int) $result['count'] > 0;
    }

    public function generateSlug($title) {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
        $originalSlug = $slug;
        $count = 1;
        
        while ($this->slugExists($slug)) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }
        
        return $slug;
    }

    public function validate($data)
    {
        $errors = [];
        
        if (empty($data['title'])) {
            $errors['title'] = 'Title is required';
        }
        
        if (empty($data['description'])) {
            $errors['description'] = 'Description is required';
        }
        
        if (empty($data['price']) || !is_numeric($data['price'])) {
            $errors['price'] = 'Valid price is required';
        }
        
        if (empty($data['duration'])) {
            $errors['duration'] = 'Duration is required';
        }
        
        if (empty($data['category_id'])) {
            $errors['category_id'] = 'Category is required';
        }
        
        if (!empty($data['start_date'])) {
            // Validate date format
            $dateObj = \DateTime::createFromFormat('Y-m-d', $data['start_date']);
            if (!$dateObj || $dateObj->format('Y-m-d') !== $data['start_date']) {
                $errors['start_date'] = 'Invalid date format';
            }
        }
        
        return $errors;
    }
}