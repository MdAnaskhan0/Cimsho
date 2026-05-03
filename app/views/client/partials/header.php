<?php
// Load settings for logo display
if (!class_exists('SettingsModel')) {
  require_once BASE_PATH . '/app/models/Models.php';
}
$settingsModel = new SettingsModel();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($pageTitle ?? 'Shop') ?> — Cimsho</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <style>
    * {
      font-family: 'Trebuchet MS', Tahoma, Geneva, Verdana, sans-serif;
    }

    :root {
      --primary: #1a1a2e;
      --accent: #e94560;
      --gold: #f5a623;
      --light: #f8f8f8;
    }

    .btn-primary {
      background: #e94560;
      color: #fff;
      transition: all 0.2s;
    }

    .btn-primary:hover {
      background: #c73652;
      transform: translateY(-1px);
    }

    .btn-outline {
      border: 2px solid #e94560;
      color: #e94560;
      transition: all 0.2s;
    }

    .btn-outline:hover {
      background: #e94560;
      color: #fff;
    }

    .nav-link {
      transition: color 0.2s;
    }

    .nav-link:hover {
      color: #e94560;
    }

    .product-card {
      transition: all 0.3s;
    }

    .product-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 12px 30px rgba(0, 0, 0, 0.12);
    }

    .product-card:hover .product-img {
      transform: scale(1.05);
    }

    .product-img {
      transition: transform 0.4s ease;
    }

    .badge-sale {
      background: #e94560;
    }

    .toast {
      position: fixed;
      bottom: 24px;
      right: 24px;
      z-index: 9999;
      transform: translateY(100px);
      opacity: 0;
      transition: all 0.3s;
    }

    .toast.show {
      transform: translateY(0);
      opacity: 1;
    }

    .dropdown-menu {
      display: none;
    }

    .dropdown:hover .dropdown-menu {
      display: block;
    }

    @media (max-width: 768px) {
      .mobile-menu {
        display: none;
      }

      .mobile-menu.open {
        display: flex;
      }
    }

    .overlay {
      display: none;
      position: fixed;
      inset: 0;
      background: rgba(0, 0, 0, 0.5);
      z-index: 40;
    }

    .overlay.open {
      display: block;
    }

    .sidebar {
      transform: translateX(-100%);
      transition: transform 0.3s;
    }

    .sidebar.open {
      transform: translateX(0);
    }

    .star-filled {
      color: #f5a623;
    }

    .star-empty {
      color: #ddd;
    }

    input:focus,
    select:focus,
    textarea:focus {
      outline: none;
      border-color: #e94560 !important;
      box-shadow: 0 0 0 3px rgba(233, 69, 96, 0.15);
    }
  </style>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            primary: '#1a1a2e',
            accent: '#e94560',
            gold: '#f5a623',
          }
        }
      }
    }
  </script>
</head>

