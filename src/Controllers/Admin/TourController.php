<?php
namespace App\Controllers\Admin;

use App\Models\TourModel;
use App\Models\CategoryModel;

class TourController extends BaseAdminController
{
    private $tourModel;
    private $categoryModel;

    public function __construct()
    {
        parent::__construct();
        $this->tourModel = new TourModel();
        $this->categoryModel = new CategoryModel();
    }

    public function indexAction()
    {
        $perPage = 10;
        $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $tours = $this->tourModel->getToursWithPagination($currentPage, $perPage);
        $totalTours = $this->tourModel->getTotalTours();
        
        $this->render('admin/tours/index', [
            'pageTitle' => 'Manage Tours',
            'tours' => $tours,
            'currentPage' => $currentPage,
            'totalPages' => ceil($totalTours / $perPage)
        ]);
    }

    public function createAction()
    {
        $this->render('admin/tours/create', [
            'pageTitle' => 'Create Tour',
            'categories' => $this->categoryModel->getActiveCategories(),
            'errors' => [],
            'tour' => [
                'title' => '',
                'description' => '',
                'price' => '',
                'duration' => '',
                'category_id' => '',
                'is_featured' => 0,
                'is_active' => 1
            ]
        ]);
    }

    public function storeAction()
    {
        $tour = $this->getTourDataFromRequest();
        $errors = $this->tourModel->validate($tour);

        if (empty($errors)) {
            try {
                if (isset($_FILES['image'])) {
                    $imageResult = $this->handleImageUpload($_FILES['image']);
                    if ($imageResult !== false) {
                        $tour['image_url'] = $imageResult;
                    }
                }

                $this->tourModel->create($tour);
                $this->setSuccessMessage('Tour created successfully.');
                $this->redirect(\App\Helpers\url('admin/tours'));
                return;
            } catch (\Exception $e) {
                $errors['general'] = 'An error occurred while saving the tour.';
            }
        }

        $this->render('admin/tours/create', [
            'pageTitle' => 'Create Tour',
            'categories' => $this->categoryModel->getActiveCategories(),
            'errors' => $errors,
            'tour' => $tour
        ]);
    }

    // ... Add edit, update, and delete actions
    
    private function getTourDataFromRequest()
    {
        return [
            'title' => $_POST['title'] ?? '',
            'slug' => \App\Helpers\slugify($_POST['title'] ?? ''),
            'description' => $_POST['description'] ?? '',
            'itinerary' => $_POST['itinerary'] ?? '',
            'includes' => $_POST['includes'] ?? '',
            'price' => $_POST['price'] ?? null,
            'duration' => $_POST['duration'] ?? '',
            'category_id' => $_POST['category_id'] ?? null,
            'is_featured' => isset($_POST['is_featured']) ? 1 : 0,
            'is_active' => isset($_POST['is_active']) ? 1 : 0,
            'created_by' => $_SESSION['admin']['id']
        ];
    }

    protected function handleImageUpload($file, $uploadDir = null)
    {
        $uploadDir = $uploadDir ?? BASE_PATH . '/public/assets/uploads/tours/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        return parent::handleImageUpload($file, $uploadDir);
    }
}
