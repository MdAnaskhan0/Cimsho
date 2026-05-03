<?php require __DIR__ . '/../partials/header.php'; ?>

<div class="flex items-center gap-3 mb-5">
  <a href="<?= BASE_URL ?>/admin/orders" class="text-sm text-gray-400 hover:text-red-500">← Orders</a>
  <span class="text-gray-300">/</span>
  <span class="font-bold text-gray-700"><?= $order['order_number'] ?></span>
  <span class="badge badge-<?= $order['order_status'] ?> ml-2"><?= ucfirst($order['order_status']) ?></span>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
  <div class="lg:col-span-2 space-y-5">
    <!-- Items -->
    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
      <div class="px-5 py-4 border-b border-gray-100"><h3 class="font-bold">Order Items</h3></div>
      <?php foreach ($items as $item): ?>
      <div class="flex items-center gap-4 px-5 py-4 border-b border-gray-50 last:border-0">
        <div class="w-14 h-14 bg-gray-50 rounded-xl overflow-hidden shrink-0">
          <?php if ($item['image_filename']): ?>
          <img src="<?= BASE_URL ?>/assets/images/products/<?= $item['image_filename'] ?>" class="w-full h-full object-contain p-1" onerror="this.src='<?= BASE_URL ?>/assets/images/placeholder.svg'">
          <?php endif; ?>
        </div>
        <div class="flex-1">
          <p class="font-semibold text-sm"><?= htmlspecialchars($item['product_name']) ?></p>
          <p class="text-xs text-gray-400"><?= $item['size'] ? 'Size: '.$item['size'].' · ' : '' ?>Qty: <?= $item['qty'] ?></p>
        </div>
        <div class="text-right">
          <p class="font-bold text-sm">৳<?= number_format($item['unit_price'] * $item['qty'], 0) ?></p>
          <p class="text-xs text-gray-400">৳<?= number_format($item['unit_price'], 0) ?> each</p>
        </div>
      </div>
      <?php endforeach; ?>
      <div class="px-5 py-4 bg-gray-50 flex justify-between font-bold text-sm">
        <span>Shipping</span><span>৳<?= number_format($order['shipping_charge'], 0) ?></span>
      </div>
      <div class="px-5 py-4 bg-gray-50 flex justify-between font-extrabold border-t border-gray-200">
        <span>Total</span><span class="text-primary">৳<?= number_format($order['total_amount'], 0) ?></span>
      </div>
    </div>

    <!-- Status Log -->
    <div class="bg-white rounded-2xl border border-gray-100 p-5">
      <h3 class="font-bold mb-4">Order Timeline</h3>
      <div class="space-y-3">
        <?php foreach ($log as $entry): ?>
        <div class="flex items-start gap-3">
          <div class="w-2 h-2 rounded-full mt-1.5 shrink-0" style="background:#e94560"></div>
          <div>
            <p class="text-sm font-semibold capitalize"><?= $entry['status'] ?></p>
            <?php if ($entry['note']): ?><p class="text-xs text-gray-400"><?= htmlspecialchars($entry['note']) ?></p><?php endif; ?>
            <p class="text-xs text-gray-400"><?= date('d M Y H:i', strtotime($entry['created_at'])) ?></p>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

  <!-- Sidebar -->
  <div class="space-y-5">
    <!-- Update Status -->
    <div class="bg-white rounded-2xl border border-gray-100 p-5">
      <h3 class="font-bold mb-4">Update Status</h3>
      <div class="space-y-3">
        <select id="new-status" class="w-full border border-gray-200 rounded-xl px-3 py-3 text-sm">
          <?php foreach (['pending','confirmed','shipped','delivered','cancelled'] as $s): ?>
          <option value="<?= $s ?>" <?= $order['order_status'] === $s ? 'selected' : '' ?>><?= ucfirst($s) ?></option>
          <?php endforeach; ?>
        </select>
        <input type="text" id="status-note" placeholder="Add a note (optional)" class="w-full border border-gray-200 rounded-xl px-3 py-3 text-sm">
        <button onclick="updateStatus()" class="btn-primary w-full py-2.5 rounded-xl text-sm font-bold">Update Status</button>
      </div>
    </div>

    <!-- Order Info -->
    <div class="bg-white rounded-2xl border border-gray-100 p-5">
      <h3 class="font-bold mb-3 text-sm text-gray-500 uppercase tracking-wide">Order Info</h3>
      <div class="space-y-2 text-sm">
        <div class="flex justify-between"><span class="text-gray-400">Payment</span><span class="capitalize font-medium"><?= $order['payment_method'] ?></span></div>
        <div class="flex justify-between"><span class="text-gray-400">Placed</span><span><?= date('d M Y', strtotime($order['placed_at'])) ?></span></div>
        <?php if ($order['tracking_number']): ?>
        <div class="flex justify-between"><span class="text-gray-400">Tracking</span><span class="font-mono text-xs"><?= $order['tracking_number'] ?></span></div>
        <?php endif; ?>
      </div>
    </div>

    <!-- Customer -->
    <div class="bg-white rounded-2xl border border-gray-100 p-5">
      <h3 class="font-bold mb-3 text-sm text-gray-500 uppercase tracking-wide">Customer</h3>
      <div class="text-sm space-y-1">
        <p class="font-bold"><?= htmlspecialchars($order['guest_name'] ?? '') ?></p>
        <p class="text-gray-500"><?= htmlspecialchars($order['guest_email'] ?? '') ?></p>
        <p class="text-gray-500"><?= htmlspecialchars($order['guest_phone'] ?? '') ?></p>
        <p class="text-gray-500 text-xs mt-2"><?= htmlspecialchars($order['guest_address'] ?? '') ?></p>
      </div>
    </div>
  </div>
</div>

<script>
async function updateStatus() {
  const status = document.getElementById('new-status').value;
  const note = document.getElementById('status-note').value;
  const data = await adminPost('<?= BASE_URL ?>/admin/orders/update-status', {
    order_id: <?= $order['order_id'] ?>,
    status, note
  });
  if (data.success) { showAdminToast('Status updated!'); setTimeout(() => location.reload(), 1000); }
}
</script>

<?php require __DIR__ . '/../partials/footer.php'; ?>