<body class="bg-gray-50 text-gray-800">

  <!-- Toast Notification -->
  <div id="toast" class="toast bg-white shadow-xl rounded-xl px-5 py-3 flex items-center gap-3 border border-gray-100">
    <div id="toast-icon" class="w-8 h-8 rounded-full flex items-center justify-center text-white text-sm font-bold">✓</div>
    <span id="toast-msg" class="text-sm font-medium"></span>
  </div>

  <!-- Top Bar -->
  <div class="bg-primary text-white text-xs py-2 px-4 text-center">
    🇧🇩 Free delivery on orders over ৳2,000 within Bangladesh &nbsp;|&nbsp; Cash on Delivery Available
  </div>

  <!-- Header -->
  <header class="bg-white shadow-sm sticky top-0 z-30">
    <div class="max-w-7xl mx-auto px-4">
      <div class="flex items-center justify-between h-16">
        <!-- Logo -->
        <?php
        $settingsModel = new SettingsModel();
        $siteLogo = $settingsModel->get('site_logo');
        $siteName = $settingsModel->get('site_name') ?: 'Cimsho';
        ?>
        <a href="<?= BASE_URL ?>/" class="flex items-center gap-2">
          <?php if ($siteLogo): ?>
            <img src="<?= BASE_URL . $siteLogo ?>" alt="<?= htmlspecialchars($siteName) ?>" class="h-9 w-auto object-contain">
          <?php else: ?>
            <div class="w-9 h-9 bg-accent rounded-lg flex items-center justify-center text-white font-bold text-lg"><?= substr($siteName, 0, 1) ?></div>
          <?php endif; ?>
          <?php if (!$siteLogo): ?>
            <span class="text-xl font-bold text-primary tracking-tight"><?= htmlspecialchars($siteName) ?></span>
          <?php endif; ?>
        </a>

        <!-- Search Bar (Desktop) -->
        <form action="<?= BASE_URL ?>/shop" method="get" class="hidden md:flex flex-1 max-w-md mx-8">
          <div class="flex w-full border-2 border-gray-200 rounded-lg overflow-hidden focus-within:border-accent transition-colors">
            <input type="text" name="q" value="<?= htmlspecialchars($_GET['q'] ?? '') ?>" placeholder="Search products, brands..." class="flex-1 px-4 py-2 text-sm border-none outline-none bg-white">
            <button type="submit" class="px-4 bg-accent text-white hover:bg-red-600 transition-colors">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
            </button>
          </div>
        </form>

        <!-- Right Icons -->
        <div class="flex items-center gap-1 md:gap-3">
          <?php if (isset($_SESSION['user_id'])): ?>
            <div class="dropdown relative hidden md:block">
              <button class="flex items-center gap-1 text-sm text-gray-600 hover:text-accent px-3 py-2 rounded-lg hover:bg-gray-50 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span class="hidden lg:block"><?= htmlspecialchars(explode(' ', $_SESSION['user_name'])[0]) ?></span>
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
              </button>
              <div class="dropdown-menu absolute right-0 top-full mt-1 bg-white shadow-xl rounded-xl border border-gray-100 w-48 py-2 z-50">
                <a href="<?= BASE_URL ?>/account/orders" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-accent">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                  </svg>
                  My Orders
                </a>
                <a href="<?= BASE_URL ?>/account/profile" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-accent">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                  </svg>
                  Profile
                </a>
                <hr class="my-1">
                <a href="<?= BASE_URL ?>/logout" class="flex items-center gap-2 px-4 py-2 text-sm text-red-500 hover:bg-red-50">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                  </svg>
                  Logout
                </a>
              </div>
            </div>
          <?php else: ?>
            <a href="<?= BASE_URL ?>/login" class="hidden md:flex items-center gap-1 text-sm text-gray-600 hover:text-accent px-3 py-2 rounded-lg hover:bg-gray-50 transition-all">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
              </svg>
              <span class="hidden lg:block">Login</span>
            </a>
          <?php endif; ?>

          <!-- Cart -->
          <a href="<?= BASE_URL ?>/cart" class="relative flex items-center gap-1 text-gray-600 hover:text-accent px-3 py-2 rounded-lg hover:bg-gray-50 transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <span id="cart-count" class="<?= ($cartCount > 0) ? '' : 'hidden' ?> absolute -top-1 -right-1 bg-accent text-white text-xs w-5 h-5 rounded-full flex items-center justify-center font-bold"><?= $cartCount ?? 0 ?></span>
            <span class="hidden lg:block text-sm">Cart</span>
          </a>

          <!-- Mobile Menu Toggle -->
          <button onclick="toggleMobileMenu()" class="md:hidden p-2 rounded-lg hover:bg-gray-50">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
          </button>
        </div>
      </div>

      <!-- Category Nav (Desktop) -->
      <nav class="hidden md:flex items-center gap-6 py-2 border-t border-gray-100 overflow-x-auto">
        <a href="<?= BASE_URL ?>/shop" class="text-sm font-medium text-gray-600 hover:text-accent whitespace-nowrap nav-link">All Products</a>
        <?php foreach (array_slice($categories, 0, 8) as $cat): ?>
          <div class="dropdown relative">
            <a href="<?= BASE_URL ?>/category/<?= $cat['slug'] ?>" class="text-sm font-medium text-gray-600 hover:text-accent whitespace-nowrap nav-link flex items-center gap-1">
              <?= htmlspecialchars($cat['name']) ?>
              <?php if (!empty($cat['subcategories'])): ?><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg><?php endif; ?>
            </a>
            <?php if (!empty($cat['subcategories'])): ?>
              <div class="dropdown-menu absolute left-0 top-full mt-1 bg-white shadow-xl rounded-xl border border-gray-100 w-48 py-2 z-50">
                <?php foreach ($cat['subcategories'] as $sub): ?>
                  <a href="<?= BASE_URL ?>/shop?cat=<?= $cat['id'] ?>&sub=<?= $sub['id'] ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-accent">
                    <?= htmlspecialchars($sub['name']) ?>
                  </a>
                <?php endforeach; ?>
              </div>
            <?php endif; ?>
          </div>
        <?php endforeach; ?>
      </nav>
    </div>

    <!-- Mobile Menu -->
    <div id="mobile-menu" class="md:hidden hidden bg-white border-t border-gray-100">
      <div class="px-4 py-3">
        <form action="<?= BASE_URL ?>/shop" method="get" class="flex border-2 border-gray-200 rounded-lg overflow-hidden mb-3">
          <input type="text" name="q" placeholder="Search..." class="flex-1 px-4 py-2 text-sm outline-none">
          <button type="submit" class="px-3 bg-accent text-white">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
          </button>
        </form>
        <div class="space-y-1">
          <a href="<?= BASE_URL ?>/shop" class="block px-3 py-2 text-sm text-gray-700 hover:text-accent rounded-lg">All Products</a>
          <?php foreach ($categories as $cat): ?>
            <a href="<?= BASE_URL ?>/category/<?= $cat['slug'] ?>" class="block px-3 py-2 text-sm text-gray-700 hover:text-accent rounded-lg"><?= htmlspecialchars($cat['name']) ?></a>
          <?php endforeach; ?>
          <hr class="my-2">
          <?php if (isset($_SESSION['user_id'])): ?>
            <a href="<?= BASE_URL ?>/account/orders" class="block px-3 py-2 text-sm text-gray-700 hover:text-accent rounded-lg">My Orders</a>
            <a href="<?= BASE_URL ?>/logout" class="block px-3 py-2 text-sm text-red-500 rounded-lg">Logout</a>
          <?php else: ?>
            <a href="<?= BASE_URL ?>/login" class="block px-3 py-2 text-sm text-gray-700 hover:text-accent rounded-lg">Login / Register</a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </header>

  <main>