<?php require __DIR__ . '/partials/header.php'; ?>

<!-- Stats -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
  <?php
  $stats = [
    ['Total Orders', $totalOrders, '🛍️', 'blue', BASE_URL.'/admin/orders'],
    ['Pending Orders', $pendingOrders, '⏳', 'yellow', BASE_URL.'/admin/orders?status=pending'],
    ['Total Revenue', '৳'.number_format($totalRevenue, 0), '💰', 'green', BASE_URL.'/admin/orders'],
    ['Customers', $totalCustomers, '👥', 'purple', BASE_URL.'/admin/customers'],
  ];
  $statBgs = ['blue'=>'bg-blue-50 text-blue-600','yellow'=>'bg-yellow-50 text-yellow-600','green'=>'bg-green-50 text-green-600','purple'=>'bg-purple-50 text-purple-600'];
  foreach ($stats as [$label, $val, $icon, $color, $link]):
  ?>
  <a href="<?= $link ?>" class="stat-card bg-white rounded-2xl border border-gray-100 p-5 flex items-center gap-4 hover:shadow-md">
    <div class="w-12 h-12 <?= $statBgs[$color] ?> rounded-xl flex items-center justify-center text-2xl"><?= $icon ?></div>
    <div>
      <p class="text-2xl font-extrabold text-gray-800"><?= $val ?></p>
      <p class="text-xs text-gray-400"><?= $label ?></p>
    </div>
  </a>
  <?php endforeach; ?>
</div>

<!-- Second row -->
<div class="grid grid-cols-2 gap-4 mb-6">
  <a href="<?= BASE_URL ?>/admin/products" class="stat-card bg-white rounded-2xl border border-gray-100 p-5 flex items-center gap-4">
    <div class="w-12 h-12 bg-orange-50 text-orange-600 rounded-xl flex items-center justify-center text-2xl">📦</div>
    <div><p class="text-2xl font-extrabold"><?= $totalProducts ?></p><p class="text-xs text-gray-400">Total Products</p></div>
  </a>
  <div class="bg-gradient-to-r from-primary to-accent rounded-2xl p-5 text-white flex items-center gap-4" style="background:linear-gradient(135deg,#1a1a2e,#e94560)">
    <div class="text-3xl">🇧🇩</div>
    <div>
      <p class="font-bold">Bangladesh</p>
      <p class="text-white/70 text-xs">All 64 districts served</p>
    </div>
  </div>
</div>

<!-- Recent Orders -->
<div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
  <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
    <h3 class="font-bold text-gray-800">Recent Orders</h3>
    <a href="<?= BASE_URL ?>/admin/orders" class="text-xs text-red-500 font-semibold hover:underline">View All →</a>
  </div>
  <div class="overflow-x-auto">
    <table class="w-full data-table">
      <thead>
        <tr class="text-left text-xs text-gray-500 uppercase tracking-wide">
          <th class="px-6 py-3">Order</th>
          <th class="px-4 py-3">Customer</th>
          <th class="px-4 py-3">Amount</th>
          <th class="px-4 py-3">Status</th>
          <th class="px-4 py-3">Date</th>
          <th class="px-4 py-3"></th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-50">
        <?php foreach ($recentOrders as $order): ?>
        <tr>
          <td class="px-6 py-3 text-sm font-bold text-gray-700"><?= $order['order_number'] ?></td>
          <td class="px-4 py-3 text-sm text-gray-600"><?= htmlspecialchars($order['user_name'] ?? $order['guest_name'] ?? 'Guest') ?></td>
          <td class="px-4 py-3 text-sm font-bold">৳<?= number_format($order['total_amount'], 0) ?></td>
          <td class="px-4 py-3"><span class="badge badge-<?= $order['order_status'] ?>"><?= ucfirst($order['order_status']) ?></span></td>
          <td class="px-4 py-3 text-xs text-gray-400"><?= date('d M y', strtotime($order['placed_at'])) ?></td>
          <td class="px-4 py-3"><a href="<?= BASE_URL ?>/admin/orders/<?= $order['order_number'] ?>" class="text-xs text-red-500 hover:underline">View</a></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<?php require __DIR__ . '/partials/footer.php'; ?>
