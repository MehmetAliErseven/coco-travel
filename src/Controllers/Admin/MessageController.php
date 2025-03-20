<?php
namespace App\Controllers\Admin;

use App\Models\MessageModel;

class MessageController extends BaseAdminController
{
    private $messageModel;

    public function __construct()
    {
        parent::__construct();
        $this->messageModel = new MessageModel();
    }

    public function indexAction()
    {
        $perPage = 10;
        $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $messages = $this->messageModel->getMessagesWithPagination($currentPage, $perPage);
        $totalMessages = $this->messageModel->getTotalCount();

        $this->render('admin/messages/index', [
            'pageTitle' => 'Messages',
            'messages' => $messages,
            'currentPage' => $currentPage,
            'totalPages' => ceil($totalMessages / $perPage)
        ]);
    }

    public function viewAction($id)
    {
        $message = $this->messageModel->findById($id);
        
        if (!$message) {
            $this->redirect(\App\Helpers\url('admin/messages'));
            return;
        }

        if ($message['is_read'] == 0) {
            $this->messageModel->markAsRead($id);
        }

        $this->render('admin/messages/view', [
            'pageTitle' => 'View Message',
            'message' => $message
        ]);
    }

    public function deleteAction($id)
    {
        try {
            $this->messageModel->delete($id);
            $this->setSuccessMessage('Message deleted successfully.');
        } catch (\Exception $e) {
            $this->setErrorMessage('An error occurred while deleting the message.');
        }
        
        $this->redirect(\App\Helpers\url('admin/messages'));
    }
}
