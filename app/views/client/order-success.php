<?php require __DIR__ . '/partials/header.php'; ?>
<div class="max-w-2xl mx-auto px-4 py-16 text-center">
  <div class="bg-white rounded-3xl border border-gray-100 p-10">
    <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center text-4xl mx-auto mb-5">✅</div>
    <h1 class="text-3xl font-extrabold text-primary mb-2">Order Placed!</h1>
    <p class="text-gray-400 mb-2">Thank you for your order. We'll process it shortly.</p>
    <div class="inline-block bg-accent/10 rounded-xl px-5 py-2 mb-6">
      <span class="text-sm text-gray-500">Order Number: </span>
      <strong class="text-accent text-sm"><?= htmlspecialchars($order['order_number']) ?></strong>
    </div>

    <div class="border border-gray-100 rounded-2xl overflow-hidden mb-6">
      <div class="bg-gray-50 px-5 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wide">Order Items</div>
      <?php foreach ($items as $item): ?>
      <div class="flex items-center gap-3 px-5 py-3 border-t border-gray-100 text-left">
        <div class="w-10 h-10 bg-gray-50 rounded-lg overflow-hidden">
          <?php if ($item['image_filename']): ?>
          <img src="<?= BASE_URL ?>/assets/images/products/<?= $item['image_filename'] ?>" class="w-full h-full object-contain p-1" onerror="this.src='<?= BASE_URL ?>/assets/images/placeholder.svg'">
          <?php endif; ?>
        </div>
        <div class="flex-1">
          <p class="text-sm font-medium"><?= htmlspecialchars($item['product_name']) ?></p>
          <p class="text-xs text-gray-400"><?= $item['size'] ? 'Size: '.$item['size'].' · ' : '' ?>Qty: <?= $item['qty'] ?></p>
        </div>
        <span class="text-sm font-bold">৳<?= number_format($item['unit_price'] * $item['qty'], 0) ?></span>
      </div>
      <?php endforeach; ?>
      <div class="flex justify-between px-5 py-3 border-t border-gray-200 bg-gray-50 font-bold">
        <span>Total Paid</span>
        <span class="text-primary">৳<?= number_format($order['total_amount'], 0) ?></span>
      </div>
    </div>

    <div class="grid grid-cols-3 gap-3 mb-8 text-sm">
      <div class="bg-gray-50 rounded-xl p-3"><p class="text-gray-400 text-xs">Payment</p><p class="font-bold capitalize"><?= $order['payment_method'] ?></p></div>
      <div class="bg-gray-50 rounded-xl p-3"><p class="text-gray-400 text-xs">Shipping</p><p class="font-bold">৳<?= number_format($order['shipping_charge'], 0) ?></p></div>
      <div class="bg-gray-50 rounded-xl p-3"><p class="text-gray-400 text-xs">Status</p><p class="font-bold text-yellow-600 capitalize"><?= $order['order_status'] ?></p></div>
    </div>

    <div class="flex gap-3 justify-center">
      <?php if (isset($_SESSION['user_id'])): ?>
      <a href="<?= BASE_URL ?>/account/orders/<?= $order['order_number'] ?>" class="btn-primary px-6 py-3 rounded-xl font-bold text-sm">Track Order</a>
      <?php endif; ?>
      <a href="<?= BASE_URL ?>/shop" class="btn-outline px-6 py-3 rounded-xl font-bold text-sm">Continue Shopping</a>
    </div>
  </div>
</div>
<?php require __DIR__ . '/partials/footer.php'; ?>
