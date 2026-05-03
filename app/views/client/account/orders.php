<?php require __DIR__ . '/_sidebar_start.php'; ?>
      <h2 class="text-xl font-bold text-primary mb-5">My Orders</h2>
      <?php if (empty($orders)): ?>
        <div class="bg-white rounded-2xl border border-gray-100 p-12 text-center">
          <div class="text-5xl mb-3">📦</div>
          <p class="font-bold text-gray-700 mb-1">No orders yet</p>
          <p class="text-sm text-gray-400 mb-4">Start shopping to see your orders here</p>
          <a href="<?= BASE_URL ?>/shop" class="btn-primary px-6 py-2 rounded-xl text-sm inline-block">Shop Now</a>
        </div>
      <?php else: ?>
        <div class="space-y-3">
          <?php foreach ($orders as $order):
            $statusColors = ['pending'=>'yellow','confirmed'=>'blue','shipped'=>'purple','delivered'=>'green','cancelled'=>'red'];
            $sc = $statusColors[$order['order_status']] ?? 'gray';
          ?>
          <div class="bg-white rounded-2xl border border-gray-100 p-5">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
              <div>
                <div class="flex items-center gap-2 mb-1">
                  <span class="font-bold text-sm text-primary"><?= $order['order_number'] ?></span>
                  <span class="text-xs bg-<?= $sc ?>-100 text-<?= $sc ?>-700 px-2 py-0.5 rounded-full font-medium capitalize"><?= $order['order_status'] ?></span>
                </div>
                <p class="text-xs text-gray-400"><?= date('d M Y, h:i A', strtotime($order['placed_at'])) ?> · <?= ucfirst($order['payment_method']) ?></p>
              </div>
              <div class="flex items-center gap-4">
                <span class="font-extrabold text-primary text-lg">৳<?= number_format($order['total_amount'], 0) ?></span>
                <a href="<?= BASE_URL ?>/account/orders/<?= $order['order_number'] ?>" class="btn-outline px-4 py-2 rounded-xl text-xs font-bold">View Details</a>
              </div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
<?php require __DIR__ . '/_sidebar_end.php'; ?>
