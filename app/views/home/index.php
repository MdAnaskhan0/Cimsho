<?php require APP_ROOT . '/app/views/partials/head.php'; ?>
<?php require APP_ROOT . '/app/views/partials/navbar.php'; ?>
<?php require APP_ROOT . '/app/views/partials/flash.php'; ?>

<!-- ═══════════════════════════════════════════════
     HERO SECTION
════════════════════════════════════════════════ -->
<section class="hero-gradient text-white overflow-hidden relative">
    <!-- Decorative blobs -->
    <div class="absolute -top-32 -right-32 w-96 h-96 bg-brand-600/20 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-24 -left-24 w-80 h-80 bg-brand-800/20 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 md:py-28 relative z-10">
        <div class="grid md:grid-cols-2 gap-12 items-center">
            <div>
                <span class="inline-block bg-brand-500/20 text-brand-300 text-xs font-semibold px-4 py-1.5 rounded-full mb-5 tracking-wider uppercase">
                    New Collection — 2026
                </span>
                <h1 class="font-serif text-4xl sm:text-5xl md:text-6xl font-bold leading-tight mb-6">
                    Elevate Your<br>
                    <span class="text-brand-400">Living Space</span>
                </h1>
                <p class="text-gray-300 text-lg leading-relaxed mb-8 max-w-md">
                    Discover curated home décor inspired by the rich art and culture of Bangladesh. Handcrafted pieces that tell a story.
                </p>
                <div class="flex flex-wrap gap-4">
                    <a href="<?= APP_URL ?>/products"
                        class="btn-brand text-white font-semibold px-7 py-3.5 rounded-xl shadow-lg hover:shadow-xl transition-all text-sm">
                        Shop Collection
                    </a>
                    <a href="<?= APP_URL ?>/about"
                        class="border border-white/30 text-white font-medium px-7 py-3.5 rounded-xl hover:bg-white/10 transition-all text-sm backdrop-blur-sm">
                        Our Story
                    </a>
                </div>
                <!-- Trust badges -->
                <div class="flex flex-wrap gap-6 mt-10 text-xs text-gray-400">
                    <div class="flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-brand-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Free delivery on ৳3,000+
                    </div>
                    <div class="flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-brand-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        30-day returns
                    </div>
                    <div class="flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-brand-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Nationwide delivery
                    </div>
                </div>
            </div>
            <!-- Hero visual -->
            <div class="hidden md:flex justify-center relative">
                <div class="relative w-80 h-80">
                    <div class="absolute inset-0 bg-brand-500/20 rounded-3xl rotate-6"></div>
                    <div class="absolute inset-0 bg-brand-700/20 rounded-3xl -rotate-3"></div>
                    <div class="relative w-full h-full bg-gradient-to-br from-brand-600/30 to-brand-900/30 rounded-3xl flex items-center justify-center border border-white/10">
                        <div class="text-center p-8">
                            <div class="font-serif text-6xl font-bold text-brand-300 mb-2">C</div>
                            <div class="text-white/70 text-sm">Art &amp; Décor</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ═══════════════════════════════════════════════
     FEATURE HIGHLIGHTS
