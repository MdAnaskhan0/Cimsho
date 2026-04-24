# Cimsho — Client-Side MVC Application

A professional PHP MVC client-side application for the Cimsho e-commerce platform.
Built with **PHP**, **Tailwind CSS**, **JavaScript**, and a clean MVC architecture.

---

## Project Structure

```
cimsho/
├── app/
│   ├── controllers/        # Controllers (one per feature)
│   │   ├── AuthController.php
│   │   ├── HomeController.php
│   │   └── AccountController.php
│   ├── models/             # Models (database access)
│   │   ├── UserModel.php
│   │   └── ProductModel.php
│   └── views/              # Views (HTML templates)
│       ├── auth/
│       │   ├── login.php
│       │   └── register.php
│       ├── home/
│       │   └── index.php
│       ├── account/
│       │   └── index.php
│       ├── pages/
│       │   └── _template.php   ← Copy this to add new pages
│       └── partials/
│           ├── head.php        ← HTML <head> + Tailwind
│           ├── navbar.php      ← Top navigation bar
│           ├── footer.php      ← Footer
│           ├── flash.php       ← Flash messages
│           └── 404.php         ← 404 error page
├── config/
│   ├── app.php             # App constants (URL, paths)
│   └── database.php        # DB credentials
├── core/
│   ├── Database.php        # PDO singleton
│   ├── Router.php          # URL router
│   ├── Controller.php      # Base controller
│   └── Model.php           # Base model
├── public/
│   ├── index.php           # Front controller (entry point)
│   ├── .htaccess           # Pretty URL rewriting
│   ├── css/                # Custom stylesheets (if any)
│   ├── js/                 # Custom scripts (if any)
│   └── assets/             # Images, icons, etc.
└── .htaccess               # Root redirect to public/
```

---

## Setup

### 1. Database
- Import `cimsho.sql` into MySQL/MariaDB
- Database name: `cimsho`

### 2. Configuration
Edit `config/database.php`:
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'cimsho');
define('DB_USER', 'root');   // your MySQL user
define('DB_PASS', '');        // your MySQL password
```

Edit `config/app.php`:
```php
define('APP_URL', 'http://localhost/cimsho/public');
define('PRODUCT_IMAGE_BASE', 'http://localhost/admin/public/productImage/');
```

### 3. Web Server
- Place the `cimsho/` folder in your web root (e.g., `htdocs/` or `www/`)
- Enable `mod_rewrite` in Apache
- Visit: `http://localhost/cimsho/`

---

## Adding a New Page

### Step 1 — Copy the template view
```
app/views/pages/_template.php  →  app/views/pages/about.php
```

### Step 2 — Create the controller
```php
// app/controllers/PageController.php
require_once APP_ROOT . '/core/Controller.php';

class PageController extends Controller {
    public function about(): void {
        $this->view('pages.about', [
            'title' => 'About Us',
        ]);
    }
}
```

### Step 3 — Register the route in `public/index.php`
```php
$router->get('/about', 'PageController', 'about');
```

### Step 4 — Done! Visit `/about`

---

## Authentication

| Route       | Method | Action                        |
|-------------|--------|-------------------------------|
| `/register` | GET    | Show signup form              |
| `/register` | POST   | Create account + auto-login   |
| `/login`    | GET    | Show login form               |
| `/login`    | POST   | Authenticate user             |
| `/logout`   | POST   | Destroy session + redirect    |
| `/account`  | GET    | Protected profile page        |

### Session Variables (after login)
```
$_SESSION['user_id']    — int
$_SESSION['user_name']  — string
$_SESSION['user_email'] — string
```

### Protect a route in a controller
```php
public function index(): void {
    $this->requireAuth();  // redirects to /login if not logged in
    // ... your code
}
```

---

## URL Routing

Routes are registered in `public/index.php`:

```php
// Static route
$router->get('/contact', 'PageController', 'contact');
$router->post('/contact', 'PageController', 'sendContact');

// Dynamic route with parameter
$router->get('/product/{id}', 'ProductController', 'show');
// Accessed in controller as: public function show(string $id): void
```

---

## Design System

The UI uses **Tailwind CSS** (CDN) with a custom brand palette:

| Token          | Value     |
|----------------|-----------|
| `brand-500`    | `#d4842a` (primary amber-gold) |
| `brand-600`    | `#b86820` |
| Font (headings)| Playfair Display (serif) |
| Font (body)    | Inter (sans-serif) |

CSS helper classes:
- `.btn-brand` — gradient CTA button
- `.card-hover` — lift-on-hover card effect
- `.hero-gradient` — dark hero background

---

## Product Images

Images are served from the admin panel's folder:
```
http://localhost/admin/public/productImage/{image_filename}
```
The constant `PRODUCT_IMAGE_BASE` in `config/app.php` controls this path.
