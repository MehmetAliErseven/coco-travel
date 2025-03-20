<?php
namespace App\Core;

use App\Exceptions\NotFoundException;
use App\Config\Config;

class Application
{
    private static $instance;
    private $router;
    
    public function __construct()
    {
        self::$instance = $this;
        $this->router = new Router();
    }
    
    public static function getInstance()
    {
        return self::$instance;
    }
    
    public function run()
    {
        try {
            $url = $this->parseUrl();
            $this->router->dispatch($url);
        } catch (\Exception $e) {
            $this->handleException($e);
        }
    }
    
    private function parseUrl()
    {
        if (isset($_GET['url'])) {
            return filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL);
        }
        return '';
    }
    
    private function handleException(\Exception $e)
    {
        if ($e instanceof NotFoundException) {
            http_response_code(404);
            
            if ($this->isApiRequest()) {
                header('Content-Type: application/json');
                echo json_encode(['error' => 'Not Found', 'message' => $e->getMessage()]);
                return;
            }
            
            include(BASE_PATH . '/src/Views/errors/404.php');
            return;
        }

        http_response_code(500);
        
        if ($this->isApiRequest()) {
            header('Content-Type: application/json');
            echo json_encode([
                'error' => 'Server Error',
                'message' => $_ENV['APP_ENV'] === 'development' ? $e->getMessage() : 'An unexpected error occurred'
            ]);
            return;
        }

        if ($_ENV['APP_ENV'] === 'development') {
            echo '<h1>Error</h1>';
            echo '<p>' . $e->getMessage() . '</p>';
            echo '<pre>' . $e->getTraceAsString() . '</pre>';
        } else {
            include(BASE_PATH . '/src/Views/errors/500.php');
        }
        
        error_log($e->getMessage() . "\n" . $e->getTraceAsString());
    }
    
    private function isApiRequest()
    {
        return strpos($this->parseUrl(), 'api/') === 0;
    }
    
    public function getRouter()
    {
        return $this->router;
    }
}