════════════════════════════════════════════════ -->
<section class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            <?php
            $highlights = [
                ['icon' => 'M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4', 'label' => 'Free Delivery', 'sub' => 'Orders over ৳3,000'],
                ['icon' => 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15', 'label' => 'Easy Returns', 'sub' => '30-day policy'],
                ['icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z', 'label' => 'Secure Payment', 'sub' => 'bKash, Card, COD'],
                ['icon' => 'M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z', 'label' => '24/7 Support', 'sub' => 'Always here for you'],
            ];
            foreach ($highlights as $h): ?>
                <div class="flex items-center gap-3.5 group">
                    <div class="w-11 h-11 bg-brand-50 text-brand-600 rounded-xl flex items-center justify-center shrink-0 group-hover:bg-brand-100 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?= $h['icon'] ?>" />
                        </svg>
                    </div>
                    <div>
                        <div class="text-sm font-semibold text-gray-800"><?= $h['label'] ?></div>
                        <div class="text-xs text-gray-400"><?= $h['sub'] ?></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ═══════════════════════════════════════════════
     CATEGORIES
════════════════════════════════════════════════ -->
<?php if (!empty($categories)): ?>
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="flex items-end justify-between mb-8">
            <div>
                <p class="text-brand-600 text-xs font-semibold uppercase tracking-widest mb-1">Browse</p>
                <h2 class="font-serif text-3xl font-bold text-gray-900">Shop by Category</h2>
            </div>
            <a href="<?= APP_URL ?>/categories" class="text-sm text-brand-600 font-medium hover:text-brand-700 flex items-center gap-1">
                View all
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
            <?php foreach ($categories as $cat): ?>
                <a href="<?= APP_URL ?>/products?category=<?= $cat->id ?>"
                    class="group bg-white border border-gray-100 rounded-2xl p-6 text-center card-hover shadow-sm hover:border-brand-200">
                    <div class="w-14 h-14 bg-brand-50 rounded-xl mx-auto mb-3 flex items-center justify-center group-hover:bg-brand-100 transition-colors">
                        <svg class="w-7 h-7 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-800 group-hover:text-brand-600 transition-colors"><?= htmlspecialchars($cat->name) ?></h3>
                    <?php if ($cat->description): ?>
                        <p class="text-xs text-gray-400 mt-1 line-clamp-1"><?= htmlspecialchars($cat->description) ?></p>
                    <?php endif; ?>
                </a>
            <?php endforeach; ?>
        </div>
    </section>
<?php endif; ?>

<!-- ═══════════════════════════════════════════════
     FEATURED PRODUCTS
════════════════════════════════════════════════ -->
<section class="bg-gray-50 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-end justify-between mb-8">
            <div>
                <p class="text-brand-600 text-xs font-semibold uppercase tracking-widest mb-1">Curated</p>
                <h2 class="font-serif text-3xl font-bold text-gray-900">Featured Products</h2>
            </div>
            <a href="<?= APP_URL ?>/products" class="text-sm text-brand-600 font-medium hover:text-brand-700 flex items-center gap-1">
                All products
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        </div>

        <?php if (empty($featured)): ?>
            <div class="text-center py-16 text-gray-400">
                <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
                <p class="text-sm">No products found yet.</p>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-5">
                <?php foreach ($featured as $product):
                    // Use the pre-calculated image_url from the model
                    $imgSrc = $product->image_url ?? DEFAULT_PRODUCT_IMAGE;
                    $price = $product->sale_price ?? $product->regular_price ?? 0;
                    $oldPrice = (!empty($product->sale_price) && $product->sale_price < $product->regular_price) ? $product->regular_price : null;
                ?>
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden card-hover group">
                        <!-- Image -->
                        <a href="<?= APP_URL ?>/product/<?= $product->product_id ?>" class="block relative overflow-hidden aspect-square bg-gray-100">
                            <img src="<?= htmlspecialchars($imgSrc) ?>"
                                alt="<?= htmlspecialchars($product->product_name) ?>"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                onerror="this.src='<?= DEFAULT_PRODUCT_IMAGE ?>'">
                            <?php if ($oldPrice): ?>
                                <span class="absolute top-2.5 left-2.5 bg-brand-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">SALE</span>
                            <?php endif; ?>
                            <!-- Quick view overlay -->
                            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors flex items-end justify-center pb-3 opacity-0 group-hover:opacity-100">
                                <span class="bg-white text-gray-800 text-xs font-semibold px-4 py-1.5 rounded-full shadow">Quick View</span>
                            </div>
                        </a>
                        <!-- Info -->
                        <div class="p-4">
                            <p class="text-[10px] text-brand-500 font-semibold uppercase tracking-wider mb-1"><?= htmlspecialchars($product->category_name ?? '') ?></p>
                            <h3 class="text-sm font-semibold text-gray-800 line-clamp-2 mb-2 leading-snug">
                                <a href="<?= APP_URL ?>/product/<?= $product->product_id ?>" class="hover:text-brand-600 transition-colors">
                                    <?= htmlspecialchars($product->product_name) ?>
                                </a>
                            </h3>
                            <div class="flex items-center justify-between">
                                <div>
                                    <span class="text-base font-bold text-gray-900">৳<?= number_format($price) ?></span>
                                    <?php if ($oldPrice): ?>
                                        <span class="text-xs text-gray-400 line-through ml-1">৳<?= number_format($oldPrice) ?></span>
                                    <?php endif; ?>
                                </div>
                                <button onclick="addToCart(<?= $product->product_id ?>)"
                                    class="w-8 h-8 bg-brand-50 hover:bg-brand-500 text-brand-500 hover:text-white rounded-lg flex items-center justify-center transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- ═══════════════════════════════════════════════
     CTA BANNER
════════════════════════════════════════════════ -->
<section class="bg-gray-900 text-white py-16">
    <div class="max-w-4xl mx-auto px-4 text-center">
        <h2 class="font-serif text-3xl md:text-4xl font-bold mb-4">
            Ready to transform your space?
        </h2>
        <p class="text-gray-400 mb-8 text-lg">Join thousands of happy customers across Bangladesh.</p>
        <div class="flex flex-wrap justify-center gap-4">
            <a href="<?= APP_URL ?>/products"
                class="btn-brand text-white font-semibold px-8 py-3.5 rounded-xl shadow-lg hover:shadow-xl transition-all">
                Explore Shop
            </a>
            <?php if (!isset($_SESSION['user_id'])): ?>
                <a href="<?= APP_URL ?>/register"
                    class="border border-white/30 text-white font-medium px-8 py-3.5 rounded-xl hover:bg-white/10 transition-all">
                    Create Free Account
                </a>
            <?php endif; ?>
        </div>
    </div>
</section>

<script>
    function addToCart(productId) {
        // Cart functionality — extend as needed
        const btn = event.currentTarget;
        btn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>';
        btn.classList.add('bg-green-500', 'text-white');
        setTimeout(() => {
            btn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>';
            btn.classList.remove('bg-green-500', 'text-white');
        }, 1500);
    }
</script>

<?php require APP_ROOT . '/app/views/partials/footer.php'; ?>