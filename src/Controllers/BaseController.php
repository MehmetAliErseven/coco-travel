<?php
namespace App\Controllers;

/**
 * Base Controller
 * 
 * All controllers extend this class
 */
abstract class BaseController
{
    protected $layout = 'layouts/main';

    /**
     * Set the layout for the view
     */
    protected function layout($layout)
    {
        $this->layout = $layout;
        return $this;
    }

    /**
     * Render a view with data
     * 
     * @param string $view The view file to render
     * @param array $data Data to pass to the view
     * @return void
     */
    protected function render($view, $data = [])
    {
        // Add translation variables to all views
        global $translator, $lang;
        $data['translator'] = $translator;
        $data['lang'] = $lang;
        
        // Extract data to make it available as variables in the view
        extract($data);
        
        // Start output buffering
        ob_start();
        
        // Include the view file
        $viewPath = BASE_PATH . "/src/Views/{$view}.php";
        if (file_exists($viewPath)) {
            include $viewPath;
        } else {
            throw new \App\Exceptions\NotFoundException("View {$view} not found");
        }
        
        // Get the content and end output buffering
        $content = ob_get_clean();
        
        // Include the layout with the content
        $layoutPath = BASE_PATH . "/src/Views/{$this->layout}.php";
        if (file_exists($layoutPath)) {
            include $layoutPath;
        } else {
            throw new \App\Exceptions\NotFoundException("Layout {$this->layout} not found");
        }
    }
    
    /**
     * Render a view without the layout
     * 
     * @param string $view The view file to render
     * @param array $data Data to pass to the view
     * @return void
     */
    protected function renderPartial($view, $data = [])
    {
        // Add translation variables to partial views
        global $translator, $lang;
        $data['translator'] = $translator;
        $data['lang'] = $lang;
        
        extract($data);
        
        $viewPath = BASE_PATH . "/src/Views/{$view}.php";
        if (file_exists($viewPath)) {
            include $viewPath;
        } else {
            throw new \App\Exceptions\NotFoundException("View {$view} not found");
        }
    }
    
    /**
     * Redirect to a URL
     * 
     * @param string $url The URL to redirect to
     * @return void
     */
    protected function redirect($url)
    {
        header("Location: {$url}");
        exit;
    }
    
    /**
     * Return JSON response
     * 
     * @param mixed $data Data to encode as JSON
     * @param int $statusCode HTTP status code
     * @return void
     */
    protected function json($data, $statusCode = 200)
    {
        header('Content-Type: application/json');
        http_response_code($statusCode);
        echo json_encode($data);
        exit;
    }
}