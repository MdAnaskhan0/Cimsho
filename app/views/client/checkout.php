<?php require __DIR__ . '/partials/header.php';
$cart = $_SESSION['cart'] ?? [];
$subtotal = 0;
foreach ($cart as $item) $subtotal += $item['price'] * $item['qty'];
$coupon = $_SESSION['coupon'] ?? null;
$discount = $coupon ? round($subtotal * $coupon['discount_pct'] / 100, 2) : 0;
?>

<div class="max-w-5xl mx-auto px-4 py-8">
  <h1 class="text-2xl font-bold text-primary mb-6">Checkout</h1>

  <form action="<?= BASE_URL ?>/checkout/place-order" method="post" id="checkout-form">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

      <!-- Left: Delivery Info -->
      <div class="lg:col-span-2 space-y-5">

        <!-- Saved Addresses -->
        <?php if (!empty($addresses)): ?>
        <div class="bg-white rounded-2xl border border-gray-100 p-5">
          <h3 class="font-bold text-primary mb-4">Saved Addresses</h3>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <?php foreach ($addresses as $addr): ?>
            <label class="address-option cursor-pointer">
              <input type="radio" name="address_id" value="<?= $addr['id'] ?>" class="sr-only" <?= $addr['is_default'] ? 'checked' : '' ?> onchange="selectAddress(this)">
              <div class="border-2 rounded-xl p-4 transition-all <?= $addr['is_default'] ? 'border-accent bg-accent/5' : 'border-gray-200 hover:border-gray-300' ?>">
                <div class="flex items-center gap-2 mb-1">
                  <span class="text-xs bg-primary/10 text-primary px-2 py-0.5 rounded-full font-medium capitalize"><?= $addr['label'] ?></span>
                  <?php if ($addr['is_default']): ?><span class="text-xs text-accent font-medium">✓ Default</span><?php endif; ?>
                </div>
                <p class="font-semibold text-sm"><?= htmlspecialchars($addr['full_name']) ?></p>
                <p class="text-xs text-gray-500"><?= htmlspecialchars($addr['phone']) ?></p>
                <p class="text-xs text-gray-500 mt-1"><?= htmlspecialchars($addr['address_line'] . ', ' . $addr['area'] . ', ' . $addr['city']) ?></p>
              </div>
            </label>
            <?php endforeach; ?>
          </div>
          <button type="button" onclick="toggleGuestForm()" class="mt-3 text-sm text-accent font-medium hover:underline">+ Use a different address</button>
        </div>
        <?php endif; ?>

        <!-- Guest / New Address Form -->
        <div id="guest-form" class="bg-white rounded-2xl border border-gray-100 p-5 <?= !empty($addresses) ? 'hidden' : '' ?>">
          <h3 class="font-bold text-primary mb-4">Delivery Details</h3>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide block mb-1">Full Name *</label>
              <input type="text" name="guest_name" placeholder="Your full name" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm" <?= empty($addresses) ? 'required' : '' ?>>
            </div>
            <div>
              <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide block mb-1">Phone *</label>
              <input type="tel" name="guest_phone" placeholder="01XXXXXXXXX" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm" <?= empty($addresses) ? 'required' : '' ?>>
            </div>
            <div>
              <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide block mb-1">Email</label>
              <input type="email" name="guest_email" placeholder="your@email.com" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm">
            </div>
            <div>
              <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide block mb-1">District *</label>
              <select name="district" id="district-select" onchange="updateDelivery()" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm" <?= empty($addresses) ? 'required' : '' ?>>
                <option value="">Select District</option>
                <?php
                $districts = ['Dhaka','Narayanganj','Gazipur','Manikganj','Chittagong','Sylhet','Rajshahi','Khulna','Barisal','Rangpur','Mymensingh','Comilla','Cox\'s Bazar','Jessore','Bogra','Dinajpur','Tangail','Faridpur','Noakhali','Brahmanbaria'];
                foreach ($districts as $d): ?>
                <option value="<?= strtolower($d) ?>"><?= $d ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="md:col-span-2">
              <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide block mb-1">Full Address *</label>
              <textarea name="guest_address" placeholder="House/Road/Area details..." rows="2" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm resize-none" <?= empty($addresses) ? 'required' : '' ?>></textarea>
            </div>
          </div>
        </div>

        <!-- Delivery Type -->
        <div class="bg-white rounded-2xl border border-gray-100 p-5">
          <h3 class="font-bold text-primary mb-4">Delivery Type</h3>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <label class="cursor-pointer">
              <input type="radio" name="delivery_type" value="inside" class="sr-only" checked onchange="calcTotal()">
              <div class="border-2 border-accent bg-accent/5 rounded-xl p-4 delivery-opt">
                <div class="flex justify-between items-center">
                  <div>
                    <p class="font-semibold text-sm">Inside Dhaka</p>
                    <p class="text-xs text-gray-400 mt-0.5">24-48 hours</p>
                  </div>
                  <span class="font-bold text-primary">৳<?= number_format($delivery['inside_dhaka_charge'], 0) ?></span>
                </div>
              </div>
            </label>
            <label class="cursor-pointer">
              <input type="radio" name="delivery_type" value="outside" class="sr-only" onchange="calcTotal()">
              <div class="border-2 border-gray-200 rounded-xl p-4 delivery-opt hover:border-gray-300 transition-all">
                <div class="flex justify-between items-center">
                  <div>
                    <p class="font-semibold text-sm">Outside Dhaka</p>
                    <p class="text-xs text-gray-400 mt-0.5">3-5 business days</p>
                  </div>
                  <span class="font-bold text-primary">৳<?= number_format($delivery['outside_dhaka_charge'], 0) ?></span>
                </div>
              </div>
            </label>
          </div>
          <?php if ($subtotal >= $delivery['free_delivery_min_amount']): ?>
          <div class="mt-3 bg-green-50 border border-green-200 rounded-xl px-4 py-2 text-sm text-green-700 flex items-center gap-2">
            🎉 <span>You qualify for <strong>free delivery</strong>!</span>
          </div>
          <?php endif; ?>
        </div>

        <!-- Payment Method -->
        <div class="bg-white rounded-2xl border border-gray-100 p-5">
          <h3 class="font-bold text-primary mb-4">Payment Method</h3>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
            <?php
            $methods = [
              ['cod','Cash on Delivery','💵','Pay when you receive'],
              ['bkash','bKash','🟢','Mobile banking'],
              ['card','Credit / Debit Card','💳','Visa, Mastercard'],
            ];
            foreach ($methods as $i => [$val,$label,$icon,$desc]):
            ?>
            <label class="cursor-pointer">
              <input type="radio" name="payment_method" value="<?= $val ?>" class="sr-only" <?= $i===0?'checked':'' ?>>
              <div class="border-2 <?= $i===0?'border-accent bg-accent/5':'border-gray-200 hover:border-gray-300' ?> rounded-xl p-4 pay-opt transition-all">
                <div class="text-2xl mb-1"><?= $icon ?></div>
                <p class="font-semibold text-sm"><?= $label ?></p>
                <p class="text-xs text-gray-400"><?= $desc ?></p>
              </div>
            </label>
            <?php endforeach; ?>
          </div>
        </div>

        <!-- Notes -->
        <div class="bg-white rounded-2xl border border-gray-100 p-5">
          <h3 class="font-bold text-primary mb-3">Order Notes <span class="text-gray-400 font-normal text-sm">(optional)</span></h3>
          <textarea name="notes" placeholder="Any special instructions for your order..." rows="2" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm resize-none"></textarea>
        </div>
      </div>

      <!-- Right: Summary -->
      <div class="lg:col-span-1">
        <div class="bg-white rounded-2xl border border-gray-100 p-5 sticky top-20">
          <h3 class="font-bold text-primary mb-4">Order Summary</h3>
          <div class="space-y-2 max-h-48 overflow-y-auto mb-4">
            <?php foreach ($cart as $item): ?>
            <?php
              $pm = new ProductModel();
              $prod = $pm->getDetail($item['product_id']);
              $imgs = $pm->getImages($item['product_id']);
              $imgF = $imgs[0]['image_filename'] ?? null;
            ?>
            <div class="flex items-center gap-3 py-2 border-b border-gray-50 last:border-0">
              <div class="w-10 h-10 bg-gray-50 rounded-lg overflow-hidden shrink-0">
                <?php if ($imgF): ?>
                <img src="<?= BASE_URL ?>/assets/images/products/<?= $imgF ?>" class="w-full h-full object-contain p-0.5" onerror="this.src='<?= BASE_URL ?>/assets/images/placeholder.svg'">
                <?php endif; ?>
              </div>
              <div class="flex-1 min-w-0">
                <p class="text-xs font-medium truncate"><?= htmlspecialchars($prod['product_name'] ?? '') ?></p>
                <p class="text-xs text-gray-400"><?= $item['size'] ?? '' ?> × <?= $item['qty'] ?></p>
              </div>
              <span class="text-xs font-bold text-primary shrink-0">৳<?= number_format($item['price'] * $item['qty'], 0) ?></span>
            </div>
            <?php endforeach; ?>
          </div>

          <div class="space-y-2 text-sm border-t border-gray-100 pt-3">
            <div class="flex justify-between"><span class="text-gray-500">Subtotal</span><span>৳<?= number_format($subtotal, 0) ?></span></div>
            <?php if ($discount > 0): ?>
            <div class="flex justify-between text-green-600"><span>Discount</span><span>-৳<?= number_format($discount, 0) ?></span></div>
            <?php endif; ?>
            <div class="flex justify-between"><span class="text-gray-500">Delivery</span><span id="delivery-charge">৳<?= number_format($delivery['inside_dhaka_charge'], 0) ?></span></div>
          </div>
          <div class="flex justify-between font-extrabold text-lg border-t border-gray-100 pt-3 mt-3">
            <span>Total</span><span id="order-total" class="text-primary">৳<?= number_format($subtotal - $discount + $delivery['inside_dhaka_charge'], 0) ?></span>
          </div>

          <button type="submit" class="btn-primary w-full py-3.5 rounded-xl font-bold text-sm mt-5 flex items-center justify-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Place Order
          </button>
          <p class="text-xs text-gray-400 text-center mt-3">🔒 Secure & Safe Checkout</p>
        </div>
      </div>
    </div>
  </form>
