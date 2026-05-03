<?php require __DIR__ . '/partials/header.php'; ?>

<div class="max-w-5xl mx-auto px-4 py-8">
  <h1 class="text-2xl font-bold text-primary mb-6">Shopping Cart</h1>

  <?php if (empty($items)): ?>
    <div class="bg-white rounded-2xl border border-gray-100 p-16 text-center">
      <div class="text-6xl mb-4">🛒</div>
      <h3 class="text-lg font-bold text-gray-700 mb-2">Your cart is empty</h3>
      <p class="text-gray-400 text-sm mb-6">Add some products to your cart and come back here.</p>
      <a href="<?= BASE_URL ?>/shop" class="btn-primary px-8 py-3 rounded-xl font-bold text-sm inline-block">Start Shopping</a>
    </div>
  <?php else: ?>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- Cart Items -->
      <div class="lg:col-span-2 space-y-3" id="cart-items">
        <?php $subtotal = 0; foreach ($items as $item):
          $itemTotal = $item['price'] * $item['qty'];
          $subtotal += $itemTotal;
          $imgSrc = !empty($item['product']['image_filename'])
              ? BASE_URL . '/assets/images/products/' . $item['product']['image_filename']
              : BASE_URL . '/assets/images/placeholder.svg';
        ?>
        <div class="bg-white rounded-2xl border border-gray-100 p-4 flex gap-4" id="item-<?= $item['key'] ?>">
          <a href="<?= BASE_URL ?>/product/<?= $item['product_id'] ?>" class="w-20 h-20 bg-gray-50 rounded-xl overflow-hidden shrink-0">
            <img src="<?= $imgSrc ?>" class="w-full h-full object-contain p-1" onerror="this.src='<?= BASE_URL ?>/assets/images/placeholder.svg'">
          </a>
          <div class="flex-1 min-w-0">
            <a href="<?= BASE_URL ?>/product/<?= $item['product_id'] ?>" class="font-semibold text-gray-800 hover:text-accent text-sm line-clamp-1"><?= htmlspecialchars($item['product']['product_name']) ?></a>
            <div class="flex gap-3 mt-1 text-xs text-gray-400">
              <?php if ($item['size']): ?><span>Size: <strong class="text-gray-600"><?= htmlspecialchars($item['size']) ?></strong></span><?php endif; ?>
              <?php if ($item['color']): ?><span>Color: <strong class="text-gray-600"><?= htmlspecialchars($item['color']) ?></strong></span><?php endif; ?>
            </div>
            <div class="flex items-center justify-between mt-3">
              <div class="flex items-center border border-gray-200 rounded-lg overflow-hidden">
                <button onclick="updateQty('<?= $item['key'] ?>', -1)" class="w-8 h-8 text-gray-600 hover:bg-gray-100 transition-colors font-bold">−</button>
                <span id="qty-<?= $item['key'] ?>" class="w-10 text-center text-sm font-bold"><?= $item['qty'] ?></span>
                <button onclick="updateQty('<?= $item['key'] ?>', 1)" class="w-8 h-8 text-gray-600 hover:bg-gray-100 transition-colors font-bold">+</button>
              </div>
              <div class="text-right">
                <p class="font-bold text-primary" id="total-<?= $item['key'] ?>">৳<?= number_format($itemTotal, 0) ?></p>
                <p class="text-xs text-gray-400">৳<?= number_format($item['price'], 0) ?> each</p>
              </div>
              <button onclick="removeItem('<?= $item['key'] ?>')" class="text-gray-300 hover:text-red-500 transition-colors p-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
              </button>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>

      <!-- Summary -->
      <div class="lg:col-span-1">
        <div class="bg-white rounded-2xl border border-gray-100 p-5 sticky top-20">
          <h3 class="font-bold text-primary mb-4">Order Summary</h3>

          <!-- Coupon -->
          <div class="mb-4">
            <div class="flex gap-2">
              <input type="text" id="coupon-input" placeholder="Coupon code" class="flex-1 border border-gray-200 rounded-xl px-3 py-2 text-sm">
              <button onclick="applyCoupon()" class="px-4 py-2 bg-primary text-white rounded-xl text-sm font-medium hover:bg-accent transition-colors">Apply</button>
            </div>
            <div id="coupon-msg" class="mt-1 text-xs"></div>
          </div>

          <div class="space-y-2 text-sm border-t border-gray-100 pt-4">
            <div class="flex justify-between"><span class="text-gray-500">Subtotal</span><span id="subtotal-display" class="font-medium">৳<?= number_format($subtotal, 0) ?></span></div>
            <div class="flex justify-between" id="discount-row" style="display:none!important">
              <span class="text-green-600">Discount</span><span class="text-green-600 font-medium" id="discount-display">-৳0</span>
            </div>
            <div class="flex justify-between"><span class="text-gray-500">Delivery</span><span class="text-gray-500 text-xs">Calculated at checkout</span></div>
          </div>

          <div class="border-t border-gray-100 mt-4 pt-4 flex justify-between font-bold text-lg">
            <span>Total</span><span id="grand-total" class="text-primary">৳<?= number_format($subtotal, 0) ?></span>
          </div>

          <a href="<?= BASE_URL ?>/checkout" class="btn-primary w-full text-center py-3 rounded-xl font-bold text-sm mt-5 block">
            Proceed to Checkout →
          </a>
          <a href="<?= BASE_URL ?>/shop" class="text-center text-sm text-gray-400 hover:text-accent block mt-3 transition-colors">← Continue Shopping</a>
        </div>
      </div>
    </div>
  <?php endif; ?>
</div>

<script>
let subtotal = <?= $subtotal ?>;
let discount = 0;

async function updateQty(key, delta) {
  const el = document.getElementById('qty-' + key);
  const newQty = Math.max(1, parseInt(el.textContent) + delta);
  el.textContent = newQty;
  const res = await fetch('<?= BASE_URL ?>/cart/update', {
    method: 'POST',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: `key=${key}&qty=${newQty}`
  });
  location.reload();
}

async function removeItem(key) {
  const res = await fetch('<?= BASE_URL ?>/cart/remove', {
    method: 'POST',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: `key=${key}`
  });
  const data = await res.json();
  document.getElementById('item-' + key).remove();
  updateCartCount(data.cartCount);
  location.reload();
}

async function applyCoupon() {
  const code = document.getElementById('coupon-input').value;
  const res = await fetch('<?= BASE_URL ?>/cart/apply-coupon', {
    method: 'POST',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: `code=${code}&subtotal=${subtotal}`
  });
  const data = await res.json();
  const msg = document.getElementById('coupon-msg');
  if (data.success) {
    discount = data.discount;
    msg.innerHTML = `<span class="text-green-600">${data.message}</span>`;
    document.getElementById('discount-row').style.removeProperty('display');
    document.getElementById('discount-display').textContent = '-৳' + discount.toFixed(0);
    updateTotal();
  } else {
    msg.innerHTML = `<span class="text-red-500">${data.message}</span>`;
    discount = 0;
    updateTotal();
  }
}

function updateTotal() {
  const total = subtotal - discount;
  document.getElementById('grand-total').textContent = '৳' + Math.round(total).toLocaleString();
}
</script>

<?php require __DIR__ . '/partials/footer.php'; ?>
