# EcomAdmin Panel — PHP MVC

A complete, production-ready e-commerce admin panel built with **PHP (MVC)**, **Tailwind CSS**, and **JavaScript**.

---

## 📁 Project Structure

```
admin/
├── app/
│   ├── controllers/
│   │   ├── AuthController.php       ← Login, logout, change password
│   │   └── DashboardController.php  ← Dashboard stats
│   ├── models/
│   │   ├── AdminModel.php           ← Admin DB queries
│   │   └── DashboardModel.php       ← Dashboard stats queries
│   └── views/
│       ├── auth/
│       │   └── login.php            ← Login page
│       ├── dashboard/
│       │   └── index.php            ← Dashboard with charts
│       ├── layouts/
│       │   └── main.php             ← Sidebar + Topbar shell
│       └── pages/
│           ├── 404.php              ← Not found page
│           └── template.php         ← ⭐ Copy this for new pages
├── config/
│   ├── app.php                      ← APP_URL, timezone, session
│   └── database.php                 ← DB credentials
├── core/
│   ├── Database.php                 ← PDO singleton
│   ├── Model.php                    ← Base model (query helpers)
│   ├── Controller.php               ← Base controller (view, redirect, auth)
│   └── Router.php                   ← Simple path router
├── public/
│   ├── index.php                    ← Front controller (entry point)
│   └── .htaccess                    ← Route all requests to index.php
└── .htaccess                        ← Redirect root to public/
```

---

## ⚡ Quick Setup

### 1. Import Database
```sql
-- In phpMyAdmin or MySQL CLI:
CREATE DATABASE ecom_db;
USE ecom_db;
SOURCE ecom.sql;       -- your original schema
SOURCE seed_admin.sql; -- creates the default admin account
```

### 2. Configure
Edit `config/database.php`:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');      // your MySQL user
define('DB_PASS', '');          // your MySQL password
define('DB_NAME', 'ecom_db');
```

Edit `config/app.php`:
```php
define('APP_URL', 'http://localhost/admin');  // adjust to your path
```

### 3. Deploy
Place the `admin/` folder inside your web root (e.g., `htdocs/admin/` or `www/admin/`).

### 4. Login
- **URL:** `http://localhost/admin/login`
- **Username:** `admin`
- **Password:** `Admin@1234`

> ⚠️ Change the password immediately via the profile dropdown → **Change Password**.

---

## 🧩 Features

| Feature | Details |
|---|---|
| **Authentication** | Session-based login, CSRF protection, bcrypt passwords |
| **Session Timeout** | Auto-logout after 1 hour of inactivity |
| **Sidebar** | Collapsible, with grouped nav + dropdown submenus |
| **Topbar** | Logo, page title, notification bell, admin profile dropdown |
| **Change Password** | AJAX modal with strength meter |
| **Dashboard** | 4 stat cards, revenue area chart, order donut chart, recent orders table, low stock alert, quick actions |
| **MVC Pattern** | Clean separation: Router → Controller → Model → View |

---

## ➕ Adding a New Page (Example: Products)

### 1. Create the Model
```php
// app/models/ProductModel.php
require_once __DIR__ . '/../../core/Model.php';

class ProductModel extends Model {
    public function getAll(): array {
        return $this->fetchAll('SELECT * FROM products ORDER BY created_at DESC');
    }
}
```

### 2. Create the Controller
```php
// app/controllers/ProductController.php
require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../models/ProductModel.php';

class ProductController extends Controller {
    private ProductModel $model;

    public function __construct() {
        $this->model = new ProductModel();
    }

    public function index(): void {
        $this->requireAuth();  // ← Always call this first
        $products  = $this->model->getAll();
        $pageTitle = 'Products';
        $csrf      = $this->csrfToken();

        $this->view('layouts/main', compact('products', 'pageTitle', 'csrf')
            + ['content_view' => 'products/index']);
    }
}
```

### 3. Create the View
```
app/views/products/index.php
```
Copy `app/views/pages/template.php` and customize it.

### 4. Register the Route
In `public/index.php`:
```php
$router->get('/products', 'ProductController', 'index');
$router->get('/products/create', 'ProductController', 'create');
$router->post('/products/store', 'ProductController', 'store');
```

---

## 🛡️ Security Features

- **CSRF tokens** on all POST forms
- **Password hashing** with bcrypt cost 12
- **Session regeneration** on login
- **HttpOnly cookies**
- **Input sanitization** via `htmlspecialchars`
- **Brute-force delay** (1s sleep on failed login)
- **Session timeout** (1 hour, configurable)

---

## 🎨 UI Stack

- **Tailwind CSS** (CDN)
- **Font Awesome 6** (icons)
- **ApexCharts** (revenue & donut charts)
- **Plus Jakarta Sans** (Google Fonts)
- **JetBrains Mono** (order numbers)

---

## 📋 Sidebar Menu Structure

```
Main
  └── Dashboard

Catalog
  ├── Products (dropdown)
  │     ├── All Products
  │     ├── Add Product
  │     └── Stock Management
  └── Categories (dropdown)
        ├── All Categories
        └── Sub Categories

Sales
  ├── Orders (dropdown)
  │     ├── All Orders
  │     ├── Pending Orders
  │     └── Shipped
  ├── Payments
  └── Coupons

Customers
  ├── Customers
  └── Reviews

Settings (dropdown)
  ├── Shop Settings
  ├── Delivery Settings
  └── Site Settings
```
