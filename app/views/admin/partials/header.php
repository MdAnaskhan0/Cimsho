<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($pageTitle ?? 'Admin') ?> — Cimsho Admin</title>
<script src="https://cdn.tailwindcss.com"></script>
<style>
  * { font-family: 'Trebuchet MS', Tahoma, Geneva, Verdana, sans-serif; }
  .sidebar-link { transition: all 0.2s; }
  .sidebar-link:hover, .sidebar-link.active { background: rgba(233,69,96,0.15); color: #e94560; }
  .sidebar-link.active { font-weight: 700; border-right: 3px solid #e94560; }
  .stat-card { transition: all 0.2s; }
  .stat-card:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(0,0,0,0.08); }
  .data-table th { background: #f8f8f8; }
  .data-table tr:hover td { background: #fafafa; }
  input:focus, select:focus, textarea:focus { outline: none; border-color: #e94560 !important; box-shadow: 0 0 0 3px rgba(233,69,96,0.12); }
  .btn-primary { background: #e94560; color: #fff; }
  .btn-primary:hover { background: #c73652; }
  .badge { display: inline-block; padding: 2px 10px; border-radius: 99px; font-size: 11px; font-weight: 700; }
  .badge-pending { background: #fef3c7; color: #d97706; }
  .badge-confirmed { background: #dbeafe; color: #1d4ed8; }
  .badge-shipped { background: #ede9fe; color: #7c3aed; }
  .badge-delivered { background: #d1fae5; color: #065f46; }
  .badge-cancelled { background: #fee2e2; color: #b91c1c; }
  #mobile-sidebar { display: none; }
  @media (max-width: 768px) { #main-sidebar { display: none; } }
</style>
</head>
<body class="bg-gray-50 text-gray-800">

<div class="flex min-h-screen">
  <!-- Sidebar -->
  <aside id="main-sidebar" class="w-60 bg-white border-r border-gray-100 flex flex-col sticky top-0 h-screen shrink-0">
    <div class="p-5 border-b border-gray-100">
      <div class="flex items-center gap-2">
        <div class="w-9 h-9 bg-accent rounded-lg flex items-center justify-center text-white font-bold" style="background:#e94560">C</div>
        <div>
          <p class="font-extrabold text-sm text-gray-800">Cimsho Admin</p>
          <p class="text-xs text-gray-400">Control Panel</p>
        </div>
      </div>
    </div>

    <nav class="flex-1 p-3 overflow-y-auto">
      <?php
      $nav = [
        ['Dashboard', '/admin', '📊'],
        ['Products', '/admin/products', '📦'],
        ['Categories', '/admin/categories', '🏷️'],
        ['Orders', '/admin/orders', '🛍️'],
        ['Customers', '/admin/customers', '👥'],
        ['Coupons', '/admin/coupons', '🎟️'],
        ['Reviews', '/admin/reviews', '⭐'],
        ['Settings', '/admin/settings', '⚙️'],
      ];
      $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
      foreach ($nav as [$label, $path, $icon]):
        $active = ($uri === $path || ($path !== '/admin' && str_starts_with($uri, $path)));
      ?>
      <a href="<?= BASE_URL . $path ?>" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm text-gray-600 mb-0.5 <?= $active ? 'active' : '' ?>">
        <span><?= $icon ?></span><span><?= $label ?></span>
      </a>
      <?php endforeach; ?>
    </nav>

    <div class="p-3 border-t border-gray-100">
      <div class="flex items-center gap-2 px-3 py-2 mb-1">
        <div class="w-8 h-8 bg-accent/10 rounded-full flex items-center justify-center text-xs font-bold text-accent" style="color:#e94560;background:rgba(233,69,96,0.1)">
          <?= strtoupper(substr($admin['full_name'] ?? 'A', 0, 1)) ?>
        </div>
        <div>
          <p class="text-xs font-bold"><?= htmlspecialchars($admin['full_name'] ?? 'Admin') ?></p>
          <p class="text-xs text-gray-400"><?= htmlspecialchars($admin['username'] ?? '') ?></p>
        </div>
      </div>
      <a href="<?= BASE_URL ?>" target="_blank" class="sidebar-link flex items-center gap-3 px-3 py-2 rounded-xl text-sm text-gray-600 mb-0.5">🌐 View Store</a>
      <a href="<?= BASE_URL ?>/admin/logout" class="sidebar-link flex items-center gap-3 px-3 py-2 rounded-xl text-sm text-red-500 hover:bg-red-50">🚪 Logout</a>
    </div>
  </aside>

  <!-- Main Content -->
  <div class="flex-1 flex flex-col min-w-0">
    <!-- Top Bar -->
    <header class="bg-white border-b border-gray-100 px-6 py-4 flex items-center justify-between sticky top-0 z-20">
      <div class="flex items-center gap-3">
        <button onclick="document.getElementById('main-sidebar').style.display = document.getElementById('main-sidebar').style.display === 'none' ? 'flex' : 'none'" class="md:hidden p-2 rounded-lg hover:bg-gray-50">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
        <div>
          <h1 class="font-bold text-lg text-gray-800"><?= htmlspecialchars($pageTitle ?? '') ?></h1>
          <p class="text-xs text-gray-400"><?= date('l, d F Y') ?></p>
        </div>
      </div>
      <div class="flex items-center gap-3">
        <a href="<?= BASE_URL ?>/" target="_blank" class="text-xs text-gray-400 hover:text-accent flex items-center gap-1">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
          Visit Store
        </a>
        <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center text-xs font-bold text-red-500">
          <?= strtoupper(substr($admin['full_name'] ?? 'A', 0, 1)) ?>
        </div>
      </div>
    </header>

    <main class="flex-1 p-6">
