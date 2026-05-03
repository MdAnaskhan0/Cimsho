<?php require __DIR__ . '/partials/header.php'; ?>

<!-- Hero Section -->
<section class="relative bg-primary overflow-hidden">
  <div class="absolute inset-0 opacity-10">
    <div class="absolute top-0 right-0 w-96 h-96 bg-accent rounded-full translate-x-32 -translate-y-16"></div>
    <div class="absolute bottom-0 left-0 w-64 h-64 bg-gold rounded-full -translate-x-16 translate-y-16"></div>
  </div>
  <div class="max-w-7xl mx-auto px-4 py-16 md:py-24 flex flex-col md:flex-row items-center gap-12 relative">
    <div class="flex-1 text-white text-center md:text-left">
      <span class="inline-block bg-accent/20 text-accent text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider mb-4">New Arrivals 2026</span>
      <h1 class="text-4xl md:text-6xl font-extrabold leading-tight mb-4">
        Shop Smart,<br><span class="text-accent">Live Better</span>
      </h1>
      <p class="text-gray-300 text-lg mb-8 max-w-md">Discover the latest products with fast delivery across Bangladesh. Quality you can trust.</p>
      <div class="flex flex-wrap gap-3 justify-center md:justify-start">
        <a href="<?= BASE_URL ?>/shop" class="btn-primary px-8 py-3 rounded-xl font-bold text-sm inline-block">Shop Now</a>
        <a href="<?= BASE_URL ?>/shop?featured=1" class="btn-outline px-8 py-3 rounded-xl font-bold text-sm inline-block">Featured Items</a>
      </div>
      <div class="flex gap-8 mt-10 justify-center md:justify-start">
        <div class="text-center"><div class="text-2xl font-bold text-white">500+</div><div class="text-xs text-gray-400">Products</div></div>
        <div class="text-center"><div class="text-2xl font-bold text-white">10K+</div><div class="text-xs text-gray-400">Customers</div></div>
        <div class="text-center"><div class="text-2xl font-bold text-white">64</div><div class="text-xs text-gray-400">Districts</div></div>
      </div>
    </div>
    <div class="flex-1 flex justify-center">
      <div class="relative w-72 h-72 md:w-96 md:h-96">
        <div class="absolute inset-0 bg-accent/20 rounded-full animate-pulse"></div>
        <div class="absolute inset-8 bg-white/5 rounded-full flex items-center justify-center">
          <span class="text-8xl">🛍️</span>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Features Bar -->
<section class="bg-white border-b border-gray-100">
  <div class="max-w-7xl mx-auto px-4 py-5">
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 bg-accent/10 rounded-xl flex items-center justify-center text-xl">🚚</div>
        <div><p class="text-sm font-bold">Fast Delivery</p><p class="text-xs text-gray-400">Dhaka 24-48 hrs</p></div>
      </div>
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 bg-accent/10 rounded-xl flex items-center justify-center text-xl">🔄</div>
        <div><p class="text-sm font-bold">Easy Returns</p><p class="text-xs text-gray-400">7 day policy</p></div>
      </div>
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 bg-accent/10 rounded-xl flex items-center justify-center text-xl">💳</div>
        <div><p class="text-sm font-bold">Secure Payment</p><p class="text-xs text-gray-400">bKash, Card, COD</p></div>
      </div>
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 bg-accent/10 rounded-xl flex items-center justify-center text-xl">🎧</div>
        <div><p class="text-sm font-bold">24/7 Support</p><p class="text-xs text-gray-400">Always here</p></div>
      </div>
    </div>
  </div>
</section>

<!-- Categories -->
<?php if (!empty($categories)): ?>
<section class="max-w-7xl mx-auto px-4 py-12">
  <div class="flex items-center justify-between mb-6">
    <div>
      <h2 class="text-2xl font-bold text-primary">Shop by Category</h2>
      <p class="text-gray-400 text-sm mt-1">Browse our wide selection</p>
    </div>
    <a href="<?= BASE_URL ?>/shop" class="text-sm text-accent font-semibold hover:underline">View All →</a>
  </div>
  <div class="grid grid-cols-3 md:grid-cols-6 gap-3">
    <?php foreach (array_slice($categories, 0, 6) as $cat): ?>
    <a href="<?= BASE_URL ?>/category/<?= $cat['slug'] ?>" class="group flex flex-col items-center gap-2 bg-white rounded-2xl p-4 border border-gray-100 hover:border-accent hover:shadow-md transition-all">
      <div class="w-12 h-12 bg-accent/10 rounded-xl flex items-center justify-center text-2xl group-hover:bg-accent group-hover:scale-110 transition-all">
        🏷️
      </div>
      <span class="text-xs font-medium text-center text-gray-700 group-hover:text-accent transition-colors"><?= htmlspecialchars($cat['name']) ?></span>
    </a>
    <?php endforeach; ?>
  </div>
</section>
<?php endif; ?>

<!-- Featured Products -->
<?php if (!empty($featured)): ?>
<section class="max-w-7xl mx-auto px-4 pb-12">
  <div class="flex items-center justify-between mb-6">
    <div>
      <h2 class="text-2xl font-bold text-primary">Featured Products</h2>
      <p class="text-gray-400 text-sm mt-1">Hand-picked for you</p>
    </div>
    <a href="<?= BASE_URL ?>/shop" class="text-sm text-accent font-semibold hover:underline">View All →</a>
  </div>
  <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
    <?php foreach ($featured as $product): ?>
      <?php require __DIR__ . '/partials/product-card.php'; ?>
    <?php endforeach; ?>
  </div>
</section>
<?php endif; ?>

<!-- Promo Banner -->
<section class="max-w-7xl mx-auto px-4 pb-12">
  <div class="bg-gradient-to-r from-primary to-accent rounded-3xl overflow-hidden">
    <div class="p-8 md:p-12 flex flex-col md:flex-row items-center gap-8">
      <div class="flex-1 text-white text-center md:text-left">
        <h3 class="text-3xl md:text-4xl font-extrabold mb-2">Free Delivery</h3>
        <p class="text-white/80 text-lg mb-4">On all orders above <strong>৳2,000</strong></p>
        <a href="<?= BASE_URL ?>/shop" class="inline-block bg-white text-accent font-bold px-6 py-3 rounded-xl hover:bg-gray-100 transition-colors">Shop Now</a>
      </div>
      <div class="text-8xl">📦</div>
    </div>
  </div>
</section>

<!-- Latest Products -->
<?php if (!empty($latest)): ?>
<section class="max-w-7xl mx-auto px-4 pb-16">
  <div class="flex items-center justify-between mb-6">
    <div>
      <h2 class="text-2xl font-bold text-primary">New Arrivals</h2>
      <p class="text-gray-400 text-sm mt-1">Just landed in our store</p>
    </div>
    <a href="<?= BASE_URL ?>/shop" class="text-sm text-accent font-semibold hover:underline">View All →</a>
  </div>
  <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
    <?php foreach ($latest as $product): ?>
      <?php require __DIR__ . '/partials/product-card.php'; ?>
    <?php endforeach; ?>
  </div>
</section>
<?php endif; ?>

<script>
function quickAddToCart(productId, price) {
  addToCart(productId, '', '', price, 1);
}
</script>

<?php require __DIR__ . '/partials/footer.php'; ?>
