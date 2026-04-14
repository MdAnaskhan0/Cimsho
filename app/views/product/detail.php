<?php
$mainImg = $images[0]['image_filename'] ?? null;
$price = null; $regularPrice = null;
foreach($sizes as $s){ if(!$price){ $price=$s['sale_price']??$s['regular_price']; $regularPrice=$s['regular_price']; } }
?>
<div class="max-w-7xl mx-auto px-4 sm:px-6 py-10">

    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 text-xs mb-8" style="color:var(--muted)">
        <a href="<?= APP_URL ?>/" style="text-decoration:none;color:var(--muted)">Home</a>
        <i class="fas fa-chevron-right text-[9px]"></i>
        <a href="<?= APP_URL ?>/shop" style="text-decoration:none;color:var(--muted)">Shop</a>
        <i class="fas fa-chevron-right text-[9px]"></i>
        <span style="color:var(--charcoal)"><?= htmlspecialchars($p['product_name']) ?></span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">

        <!-- Images -->
        <div class="space-y-3">
            <div class="aspect-square rounded-3xl overflow-hidden" style="background:var(--warm)">
                <img id="main-image" src="<?= $mainImg ? UPLOAD_URL.htmlspecialchars($mainImg) : '' ?>" alt="<?= htmlspecialchars($p['product_name']) ?>" class="w-full h-full object-cover img-zoom">
            </div>
            <?php if(count($images)>1): ?>
            <div class="flex gap-3 overflow-x-auto pb-2">
                <?php foreach($images as $i=>$img): ?>
                <button onclick="switchImage('<?= UPLOAD_URL.htmlspecialchars($img['image_filename']) ?>')" class="flex-shrink-0 w-20 h-20 rounded-xl overflow-hidden border-2 transition-all <?= $i===0?'border-[var(--clay)]':'border-transparent hover:border-[var(--clay)]' ?>" style="background:var(--warm)">
                    <img src="<?= UPLOAD_URL.htmlspecialchars($img['image_filename']) ?>" class="w-full h-full object-cover" alt="">
                </button>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>

        <!-- Details -->
        <div>
            <!-- Badge row -->
            <div class="flex items-center gap-2 mb-3">
                <?php if($p['brand']): ?><span class="text-xs font-medium uppercase tracking-widest" style="color:var(--clay)"><?= htmlspecialchars($p['brand']) ?></span><?php endif; ?>
                <?php if($p['product_stock']>0): ?>
                <span class="inline-flex items-center gap-1 text-xs font-medium px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700"><i class="fas fa-check text-[9px]"></i>In Stock</span>
                <?php else: ?>
                <span class="inline-flex items-center gap-1 text-xs font-medium px-2.5 py-1 rounded-full bg-red-50 text-red-600">Out of Stock</span>
                <?php endif; ?>
            </div>

            <h1 class="serif text-3xl sm:text-4xl font-light leading-snug mb-3" style="color:var(--charcoal)"><?= htmlspecialchars($p['product_name']) ?></h1>

            <!-- Rating -->
            <?php if($avgRating > 0): ?>
            <div class="flex items-center gap-2 mb-4">
                <?php for($i=1;$i<=5;$i++): ?>
                <i class="fas fa-star text-sm <?= $i<=$avgRating?'star-filled':'star-empty' ?>"></i>
                <?php endfor; ?>
                <span class="text-sm" style="color:var(--muted)"><?= $avgRating ?> (<?= count($reviews) ?> reviews)</span>
            </div>
            <?php endif; ?>

            <!-- Price display -->
            <div id="price-display" class="flex items-baseline gap-3 mb-6">
                <span class="serif text-4xl font-semibold" style="color:var(--clay)"><?= CURRENCY_SYMBOL.number_format($price??0,0) ?></span>
                <?php if($regularPrice && $price < $regularPrice): ?>
                <span class="text-xl line-through" style="color:var(--muted)"><?= CURRENCY_SYMBOL.number_format($regularPrice,0) ?></span>
                <?php endif; ?>
            </div>

            <!-- Size selector -->
            <?php if(!empty($sizes)): ?>
            <div class="mb-5">
                <p class="text-xs font-semibold uppercase tracking-wider mb-3" style="color:var(--muted)">Select Size</p>
                <div class="flex flex-wrap gap-2" id="size-options">
                    <?php foreach($sizes as $s): ?>
                    <button onclick="selectSize(this, <?= htmlspecialchars(json_encode($s)) ?>)"
                            class="size-btn px-4 py-2.5 rounded-xl border text-sm font-medium transition-all"
                            style="border-color:var(--border);color:var(--charcoal)"
                            data-size="<?= htmlspecialchars($s['size_name']) ?>"
                            data-price="<?= $s['sale_price']??$s['regular_price'] ?>"
                            data-regular="<?= $s['regular_price'] ?>">
                        <span class="font-semibold"><?= htmlspecialchars($s['size_name']) ?></span>
                        <?php if($s['width']&&$s['height']): ?>
                        <span class="text-xs block" style="color:var(--muted)"><?= $s['width'] ?>×<?= $s['height'] ?>cm</span>
                        <?php endif; ?>
                    </button>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Color selector -->
            <?php if(!empty($colors)): ?>
            <div class="mb-6">
                <p class="text-xs font-semibold uppercase tracking-wider mb-3" style="color:var(--muted)">Select Color — <span id="selected-color-name" class="font-normal normal-case tracking-normal"><?= htmlspecialchars($colors[0]['color_name']) ?></span></p>
                <div class="flex gap-2.5">
                    <?php foreach($colors as $i=>$c): ?>
                    <button onclick="selectColor(this, '<?= htmlspecialchars($c['color_name']) ?>')"
                            class="color-btn w-9 h-9 rounded-full border-2 transition-all <?= $i===0?'border-[var(--clay)] scale-110':'border-transparent hover:scale-110' ?>"
                            style="background:<?= htmlspecialchars($c['color_code']??'#ccc') ?>;box-shadow:0 0 0 1px var(--border)"
                            data-color="<?= htmlspecialchars($c['color_name']) ?>"
                            title="<?= htmlspecialchars($c['color_name']) ?>">
                    </button>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Qty + Add to Cart -->
            <div class="flex items-center gap-3 mb-6">
                <div class="flex items-center border rounded-xl overflow-hidden" style="border-color:var(--border)">
                    <button onclick="changeQty(-1)" class="w-10 h-11 flex items-center justify-center hover:bg-[var(--warm)] transition-colors text-lg">−</button>
                    <input type="number" id="qty-input" value="1" min="1" max="<?= $p['product_stock'] ?>" class="w-12 h-11 text-center text-sm font-semibold border-0 outline-none">
                    <button onclick="changeQty(1)" class="w-10 h-11 flex items-center justify-center hover:bg-[var(--warm)] transition-colors text-lg">+</button>
                </div>
                <button id="add-to-cart-btn" onclick="doAddToCart()" class="btn-clay flex-1 h-11 rounded-xl text-sm font-semibold flex items-center justify-center gap-2 <?= $p['product_stock']<1?'opacity-50 pointer-events-none':'' ?>">
                    <i class="fas fa-bag-shopping text-xs"></i>
                    <?= $p['product_stock']>0 ? 'Add to Cart' : 'Out of Stock' ?>
                </button>
            </div>

            <!-- Product info -->
            <div class="pt-5 border-t space-y-2.5 text-sm" style="border-color:var(--border)">
                <?php if($p['material']): ?><div class="flex gap-3"><span style="color:var(--muted);width:80px">Material</span><span style="color:var(--charcoal)"><?= htmlspecialchars($p['material']) ?></span></div><?php endif; ?>
                <?php if($p['sku']): ?><div class="flex gap-3"><span style="color:var(--muted);width:80px">SKU</span><span class="mono" style="color:var(--charcoal)"><?= htmlspecialchars($p['sku']) ?></span></div><?php endif; ?>
                <?php if($p['category_name']): ?><div class="flex gap-3"><span style="color:var(--muted);width:80px">Category</span><span style="color:var(--charcoal)"><?= htmlspecialchars($p['category_name']) ?></span></div><?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Description + Reviews -->
    <div class="mt-16 grid grid-cols-1 lg:grid-cols-3 gap-10">

        <!-- Description -->
        <div class="lg:col-span-2">
            <h2 class="serif text-2xl font-light mb-5" style="color:var(--charcoal)">Product Description</h2>
            <div class="text-sm leading-relaxed" style="color:var(--muted)">
                <?= nl2br(htmlspecialchars($p['product_description']??'')) ?>
            </div>

            <!-- Reviews -->
            <div class="mt-12">
                <h2 class="serif text-2xl font-light mb-6" style="color:var(--charcoal)">Customer Reviews</h2>

                <?php if(!empty($_SESSION['user_id'])): ?>
                <div class="bg-white rounded-2xl p-5 border mb-6" style="border-color:var(--border)">
                    <h3 class="font-semibold text-sm mb-4">Write a Review</h3>
                    <div class="mb-4">
                        <p class="text-xs text-[var(--muted)] mb-2">Your Rating</p>
                        <div class="flex gap-1" id="star-input">
                            <?php for($i=1;$i<=5;$i++): ?>
                            <button onclick="setRating(<?= $i ?>)" class="star-btn text-2xl transition-colors" style="color:#e5e7eb" data-val="<?= $i ?>">★</button>
                            <?php endfor; ?>
                        </div>
                        <input type="hidden" id="review-rating" value="5">
                    </div>
                    <textarea id="review-text" rows="3" placeholder="Share your experience with this product…" class="form-input w-full px-4 py-3 rounded-xl text-sm mb-3"></textarea>
                    <button onclick="submitReview(<?= $p['product_id'] ?>)" class="btn-clay px-5 py-2.5 rounded-xl text-sm font-medium">
                        Submit Review
                    </button>
                </div>
                <?php else: ?>
                <div class="bg-[var(--warm)] rounded-2xl p-5 mb-6 text-center">
                    <p class="text-sm mb-3" style="color:var(--muted)">Please sign in to leave a review.</p>
                    <a href="<?= APP_URL ?>/account/login" class="btn-clay px-5 py-2 rounded-xl text-sm font-medium inline-block" style="text-decoration:none">Sign In</a>
                </div>
                <?php endif; ?>

                <?php if(empty($reviews)): ?>
                <p class="text-sm text-center py-8" style="color:var(--muted)">No reviews yet. Be the first to review this product!</p>
                <?php else: ?>
                <div class="space-y-4" id="reviews-list">
                    <?php foreach($reviews as $rev): ?>
                    <div class="bg-white rounded-2xl p-5 border" style="border-color:var(--border)">
                        <div class="flex items-start justify-between mb-2">
                            <div>
                                <p class="font-semibold text-sm"><?= htmlspecialchars($rev['user_name']??'Anonymous') ?></p>
                                <p class="text-xs" style="color:var(--muted)"><?= date('d M Y', strtotime($rev['created_at'])) ?></p>
                            </div>
                            <div class="flex gap-0.5">
                                <?php for($i=1;$i<=5;$i++): ?><i class="fas fa-star text-xs <?= $i<=$rev['rating']?'star-filled':'star-empty' ?>"></i><?php endfor; ?>
                            </div>
                        </div>
                        <p class="text-sm" style="color:var(--muted)"><?= htmlspecialchars($rev['review']??'') ?></p>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Shipping info sidebar -->
        <div class="space-y-4">
            <div class="bg-white rounded-2xl p-5 border" style="border-color:var(--border)">
                <h3 class="font-semibold text-sm mb-4">Shipping & Delivery</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex items-start gap-3"><i class="fas fa-truck mt-0.5" style="color:var(--clay)"></i><div><p class="font-medium">Standard Delivery</p><p style="color:var(--muted)">3–5 business days, ৳60 inside Dhaka</p></div></div>
                    <div class="flex items-start gap-3"><i class="fas fa-shipping-fast mt-0.5" style="color:var(--clay)"></i><div><p class="font-medium">Outside Dhaka</p><p style="color:var(--muted)">5–7 days, ৳120</p></div></div>
                    <div class="flex items-start gap-3"><i class="fas fa-gift mt-0.5" style="color:var(--clay)"></i><div><p class="font-medium">Free Delivery</p><p style="color:var(--muted)">On orders above ৳3,000</p></div></div>
                </div>
            </div>
            <div class="bg-white rounded-2xl p-5 border" style="border-color:var(--border)">
                <h3 class="font-semibold text-sm mb-4">Payment Options</h3>
                <div class="grid grid-cols-3 gap-2">
                    <?php foreach(['bKash','Nagad','COD'] as $pm): ?>
                    <div class="text-center py-2 px-1 rounded-lg text-xs font-medium" style="background:var(--warm)"><?= $pm ?></div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    <?php if(!empty($related)): ?>
    <div class="mt-16">
        <h2 class="serif text-3xl font-light mb-8" style="color:var(--charcoal)">You May Also Like</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-5">
            <?php foreach($related as $r):
                $ri = !empty($r['image_filename'])?UPLOAD_URL.$r['image_filename']:null;
            ?>
            <a href="<?= APP_URL ?>/product/<?= $r['product_id'] ?>" class="product-card bg-white rounded-2xl overflow-hidden border" style="border-color:var(--border);text-decoration:none">
                <div class="aspect-square overflow-hidden" style="background:var(--warm)">
                    <?php if($ri): ?><img src="<?= htmlspecialchars($ri) ?>" class="w-full h-full object-cover img-zoom" alt=""><?php else: ?><div class="w-full h-full flex items-center justify-center"><i class="fas fa-image text-3xl" style="color:var(--clay)"></i></div><?php endif; ?>
                </div>
                <div class="p-3">
                    <p class="text-xs font-medium line-clamp-2 mb-1" style="color:var(--charcoal)"><?= htmlspecialchars($r['product_name']) ?></p>
                    <p class="text-sm font-semibold" style="color:var(--clay)"><?= CURRENCY_SYMBOL.number_format($r['min_price']??0,0) ?></p>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<script>
