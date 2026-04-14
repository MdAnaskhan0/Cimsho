<!-- Hero -->
<section class="relative overflow-hidden" style="background:linear-gradient(135deg,#2c2c2c 0%,#3d3530 60%,#4a3728 100%);min-height:88vh">
    <div class="absolute inset-0 opacity-10" style="background-image:url('data:image/svg+xml,<svg width=\"60\" height=\"60\" viewBox=\"0 0 60 60\" xmlns=\"http://www.w3.org/2000/svg\"><g fill=\"none\" fill-rule=\"evenodd\"><g fill=\"%23ffffff\" fill-opacity=\"1\"><path d=\"M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\"/></g></g></svg>')"></div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 flex flex-col lg:flex-row items-center min-h-[88vh] gap-12 py-20">
        <div class="lg:w-1/2 text-center lg:text-left">
            <span class="inline-block text-xs font-medium uppercase tracking-widest mb-5 px-4 py-1.5 rounded-full" style="background:rgba(196,149,106,.15);color:var(--clay)">✦ Artisan Home Decor</span>
            <h1 class="serif text-5xl sm:text-6xl lg:text-7xl font-light text-white leading-tight mb-6">
                Walls that<br>
                <em class="not-italic font-semibold" style="color:var(--clay)">tell stories</em>
            </h1>
            <p class="text-base sm:text-lg leading-relaxed mb-8 max-w-lg" style="color:#9ca3af">
                Handcrafted wooden wall art celebrating Bangladesh's rich cultural heritage. Each piece a unique conversation starter.
            </p>
            <div class="flex flex-col sm:flex-row gap-3 justify-center lg:justify-start">
                <a href="<?= APP_URL ?>/shop" class="btn-clay px-8 py-3.5 rounded-full text-sm font-semibold inline-flex items-center gap-2" style="text-decoration:none">
                    Explore Collection <i class="fas fa-arrow-right text-xs"></i>
                </a>
                <a href="<?= APP_URL ?>/order/track" class="btn-outline px-8 py-3.5 rounded-full text-sm font-semibold inline-flex items-center gap-2" style="text-decoration:none;border-color:rgba(255,255,255,.3);color:rgba(255,255,255,.8)">
                    <i class="fas fa-truck text-xs"></i> Track Order
                </a>
            </div>
            <div class="flex items-center gap-8 mt-10 justify-center lg:justify-start">
                <?php foreach([['50+','Products'],['2K+','Happy Customers'],['4.9','Rating']] as [$v,$l]): ?>
                <div class="text-center">
                    <div class="serif text-3xl font-semibold text-white"><?= $v ?></div>
                    <div class="text-xs mt-0.5" style="color:#9ca3af"><?= $l ?></div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="lg:w-1/2 flex justify-center">
            <div class="relative w-72 h-72 sm:w-96 sm:h-96">
                <div class="absolute inset-0 rounded-full opacity-30" style="background:radial-gradient(circle,var(--clay),transparent)"></div>
                <div class="absolute inset-6 rounded-3xl overflow-hidden shadow-2xl" style="background:var(--warm)">
                    <?php if(!empty($featured[0]['image_filename'])): ?>
                    <img src="<?= UPLOAD_URL.htmlspecialchars($featured[0]['image_filename']) ?>" alt="Featured" class="w-full h-full object-cover img-zoom">
                    <?php else: ?>
                    <div class="w-full h-full flex items-center justify-center">
                        <i class="fas fa-image text-6xl" style="color:var(--clay)"></i>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Category tiles -->
<?php if(!empty($cats)): ?>
<section class="max-w-7xl mx-auto px-4 sm:px-6 py-16">
    <div class="text-center mb-10">
        <p class="text-xs font-semibold uppercase tracking-widest mb-2" style="color:var(--clay)">Browse by Category</p>
        <h2 class="serif text-4xl font-light" style="color:var(--charcoal)">Shop Collections</h2>
    </div>
    <div class="grid grid-cols-2 md:grid-cols-<?= min(count($cats),4) ?> gap-4">
        <?php foreach($cats as $cat): ?>
        <a href="<?= APP_URL ?>/shop?category=<?= $cat['id'] ?>" class="group relative overflow-hidden rounded-2xl aspect-video flex items-end p-5" style="background:var(--charcoal);text-decoration:none">
            <div class="absolute inset-0 opacity-40 group-hover:opacity-60 transition-opacity" style="background:linear-gradient(135deg,var(--clay),var(--charcoal))"></div>
            <div class="relative z-10">
                <p class="text-white font-semibold text-base leading-tight"><?= htmlspecialchars($cat['name']) ?></p>
                <p class="text-xs mt-1 group-hover:translate-x-1 transition-transform inline-flex items-center gap-1" style="color:rgba(255,255,255,.7)">
                    Shop now <i class="fas fa-arrow-right text-[9px]"></i>
                </p>
            </div>
        </a>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<!-- Featured Products -->
