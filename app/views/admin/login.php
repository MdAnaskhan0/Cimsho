<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Login — Cimsho</title>
<script src="https://cdn.tailwindcss.com"></script>
<style>* { font-family: 'Trebuchet MS', Tahoma, Geneva, Verdana, sans-serif; }</style>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center px-4">
<div class="w-full max-w-md">
  <div class="text-center mb-8">
    <div class="w-16 h-16 rounded-2xl flex items-center justify-center text-white font-bold text-3xl mx-auto mb-3" style="background:#e94560">C</div>
    <h1 class="text-2xl font-extrabold text-gray-800">Admin Panel</h1>
    <p class="text-gray-400 text-sm">Cimsho Store Management</p>
  </div>
  <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
    <?php if (!empty($error)): ?>
    <div class="bg-red-50 border border-red-200 text-red-600 rounded-xl px-4 py-3 text-sm mb-5">⚠️ <?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form action="<?= BASE_URL ?>/admin/login" method="post" class="space-y-4">
      <div>
        <label class="text-xs font-bold text-gray-500 uppercase tracking-wide block mb-1.5">Username</label>
        <input type="text" name="username" required placeholder="admin" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 text-sm focus:border-red-400 outline-none transition-all">
      </div>
      <div>
        <label class="text-xs font-bold text-gray-500 uppercase tracking-wide block mb-1.5">Password</label>
        <input type="password" name="password" required placeholder="••••••••" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 text-sm focus:border-red-400 outline-none transition-all">
      </div>
      <button type="submit" class="w-full py-3.5 rounded-xl font-bold text-sm text-white transition-colors" style="background:#e94560">Login to Dashboard</button>
    </form>
    <p class="text-xs text-center text-gray-400 mt-5">
      <a href="<?= BASE_URL ?>/" class="hover:text-gray-600">← Back to Store</a>
    </p>
  </div>
</div>
</body>
</html>
