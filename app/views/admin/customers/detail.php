<?php require __DIR__ . '/../partials/header.php'; ?>
<div class="grid grid-cols-1 md:grid-cols-3 gap-5">
  <div>
    <div class="bg-white rounded-2xl border border-gray-100 p-5">
      <div class="text-center mb-4">
        <div class="w-16 h-16 rounded-full flex items-center justify-center text-white text-2xl font-bold mx-auto mb-2" style="background:#e94560"><?= strtoupper(substr($user['name'],0,1)) ?></div>
        <p class="font-bold"><?= htmlspecialchars($user['name']) ?></p>
        <p class="text-sm text-gray-400"><?= htmlspecialchars($user['email']) ?></p>
        <p class="text-sm text-gray-400"><?= htmlspecialchars($user['phone'] ?? '') ?></p>
      </div>
      <div class="space-y-2 text-sm border-t border-gray-100 pt-4">
        <div class="flex justify-between"><span class="text-gray-400">Joined</span><span><?= date('d M Y', strtotime($user['created_at'])) ?></span></div>
        <div class="flex justify-between"><span class="text-gray-400">Status</span><span class="<?= $user['is_active'] ? 'text-green-600' : 'text-red-500' ?> font-medium"><?= $user['is_active'] ? 'Active' : 'Inactive' ?></span></div>
        <div class="flex justify-between"><span class="text-gray-400">Total Orders</span><span class="font-bold"><?= count($orders) ?></span></div>
      </div>
    </div>
  </div>
  <div class="md:col-span-2">
    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
      <div class="px-5 py-4 border-b border-gray-100"><h3 class="font-bold">Order History</h3></div>
      <table class="w-full data-table">
        <thead><tr class="text-left text-xs text-gray-500 uppercase"><th class="px-5 py-3">Order</th><th class="px-4 py-3">Amount</th><th class="px-4 py-3">Status</th><th class="px-4 py-3">Date</th></tr></thead>
        <tbody class="divide-y divide-gray-50">
          <?php foreach ($orders as $o): ?>
          <tr>
            <td class="px-5 py-3 font-bold text-sm"><a href="<?= BASE_URL ?>/admin/orders/<?= $o['order_number'] ?>" class="text-red-500 hover:underline"><?= $o['order_number'] ?></a></td>
            <td class="px-4 py-3 text-sm font-bold">৳<?= number_format($o['total_amount'],0) ?></td>
            <td class="px-4 py-3"><span class="badge badge-<?= $o['order_status'] ?>"><?= ucfirst($o['order_status']) ?></span></td>
            <td class="px-4 py-3 text-xs text-gray-400"><?= date('d M Y', strtotime($o['placed_at'])) ?></td>
          </tr>
          <?php endforeach; ?>
          <?php if (empty($orders)): ?><tr><td colspan="4" class="px-5 py-8 text-center text-gray-400">No orders</td></tr><?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<?php require __DIR__ . '/../partials/footer.php'; ?>
