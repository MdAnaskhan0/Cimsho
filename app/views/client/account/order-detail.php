<?php require __DIR__ . '/_sidebar_start.php'; ?>
      <div class="flex items-center gap-3 mb-5">
        <a href="<?= BASE_URL ?>/account/orders" class="text-gray-400 hover:text-accent text-sm">← Back to Orders</a>
        <h2 class="text-xl font-bold text-primary">Order #<?= htmlspecialchars($order['order_number']) ?></h2>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-5">
        <div class="bg-white rounded-2xl border border-gray-100 p-5">
          <h3 class="font-bold text-sm text-gray-500 uppercase tracking-wide mb-3">Order Info</h3>
          <?php $statusColors = ['pending'=>'yellow','confirmed'=>'blue','shipped'=>'purple','delivered'=>'green','cancelled'=>'red'];
                $sc = $statusColors[$order['order_status']] ?? 'gray'; ?>
          <div class="space-y-2 text-sm">
            <div class="flex justify-between"><span class="text-gray-400">Status</span><span class="bg-<?= $sc ?>-100 text-<?= $sc ?>-700 px-2 py-0.5 rounded-full text-xs font-bold capitalize"><?= $order['order_status'] ?></span></div>
            <div class="flex justify-between"><span class="text-gray-400">Date</span><span><?= date('d M Y', strtotime($order['placed_at'])) ?></span></div>
            <div class="flex justify-between"><span class="text-gray-400">Payment</span><span class="capitalize"><?= $order['payment_method'] ?></span></div>
            <div class="flex justify-between"><span class="text-gray-400">Shipping</span><span>৳<?= number_format($order['shipping_charge'], 0) ?></span></div>
            <div class="flex justify-between font-bold border-t pt-2 mt-1"><span>Total</span><span class="text-primary">৳<?= number_format($order['total_amount'], 0) ?></span></div>
          </div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 p-5">
          <h3 class="font-bold text-sm text-gray-500 uppercase tracking-wide mb-3">Delivery Address</h3>
          <div class="text-sm space-y-1 text-gray-600">
            <p class="font-bold text-gray-800"><?= htmlspecialchars($order['guest_name'] ?? '') ?></p>
            <p><?= htmlspecialchars($order['guest_phone'] ?? '') ?></p>
            <p><?= htmlspecialchars($order['guest_address'] ?? '') ?></p>
          </div>
        </div>
      </div>

      <!-- Items -->
      <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden mb-5">
        <div class="px-5 py-3 bg-gray-50 border-b border-gray-100">
          <h3 class="font-bold text-sm text-gray-700">Order Items</h3>
        </div>
        <?php foreach ($items as $item): ?>
        <div class="flex items-center gap-4 px-5 py-4 border-b border-gray-50 last:border-0">
          <div class="w-14 h-14 bg-gray-50 rounded-xl overflow-hidden shrink-0">
            <?php if ($item['image_filename']): ?>
            <img src="<?= BASE_URL ?>/assets/images/products/<?= $item['image_filename'] ?>" class="w-full h-full object-contain p-1" onerror="this.src='<?= BASE_URL ?>/assets/images/placeholder.svg'">
            <?php endif; ?>
          </div>
          <div class="flex-1">
            <p class="font-semibold text-sm"><?= htmlspecialchars($item['product_name']) ?></p>
            <p class="text-xs text-gray-400 mt-0.5">
              <?= $item['size'] ? 'Size: '.$item['size'].' · ' : '' ?>
              <?= $item['color'] ? 'Color: '.$item['color'].' · ' : '' ?>
              Qty: <?= $item['qty'] ?>
            </p>
          </div>
          <div class="text-right">
            <p class="font-bold">৳<?= number_format($item['unit_price'] * $item['qty'], 0) ?></p>
            <p class="text-xs text-gray-400">৳<?= number_format($item['unit_price'], 0) ?> each</p>
          </div>
        </div>
        <?php endforeach; ?>
      </div>

      <!-- Timeline -->
      <div class="bg-white rounded-2xl border border-gray-100 p-5">
        <h3 class="font-bold text-sm text-gray-500 uppercase tracking-wide mb-4">Order Timeline</h3>
        <div class="space-y-3">
          <?php foreach ($log as $entry): ?>
          <div class="flex items-start gap-3">
            <div class="w-2 h-2 rounded-full bg-accent mt-1.5 shrink-0"></div>
            <div>
              <p class="text-sm font-semibold capitalize"><?= $entry['status'] ?></p>
              <?php if ($entry['note']): ?><p class="text-xs text-gray-400"><?= htmlspecialchars($entry['note']) ?></p><?php endif; ?>
              <p class="text-xs text-gray-400"><?= date('d M Y, h:i A', strtotime($entry['created_at'])) ?></p>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
<?php require __DIR__ . '/_sidebar_end.php'; ?>
