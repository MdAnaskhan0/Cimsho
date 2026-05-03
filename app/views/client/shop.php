<?php require __DIR__ . '/partials/header.php'; ?>

<div class="max-w-7xl mx-auto px-4 py-8">
  <!-- Breadcrumb -->
  <div class="flex items-center gap-2 text-sm text-gray-400 mb-6">
    <a href="<?= BASE_URL ?>/" class="hover:text-accent">Home</a>
    <span>/</span>
    <span class="text-gray-700"><?= htmlspecialchars($category['name'] ?? ($search ? 'Search: ' . $search : 'Shop')) ?></span>
  </div>

  <div class="flex flex-col md:flex-row gap-6">
    <!-- Sidebar Filters -->
    <aside class="w-full md:w-56 shrink-0">
      <div class="bg-white rounded-2xl border border-gray-100 p-4 sticky top-20">
        <h3 class="font-bold text-primary mb-4">Categories</h3>
        <div class="space-y-1">
          <a href="<?= BASE_URL ?>/shop" class="block text-sm px-3 py-2 rounded-lg <?= empty($catId) ? 'bg-accent text-white font-medium' : 'text-gray-600 hover:bg-gray-50 hover:text-accent' ?> transition-all">
            All Products
          </a>
          <?php foreach ($categories as $cat): ?>
          <a href="<?= BASE_URL ?>/shop?cat=<?= $cat['id'] ?>" class="block text-sm px-3 py-2 rounded-lg <?= ($catId == $cat['id']) ? 'bg-accent text-white font-medium' : 'text-gray-600 hover:bg-gray-50 hover:text-accent' ?> transition-all">
            <?= htmlspecialchars($cat['name']) ?>
          </a>
          <?php if (!empty($cat['subcategories']) && $catId == $cat['id']): ?>
            <div class="ml-3 space-y-1 mt-1">
              <?php foreach ($cat['subcategories'] as $sub): ?>
              <a href="<?= BASE_URL ?>/shop?cat=<?= $cat['id'] ?>&sub=<?= $sub['id'] ?>" class="block text-xs px-3 py-1.5 rounded-lg text-gray-500 hover:text-accent hover:bg-gray-50 transition-all">
                ↳ <?= htmlspecialchars($sub['name']) ?>
              </a>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
          <?php endforeach; ?>
        </div>
      </div>
    </aside>

    <!-- Products Grid -->
    <div class="flex-1">
      <div class="flex items-center justify-between mb-4">
        <div>
          <h1 class="text-xl font-bold text-primary"><?= htmlspecialchars($category['name'] ?? ($search ? "Results for \"$search\"" : 'All Products')) ?></h1>
          <p class="text-sm text-gray-400"><?= number_format($total) ?> products found</p>
        </div>
        <form method="get" class="hidden md:flex items-center gap-2">
          <?php if ($search): ?><input type="hidden" name="q" value="<?= htmlspecialchars($search) ?>"><?php endif; ?>
          <?php if ($catId ?? null): ?><input type="hidden" name="cat" value="<?= $catId ?>"><?php endif; ?>
          <select name="sort" onchange="this.form.submit()" class="text-sm border border-gray-200 rounded-lg px-3 py-2 bg-white">
            <option>Latest</option>
            <option>Price: Low to High</option>
            <option>Price: High to Low</option>
          </select>
        </form>
      </div>

      <?php if (empty($products)): ?>
        <div class="bg-white rounded-2xl border border-gray-100 p-16 text-center">
          <div class="text-5xl mb-4">🔍</div>
          <h3 class="text-lg font-bold text-gray-700 mb-2">No products found</h3>
          <p class="text-gray-400 text-sm mb-4">Try different keywords or browse categories</p>
          <a href="<?= BASE_URL ?>/shop" class="btn-primary px-6 py-2 rounded-xl text-sm inline-block">Browse All</a>
        </div>
      <?php else: ?>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
          <?php foreach ($products as $product): ?>
            <?php require __DIR__ . '/partials/product-card.php'; ?>
          <?php endforeach; ?>
        </div>

        <!-- Pagination -->
        <?php if ($total > $limit): ?>
        <div class="flex justify-center gap-2 mt-8">
          <?php
          $totalPages = ceil($total / $limit);
          for ($i = 1; $i <= $totalPages; $i++):
            $params = array_filter(['q' => $search, 'cat' => $catId ?? null, 'page' => $i]);
            $qstr = http_build_query($params);
          ?>
            <a href="?<?= $qstr ?>" class="w-9 h-9 flex items-center justify-center rounded-lg text-sm font-medium <?= $i === $page ? 'bg-accent text-white' : 'bg-white border border-gray-200 text-gray-600 hover:border-accent hover:text-accent' ?> transition-all">
              <?= $i ?>
            </a>
          <?php endfor; ?>
        </div>
        <?php endif; ?>
      <?php endif; ?>
    </div>
  </div>
</div>

<script>
function quickAddToCart(productId, price) {
  addToCart(productId, '', '', price, 1);
}
</script>

<?php require __DIR__ . '/partials/footer.php'; ?>
