<?php

/**
 * This script shows all registered routes in the application
 * Run with: php show-routes.php
 */

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Http\Kernel')->bootstrap();

// Get all routes
$routes = app('router')->getRoutes();

echo "=================================================================\n";
echo " REGISTERED ROUTES IN APPLICATION\n";
echo "=================================================================\n\n";

// Store routes by controller method
$routesByController = [];

foreach ($routes as $route) {
    $action = $route->getAction();
    
    if (isset($action['controller'])) {
        list($controller, $method) = explode('@', class_basename($action['controller']));
        
        if (!isset($routesByController[$controller])) {
            $routesByController[$controller] = [];
        }
        
        $routesByController[$controller][] = [
            'method' => $method,
            'uri' => $route->uri(),
            'name' => $route->getName(),
            'middleware' => isset($action['middleware']) ? implode(', ', (array)$action['middleware']) : 'none',
        ];
    } else {
        // Closure or other type of route
        $routesByController['Other'][] = [
            'uri' => $route->uri(),
            'name' => $route->getName(),
            'middleware' => isset($action['middleware']) ? implode(', ', (array)$action['middleware']) : 'none',
        ];
    }
}

// Print admin routes first
$adminRoutes = [];
foreach ($routesByController as $controller => $routes) {
    if (strpos($controller, 'Admin') !== false) {
        $adminRoutes[$controller] = $routes;
    }
}

echo "ADMIN ROUTES:\n";
echo "=================================================================\n";
foreach ($adminRoutes as $controller => $routes) {
    echo "\n[Controller: $controller]\n\n";
    
    foreach ($routes as $route) {
        echo "  URI: " . str_pad($route['uri'], 40) . " | ";
        echo "Name: " . str_pad($route['name'] ?? 'unnamed', 30) . " | ";
        echo "Method: " . str_pad($route['method'] ?? 'n/a', 20) . " | ";
        echo "Middleware: " . $route['middleware'] . "\n";
    }
}

// Print other routes
echo "\n\nOTHER IMPORTANT ROUTES:\n";
echo "=================================================================\n";

$importantControllers = ['ProductController', 'CategoryController', 'OrderController', 'ProfileController'];

foreach ($routesByController as $controller => $routes) {
    if (!isset($adminRoutes[$controller]) && in_array($controller, $importantControllers)) {
        echo "\n[Controller: $controller]\n\n";
        
        foreach ($routes as $route) {
            echo "  URI: " . str_pad($route['uri'], 40) . " | ";
            echo "Name: " . str_pad($route['name'] ?? 'unnamed', 30) . " | ";
            echo "Method: " . str_pad($route['method'] ?? 'n/a', 20) . " | ";
            echo "Middleware: " . $route['middleware'] . "\n";
        }
    }
}

echo "\n=================================================================\n";