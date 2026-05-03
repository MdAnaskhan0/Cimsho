# 🛍️ Cimsho — Bangladesh E-Commerce Platform

A full-stack PHP MVC e-commerce system built for Bangladesh, with a complete **Client Storefront** and **Admin Panel**.

---

## 🚀 Tech Stack

| Layer | Technology |
|---|---|
| Backend | PHP 8+ (MVC architecture) |
| Database | MySQL / MariaDB |
| Frontend | HTML5, Tailwind CSS CDN, Vanilla JavaScript |
| Font | Trebuchet MS |
| Server | Apache with mod_rewrite |

---

## 📁 Project Structure

```
cimsho/
├── public/                    ← Web root (point Apache/Nginx here)
│   ├── index.php              ← Application entry point
│   ├── .htaccess              ← URL rewriting rules
│   └── assets/
│       └── images/
│           ├── placeholder.svg
│           └── products/      ← Uploaded product images go here
│
├── config/
│   └── database.php           ← DB credentials & BASE_URL
│
├── core/
│   ├── Database.php           ← Singleton DB wrapper (MySQLi)
│   ├── Controller.php         ← Base controller
│   ├── Model.php              ← Base model
│   └── Router.php             ← URL router with regex params
│
├── routes/
│   └── web.php                ← All client + admin routes
│
├── app/
│   ├── controllers/           ← All controllers
│   │   ├── HomeController.php
│   │   ├── ShopController.php
│   │   ├── CartController.php
│   │   ├── CheckoutController.php
│   │   ├── AuthController.php
│   │   ├── AccountController.php
│   │   ├── AdminControllers.php  ← All admin controllers
│   │   └── BaseAdminController.php
│   │
│   ├── models/
│   │   ├── ProductModel.php
│   │   └── Models.php         ← OrderModel, UserModel, CategoryModel, CouponModel, etc.
│   │
│   └── views/
│       ├── client/            ← Client-facing pages
│       │   ├── partials/      ← header.php, footer.php, product-card.php
│       │   ├── home.php
│       │   ├── shop.php
│       │   ├── product.php
│       │   ├── cart.php
│       │   ├── checkout.php
│       │   ├── login.php
│       │   ├── register.php
│       │   ├── order-success.php
│       │   └── account/       ← orders, order-detail, profile, addresses
│       │
│       └── admin/             ← Admin panel pages
│           ├── partials/      ← header.php, footer.php
│           ├── login.php
│           ├── dashboard.php
│           ├── products/      ← index.php, form.php
│           ├── categories/
│           ├── orders/        ← index.php, detail.php
│           ├── coupons/
│           ├── customers/
│           ├── settings/
│           └── reviews/
│
├── setup_seed.sql             ← Run this after DB import
└── README.md
```

---

## ⚙️ Installation

### Step 1 — Clone / Copy Project
Place the `cimsho/` folder in your web server root:
- XAMPP: `C:/xampp/htdocs/cimsho/`
- WAMP:  `C:/wamp64/www/cimsho/`
- Linux: `/var/www/html/cimsho/`

### Step 2 — Database Setup
1. Open **phpMyAdmin** → Create database: `cimsho`
2. Import `cimsho_db__.sql` (the schema file)
3. Import `setup_seed.sql` (demo data + admin account)

### Step 3 — Configure
Edit `config/database.php`:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');       // your MySQL username
define('DB_PASS', '');           // your MySQL password
define('DB_NAME', 'cimsho');
define('BASE_URL', 'http://localhost/cimsho/public');
define('SITE_NAME', 'Cimsho');
```

### Step 4 — Enable mod_rewrite
Make sure Apache `mod_rewrite` is enabled and `.htaccess` is allowed (`AllowOverride All`).

In `httpd.conf` or virtual host config:
```apache
<Directory "/xampp/htdocs/cimsho/public">
    AllowOverride All
</Directory>
```

### Step 5 — Image Upload Permissions
Make sure the uploads directory is writable:
```bash
chmod 755 public/assets/images/products/
```

---

## 🔐 Default Login Credentials

### 🛡️ Admin Panel
| Field | Value |
|---|---|
| URL | `http://localhost/cimsho/public/admin/login` |
| Username | `admin` |
| Password | `admin123` |

### 👤 Demo Customer
| Field | Value |
|---|---|
| URL | `http://localhost/cimsho/public/login` |
| Email | `demo@cimsho.com` |
| Password | `demo1234` |

---

## 🎯 Features

### 🛒 Client Storefront
- **Homepage** — Hero banner, featured products, categories, new arrivals
- **Shop** — Product grid with category/subcategory filters + pagination
- **Product Page** — Multiple images, size/color selector, reviews, related products
- **Cart** — Real-time updates, coupon code support, quantity control
- **Checkout** — Address management, delivery type (inside/outside Dhaka), payment methods
- **Order Success** — Order confirmation with item summary
- **Account** — My Orders, Order Detail with timeline, Profile, Addresses
- **Auth** — Login / Register with session management
- **Search** — Full-text product search

### 🔧 Admin Panel
- **Dashboard** — Stats (orders, revenue, customers, products), recent orders
- **Products** — CRUD with sizes, colors, multiple images, featured toggle
- **Categories** — Create/edit/delete with subcategories
- **Orders** — List with status filter, detail view, status update with notes
- **Customers** — List with order count, customer detail view
- **Coupons** — Create/delete coupons with % discount, min order, expiry
- **Reviews** — View and delete customer reviews
- **Settings** — Delivery charges (inside/outside Dhaka, free delivery threshold)

### 🇧🇩 Bangladesh-Specific
- **BDT (৳)** currency throughout
- **bKash, Nagad, Card, COD** payment methods
- Inside Dhaka / Outside Dhaka delivery pricing
- 64 districts delivery coverage
- Local phone number formats (01X XXXXXXXX)

---

## 🗄️ Database Tables

| Table | Purpose |
|---|---|
| `admins` | Admin users |
| `users` | Customer accounts |
| `user_addresses` | Saved delivery addresses |
| `categories` | Main product categories |
| `sub_categories` | Sub-categories linked to categories |
| `products` | Product catalog |
| `product_sizes` | Size variants with pricing |
| `product_colors` | Color variants |
| `product_images` | Product image gallery |
| `product_reviews` | Customer reviews & ratings |
| `orders` | Order master records |
| `order_items` | Items within each order |
| `order_status_log` | Order tracking timeline |
| `payments` | Payment records |
| `coupons` | Discount coupon codes |
| `delivery_settings` | Delivery charge configuration |
| `shop_settings` | General shop settings |
| `site_settings` | Site-wide settings |

---

## 🎨 Sample Coupon Codes
| Code | Discount | Min Order |
|---|---|---|
| `WELCOME10` | 10% off | ৳500 |
| `SAVE20` | 20% off | ৳1,500 |
| `EID2026` | 15% off | ৳1,000 |

---

## 📦 Extending the Project

### Adding a new page
1. Create a route in `routes/web.php`
2. Create a controller in `app/controllers/`
3. Create a view in `app/views/client/` or `app/views/admin/`

### Adding a new model
1. Create a class in `app/models/` extending `Model`
2. All models are auto-loaded from the models directory

---

## 🔒 Security Notes
- Passwords hashed with `password_hash()` (bcrypt)
- All DB queries use prepared statements
- User input sanitized with `htmlspecialchars()` + `strip_tags()`
- Session-based authentication for both admin and users
- Admin routes require `$_SESSION['admin_id']`
- User routes require `$_SESSION['user_id']`

---

*Built with ❤️ for Bangladesh 🇧🇩*