</div>

<script>
const insideCharge = <?= $delivery['inside_dhaka_charge'] ?>;
const outsideCharge = <?= $delivery['outside_dhaka_charge'] ?>;
const freeMin = <?= $delivery['free_delivery_min_amount'] ?>;
const subtotal = <?= $subtotal ?>;
const discount = <?= $discount ?>;

function calcTotal() {
  const isOutside = document.querySelector('input[name="delivery_type"]:checked')?.value === 'outside';
  let charge = isOutside ? outsideCharge : insideCharge;
  if ((subtotal - discount) >= freeMin) charge = 0;
  document.getElementById('delivery-charge').textContent = '৳' + charge.toLocaleString();
  const total = subtotal - discount + charge;
  document.getElementById('order-total').textContent = '৳' + Math.round(total).toLocaleString();

  document.querySelectorAll('.delivery-opt').forEach(opt => {
    opt.className = opt.className.replace('border-accent bg-accent/5','border-gray-200 hover:border-gray-300');
  });
  const selectedOpt = document.querySelector('input[name="delivery_type"]:checked')?.parentElement?.querySelector('.delivery-opt');
  if (selectedOpt) selectedOpt.className = selectedOpt.className.replace('border-gray-200 hover:border-gray-300','border-accent bg-accent/5');
}

