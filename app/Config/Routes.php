<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Public routes (read-only)
$routes->group('p', ['namespace' => 'App\Controllers'], function($routes) {
    // Public content routes
    $routes->get('(:segment)', 'Public\Home::index/$1', ['as' => 'public.home']);
    $routes->get('(:segment)/(:any)', 'Public\Home::index/$1', ['as' => 'public.catchall']);
});

// Tenant-scoped admin routes
$routes->group('t', ['namespace' => 'App\\Controllers'], function($routes) {
    // Tenant authentication routes
    $routes->group('(:segment)', function($routes) {
        // Authentication routes
        $routes->get('login', 'AuthController::login/$1', ['as' => 'login']);
        $routes->post('login', 'AuthController::attemptLogin/$1', ['as' => 'login.attempt']);
        $routes->get('logout', 'AuthController::logout/$1', ['as' => 'logout']);
        
        // Protected routes (require authentication)
        $routes->group('', ['filter' => 'auth'], function($routes) {
            // Simple home route after login
            $routes->get('', 'DashboardController::index');
            
            // Pages resource
            $routes->resource('pages', [
                'controller' => 'PagesController',
                'filter' => 'auth',
                'except' => ['show', 'new', 'edit'],
                'placeholder' => '(:num)'
            ]);
            
            // Custom routes for pages that need different handling
            $routes->get('pages/new', 'PagesController::new', ['as' => 'pages.new']);
            $routes->get('pages/(:num)/edit', 'PagesController::edit/$1', ['as' => 'pages.edit']);
            $routes->get('pages/(:num)', 'PagesController::show/$1', ['as' => 'pages.show']);
        });
    });
});

// Root redirect (in a real app, you might have a landing page)
// $routes->get('/', function() {
//     // In a real implementation, this would go to a landing page
//     // For now, we'll redirect to the admin interface
//     return redirect()->to('/t/default');
// });
$routes->get('/', 'LandingController::index');


// Fallback route - must be last
$routes->set404Override(function() {
    return view('errors/html/error_404');
});
