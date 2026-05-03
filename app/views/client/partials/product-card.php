<?php
// $product must be set before including this partial
$imgSrc = !empty($product['image_filename'])
    ? BASE_URL . '/assets/images/products/' . $product['image_filename']
    : BASE_URL . '/assets/images/placeholder.svg';
$price = $product['sale_price'] ?? $product['regular_price'] ?? 0;
$originalPrice = $product['regular_price'] ?? 0;
$hasDiscount = $product['sale_price'] && $product['sale_price'] < $originalPrice;
$discountPct = $hasDiscount ? round((($originalPrice - $product['sale_price']) / $originalPrice) * 100) : 0;
?>
<div class="product-card bg-white rounded-2xl overflow-hidden border border-gray-100 flex flex-col">
  <a href="<?= BASE_URL ?>/product/<?= $product['product_id'] ?>" class="relative overflow-hidden block aspect-square bg-gray-50">
    <img src="<?= $imgSrc ?>" alt="<?= htmlspecialchars($product['product_name']) ?>"
         class="product-img w-full h-full object-cover" onerror="this.src='<?= BASE_URL ?>/assets/images/placeholder.svg'">
    <?php if ($hasDiscount): ?>
      <span class="absolute top-2 left-2 badge-sale text-white text-xs font-bold px-2 py-1 rounded-lg">-<?= $discountPct ?>%</span>
    <?php endif; ?>
    <?php if ($product['is_featured'] ?? false): ?>
      <span class="absolute top-2 right-2 bg-gold text-white text-xs font-bold px-2 py-1 rounded-lg">Featured</span>
    <?php endif; ?>
  </a>
  <div class="p-4 flex flex-col flex-1">
    <?php if (!empty($product['category_name'])): ?>
      <span class="text-xs text-accent font-medium uppercase tracking-wide"><?= htmlspecialchars($product['category_name']) ?></span>
    <?php endif; ?>
    <a href="<?= BASE_URL ?>/product/<?= $product['product_id'] ?>" class="font-semibold text-gray-800 hover:text-accent transition-colors text-sm mt-1 line-clamp-2 flex-1">
      <?= htmlspecialchars($product['product_name']) ?>
    </a>
    <div class="flex items-center justify-between mt-3">
      <div>
        <span class="text-lg font-bold text-primary">৳<?= number_format($price, 0) ?></span>
        <?php if ($hasDiscount): ?>
          <span class="text-xs text-gray-400 line-through ml-1">৳<?= number_format($originalPrice, 0) ?></span>
        <?php endif; ?>
      </div>
      <button onclick="quickAddToCart(<?= $product['product_id'] ?>, <?= $price ?>)" 
              class="w-9 h-9 bg-accent text-white rounded-lg flex items-center justify-center hover:bg-red-600 transition-colors text-sm font-bold">
        +
      </button>
    </div>
  </div>
</div>
