<?php
namespace App\Controllers;

use App\Models\TourModel;
use App\Models\CategoryModel;
use App\Models\MessageModel;

class ApiController extends BaseController
{
    private $tourModel;
    private $categoryModel;
    private $messageModel;
    private $translationService;

    public function __construct()
    {
        header('Content-Type: application/json');
        $this->tourModel = new TourModel();
        $this->categoryModel = new CategoryModel();
        $this->messageModel = new MessageModel();
        $this->translationService = new \App\Core\TranslationService();
    }

    public function searchToursAction()
    {
        // Get search parameters
        $query = isset($_GET['q']) ? trim($_GET['q']) : null;
        $categoryId = isset($_GET['category_id']) ? (int)$_GET['category_id'] : null;
        $startDate = isset($_GET['start_date']) ? trim($_GET['start_date']) : null;

        // Get current page and items per page
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perPage = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 12;

        // Use TourModel to search tours
        $searchResults = $this->tourModel->searchTours($query, $categoryId, $page, $perPage, $startDate);
        $totalResults = $this->tourModel->getTotalSearchResults($query, $categoryId, $startDate);

        // Format results
        $response = [
            'success' => true,
            'tours' => $searchResults,
            'totalCount' => $totalResults,
            'filteredCount' => count($searchResults),
            'page' => $page,
            'totalPages' => ceil($totalResults / $perPage)
        ];

        $this->jsonResponse($response);
    }

    public function getToursByCategoryAction($id)
    {
        try {
            // If a search query exists, redirect to search endpoint logic
            if (isset($_GET['q'])) {
                $tours = $this->tourModel->searchTours($_GET['q'], $id === 'all' ? null : $id);
            } else {
                // Just filter by category
                $tours = $this->tourModel->getToursWithPagination(1, null, $id === 'all' ? null : $id);
            }

            $this->jsonResponse([
                'success' => true,
                'tours' => $tours
            ]);
        } catch (\Exception $e) {
            $this->jsonResponse([
                'success' => false,
                'error' => 'Failed to fetch tours'
            ], 500);
        }
    }

    public function getToursByPageAction($page)
    {
        try {
            $perPage = 12;
            $query = $_GET['q'] ?? null;
            $categoryId = $_GET['category_id'] ?? null;
            
            // Get tours based on criteria
            $tours = $this->tourModel->searchTours($query, $categoryId, $page, $perPage);
            $totalTours = $this->tourModel->getTotalSearchResults($query, $categoryId);
            
            $this->jsonResponse([
                'success' => true,
                'tours' => $tours,
                'hasMore' => ($page * $perPage) < $totalTours
            ]);
        } catch (\Exception $e) {
            $this->jsonResponse([
                'success' => false,
                'error' => 'Failed to fetch tours'
            ], 500);
        }
    }

    public function submitContactAction()
    {
        $data = [
            'name' => $_POST['name'] ?? '',
            'email' => $_POST['email'] ?? '',
            'phone' => $_POST['phone'] ?? '',
            'subject' => $_POST['subject'] ?? '',
            'message' => $_POST['message'] ?? ''
        ];

        $errors = $this->messageModel->validate($data);
        
        if (empty($errors)) {
            $this->messageModel->create($data);
            $this->jsonResponse(['success' => true]);
        } else {
            $this->jsonResponse([
                'success' => false,
                'errors' => $errors
            ]);
        }
    }

    public function changeLanguageAction()
    {
        // Check if this is an AJAX request
        if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest') {
            $this->jsonResponse(['success' => false, 'message' => 'Only AJAX requests are allowed'], 400);
            return;
        }
        
        // Get the language code from the request
        $langCode = $_GET['lang'] ?? null;
        
        // Supported languages
        $supportedLanguages = ['en', 'th', 'tr', 'ru'];
        
        // Validate the language code
        if (!$langCode || !in_array($langCode, $supportedLanguages)) {
            $this->jsonResponse(['success' => false, 'message' => 'Invalid language code'], 400);
            return;
        }
        
        // Set the language in the session
        $_SESSION['lang'] = $langCode;
        
        // Set cookie for persistent language preference (30 days)
        setcookie('preferred_language', $langCode, time() + (86400 * 30), '/');
        
        // Return success response
        $this->jsonResponse(['success' => true, 'language' => $langCode]);
    }

    public function getTranslationsAction()
    {
        $keys = $_GET['keys'] ?? '';
        if (empty($keys)) {
            $this->jsonResponse(['success' => false, 'message' => 'No translation keys provided'], 400);
            return;
        }

        $keys = explode(',', $keys);
        $translations = [];
        
        foreach ($keys as $key) {
            $translations[$key] = $this->translationService->trans($key);
        }

        $this->jsonResponse([
            'success' => true,
            'translations' => $translations
        ]);
    }

    public function generatePasswordHashAction()
    {
        $password = $_GET['password'] ?? null;
        
        if (!$password) {
            echo json_encode(['error' => 'Password parameter is required']);
            return;
        }
        
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        echo json_encode(['hash' => $hashedPassword]);
    }

    private function jsonResponse($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        echo json_encode($data);
        exit;
    }
}
