<?php

return [
    // Tours routes
    'tours' => ['controller' => 'Tours', 'action' => 'index'],
    'tours/view/{slug}' => ['controller' => 'Tours', 'action' => 'view'],
    
    // API Routes
    'api/generate-password-hash' => ['controller' => 'Api', 'action' => 'generatePasswordHash'],
    'api/tours/search' => ['controller' => 'Api', 'action' => 'searchTours'],
    'api/tours/category/{id}' => ['controller' => 'Api', 'action' => 'getToursByCategory'],
    'api/tours/page/{page}' => ['controller' => 'Api', 'action' => 'getToursByPage'],
    'api/contact/submit' => ['controller' => 'Api', 'action' => 'submitContact'],
    'api/change-language' => ['controller' => 'Api', 'action' => 'changeLanguage'],
    'api/translations' => ['controller' => 'Api', 'action' => 'getTranslations'],

    // Admin Routes
    'admin' => ['controller' => 'Admin\Dashboard', 'action' => 'index'],
    'admin/login' => ['controller' => 'Admin\Auth', 'action' => 'login'],
    'admin/authenticate' => ['controller' => 'Admin\Auth', 'action' => 'authenticate'],
    'admin/logout' => ['controller' => 'Admin\Auth', 'action' => 'logout'],
    'admin/unauthorized' => ['controller' => 'Admin\Auth', 'action' => 'unauthorized'],
    
    // Admin Tour Routes
    'admin/tours' => ['controller' => 'Admin\Tour', 'action' => 'index'],
    'admin/tours/create' => ['controller' => 'Admin\Tour', 'action' => 'create'],
    'admin/tours/store' => ['controller' => 'Admin\Tour', 'action' => 'store'],
    'admin/tours/edit/{id}' => ['controller' => 'Admin\Tour', 'action' => 'edit'],
    'admin/tours/update/{id}' => ['controller' => 'Admin\Tour', 'action' => 'update'],
    'admin/tours/delete/{id}' => ['controller' => 'Admin\Tour', 'action' => 'delete'],
    
    // Admin Category Routes
    'admin/categories' => ['controller' => 'Admin\Category', 'action' => 'index'],
    'admin/categories/create' => ['controller' => 'Admin\Category', 'action' => 'create'],
    'admin/categories/store' => ['controller' => 'Admin\Category', 'action' => 'store'],
    'admin/categories/edit/{id}' => ['controller' => 'Admin\Category', 'action' => 'edit'],
    'admin/categories/update/{id}' => ['controller' => 'Admin\Category', 'action' => 'update'],
    'admin/categories/delete/{id}' => ['controller' => 'Admin\Category', 'action' => 'delete'],
    
    // Admin Message Routes
    'admin/messages' => ['controller' => 'Admin\Message', 'action' => 'index'],
    'admin/messages/view/{id}' => ['controller' => 'Admin\Message', 'action' => 'view'],
    'admin/messages/delete/{id}' => ['controller' => 'Admin\Message', 'action' => 'delete']
];
