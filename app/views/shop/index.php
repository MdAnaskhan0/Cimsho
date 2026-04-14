<div class="max-w-7xl mx-auto px-4 sm:px-6 py-10">

    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 text-xs mb-6" style="color:var(--muted)">
        <a href="<?= APP_URL ?>/" style="text-decoration:none;color:var(--muted)">Home</a>
        <i class="fas fa-chevron-right text-[9px]"></i>
        <span style="color:var(--charcoal)">Shop</span>
    </div>

    <div class="flex flex-col lg:flex-row gap-8">

        <!-- Sidebar Filters -->
        <aside class="lg:w-64 flex-shrink-0">
            <div class="sticky top-24 bg-white rounded-2xl p-5 border" style="border-color:var(--border)">
                <h3 class="font-semibold text-sm uppercase tracking-wider mb-5" style="color:var(--charcoal)">Filter By</h3>

                <!-- Search -->
                <form action="<?= APP_URL ?>/shop" method="GET" class="mb-5">
                    <div class="relative">
                        <input type="text" name="q" placeholder="Search…" value="<?= htmlspecialchars($filters['search']??'') ?>"
                               class="form-input w-full pl-9 pr-3 py-2.5 rounded-xl text-sm">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-xs" style="color:var(--muted)"></i>
                    </div>
                </form>

                <!-- Categories -->
                <div class="mb-5">
                    <p class="text-xs font-semibold uppercase tracking-wider mb-3" style="color:var(--muted)">Categories</p>
                    <div class="space-y-1">
                        <a href="<?= APP_URL ?>/shop" class="flex items-center justify-between px-3 py-2 rounded-xl text-sm transition-colors <?= empty($filters['category_id'])?'font-semibold':'hover:bg-[var(--warm)]' ?>" style="<?= empty($filters['category_id'])?'background:var(--warm);color:var(--clay)':'color:var(--charcoal)' ?>;text-decoration:none">
                            All Products
                        </a>
                        <?php foreach($cats as $cat):
                            $subs = $cat['subs'] ? explode('|',$cat['subs']) : [];
                            $isActive = (int)($filters['category_id']??0) === (int)$cat['id'];
                        ?>
                        <a href="<?= APP_URL ?>/shop?category=<?= $cat['id'] ?>" class="flex items-center justify-between px-3 py-2 rounded-xl text-sm transition-colors <?= $isActive?'font-semibold':'hover:bg-[var(--warm)]' ?>" style="<?= $isActive?'background:var(--warm);color:var(--clay)':'color:var(--charcoal)' ?>;text-decoration:none">
                            <?= htmlspecialchars($cat['name']) ?>
                        </a>
                        <?php if($isActive && !empty($subs)): ?>
                        <div class="pl-4 space-y-0.5">
                            <?php foreach($subs as $sub): [$sid,$sname,$sslug]=explode(':',$sub,3)+['','',''];
                                $subActive = (int)($filters['sub_category_id']??0)===(int)$sid;
                            ?>
                            <a href="<?= APP_URL ?>/shop?category=<?= $cat['id'] ?>&sub=<?= $sid ?>" class="flex items-center gap-2 px-3 py-1.5 rounded-lg text-xs transition-colors <?= $subActive?'font-semibold':'hover:bg-[var(--warm)]' ?>" style="<?= $subActive?'color:var(--clay)':'color:var(--muted)' ?>;text-decoration:none">
                                <i class="fas fa-circle text-[5px]"></i> <?= htmlspecialchars($sname) ?>
                            </a>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>

                <?php if(!empty($filters)): ?>
                <a href="<?= APP_URL ?>/shop" class="block text-center text-xs font-medium py-2 rounded-xl transition-colors" style="color:var(--clay);text-decoration:none">
                    <i class="fas fa-xmark mr-1"></i> Clear Filters
                </a>
                <?php endif; ?>
            </div>
        </aside>

        <!-- Product Grid -->
        <div class="flex-1">
            <div class="flex items-center justify-between mb-6">
                <p class="text-sm" style="color:var(--muted)">
                    Showing <strong style="color:var(--charcoal)"><?= count($products) ?></strong> of <strong style="color:var(--charcoal)"><?= $total ?></strong> products
                </p>
                <?php if(!empty($filters['search'])): ?>
                <p class="text-sm" style="color:var(--muted)">Results for "<strong><?= htmlspecialchars($filters['search']) ?></strong>"</p>
                <?php endif; ?>
            </div>

            <?php if(empty($products)): ?>
            <div class="flex flex-col items-center justify-center py-24 text-center">
                <div class="w-16 h-16 rounded-2xl flex items-center justify-center mb-4" style="background:var(--warm)">
                    <i class="fas fa-box-open text-2xl" style="color:var(--clay)"></i>
                </div>
                <h3 class="font-semibold text-lg mb-1">No products found</h3>
                <p class="text-sm mb-5" style="color:var(--muted)">Try adjusting your filters or search terms.</p>
                <a href="<?= APP_URL ?>/shop" class="btn-clay px-6 py-2.5 rounded-full text-sm font-medium" style="text-decoration:none">View All Products</a>
            </div>
            <?php else: ?>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-5">
                <?php foreach($products as $prod):
                    $img = !empty($prod['image_filename']) ? UPLOAD_URL.$prod['image_filename'] : null;
                    $price = $prod['sale_price'] ?? $prod['regular_price'] ?? $prod['min_price'] ?? 0;
                    $regular = $prod['regular_price'] ?? 0;
                    $onSale = $prod['sale_price'] && $prod['sale_price'] < $regular;
                ?>
                <a href="<?= APP_URL ?>/product/<?= $prod['product_id'] ?>" class="product-card bg-white rounded-2xl overflow-hidden block border" style="border-color:var(--border);text-decoration:none">
                    <div class="relative overflow-hidden" style="aspect-ratio:1;background:var(--warm)">
                        <?php if($img): ?>
                        <img src="<?= htmlspecialchars($img) ?>" alt="<?= htmlspecialchars($prod['product_name']) ?>" class="w-full h-full object-cover img-zoom">
                        <?php else: ?>
                        <div class="w-full h-full flex items-center justify-center"><i class="fas fa-image text-3xl" style="color:var(--clay)"></i></div>
                        <?php endif; ?>
                        <?php if($onSale): ?>
                        <span class="absolute top-2.5 left-2.5 text-[10px] font-semibold text-white px-2 py-0.5 rounded-full" style="background:var(--clay)">Sale</span>
                        <?php endif; ?>
                        <?php if($prod['product_stock']<1): ?>
                        <div class="absolute inset-0 flex items-center justify-center" style="background:rgba(255,255,255,.75)">
                            <span class="font-semibold text-xs px-3 py-1 rounded-full bg-gray-200 text-gray-500">Out of Stock</span>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="p-4">
                        <p class="text-[10px] font-medium uppercase tracking-wide mb-1" style="color:var(--clay)"><?= htmlspecialchars($prod['brand']??'Cimsho') ?></p>
                        <h3 class="font-medium text-sm leading-snug mb-2 line-clamp-2" style="color:var(--charcoal)"><?= htmlspecialchars($prod['product_name']) ?></h3>
                        <div class="flex items-center gap-2">
                            <span class="font-semibold text-sm" style="color:var(--clay)"><?= CURRENCY_SYMBOL.number_format($price,0) ?></span>
                            <?php if($onSale): ?>
                            <span class="text-xs line-through" style="color:var(--muted)"><?= CURRENCY_SYMBOL.number_format($regular,0) ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>

            <!-- Pagination -->
            <?php if($pages > 1): ?>
            <div class="flex items-center justify-center gap-2 mt-10">
                <?php
                $q = http_build_query(array_merge($_GET,['page'=>max(1,$page-1)]));
                $qn= http_build_query(array_merge($_GET,['page'=>min($pages,$page+1)]));
                ?>
                <a href="<?= APP_URL ?>/shop?<?= $q ?>" class="w-9 h-9 rounded-xl flex items-center justify-center border text-sm <?= $page<=1?'opacity-40 pointer-events-none':'' ?>" style="border-color:var(--border);text-decoration:none">
                    <i class="fas fa-chevron-left text-xs"></i>
                </a>
                <?php for($i=1;$i<=$pages;$i++): $qi=http_build_query(array_merge($_GET,['page'=>$i])); ?>
                <a href="<?= APP_URL ?>/shop?<?= $qi ?>" class="w-9 h-9 rounded-xl flex items-center justify-center text-sm font-medium" style="<?= $i===$page?'background:var(--clay);color:white':'border:1px solid var(--border);color:var(--charcoal)' ?>;text-decoration:none"><?= $i ?></a>
                <?php endfor; ?>
                <a href="<?= APP_URL ?>/shop?<?= $qn ?>" class="w-9 h-9 rounded-xl flex items-center justify-center border text-sm <?= $page>=$pages?'opacity-40 pointer-events-none':'' ?>" style="border-color:var(--border);text-decoration:none">
                    <i class="fas fa-chevron-right text-xs"></i>
                </a>
            </div>
            <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
