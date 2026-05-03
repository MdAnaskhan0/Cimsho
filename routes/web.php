<?php
// ---- CLIENT ROUTES ----
$router->get('/', 'HomeController@index');
$router->get('/shop', 'ShopController@index');
$router->get('/product/{slug}', 'ShopController@product');
$router->get('/category/{slug}', 'ShopController@category');
$router->get('/cart', 'CartController@index');
$router->post('/cart/add', 'CartController@add');
$router->post('/cart/update', 'CartController@update');
$router->post('/cart/remove', 'CartController@remove');
$router->post('/cart/apply-coupon', 'CartController@applyCoupon');
$router->get('/checkout', 'CheckoutController@index');
$router->post('/checkout/place-order', 'CheckoutController@placeOrder');
$router->get('/order-success/{orderNumber}', 'CheckoutController@success');
$router->get('/login', 'AuthController@loginPage');
$router->post('/login', 'AuthController@login');
$router->get('/register', 'AuthController@registerPage');
$router->post('/register', 'AuthController@register');
$router->get('/logout', 'AuthController@logout');
$router->get('/account', 'AccountController@index');
$router->get('/account/orders', 'AccountController@orders');
$router->get('/account/orders/{orderNumber}', 'AccountController@orderDetail');
$router->get('/account/profile', 'AccountController@profile');
$router->post('/account/profile', 'AccountController@updateProfile');
$router->get('/account/addresses', 'AccountController@addresses');
$router->post('/account/addresses', 'AccountController@saveAddress');
$router->post('/wishlist/toggle', 'WishlistController@toggle');
$router->get('/search', 'ShopController@search');

// ---- ADMIN ROUTES ----
$router->get('/admin', 'AdminDashboardController@index');
$router->get('/admin/login', 'AdminAuthController@loginPage');
$router->post('/admin/login', 'AdminAuthController@login');
$router->get('/admin/logout', 'AdminAuthController@logout');

// Products
$router->get('/admin/products', 'AdminProductController@index');
$router->get('/admin/products/create', 'AdminProductController@create');
$router->post('/admin/products/create', 'AdminProductController@store');
$router->get('/admin/products/edit/{id}', 'AdminProductController@edit');
$router->post('/admin/products/edit/{id}', 'AdminProductController@update');
$router->post('/admin/products/delete/{id}', 'AdminProductController@delete');
$router->post('/admin/products/toggle-featured', 'AdminProductController@toggleFeatured');

// Categories
$router->get('/admin/categories', 'AdminCategoryController@index');
$router->post('/admin/categories/create', 'AdminCategoryController@store');
$router->post('/admin/categories/edit/{id}', 'AdminCategoryController@update');
$router->post('/admin/categories/delete/{id}', 'AdminCategoryController@delete');

// addmin category management routes for subcategories
// Subcategories (Admin)
$router->get('/admin/categories/subcategories/{id}', 'AdminCategoryController@subcategories');
$router->post('/admin/categories/subcategory/create', 'AdminCategoryController@createSubcategory');
$router->post('/admin/categories/subcategory/edit/{id}', 'AdminCategoryController@updateSubcategory');
$router->post('/admin/categories/subcategory/delete/{id}', 'AdminCategoryController@deleteSubcategory');
$router->post('/admin/categories/subcategory/toggle/{id}', 'AdminCategoryController@toggleSubcategoryStatus');

// Frontend - Subcategory product listing
$router->get('/category/{categorySlug}/{subCategorySlug}', 'ShopController@subcategory');

// Orders
$router->get('/admin/orders', 'AdminOrderController@index');
$router->get('/admin/orders/{orderNumber}', 'AdminOrderController@detail');
$router->post('/admin/orders/update-status', 'AdminOrderController@updateStatus');

// Coupons
$router->get('/admin/coupons', 'AdminCouponController@index');
$router->post('/admin/coupons/create', 'AdminCouponController@store');
$router->post('/admin/coupons/delete/{id}', 'AdminCouponController@delete');

// Customers
$router->get('/admin/customers', 'AdminCustomerController@index');
$router->get('/admin/customers/{id}', 'AdminCustomerController@detail');

// Settings
$router->get('/admin/settings', 'AdminSettingsController@index');
$router->post('/admin/settings', 'AdminSettingsController@save');

// Reviews
$router->get('/admin/reviews', 'AdminReviewController@index');
$router->post('/admin/reviews/delete/{id}', 'AdminReviewController@delete');

// Admin Settings - Logo upload routes
$router->post('/admin/settings/upload-logo', 'AdminSettingsController@uploadLogo');
$router->post('/admin/settings/remove-logo', 'AdminSettingsController@removeLogo');

// AJAX
$router->post('/ajax/get-subcategories', 'AjaxController@getSubcategories');
$router->post('/ajax/product-sizes', 'AjaxController@productSizes');
$router->post('/ajax/review', 'AjaxController@submitReview');
$router->post('/ajax/check-delivery', 'AjaxController@checkDelivery');