<?php if(!empty($latest)): ?>
<section class="py-16" style="background:var(--warm)">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <div class="flex items-end justify-between mb-10">
            <div>
                <p class="text-xs font-semibold uppercase tracking-widest mb-2" style="color:var(--clay)">Our Collection</p>
                <h2 class="serif text-4xl font-light" style="color:var(--charcoal)">Latest Pieces</h2>
            </div>
            <a href="<?= APP_URL ?>/shop" class="hidden sm:inline-flex items-center gap-2 text-sm font-medium hover:gap-3 transition-all" style="color:var(--clay);text-decoration:none">
                View All <i class="fas fa-arrow-right text-xs"></i>
            </a>
        </div>
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-5">
            <?php foreach($latest as $prod):
                $img = !empty($prod['image_filename']) ? UPLOAD_URL.$prod['image_filename'] : null;
                $price = $prod['sale_price'] ?? $prod['regular_price'] ?? $prod['min_price'] ?? 0;
                $regular = $prod['regular_price'] ?? 0;
                $onSale = $prod['sale_price'] && $prod['sale_price'] < $regular;
            ?>
            <a href="<?= APP_URL ?>/product/<?= $prod['product_id'] ?>" class="product-card bg-white rounded-2xl overflow-hidden block" style="text-decoration:none">
                <div class="relative aspect-square overflow-hidden" style="background:var(--warm)">
                    <?php if($img): ?>
                    <img src="<?= htmlspecialchars($img) ?>" alt="<?= htmlspecialchars($prod['product_name']) ?>" class="w-full h-full object-cover img-zoom">
                    <?php else: ?>
                    <div class="w-full h-full flex items-center justify-center"><i class="fas fa-image text-4xl" style="color:var(--clay)"></i></div>
                    <?php endif; ?>
                    <?php if($onSale): ?>
                    <span class="absolute top-3 left-3 text-xs font-semibold text-white px-2.5 py-1 rounded-full" style="background:var(--clay)">Sale</span>
                    <?php endif; ?>
                    <div class="overlay absolute inset-0 flex items-center justify-center" style="background:rgba(44,44,44,.4)">
                        <span class="text-white text-xs font-semibold uppercase tracking-wider px-4 py-2 rounded-full border border-white">Quick View</span>
                    </div>
                </div>
                <div class="p-4">
                    <p class="text-xs mb-1" style="color:var(--muted)"><?= htmlspecialchars($prod['category_name']??'') ?></p>
                    <h3 class="font-medium text-sm leading-snug mb-2 line-clamp-2" style="color:var(--charcoal)"><?= htmlspecialchars($prod['product_name']) ?></h3>
                    <div class="flex items-center gap-2">
                        <span class="font-semibold" style="color:var(--clay)"><?= CURRENCY_SYMBOL.number_format($price,0) ?></span>
                        <?php if($onSale): ?>
                        <span class="text-xs line-through" style="color:var(--muted)"><?= CURRENCY_SYMBOL.number_format($regular,0) ?></span>
                        <?php endif; ?>
                    </div>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Value Props -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 py-16">
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
        <?php foreach([
            ['fa-truck','Fast Delivery','3–5 business days across Bangladesh'],
            ['fa-shield-halved','Secure Payment','bKash, Nagad & Cash on Delivery'],
            ['fa-rotate-left','Easy Returns','30-day return for unused items'],
            ['fa-headset','24/7 Support','We\'re always here to help'],
        ] as [$icon,$title,$desc]): ?>
        <div class="text-center p-6 rounded-2xl" style="background:var(--warm)">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center mx-auto mb-4" style="background:rgba(196,149,106,.15)">
                <i class="fas <?= $icon ?> text-lg" style="color:var(--clay)"></i>
            </div>
            <h4 class="font-semibold text-sm mb-1"><?= $title ?></h4>
            <p class="text-xs leading-relaxed" style="color:var(--muted)"><?= $desc ?></p>
        </div>
        <?php endforeach; ?>
    </div>
</section>
