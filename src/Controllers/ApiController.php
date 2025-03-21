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
        $query = $_GET['q'] ?? '';
        $categoryId = $_GET['category_id'] ?? null;
        
        // Get total count of all active tours
        $totalCount = $this->tourModel->getTotalTours();
        
        // Get filtered tours
        $tours = $this->tourModel->searchTours($query, $categoryId);
        
        $this->jsonResponse([
            'success' => true,
            'tours' => $tours,
            'totalCount' => $totalCount,
            'filteredCount' => count($tours)
        ]);
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

    private function jsonResponse($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        echo json_encode($data);
        exit;
    }
}
