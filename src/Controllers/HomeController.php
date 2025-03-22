<?php
namespace App\Controllers;

use App\Core\Database;

class HomeController extends BaseController
{
    /**
     * Display homepage with featured tours
     */
    public function indexAction()
    {
        $db = Database::getInstance();
        
        // Get featured tours for the homepage slider
        $featuredTours = $db->fetchAll("
            SELECT t.*, c.name as category_name 
            FROM tours t
            LEFT JOIN categories c ON t.category_id = c.id
            WHERE t.is_active = 1 AND t.is_featured = 1
            ORDER BY t.created_at DESC
            LIMIT 6
        ");
        
        // Get categories for the tour filter
        $categories = $db->fetchAll("
            SELECT * FROM categories 
            WHERE is_active = 1
            ORDER BY name ASC
        ");
        
        // Get recent tours for the tour listing section
        $recentTours = $db->fetchAll("
            SELECT t.*, c.name as category_name
            FROM tours t
            LEFT JOIN categories c ON t.category_id = c.id
            WHERE t.is_active = 1
            ORDER BY t.created_at DESC
            LIMIT 12
        ");
        
        // Render the view with data
        $this->render('home/index', [
            'pageTitle' => '',
            'featuredTours' => $featuredTours,
            'categories' => $categories,
            'recentTours' => $recentTours
        ]);
    }
    
    /**
     * Search tours by name
     */
    public function searchAction()
    {
        $query = $_GET['query'] ?? '';
        $categoryId = isset($_GET['category_id']) && $_GET['category_id'] > 0 ? (int)$_GET['category_id'] : null;
        $startDate = isset($_GET['start_date']) && !empty($_GET['start_date']) ? $_GET['start_date'] : null;
        
        $db = Database::getInstance();
        
        // Base query
        $sql = "SELECT t.*, c.name as category_name 
                FROM tours t
                LEFT JOIN categories c ON t.category_id = c.id
                WHERE t.is_active = 1";
        
        $params = [];
        
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
        
        // Order by featured and created date
        $sql .= " ORDER BY t.is_featured DESC, t.created_at DESC";
        
        $searchResults = $db->fetchAll($sql, $params);

        // Get categories for the filter
        $categories = $db->fetchAll("
            SELECT * FROM categories 
            WHERE is_active = 1
            ORDER BY name ASC
        ");
        
        $this->render('home/search', [
            'pageTitle' => 'Search Results',
            'searchResults' => $searchResults,
            'query' => $query,
            'startDate' => $startDate,
            'categoryId' => $categoryId,
            'categories' => $categories
        ]);
    }
}