document.querySelectorAll('input[name="delivery_type"]').forEach(r => r.addEventListener('change', calcTotal));

document.querySelectorAll('input[name="payment_method"]').forEach(r => {
  r.addEventListener('change', function() {
    document.querySelectorAll('.pay-opt').forEach(o => o.className = o.className.replace('border-accent bg-accent/5','border-gray-200 hover:border-gray-300'));
    this.parentElement.querySelector('.pay-opt').className = this.parentElement.querySelector('.pay-opt').className.replace('border-gray-200 hover:border-gray-300','border-accent bg-accent/5');
  });
});

document.querySelectorAll('input[name="address_id"]').forEach(r => {
  r.addEventListener('change', function() {
    document.querySelectorAll('.address-option > div').forEach(d => d.className = d.className.replace('border-accent bg-accent/5','border-gray-200 hover:border-gray-300'));
    this.parentElement.querySelector('div').className = this.parentElement.querySelector('div').className.replace('border-gray-200 hover:border-gray-300','border-accent bg-accent/5');
  });
});

function toggleGuestForm() {
  const f = document.getElementById('guest-form');
  f.classList.toggle('hidden');
}

function updateDelivery() {
  const district = document.getElementById('district-select')?.value;
  const dhakaDistricts = ['dhaka','narayanganj','gazipur','manikganj'];
  if (district && !dhakaDistricts.includes(district)) {
    document.querySelector('input[value="outside"]').checked = true;
  } else {
    document.querySelector('input[value="inside"]').checked = true;
  }
  calcTotal();
}
</script>

<?php require __DIR__ . '/partials/footer.php'; ?>
