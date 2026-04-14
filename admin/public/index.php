<?php

declare(strict_types=1);

// ---- Bootstrap ----
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/../core/Model.php';
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Router.php';

// ---- Session ----
ini_set('session.cookie_httponly', '1');
ini_set('session.use_strict_mode', '1');
session_start();

// ---- Router ----
$router = new Router();

// Auth routes
$router->get('/login',           'AuthController', 'login');
$router->post('/login',          'AuthController', 'authenticate');
$router->get('/logout',          'AuthController', 'logout');
$router->post('/change-password', 'AuthController', 'changePassword');

// Dashboard
$router->get('/dashboard', 'DashboardController', 'index');
$router->get('/',          'DashboardController', 'index');

// Category routes
$router->get('/categories',           'CategoryController', 'index');
$router->get('/categories/create',    'CategoryController', 'create');
$router->post('/categories/store',    'CategoryController', 'store');
$router->get('/categories/edit/{id}', 'CategoryController', 'edit');
$router->post('/categories/update/{id}', 'CategoryController', 'update');
$router->post('/categories/delete/{id}', 'CategoryController', 'delete');
$router->post('/categories/toggle-status/{id}', 'CategoryController', 'toggleStatus');

// Sub Category routes
$router->get('/sub-categories', 'SubCategoryController', 'index');
$router->get('/sub-categories/create', 'SubCategoryController', 'create');
$router->post('/sub-categories/store', 'SubCategoryController', 'store');
$router->get('/sub-categories/edit/{id}', 'SubCategoryController', 'edit');
$router->post('/sub-categories/update/{id}', 'SubCategoryController', 'update');
$router->post('/sub-categories/delete/{id}', 'SubCategoryController', 'delete');
$router->post('/sub-categories/toggle-status/{id}', 'SubCategoryController', 'toggleStatus');
$router->get('/sub-categories/get-by-category/{id}', 'SubCategoryController', 'getByCategory');

// Product routes
$router->get('/products', 'ProductController', 'index');
$router->get('/products/create', 'ProductController', 'create');
$router->post('/products/store', 'ProductController', 'store');
$router->get('/products/edit/{id}', 'ProductController', 'edit');
$router->post('/products/update/{id}', 'ProductController', 'update');
$router->post('/products/delete/{id}', 'ProductController', 'delete');
$router->get('/products/stock', 'ProductController', 'stock');
$router->post('/products/update-stock', 'ProductController', 'updateStock');
$router->get('/products/get-subcategories', 'ProductController', 'getSubCategories');
$router->post('/products/delete-image', 'ProductController', 'deleteImage');
$router->post('/products/set-primary-image', 'ProductController', 'setPrimaryImage');

// Optional - if needed
$router->post('/products/toggle-status/{id}', 'ProductController', 'toggleStatus');

// Coupon routes
$router->get('/coupons', 'CouponController', 'index');
$router->get('/coupons/create', 'CouponController', 'create');
$router->post('/coupons/store', 'CouponController', 'store');
$router->get('/coupons/edit/{id}', 'CouponController', 'edit');
$router->post('/coupons/update/{id}', 'CouponController', 'update');
$router->post('/coupons/delete/{id}', 'CouponController', 'delete');
$router->post('/coupons/toggle-status/{id}', 'CouponController', 'toggleStatus');
$router->get('/coupons/generate-code', 'CouponController', 'generateCode');

// Delivery Settings routes
$router->get('/settings/delivery', 'DeliverySettingsController', 'index');
$router->post('/settings/delivery/update', 'DeliverySettingsController', 'update');
$router->post('/settings/delivery/calculate-preview', 'DeliverySettingsController', 'calculatePreview');

// Settings routes
$router->get('/settings/shop', 'SettingsController', 'shop');
$router->post('/settings/shop/update', 'SettingsController', 'updateShop');
$router->get('/settings/site', 'SettingsController', 'site');
$router->post('/settings/site/update', 'SettingsController', 'updateSite');

// ---- Dispatch ----
$router->dispatch();
