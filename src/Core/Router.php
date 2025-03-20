<?php
namespace App\Core;

use App\Exceptions\NotFoundException;
use App\Config\Config;

/**
 * Router Class
 * 
 * Handles mapping URLs to controllers and actions
 */
class Router
{
    private $routes;

    public function __construct()
    {
        $this->routes = require BASE_PATH . '/src/Config/routes.php';
    }

    /**
     * Dispatch the request to the appropriate controller
     * 
     * @param string $url URL
     * @return void
     */
    public function dispatch($url)
    {
        // Debug for development environment
        if ($_ENV['APP_ENV'] === 'development') {
            error_log("Dispatching URL: $url");
        }

        // Remove query string
        if ($pos = strpos($url, '?')) {
            $url = substr($url, 0, $pos);
        }
        $url = trim($url, '/');

        // Handle default route
        if (empty($url)) {
            return $this->handleDefaultRoute();
        }

        // Check if route exists in routes config
        if ($matchedRoute = $this->matchRoute($url)) {
            if ($_ENV['APP_ENV'] === 'development') {
                error_log("Matched Route: " . json_encode($matchedRoute));
            }
            return $this->executeRoute($matchedRoute['controller'], $matchedRoute['action'], $matchedRoute['params']);
        }

        // Check for API routes first
        if (strpos($url, 'api/') === 0) {
            return $this->handleApiRoute($url);
        }

        // Check for controller/action format
        $parts = explode('/', $url);
        $controller = ucfirst(array_shift($parts) ?: 'Home');
        $action = array_shift($parts) ?: 'index';
        $params = $parts;

        // Format controller class name
        $controllerClass = "App\\Controllers\\{$controller}Controller";
        
        if ($_ENV['APP_ENV'] === 'development') {
            error_log("Controller Class: $controllerClass");
            error_log("Action: $action");
            error_log("Params: " . json_encode($params));
        }
        
        // Check if controller exists
        if (!class_exists($controllerClass)) {
            throw new NotFoundException("Controller $controller not found");
        }
        
        // Create controller instance
        $controllerInstance = new $controllerClass();
        
        // Format action name
        $action = lcfirst($action) . 'Action';
        
        // Check if action exists
        if (!method_exists($controllerInstance, $action)) {
            throw new NotFoundException("Action $action not found in controller $controller");
        }
        
        // Call the action with parameters
        return call_user_func_array([$controllerInstance, $action], $params);
    }

    /**
     * Handle the default route (homepage)
     */
    private function handleDefaultRoute()
    {
        $controller = new \App\Controllers\HomeController();
        return $controller->indexAction();
    }

    /**
     * Match a URL against defined routes
     * 
     * @param string $url The URL to match
     * @return array|false Matched route or false
     */
    private function matchRoute($url)
    {
        foreach ($this->routes as $pattern => $route) {
            // Check for direct match
            if ($pattern === $url) {
                return [
                    'controller' => $route['controller'],
                    'action' => $route['action'],
                    'params' => []
                ];
            }
            
            // Convert route pattern to regex for parameter matches
            $regexPattern = str_replace('/', '\/', $pattern);
            $regexPattern = preg_replace('/\{(\w+)\}/', '(?P<$1>[^\/]+)', $regexPattern);
            $regexPattern = "/^$regexPattern$/";

            if (preg_match($regexPattern, $url, $matches)) {
                // Remove numeric keys from matches
                $params = array_filter($matches, function($key) {
                    return !is_numeric($key);
                }, ARRAY_FILTER_USE_KEY);

                return [
                    'controller' => $route['controller'],
                    'action' => $route['action'],
                    'params' => $params
                ];
            }
        }

        return false;
    }

    /**
     * Execute a route with given controller, action and parameters
     */
    private function executeRoute($controller, $action, $params)
    {
        // Handle namespaced controllers (admin controllers)
        if (strpos($controller, '\\') !== false) {
            $controllerClass = "App\\Controllers\\$controller" . "Controller";
        } else {
            $controllerClass = "App\\Controllers\\{$controller}Controller";
        }
        
        if (!class_exists($controllerClass)) {
            throw new NotFoundException("Controller '$controller' not found");
        }
        
        $controllerInstance = new $controllerClass();
        $action = lcfirst($action) . 'Action';
        
        if (!method_exists($controllerInstance, $action)) {
            throw new NotFoundException("Action '$action' not found in controller '$controller'");
        }
        
        return call_user_func_array([$controllerInstance, $action], $params);
    }

    private function handleApiRoute($url)
    {
        if ($_ENV['APP_ENV'] === 'development') {
            error_log("Handling API Route: $url");
        }

        // Set API response headers
        header('Content-Type: application/json');

        // Check direct match with routes first
        foreach ($this->routes as $pattern => $route) {
            // Only process api routes
            if (strpos($pattern, 'api/') !== 0) {
                continue;
            }
            
            // Check for direct match
            if ($pattern === $url) {
                return $this->executeRoute($route['controller'], $route['action'], []);
            }
            
            // Convert route pattern to regex for parameter matches
            $regexPattern = str_replace('/', '\/', $pattern);
            $regexPattern = preg_replace('/\{(\w+)\}/', '(?P<$1>[^\/]+)', $regexPattern);
            $regexPattern = "/^$regexPattern$/";

            if (preg_match($regexPattern, $url, $matches)) {
                // Remove numeric keys from matches
                $params = array_filter($matches, function($key) {
                    return !is_numeric($key);
                }, ARRAY_FILTER_USE_KEY);

                return $this->executeRoute($route['controller'], $route['action'], $params);
            }
        }

        // Return 404 for API
        http_response_code(404);
        echo json_encode(['error' => 'API Endpoint not found']);
        exit;
    }
}