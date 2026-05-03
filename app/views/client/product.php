<?php require __DIR__ . '/partials/header.php';
$mainImg = BASE_URL . '/assets/images/placeholder.svg';
foreach ($images as $img) {
  if ($img['is_primary']) {
    $mainImg = BASE_URL . '/assets/images/products/' . $img['image_filename'];
    break;
  }
}
if ($mainImg === BASE_URL . '/assets/images/placeholder.svg' && !empty($images)) {
  $mainImg = BASE_URL . '/assets/images/products/' . $images[0]['image_filename'];
}
$avgRating = round($rating['avg_rating'] ?? 0);
$totalReviews = $rating['total'] ?? 0;
$firstSize = $sizes[0] ?? null;
$currentPrice = $firstSize ? ($firstSize['sale_price'] ?? $firstSize['regular_price']) : 0;
?>

<div class="max-w-7xl mx-auto px-4 py-8">
  <!-- Breadcrumb -->
  <div class="flex items-center gap-2 text-sm text-gray-400 mb-6 flex-wrap">
    <a href="<?= BASE_URL ?>/" class="hover:text-accent">Home</a>
    <span>/</span>
    <a href="<?= BASE_URL ?>/shop" class="hover:text-accent">Shop</a>
    <?php if ($product['category_name']): ?>
      <span>/</span>
      <a href="<?= BASE_URL ?>/shop?cat=<?= $product['category_id'] ?>" class="hover:text-accent"><?= htmlspecialchars($product['category_name']) ?></a>
    <?php endif; ?>
    <span>/</span>
    <span class="text-gray-700 truncate max-w-xs"><?= htmlspecialchars($product['product_name']) ?></span>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-10 bg-white rounded-3xl border border-gray-100 p-6 md:p-10 mb-10">
    <!-- Images -->
    <div>
      <div class="aspect-square bg-gray-50 rounded-2xl overflow-hidden mb-3">
        <img id="main-img" src="<?= $mainImg ?>" alt="<?= htmlspecialchars($product['product_name']) ?>" class="w-full h-full object-contain p-4" onerror="this.src='<?= BASE_URL ?>/assets/images/placeholder.svg'">
      </div>
      <?php if (count($images) > 1): ?>
        <div class="flex gap-2 overflow-x-auto pb-1">
          <?php foreach ($images as $img): ?>
            <button onclick="document.getElementById('main-img').src='<?= BASE_URL ?>/assets/images/products/<?= $img['image_filename'] ?>'"
              class="w-16 h-16 rounded-xl border-2 border-gray-100 hover:border-accent overflow-hidden shrink-0 transition-all">
              <img src="<?= BASE_URL ?>/assets/images/products/<?= $img['image_filename'] ?>" class="w-full h-full object-cover" onerror="this.src='<?= BASE_URL ?>/assets/images/placeholder.svg'">
            </button>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>

    <!-- Product Info -->
    <div>
      <?php if ($product['category_name']): ?>
        <span class="text-xs text-accent font-bold uppercase tracking-wide"><?= htmlspecialchars($product['category_name']) ?></span>
      <?php endif; ?>
      <h1 class="text-2xl md:text-3xl font-extrabold text-primary mt-2 mb-3"><?= htmlspecialchars($product['product_name']) ?></h1>

      <!-- Rating -->
      <div class="flex items-center gap-2 mb-4">
        <div class="flex text-xl"><?= str_repeat('★', $avgRating) . str_repeat('☆', 5 - $avgRating) ?></div>
        <span class="text-sm text-gray-400">(<?= $totalReviews ?> reviews)</span>
        <?php if ($product['brand']): ?><span class="text-sm text-gray-400 ml-2">| Brand: <strong><?= htmlspecialchars($product['brand']) ?></strong></span><?php endif; ?>
      </div>

      <!-- Price -->
      <div class="mb-5">
        <span id="current-price" class="text-3xl font-extrabold text-primary">৳<?= number_format($currentPrice, 0) ?></span>
        <?php if ($firstSize && $firstSize['sale_price'] && $firstSize['sale_price'] < $firstSize['regular_price']): ?>
          <span id="original-price" class="text-lg text-gray-400 line-through ml-2">৳<?= number_format($firstSize['regular_price'], 0) ?></span>
        <?php endif; ?>
      </div>

      <!-- Sizes -->
      <?php if (!empty($sizes)): ?>
        <div class="mb-5">
          <p class="text-sm font-bold text-gray-700 mb-2">Size / Variant:</p>
          <div class="flex flex-wrap gap-2" id="size-options">
            <?php foreach ($sizes as $i => $size): ?>
              <button onclick="selectSize(this, '<?= htmlspecialchars($size['size_name']) ?>', <?= $size['sale_price'] ?? $size['regular_price'] ?>, <?= $size['regular_price'] ?>)"
                class="size-btn px-4 py-2 border-2 rounded-xl text-sm font-medium transition-all <?= $i === 0 ? 'border-accent bg-accent text-white' : 'border-gray-200 text-gray-700 hover:border-accent' ?>">
                <?= htmlspecialchars($size['size_name']) ?>
              </button>
            <?php endforeach; ?>
          </div>
          <input type="hidden" id="selected-size" value="<?= htmlspecialchars($sizes[0]['size_name'] ?? '') ?>">
        </div>
      <?php endif; ?>

      <!-- Colors -->
      <?php if (!empty($colors)): ?>
        <div class="mb-5">
          <p class="text-sm font-bold text-gray-700 mb-2">Color: <span id="selected-color-name"><?= htmlspecialchars($colors[0]['color_name']) ?></span></p>
          <div class="flex flex-wrap gap-2">
            <?php foreach ($colors as $color): ?>
              <button onclick="selectColor(this, '<?= htmlspecialchars($color['color_name']) ?>')"
                title="<?= htmlspecialchars($color['color_name']) ?>"
                class="color-btn w-9 h-9 rounded-full border-4 border-white shadow-md hover:scale-110 transition-all ring-0 hover:ring-2 ring-accent"
                style="background: <?= $color['color_code'] ?? '#888' ?>">
              </button>
            <?php endforeach; ?>
          </div>
          <input type="hidden" id="selected-color" value="<?= htmlspecialchars($colors[0]['color_name']) ?>">
        </div>
      <?php endif; ?>

      <!-- Qty + Add to Cart -->
      <div class="flex items-center gap-3 mb-5">
        <div class="flex items-center border-2 border-gray-200 rounded-xl overflow-hidden">
          <button onclick="changeQty(-1)" class="w-10 h-11 text-lg font-bold text-gray-600 hover:bg-gray-100 transition-colors">−</button>
          <input type="number" id="qty" value="1" min="1" max="<?= $product['product_stock'] ?>" class="w-14 h-11 text-center text-sm font-bold border-none outline-none">
          <button onclick="changeQty(1)" class="w-10 h-11 text-lg font-bold text-gray-600 hover:bg-gray-100 transition-colors">+</button>
        </div>
        <button onclick="handleAddToCart()" class="btn-primary flex-1 py-3 rounded-xl font-bold text-sm flex items-center justify-center gap-2">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
          </svg>
          <span class="hidden sm:inline">Add to Cart</span>
        </button>
        <a href="<?= BASE_URL ?>/checkout" onclick="handleAddToCart()" class="btn-outline py-3 px-5 rounded-xl font-bold text-sm whitespace-nowrap">Buy Now</a>
      </div>

      <!-- Details -->
      <div class="border-t border-gray-100 pt-4 space-y-2 text-sm">
        <?php if ($product['sku']): ?><div class="flex gap-2"><span class="text-gray-400 w-24">SKU:</span><span class="font-medium"><?= htmlspecialchars($product['sku']) ?></span></div><?php endif; ?>
        <?php if ($product['material']): ?><div class="flex gap-2"><span class="text-gray-400 w-24">Material:</span><span class="font-medium"><?= htmlspecialchars($product['material']) ?></span></div><?php endif; ?>
        <div class="flex gap-2"><span class="text-gray-400 w-24">Stock:</span>
          <span class="font-medium <?= $product['product_stock'] > 0 ? 'text-green-600' : 'text-red-500' ?>">
            <?= $product['product_stock'] > 0 ? $product['product_stock'] . ' in stock' : 'Out of stock' ?>
          </span>
        </div>
      </div>
    </div>
  </div>

  <!-- Description & Reviews -->
  <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
    <div class="md:col-span-2 bg-white rounded-2xl border border-gray-100 p-6">
      <div class="flex gap-4 mb-6 border-b border-gray-100" id="tabs">
        <button onclick="showTab('desc')" class="tab-btn pb-3 text-sm font-bold border-b-2 border-accent text-accent" data-tab="desc">Description</button>
        <button onclick="showTab('reviews')" class="tab-btn pb-3 text-sm font-bold border-b-2 border-transparent text-gray-400 hover:text-gray-700" data-tab="reviews">Reviews (<?= $totalReviews ?>)</button>
      </div>
      <div id="tab-desc">
        <p class="text-sm text-gray-600 leading-relaxed"><?= nl2br(htmlspecialchars($product['product_description'] ?? 'No description available.')) ?></p>
      </div>
      <div id="tab-reviews" class="hidden">
        <?php if (isset($_SESSION['user_id'])): ?>
          <div class="bg-gray-50 rounded-xl p-4 mb-4">
            <p class="text-sm font-bold mb-3">Write a Review</p>
            <div class="flex gap-1 mb-3" id="star-input">
              <?php for ($i = 1; $i <= 5; $i++): ?>
                <button onclick="setRating(<?= $i ?>)" class="star-select text-2xl text-gray-300 hover:text-gold transition-colors" data-val="<?= $i ?>">★</button>
              <?php endfor; ?>
            </div>
            <textarea id="review-text" placeholder="Share your experience..." class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm resize-none h-20"></textarea>
            <button onclick="submitReview(<?= $product['product_id'] ?>)" class="btn-primary px-5 py-2 rounded-xl text-sm mt-2">Submit Review</button>
          </div>
        <?php endif; ?>
        <div class="space-y-4">
          <?php foreach ($reviews as $rev): ?>
            <div class="border-b border-gray-100 pb-4 last:border-0">
              <div class="flex items-center gap-2 mb-1">
                <div class="w-8 h-8 bg-accent/10 rounded-full flex items-center justify-center text-xs font-bold text-accent"><?= strtoupper(substr($rev['user_name'], 0, 1)) ?></div>
                <span class="text-sm font-bold"><?= htmlspecialchars($rev['user_name']) ?></span>
                <span class="text-yellow-400 text-sm"><?= str_repeat('★', $rev['rating']) ?></span>
                <span class="text-xs text-gray-400 ml-auto"><?= date('M d, Y', strtotime($rev['created_at'])) ?></span>
              </div>
              <p class="text-sm text-gray-600 ml-10"><?= htmlspecialchars($rev['review']) ?></p>
            </div>
          <?php endforeach; ?>
          <?php if (empty($reviews)): ?><p class="text-sm text-gray-400 text-center py-4">No reviews yet. Be the first!</p><?php endif; ?>
        </div>
      </div>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 p-6">
      <h3 class="font-bold text-primary mb-4">Delivery Info</h3>
      <div class="space-y-3 text-sm">
        <div class="flex items-start gap-3"><span class="text-xl">📍</span>
          <div>
            <p class="font-medium">Inside Dhaka</p>
            <p class="text-gray-400">৳60 · 24-48 hours</p>
          </div>
        </div>
        <div class="flex items-start gap-3"><span class="text-xl">🚚</span>
          <div>
            <p class="font-medium">Outside Dhaka</p>
            <p class="text-gray-400">৳120 · 3-5 days</p>
          </div>
        </div>
        <div class="flex items-start gap-3"><span class="text-xl">🎁</span>
          <div>
            <p class="font-medium">Free Delivery</p>
            <p class="text-gray-400">Orders above ৳2,000</p>
          </div>
        </div>
      </div>
      <hr class="my-4">
      <h3 class="font-bold text-primary mb-3">Payment</h3>
      <div class="flex flex-wrap gap-2 text-xs">
        <span class="bg-green-100 text-green-700 px-2 py-1 rounded-lg font-medium">bKash</span>
        <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded-lg font-medium">Card</span>
        <span class="bg-orange-100 text-orange-700 px-2 py-1 rounded-lg font-medium">COD</span>
        <span class="bg-purple-100 text-purple-700 px-2 py-1 rounded-lg font-medium">Nagad</span>
      </div>
    </div>
  </div>

  <!-- Related Products -->
  <?php if (!empty($related)): ?>
    <div>
      <h2 class="text-xl font-bold text-primary mb-4">You May Also Like</h2>
      <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <?php foreach ($related as $product): ?>
          <?php require __DIR__ . '/partials/product-card.php'; ?>
        <?php endforeach; ?>
      </div>
    </div>
  <?php endif; ?>
