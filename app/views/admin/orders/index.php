<?php require __DIR__ . '/../partials/header.php'; ?>

<div class="flex flex-col md:flex-row gap-3 mb-5">
  <form method="get" class="flex gap-2 flex-1">
    <input type="text" name="q" value="<?= htmlspecialchars($_GET['q'] ?? '') ?>" placeholder="Search order number, name..." class="border border-gray-200 rounded-xl px-4 py-2 text-sm w-64 focus:border-red-400 outline-none">
    <input type="hidden" name="status" value="<?= htmlspecialchars($statusFilter) ?>">
    <button class="px-4 py-2 rounded-xl text-sm border border-gray-200 hover:border-red-400 transition-all">Search</button>
  </form>
  <div class="flex gap-2 flex-wrap">
    <?php foreach ([''=>'All','pending'=>'Pending','confirmed'=>'Confirmed','shipped'=>'Shipped','delivered'=>'Delivered','cancelled'=>'Cancelled'] as $val=>$lbl): ?>
    <a href="?status=<?= $val ?>" class="px-4 py-2 rounded-xl text-sm font-medium border transition-all <?= $statusFilter === $val ? 'border-red-500 bg-red-50 text-red-600' : 'border-gray-200 text-gray-600 hover:border-red-300' ?>"><?= $lbl ?></a>
    <?php endforeach; ?>
  </div>
</div>

<div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
  <div class="overflow-x-auto">
    <table class="w-full data-table">
      <thead>
        <tr class="text-left text-xs text-gray-500 uppercase tracking-wide">
          <th class="px-5 py-3">Order #</th>
          <th class="px-4 py-3">Customer</th>
          <th class="px-4 py-3">Amount</th>
          <th class="px-4 py-3">Payment</th>
          <th class="px-4 py-3">Status</th>
          <th class="px-4 py-3">Date</th>
          <th class="px-4 py-3"></th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-50">
        <?php foreach ($orders as $order): ?>
        <tr>
          <td class="px-5 py-3 font-bold text-sm text-gray-800"><?= $order['order_number'] ?></td>
          <td class="px-4 py-3 text-sm"><?= htmlspecialchars($order['user_name'] ?? $order['guest_name'] ?? 'Guest') ?><br><span class="text-xs text-gray-400"><?= htmlspecialchars($order['guest_phone'] ?? '') ?></span></td>
          <td class="px-4 py-3 font-bold text-sm">৳<?= number_format($order['total_amount'], 0) ?></td>
          <td class="px-4 py-3 text-sm capitalize"><?= $order['payment_method'] ?></td>
          <td class="px-4 py-3"><span class="badge badge-<?= $order['order_status'] ?>"><?= ucfirst($order['order_status']) ?></span></td>
          <td class="px-4 py-3 text-xs text-gray-400"><?= date('d M Y', strtotime($order['placed_at'])) ?></td>
          <td class="px-4 py-3"><a href="<?= BASE_URL ?>/admin/orders/<?= $order['order_number'] ?>" class="text-xs text-red-500 hover:underline font-medium">View →</a></td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($orders)): ?><tr><td colspan="7" class="px-5 py-10 text-center text-gray-400">No orders found</td></tr><?php endif; ?>
      </tbody>
    </table>
  </div>
  <?php if ($total > $limit): ?>
  <div class="px-6 py-4 border-t border-gray-100 flex gap-2">
    <?php for ($i=1; $i<=ceil($total/$limit); $i++): ?>
    <a href="?page=<?= $i ?>&status=<?= $statusFilter ?>" class="w-8 h-8 flex items-center justify-center rounded-lg text-sm <?= $i===$page?'bg-red-500 text-white':'bg-gray-50 text-gray-600' ?>"><?= $i ?></a>
    <?php endfor; ?>
  </div>
  <?php endif; ?>
</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>
