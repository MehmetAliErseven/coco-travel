<?php
namespace App\Controllers\Admin;

use App\Models\TourModel;
use App\Models\CategoryModel;
use App\Models\MessageModel;

class DashboardController extends BaseAdminController
{
    private $tourModel;
    private $categoryModel;
    private $messageModel;

    public function __construct()
    {
        parent::__construct();
        $this->tourModel = new TourModel();
        $this->categoryModel = new CategoryModel();
        $this->messageModel = new MessageModel();
    }

    public function indexAction()
    {
        $stats = [
            'totalTours' => $this->tourModel->getTotalTours(),
            'activeTours' => $this->tourModel->getTotalTours(),
            'totalCategories' => count($this->categoryModel->getActiveCategories()),
            'totalMessages' => $this->messageModel->getTotalCount()
        ];
        
        $recentMessages = $this->messageModel->getRecentMessages();
        
        $this->layout('admin/layouts/main')
             ->render('admin/dashboard', [
                'pageTitle' => 'Admin Dashboard',
                'stats' => $stats,
                'recentMessages' => $recentMessages
        ]);
    }
}
