<?php
// ═══════════════════════════════════════════════
// Cimsho — Client-Side Front Controller
// public/index.php
// ═══════════════════════════════════════════════

session_start();

// ── Bootstrap ────────────────────────────────────
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/../core/Model.php';
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Router.php';

// ── Router ───────────────────────────────────────
$router = new Router();

// ── Public Routes ────────────────────────────────
$router->get('/',          'HomeController',    'index');

// Auth
$router->get('/login',     'AuthController',    'loginForm');
$router->post('/login',    'AuthController',    'login');
$router->get('/register',  'AuthController',    'registerForm');
$router->post('/register', 'AuthController',    'register');
$router->post('/logout',   'AuthController',    'logout');

// Account (protected)
$router->get('/account',   'AccountController', 'index');

// ── TEMPLATE: Add new routes here ────────────────
// $router->get('/products',          'ProductController',  'index');
// $router->get('/product/{id}',      'ProductController',  'show');
// $router->get('/categories',        'CategoryController', 'index');
// $router->get('/cart',              'CartController',     'index');
// $router->post('/cart/add',         'CartController',     'add');
// $router->get('/checkout',          'CheckoutController', 'index');
// $router->post('/checkout',         'CheckoutController', 'place');
// $router->get('/orders',            'OrderController',    'index');
// $router->get('/orders/{number}',   'OrderController',    'show');
// $router->get('/about',             'PageController',     'about');
// $router->get('/contact',           'PageController',     'contact');
// $router->post('/contact',          'PageController',     'sendContact');
// $router->get('/account/edit',      'AccountController',  'edit');
// $router->post('/account/edit',     'AccountController',  'update');
// $router->get('/account/addresses', 'AccountController',  'addresses');
// $router->get('/account/password',  'AccountController',  'passwordForm');
// $router->post('/account/password', 'AccountController',  'updatePassword');
// ─────────────────────────────────────────────────

$router->dispatch();
