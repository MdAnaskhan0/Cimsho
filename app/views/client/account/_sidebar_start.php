<?php require __DIR__ . '/../partials/header.php'; ?>
<div class="max-w-5xl mx-auto px-4 py-8">
  <div class="flex flex-col md:flex-row gap-6">
    <!-- Sidebar -->
    <aside class="w-full md:w-56 shrink-0">
      <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden sticky top-20">
        <div class="bg-primary p-5 text-white">
          <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center text-xl font-bold mb-2">
            <?= strtoupper(substr($_SESSION['user_name'] ?? 'U', 0, 1)) ?>
          </div>
          <p class="font-bold text-sm"><?= htmlspecialchars($_SESSION['user_name'] ?? '') ?></p>
          <p class="text-white/60 text-xs"><?= htmlspecialchars($_SESSION['user_email'] ?? '') ?></p>
        </div>
        <nav class="p-2">
          <?php
          $accountLinks = [
            ['/account/orders', '📦', 'My Orders'],
            ['/account/profile', '👤', 'Profile'],
            ['/account/addresses', '📍', 'Addresses'],
          ];
          $currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
          foreach ($accountLinks as [$path, $icon, $label]):
            $isActive = str_contains($currentPath, $path);
          ?>
          <a href="<?= BASE_URL . $path ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-all <?= $isActive ? 'bg-accent text-white font-bold' : 'text-gray-600 hover:bg-gray-50 hover:text-accent' ?>">
            <span><?= $icon ?></span><?= $label ?>
          </a>
          <?php endforeach; ?>
          <hr class="my-2">
          <a href="<?= BASE_URL ?>/logout" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm text-red-500 hover:bg-red-50 transition-all">
            <span>🚪</span>Logout
          </a>
        </nav>
      </div>
    </aside>
    <div class="flex-1 min-w-0">