</div>

<script>
  let selectedSize = '<?= htmlspecialchars($sizes[0]['size_name'] ?? '') ?>';
  let selectedColor = '<?= htmlspecialchars($colors[0]['color_name'] ?? '') ?>';
  let selectedPrice = <?= $currentPrice ?>;
  let selectedRating = 5;

  function selectSize(btn, sizeName, salePrice, regularPrice) {
    document.querySelectorAll('.size-btn').forEach(b => b.className = b.className.replace('border-accent bg-accent text-white', 'border-gray-200 text-gray-700 hover:border-accent'));
    btn.className = btn.className.replace('border-gray-200 text-gray-700 hover:border-accent', 'border-accent bg-accent text-white');
    selectedSize = sizeName;
    selectedPrice = salePrice;
    document.getElementById('current-price').textContent = '৳' + Math.round(salePrice).toLocaleString();
    const origEl = document.getElementById('original-price');
    if (origEl) {
      origEl.textContent = salePrice < regularPrice ? '৳' + Math.round(regularPrice).toLocaleString() : '';
    }
  }

  function selectColor(btn, colorName) {
    document.querySelectorAll('.color-btn').forEach(b => b.classList.remove('ring-2'));
    btn.classList.add('ring-2');
    selectedColor = colorName;
    document.getElementById('selected-color-name').textContent = colorName;
  }

  function changeQty(delta) {
    const input = document.getElementById('qty');
    input.value = Math.max(1, parseInt(input.value) + delta);
  }

  function handleAddToCart() {
    const qty = parseInt(document.getElementById('qty').value);
    addToCart(<?= $product['product_id'] ?>, selectedSize, selectedColor, selectedPrice, qty);
  }

  function showTab(tab) {
    document.getElementById('tab-desc').classList.toggle('hidden', tab !== 'desc');
    document.getElementById('tab-reviews').classList.toggle('hidden', tab !== 'reviews');
    document.querySelectorAll('.tab-btn').forEach(b => {
      b.classList.toggle('border-accent', b.dataset.tab === tab);
      b.classList.toggle('text-accent', b.dataset.tab === tab);
      b.classList.toggle('border-transparent', b.dataset.tab !== tab);
      b.classList.toggle('text-gray-400', b.dataset.tab !== tab);
    });
  }

  function setRating(val) {
    selectedRating = val;
    document.querySelectorAll('.star-select').forEach(s => {
      s.style.color = s.dataset.val <= val ? '#f5a623' : '#d1d5db';
    });
  }

  async function submitReview(productId) {
    const review = document.getElementById('review-text').value;
    const res = await fetch('<?= BASE_URL ?>/ajax/review', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded'
      },
      body: `product_id=${productId}&rating=${selectedRating}&review=${encodeURIComponent(review)}`
    });
    const data = await res.json();
    if (data.success) {
      showToast('Review submitted!');
      location.reload();
    }
  }

  function quickAddToCart(productId, price) {
    addToCart(productId, '', '', price, 1);
  }
</script>

<?php require __DIR__ . '/partials/footer.php'; ?>