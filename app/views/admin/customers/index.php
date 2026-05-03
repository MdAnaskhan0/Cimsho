<?php require __DIR__ . '/../partials/header.php'; ?>
<div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
  <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
    <h3 class="font-bold">All Customers</h3>
    <span class="text-sm text-gray-400"><?= $total ?> total</span>
  </div>
  <div class="overflow-x-auto">
    <table class="w-full data-table">
      <thead><tr class="text-left text-xs text-gray-500 uppercase tracking-wide">
        <th class="px-5 py-3">Customer</th>
        <th class="px-4 py-3">Phone</th>
        <th class="px-4 py-3">Orders</th>
        <th class="px-4 py-3">Joined</th>
        <th class="px-4 py-3">Status</th>
        <th class="px-4 py-3"></th>
      </tr></thead>
      <tbody class="divide-y divide-gray-50">
        <?php foreach ($users as $u): ?>
        <tr>
          <td class="px-5 py-3">
            <div class="flex items-center gap-3">
              <div class="w-9 h-9 rounded-full flex items-center justify-center text-white text-sm font-bold shrink-0" style="background:#e94560"><?= strtoupper(substr($u['name'],0,1)) ?></div>
              <div>
                <p class="text-sm font-semibold"><?= htmlspecialchars($u['name']) ?></p>
                <p class="text-xs text-gray-400"><?= htmlspecialchars($u['email']) ?></p>
              </div>
            </div>
          </td>
          <td class="px-4 py-3 text-sm"><?= htmlspecialchars($u['phone'] ?? '—') ?></td>
          <td class="px-4 py-3"><span class="font-bold text-sm"><?= $u['order_count'] ?></span></td>
          <td class="px-4 py-3 text-xs text-gray-400"><?= date('d M Y', strtotime($u['created_at'])) ?></td>
          <td class="px-4 py-3"><span class="badge <?= $u['is_active'] ? 'badge-delivered' : 'badge-cancelled' ?>"><?= $u['is_active'] ? 'Active' : 'Inactive' ?></span></td>
          <td class="px-4 py-3"><a href="<?= BASE_URL ?>/admin/customers/<?= $u['user_id'] ?>" class="text-xs text-red-500 hover:underline">View</a></td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($users)): ?><tr><td colspan="6" class="px-5 py-10 text-center text-gray-400">No customers yet</td></tr><?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
<?php require __DIR__ . '/../partials/footer.php'; ?>
