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
                'start_date' => '',
                'location' => '',
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
                if (isset($_FILES['tour_image']) && $_FILES['tour_image']['error'] === UPLOAD_ERR_OK) {
                    $imageResult = $this->handleImageUpload($_FILES['tour_image']);
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

    public function editAction($id)
    {
        $tour = $this->tourModel->findById($id);
        
        if (!$tour) {
            $this->setErrorMessage('Tour not found.');
            $this->redirect(\App\Helpers\url('admin/tours'));
            return;
        }

        $this->render('admin/tours/edit', [
            'pageTitle' => 'Edit Tour',
            'tour' => $tour,
            'categories' => $this->categoryModel->getActiveCategories(),
            'errors' => []
        ]);
    }

    public function updateAction($id)
    {
        $tour = $this->tourModel->findById($id);
        
        if (!$tour) {
            $this->setErrorMessage('Tour not found.');
            $this->redirect(\App\Helpers\url('admin/tours'));
            return;
        }

        $updatedData = $this->getTourDataFromRequest();
        $errors = $this->tourModel->validate($updatedData);

        if (empty($errors)) {
            try {
                // Handle image upload if new image is provided
                if (isset($_FILES['tour_image']) && $_FILES['tour_image']['error'] === UPLOAD_ERR_OK) {
                    // Önce eski resmi sil (eğer varsa)
                    if (!empty($tour['image_url'])) {
                        $oldImagePath = BASE_PATH . '/public/assets/uploads/' . $tour['image_url'];
                        if (file_exists($oldImagePath)) {
                            unlink($oldImagePath);
                        }
                    }
                    
                    // Yeni resmi yükle
                    $imageResult = $this->handleImageUpload($_FILES['tour_image']);
                    if ($imageResult !== false) {
                        $updatedData['image_url'] = $imageResult;
                    }
                }

                $this->tourModel->update($id, $updatedData);
                $this->setSuccessMessage('Tour updated successfully.');
                $this->redirect(\App\Helpers\url('admin/tours'));
                return;
            } catch (\Exception $e) {
                $errors['general'] = 'An error occurred while updating the tour.';
            }
        }

        $this->render('admin/tours/edit', [
            'pageTitle' => 'Edit Tour',
            'tour' => array_merge($tour, $updatedData),
            'categories' => $this->categoryModel->getActiveCategories(),
            'errors' => $errors
        ]);
    }

    public function deleteAction($id)
    {
        try {
            $tour = $this->tourModel->findById($id);
            
            if (!$tour) {
                $this->setErrorMessage('Tour not found.');
            } else {
                // Delete image file if exists
                if (!empty($tour['image_url'])) {
                    $imagePath = BASE_PATH . '/public/assets/uploads/' . $tour['image_url'];
                    if (file_exists($imagePath)) {
                        unlink($imagePath);
                    }
                }
                
                $this->tourModel->delete($id);
                $this->setSuccessMessage('Tour deleted successfully.');
            }
        } catch (\Exception $e) {
            $this->setErrorMessage('An error occurred while deleting the tour.');
        }
        
        $this->redirect(\App\Helpers\url('admin/tours'));
    }

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
            'start_date' => !empty($_POST['start_date']) ? $_POST['start_date'] : null,
            'location' => $_POST['location'] ?? '',
            'category_id' => $_POST['category_id'] ?? null,
            'is_featured' => isset($_POST['is_featured']) ? 1 : 0,
            'is_active' => isset($_POST['is_active']) ? 1 : 0
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
