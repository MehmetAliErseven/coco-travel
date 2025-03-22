<?php
namespace App\Controllers;

use App\Core\Database;
use App\Exceptions\NotFoundException;
use App\Models\TourModel;
use App\Models\CategoryModel;

class ToursController extends BaseController
{
    private $tourModel;
    private $categoryModel;

    public function __construct()
    {
        $this->tourModel = new TourModel();
        $this->categoryModel = new CategoryModel();
    }

    /**
     * Display all tours
     */
    public function indexAction()
    {
        // Get all active categories for the filter
        $categories = $this->categoryModel->getActiveCategories();
        
        // Pagination parameters
        $perPage = 12;
        $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($currentPage < 1) $currentPage = 1;
        
        // Get search parameters
        $query = $_GET['q'] ?? '';
        $categoryId = $_GET['category_id'] ?? null;
        
        // Get tours based on search/filter criteria
        if ($query || $categoryId) {
            $tours = $this->tourModel->searchToursWithPagination($query, $currentPage, $perPage, $categoryId);
            $totalCount = $this->tourModel->getTotalSearchResults($query, $categoryId);
        } else {
            $tours = $this->tourModel->getToursWithPagination($currentPage, $perPage);
            $totalCount = $this->tourModel->getTotalTours();
        }
        
        $totalPages = ceil($totalCount / $perPage);
        
        // Ensure current page doesn't exceed total pages
        if ($currentPage > $totalPages) {
            $this->redirect(\App\Helpers\url('tours?page=' . $totalPages));
            return;
        }
        
        // Render the view
        $this->render('tours/index', [
            'pageTitle' => 'All Tours',
            'tours' => $tours,
            'categories' => $categories,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
            'totalCount' => $totalCount,
            'currentQuery' => $query,
            'currentCategory' => $categoryId
        ]);
    }
    
    /**
     * Display tour details
     */
    public function viewAction($slug = '')
    {
        if (empty($slug)) {
            $this->redirect(\App\Helpers\url('tours'));
            return;
        }
        
        // Get tour details
        $tour = $this->tourModel->findBySlug($slug);
        
        if (!$tour) {
            throw new NotFoundException("Tour not found");
        }
        
        // Get related tours (same category)
        $relatedTours = $this->tourModel->getRelatedTours($tour['id'], $tour['category_id'], 3);
        
        // Render the view
        $this->render('tours/view', [
            'pageTitle' => htmlspecialchars($tour['title']),
            'tour' => $tour,
            'relatedTours' => $relatedTours
        ]);
    }
}