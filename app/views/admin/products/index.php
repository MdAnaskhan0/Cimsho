<?php require __DIR__ . '/../partials/header.php'; ?>

<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-5">
  <form method="get" class="flex gap-2">
    <input type="text" name="q" value="<?= htmlspecialchars($search) ?>" placeholder="Search products..." class="border border-gray-200 rounded-xl px-4 py-2 text-sm w-64 focus:border-red-400 outline-none">
    <button class="px-4 py-2 rounded-xl text-sm font-medium border border-gray-200 hover:border-red-400 transition-all">Search</button>
  </form>
  <a href="<?= BASE_URL ?>/admin/products/create" class="btn-primary px-5 py-2.5 rounded-xl text-sm font-bold inline-flex items-center gap-2">
    <span>+</span> Add Product
  </a>
</div>

<?php if (isset($_GET['created'])): ?><div class="bg-green-50 border border-green-200 text-green-700 rounded-xl px-4 py-3 text-sm mb-4">✅ Product created successfully!</div><?php endif; ?>

<div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
  <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
    <p class="text-sm text-gray-500"><?= number_format($total) ?> products</p>
  </div>
  <div class="overflow-x-auto">
    <table class="w-full data-table">
      <thead>
        <tr class="text-left text-xs text-gray-500 uppercase tracking-wide">
          <th class="px-5 py-3">Product</th>
          <th class="px-4 py-3">Category</th>
          <th class="px-4 py-3">Price</th>
          <th class="px-4 py-3">Stock</th>
          <th class="px-4 py-3">Featured</th>
          <th class="px-4 py-3">Actions</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-50">
        <?php foreach ($products as $p): ?>
        <tr>
          <td class="px-5 py-3">
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 bg-gray-50 rounded-lg overflow-hidden shrink-0">
                <?php if ($p['image_filename']): ?>
                <img src="<?= BASE_URL ?>/assets/images/products/<?= $p['image_filename'] ?>" class="w-full h-full object-contain p-0.5" onerror="this.src='<?= BASE_URL ?>/assets/images/placeholder.svg'">
                <?php else: ?>
                <div class="w-full h-full flex items-center justify-center text-gray-300 text-lg">📦</div>
                <?php endif; ?>
              </div>
              <div>
                <p class="text-sm font-semibold text-gray-800 max-w-xs truncate"><?= htmlspecialchars($p['product_name']) ?></p>
                <p class="text-xs text-gray-400"><?= htmlspecialchars($p['sku'] ?? '') ?></p>
              </div>
            </div>
          </td>
          <td class="px-4 py-3 text-sm text-gray-600"><?= htmlspecialchars($p['category_name'] ?? '—') ?></td>
          <td class="px-4 py-3">
            <?php $pr = $p['min_sale'] ?? $p['min_regular']; ?>
            <span class="text-sm font-bold">৳<?= $pr ? number_format($pr, 0) : '—' ?></span>
          </td>
          <td class="px-4 py-3">
            <span class="text-sm <?= $p['product_stock'] > 0 ? 'text-green-600' : 'text-red-500' ?> font-medium"><?= $p['product_stock'] ?></span>
          </td>
          <td class="px-4 py-3">
            <button onclick="toggleFeatured(<?= $p['product_id'] ?>, <?= $p['is_featured'] ? 0 : 1 ?>)" class="text-xl <?= $p['is_featured'] ? 'text-yellow-400' : 'text-gray-200' ?> hover:text-yellow-400 transition-colors" title="Toggle Featured">
              ★
            </button>
          </td>
          <td class="px-4 py-3">
            <div class="flex items-center gap-2">
              <a href="<?= BASE_URL ?>/admin/products/edit/<?= $p['product_id'] ?>" class="text-xs bg-gray-50 hover:bg-blue-50 hover:text-blue-600 px-3 py-1.5 rounded-lg border border-gray-200 transition-all font-medium">Edit</a>
              <button onclick="deleteProduct(<?= $p['product_id'] ?>)" class="text-xs bg-gray-50 hover:bg-red-50 hover:text-red-600 px-3 py-1.5 rounded-lg border border-gray-200 transition-all font-medium">Delete</button>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($products)): ?>
        <tr><td colspan="6" class="px-5 py-10 text-center text-gray-400 text-sm">No products found</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <!-- Pagination -->
  <?php if ($total > $limit): ?>
  <div class="px-6 py-4 border-t border-gray-100 flex gap-2">
    <?php for ($i = 1; $i <= ceil($total/$limit); $i++): ?>
    <a href="?page=<?= $i ?>&q=<?= urlencode($search) ?>" class="w-8 h-8 flex items-center justify-center rounded-lg text-sm <?= $i === $page ? 'bg-red-500 text-white' : 'bg-gray-50 text-gray-600 hover:bg-gray-100' ?> transition-all"><?= $i ?></a>
    <?php endfor; ?>
  </div>
  <?php endif; ?>
</div>

<script>
async function toggleFeatured(id, val) {
  const data = await adminPost('<?= BASE_URL ?>/admin/products/toggle-featured', {id, val});
  if (data.success) { showAdminToast(val ? 'Marked as featured!' : 'Removed from featured'); setTimeout(() => location.reload(), 1000); }
}
async function deleteProduct(id) {
  if (!confirm('Delete this product?')) return;
  const data = await adminPost('<?= BASE_URL ?>/admin/products/delete/' + id, {});
  if (data.success) { showAdminToast('Product deleted'); setTimeout(() => location.reload(), 1000); }
}
</script>

<?php require __DIR__ . '/../partials/footer.php'; ?>
