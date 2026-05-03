</main>

<!-- Footer -->
<footer class="bg-primary text-white mt-16">
  <div class="max-w-7xl mx-auto px-4 py-12">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
      <div>
        <?php
        $footerLogo = $settingsModel->get('footer_logo');
        $siteName = $settingsModel->get('site_name') ?: 'Cimsho';
        ?>
        <div class="flex items-center gap-2 mb-4">
          <?php if ($footerLogo): ?>
            <img src="<?= BASE_URL . $footerLogo ?>" alt="<?= htmlspecialchars($siteName) ?>" class="h-10 w-auto object-contain brightness-0 invert">
          <?php else: ?>
            <div class="w-9 h-9 bg-accent rounded-lg flex items-center justify-center font-bold text-lg"><?= substr($siteName, 0, 1) ?></div>
            <span class="text-xl font-bold tracking-tight"><?= htmlspecialchars($siteName) ?></span>
          <?php endif; ?>
        </div>
        <p class="text-gray-400 text-sm leading-relaxed">Your trusted online shopping destination in Bangladesh. Quality products, fast delivery.</p>
        <div class="flex gap-3 mt-4">
          <a href="#" class="w-9 h-9 bg-white/10 rounded-lg flex items-center justify-center hover:bg-accent transition-colors text-sm">f</a>
          <a href="#" class="w-9 h-9 bg-white/10 rounded-lg flex items-center justify-center hover:bg-accent transition-colors text-sm">in</a>
          <a href="#" class="w-9 h-9 bg-white/10 rounded-lg flex items-center justify-center hover:bg-accent transition-colors text-sm">ig</a>
        </div>
      </div>
      <div>
        <h4 class="font-bold mb-4 text-sm uppercase tracking-wide text-gray-300">Shop</h4>
        <ul class="space-y-2 text-sm text-gray-400">
          <li><a href="<?= BASE_URL ?>/shop" class="hover:text-white transition-colors">All Products</a></li>
          <?php foreach (array_slice($categories ?? [], 0, 5) as $cat): ?>
            <li><a href="<?= BASE_URL ?>/category/<?= $cat['slug'] ?>" class="hover:text-white transition-colors"><?= htmlspecialchars($cat['name']) ?></a></li>
          <?php endforeach; ?>
        </ul>
      </div>
      <div>
        <h4 class="font-bold mb-4 text-sm uppercase tracking-wide text-gray-300">Account</h4>
        <ul class="space-y-2 text-sm text-gray-400">
          <li><a href="<?= BASE_URL ?>/login" class="hover:text-white transition-colors">Login / Register</a></li>
          <li><a href="<?= BASE_URL ?>/account/orders" class="hover:text-white transition-colors">My Orders</a></li>
          <li><a href="<?= BASE_URL ?>/cart" class="hover:text-white transition-colors">My Cart</a></li>
          <li><a href="<?= BASE_URL ?>/account/profile" class="hover:text-white transition-colors">Profile</a></li>
        </ul>
      </div>
      <div>
        <h4 class="font-bold mb-4 text-sm uppercase tracking-wide text-gray-300">Contact</h4>
        <ul class="space-y-2 text-sm text-gray-400">
          <li class="flex items-center gap-2">📍 Dhaka, Bangladesh</li>
          <li class="flex items-center gap-2">📞 +880 1700-000000</li>
          <li class="flex items-center gap-2">✉️ support@cimsho.com</li>
        </ul>
        <div class="mt-4">
          <p class="text-xs text-gray-500 mb-2">Payment Methods</p>
          <div class="flex gap-2 text-xs">
            <span class="bg-white/10 px-2 py-1 rounded">bKash</span>
            <span class="bg-white/10 px-2 py-1 rounded">Nagad</span>
            <span class="bg-white/10 px-2 py-1 rounded">COD</span>
            <span class="bg-white/10 px-2 py-1 rounded">Card</span>
          </div>
        </div>
      </div>
    </div>
    <div class="border-t border-white/10 mt-8 pt-6 flex flex-col md:flex-row justify-between items-center gap-3">
      <p class="text-gray-500 text-sm">© <?= date('Y') ?> Cimsho. All rights reserved.</p>
      <p class="text-gray-500 text-sm">Made with ❤️ in Bangladesh 🇧🇩</p>
    </div>
  </div>
</footer>

<script>
  function toggleMobileMenu() {
    document.getElementById('mobile-menu').classList.toggle('hidden');
  }

  function showToast(msg, type = 'success') {
    const toast = document.getElementById('toast');
    const icon = document.getElementById('toast-icon');
    const msgEl = document.getElementById('toast-msg');
    icon.style.background = type === 'success' ? '#10b981' : '#e94560';
    icon.textContent = type === 'success' ? '✓' : '✕';
    msgEl.textContent = msg;
    toast.classList.add('show');
    setTimeout(() => toast.classList.remove('show'), 3000);
  }

  function updateCartCount(count) {
    const el = document.getElementById('cart-count');
    if (count > 0) {
      el.textContent = count;
      el.classList.remove('hidden');
    } else el.classList.add('hidden');
  }

  async function addToCart(productId, size, color, price, qty = 1) {
    const res = await fetch('<?= BASE_URL ?>/cart/add', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded'
      },
      body: `product_id=${productId}&size=${encodeURIComponent(size)}&color=${encodeURIComponent(color)}&price=${price}&qty=${qty}`
    });
    const data = await res.json();
    if (data.success) {
      updateCartCount(data.cartCount);
      showToast('Added to cart!', 'success');
    }
  }

  function stars(rating, total) {
    let html = '';
    for (let i = 1; i <= 5; i++) {
      html += `<span class="${i <= Math.round(rating) ? 'star-filled' : 'star-empty'}">★</span>`;
    }
    if (total !== undefined) html += `<span class="text-xs text-gray-400 ml-1">(${total})</span>`;
    return html;
  }
</script>
</body>

</html>