let selectedSize = <?= !empty($sizes)?json_encode($sizes[0]):json_encode(null) ?>;
let selectedColor = '<?= !empty($colors)?htmlspecialchars($colors[0]['color_name']??'',ENT_QUOTES):'' ?>';
let selectedRating = 5;

function switchImage(src){ document.getElementById('main-image').src=src; }

function selectSize(el, sizeData){
    document.querySelectorAll('.size-btn').forEach(b=>{ b.style.background='white'; b.style.borderColor='var(--border)'; b.style.color='var(--charcoal)'; });
    el.style.background='var(--clay)'; el.style.borderColor='var(--clay)'; el.style.color='white';
    selectedSize = sizeData;
    const p = sizeData.sale_price??sizeData.regular_price;
    const r = sizeData.regular_price;
    document.getElementById('price-display').innerHTML = `<span class="serif text-4xl font-semibold" style="color:var(--clay)"><?= CURRENCY_SYMBOL ?>${Number(p).toLocaleString()}</span>${(r&&p<r)?`<span class="text-xl line-through" style="color:var(--muted)"><?= CURRENCY_SYMBOL ?>${Number(r).toLocaleString()}</span>`:''}`;
}
// Auto-select first size
<?php if(!empty($sizes)): ?>document.querySelector('.size-btn')?.click();<?php endif; ?>

