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
        $db = Database::getInstance();
        $query = $_GET['query'] ?? '';
        $categoryId = intval($_GET['category_id'] ?? 0);
        
        $params = ['query' => "%{$query}%"];
        $categoryFilter = '';
        
        if ($categoryId > 0) {
            $params['category_id'] = $categoryId;
            $categoryFilter = "AND t.category_id = :category_id";
        }
        
        // Search tours by title and description
        $searchResults = $db->fetchAll("
            SELECT t.*, c.name as category_name
            FROM tours t
            LEFT JOIN categories c ON t.category_id = c.id
            WHERE t.is_active = 1 
            AND (t.title LIKE :query OR t.description LIKE :query)
            $categoryFilter
            ORDER BY t.title ASC
        ", $params);
        
        // Return JSON response for AJAX requests
        if (isset($_GET['ajax']) && $_GET['ajax'] === '1') {
            $this->json($searchResults);
        }
        
        // Get categories for the filter
        $categories = $db->fetchAll("
            SELECT * FROM categories 
            WHERE is_active = 1
            ORDER BY name ASC
        ");
        
        // Render the view with search results
        $this->render('home/search', [
            'pageTitle' => 'Search Results',
            'searchResults' => $searchResults,
            'query' => $query,
            'categoryId' => $categoryId,
            'categories' => $categories
        ]);
    }
}