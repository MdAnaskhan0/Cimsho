<?php
declare(strict_types=1);

// ── Bootstrap ──────────────────────────────────────────────
require_once __DIR__.'/../config/app.php';
require_once __DIR__.'/../config/database.php';
require_once __DIR__.'/../core/Database.php';
require_once __DIR__.'/../core/Model.php';
require_once __DIR__.'/../core/Controller.php';
require_once __DIR__.'/../core/Router.php';

// ── Session ────────────────────────────────────────────────
ini_set('session.cookie_httponly','1');
ini_set('session.use_strict_mode','1');
session_start();

// Session timeout check
if(!empty($_SESSION['user_id']) && isset($_SESSION['last_activity'])){
    if(time()-$_SESSION['last_activity'] > SESSION_TIMEOUT){
        session_destroy();
        header('Location: '.APP_URL.'/account/login?timeout=1');
        exit;
    }
    $_SESSION['last_activity'] = time();
}

// ── Router ─────────────────────────────────────────────────
$router = new Router();

// Home
$router->get('/',          'HomeController', 'index');
$router->get('/index',     'HomeController', 'index');

// Shop
$router->get('/shop',             'ShopController', 'index');
$router->get('/product/:id',      'ShopController', 'product');
$router->post('/shop/review',     'ShopController', 'submitReview');

// Cart
$router->get( '/cart',        'CartController', 'index');
$router->post('/cart/add',    'CartController', 'add');
$router->post('/cart/update', 'CartController', 'update');
$router->post('/cart/remove', 'CartController', 'remove');
$router->get( '/cart/count',  'CartController', 'count');

// Checkout
$router->get( '/checkout',         'CheckoutController', 'index');
$router->post('/checkout/place',   'CheckoutController', 'place');
$router->post('/checkout/coupon',  'CheckoutController', 'applyCoupon');

// Orders
$router->get( '/order/success/:num', 'OrderController', 'success');
$router->get( '/order/track',        'OrderController', 'track');
$router->post('/order/track',        'OrderController', 'track');

// Auth
$router->get( '/account/login',    'AuthController', 'loginPage');
$router->post('/auth/login',       'AuthController', 'login');
$router->get( '/account/signup',   'AuthController', 'signupPage');
$router->post('/auth/signup',      'AuthController', 'signup');
$router->get( '/auth/logout',      'AuthController', 'logout');
$router->get( '/account/change-password',  'AuthController', 'changePasswordPage');
$router->post('/account/change-password',  'AuthController', 'changePassword');

// Account
$router->get( '/account',                      'AccountController', 'index');
$router->get( '/account/profile',              'AccountController', 'profile');
$router->post('/account/profile/update',       'AccountController', 'updateProfile');
$router->get( '/account/orders',               'AccountController', 'orders');
$router->get( '/account/order/:id',            'AccountController', 'orderDetail');
$router->get( '/account/addresses',            'AccountController', 'addresses');
$router->post('/account/address/add',          'AccountController', 'addAddress');
$router->get( '/account/address/edit/:id',     'AccountController', 'editAddress');
$router->post('/account/address/update/:id',   'AccountController', 'updateAddress');
$router->post('/account/address/delete/:id',   'AccountController', 'deleteAddress');
$router->post('/account/address/default/:id',  'AccountController', 'setDefault');

// ── Dispatch ──────────────────────────────────────────────
// Load controllers that bundle multiple classes
$bundleFile = __DIR__.'/../app/controllers/HomeController.php';
if(file_exists($bundleFile)) require_once $bundleFile;
require_once __DIR__.'/../app/controllers/AuthController.php';
require_once __DIR__.'/../app/controllers/AccountController.php';

$router->dispatch();