function selectColor(el, name){
    document.querySelectorAll('.color-btn').forEach(b=>{ b.style.borderColor='transparent'; b.style.transform='scale(1)'; });
    el.style.borderColor='var(--clay)'; el.style.transform='scale(1.15)';
    selectedColor=name;
    document.getElementById('selected-color-name').textContent=name;
}

function changeQty(d){
    const el=document.getElementById('qty-input');
    const max=parseInt(el.max)||99;
    el.value=Math.max(1,Math.min(max,parseInt(el.value)+d));
}

function doAddToCart(){
    if(!selectedSize){ showToast('Please select a size.','warning'); return; }
    addToCart({
        product_id: <?= $p['product_id'] ?>,
        size: selectedSize.size_name,
        color: selectedColor,
        qty: document.getElementById('qty-input').value,
        price: selectedSize.sale_price??selectedSize.regular_price
    });
}

// Star rating input
function setRating(val){
    selectedRating=val;
    document.getElementById('review-rating').value=val;
    document.querySelectorAll('.star-btn').forEach((b,i)=>{ b.style.color=i<val?'#f59e0b':'#e5e7eb'; });
}
setRating(5);

async function submitReview(pid){
    const text=document.getElementById('review-text').value.trim();
    if(!text){ showToast('Please write your review.','warning'); return; }
    const fd=new FormData();
    fd.append('product_id',pid); fd.append('rating',selectedRating); fd.append('review',text);
    const res=await fetch(APP_URL+'/shop/review',{method:'POST',body:fd});
    const d=await res.json();
    showToast(d.message,d.success?'success':'error');
    if(d.success){ document.getElementById('review-text').value=''; setRating(5); }
}
</script>
