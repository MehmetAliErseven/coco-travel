<?php
namespace App\Controllers\Admin;

use App\Models\CategoryModel;

class CategoryController extends BaseAdminController
{
    private $categoryModel;

    public function __construct()
    {
        parent::__construct();
        $this->categoryModel = new CategoryModel();
    }

    public function indexAction()
    {
        $categories = $this->categoryModel->getAllCategories();
        
        $this->render('admin/categories/index', [
            'pageTitle' => 'Manage Categories',
            'categories' => $categories
        ]);
    }

    public function createAction()
    {
        $this->render('admin/categories/create', [
            'pageTitle' => 'Create Category',
            'errors' => [],
            'category' => ['name' => '', 'description' => '', 'is_active' => 1]
        ]);
    }

    public function storeAction()
    {
        $category = [
            'name' => $_POST['name'] ?? '',
            'slug' => \App\Helpers\slugify($_POST['name'] ?? ''),
            'description' => $_POST['description'] ?? '',
            'is_active' => isset($_POST['is_active']) ? 1 : 0
        ];

        $errors = $this->categoryModel->validate($category);

        if (empty($errors)) {
            try {
                $this->categoryModel->create($category);
                $this->setSuccessMessage('Category created successfully.');
                $this->redirect(\App\Helpers\url('admin/categories'));
                return;
            } catch (\Exception $e) {
                $errors['general'] = 'An error occurred while saving the category.';
            }
        }

        $this->render('admin/categories/create', [
            'pageTitle' => 'Create Category',
            'errors' => $errors,
            'category' => $category
        ]);
    }

    public function editAction($id)
    {
        $category = $this->categoryModel->findById($id);
        
        if (!$category) {
            $this->redirect(\App\Helpers\url('admin/categories'));
            return;
        }

        $this->render('admin/categories/edit', [
            'pageTitle' => 'Edit Category',
            'category' => $category,
            'errors' => []
        ]);
    }

    public function updateAction($id)
    {
        $category = $this->categoryModel->findById($id);
        
        if (!$category) {
            $this->redirect(\App\Helpers\url('admin/categories'));
            return;
        }

        $updatedData = [
            'name' => $_POST['name'] ?? '',
            'slug' => \App\Helpers\slugify($_POST['name'] ?? ''),
            'description' => $_POST['description'] ?? '',
            'is_active' => isset($_POST['is_active']) ? 1 : 0
        ];

        $errors = $this->categoryModel->validate($updatedData, $id);

        if (empty($errors)) {
            try {
                $this->categoryModel->update($id, $updatedData);
                $this->setSuccessMessage('Category updated successfully.');
                $this->redirect(\App\Helpers\url('admin/categories'));
                return;
            } catch (\Exception $e) {
                $errors['general'] = 'An error occurred while updating the category.';
            }
        }

        $this->render('admin/categories/edit', [
            'pageTitle' => 'Edit Category',
            'category' => array_merge($category, $updatedData),
            'errors' => $errors
        ]);
    }

    public function deleteAction($id)
    {
        try {
            if ($this->categoryModel->hasTours($id)) {
                $this->setErrorMessage('Cannot delete category with associated tours.');
            } else {
                $this->categoryModel->delete($id);
                $this->setSuccessMessage('Category deleted successfully.');
            }
        } catch (\Exception $e) {
            $this->setErrorMessage('An error occurred while deleting the category.');
        }
        
        $this->redirect(\App\Helpers\url('admin/categories'));
    }
}
