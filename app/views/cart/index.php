<?php
$subtotal = 0;
foreach($items as $item) $subtotal += $item['subtotal'];
?>
<div class="max-w-6xl mx-auto px-4 sm:px-6 py-10">

    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 text-xs mb-8" style="color:var(--muted)">
        <a href="<?= APP_URL ?>/" style="text-decoration:none;color:var(--muted)">Home</a>
        <i class="fas fa-chevron-right text-[9px]"></i>
        <span style="color:var(--charcoal)">Shopping Cart</span>
    </div>

    <h1 class="serif text-4xl font-light mb-8" style="color:var(--charcoal)">Shopping Cart
        <span class="text-base font-normal ml-2" style="color:var(--muted)">(<?= count($items) ?> <?= count($items)===1?'item':'items' ?>)</span>
    </h1>

    <?php if(empty($items)): ?>
    <div class="flex flex-col items-center justify-center py-24 text-center">
        <div class="w-20 h-20 rounded-3xl flex items-center justify-center mb-5" style="background:var(--warm)">
            <i class="fas fa-bag-shopping text-3xl" style="color:var(--clay)"></i>
        </div>
        <h2 class="serif text-3xl font-light mb-2">Your cart is empty</h2>
        <p class="text-sm mb-7" style="color:var(--muted)">Discover our beautiful collection of artisan home decor.</p>
        <a href="<?= APP_URL ?>/shop" class="btn-clay px-8 py-3.5 rounded-full text-sm font-semibold" style="text-decoration:none">
            <i class="fas fa-store mr-2"></i>Browse Products
        </a>
    </div>

    <?php else: ?>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <!-- Cart Items -->
        <div class="lg:col-span-2 space-y-4" id="cart-items-container">
            <?php foreach($items as $key=>$item):
                $img = !empty($item['image']['image_filename']) ? UPLOAD_URL.$item['image']['image_filename'] : null;
            ?>
            <div id="item-<?= htmlspecialchars($key) ?>" class="bg-white rounded-2xl p-4 border flex gap-4" style="border-color:var(--border)">
                <!-- Image -->
                <div class="w-24 h-24 sm:w-28 sm:h-28 rounded-xl overflow-hidden flex-shrink-0" style="background:var(--warm)">
                    <?php if($img): ?>
                    <img src="<?= htmlspecialchars($img) ?>" class="w-full h-full object-cover" alt="">
                    <?php else: ?>
                    <div class="w-full h-full flex items-center justify-center"><i class="fas fa-image text-2xl" style="color:var(--clay)"></i></div>
                    <?php endif; ?>
                </div>

                <!-- Info -->
                <div class="flex-1 min-w-0">
                    <a href="<?= APP_URL ?>/product/<?= $item['product']['product_id'] ?>" class="font-medium text-sm leading-snug hover:text-[var(--clay)] transition-colors line-clamp-2" style="color:var(--charcoal);text-decoration:none">
                        <?= htmlspecialchars($item['product']['product_name']) ?>
                    </a>
                    <div class="flex flex-wrap gap-2 mt-1.5 mb-3">
                        <span class="text-xs px-2 py-0.5 rounded-md" style="background:var(--warm);color:var(--muted)">Size: <?= htmlspecialchars($item['size']) ?></span>
                        <?php if($item['color']): ?><span class="text-xs px-2 py-0.5 rounded-md" style="background:var(--warm);color:var(--muted)">Color: <?= htmlspecialchars($item['color']) ?></span><?php endif; ?>
                    </div>
                    <div class="flex items-center justify-between flex-wrap gap-3">
                        <!-- Qty -->
                        <div class="flex items-center border rounded-xl overflow-hidden" style="border-color:var(--border)">
                            <button onclick="updateQty('<?= htmlspecialchars($key) ?>', -1)" class="w-8 h-8 flex items-center justify-center hover:bg-[var(--warm)] transition-colors text-base">−</button>
                            <span id="qty-<?= htmlspecialchars($key) ?>" class="w-10 text-center text-sm font-semibold"><?= $item['qty'] ?></span>
                            <button onclick="updateQty('<?= htmlspecialchars($key) ?>', 1)" class="w-8 h-8 flex items-center justify-center hover:bg-[var(--warm)] transition-colors text-base">+</button>
                        </div>
                        <div class="flex items-center gap-4">
                            <span id="sub-<?= htmlspecialchars($key) ?>" class="font-semibold text-sm" style="color:var(--clay)"><?= CURRENCY_SYMBOL.number_format($item['subtotal'],0) ?></span>
                            <button onclick="removeItem('<?= htmlspecialchars($key) ?>')" class="text-xs hover:text-red-500 transition-colors flex items-center gap-1" style="color:var(--muted)">
                                <i class="fas fa-trash text-[10px]"></i> Remove
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Order Summary -->
        <div class="space-y-4">
            <div class="bg-white rounded-2xl p-6 border sticky top-24" style="border-color:var(--border)">
                <h2 class="font-bold text-base mb-5" style="color:var(--charcoal)">Order Summary</h2>

                <div class="space-y-3 text-sm mb-5">
                    <div class="flex justify-between">
                        <span style="color:var(--muted)">Subtotal</span>
                        <span id="summary-subtotal" class="font-semibold"><?= CURRENCY_SYMBOL.number_format($subtotal,0) ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span style="color:var(--muted)">Shipping</span>
                        <span style="color:var(--muted)">Calculated at checkout</span>
                    </div>
                    <div class="border-t pt-3" style="border-color:var(--border)">
                        <div class="flex justify-between">
                            <span class="font-semibold">Estimated Total</span>
                            <span id="summary-total" class="font-bold text-base" style="color:var(--clay)"><?= CURRENCY_SYMBOL.number_format($subtotal,0) ?></span>
                        </div>
                    </div>
                </div>

                <a href="<?= APP_URL ?>/checkout" class="btn-clay w-full py-3.5 rounded-xl text-sm font-semibold text-center block" style="text-decoration:none">
                    <i class="fas fa-lock mr-2 text-xs"></i>Proceed to Checkout
                </a>
                <a href="<?= APP_URL ?>/shop" class="block text-center text-xs mt-3 hover:text-[var(--clay)] transition-colors" style="color:var(--muted);text-decoration:none">
                    <i class="fas fa-arrow-left mr-1"></i>Continue Shopping
                </a>

                <!-- Delivery note -->
                <div class="mt-5 pt-5 border-t space-y-2 text-xs" style="border-color:var(--border);color:var(--muted)">
                    <div class="flex items-center gap-2"><i class="fas fa-truck" style="color:var(--clay)"></i> Free delivery on orders over ৳3,000</div>
                    <div class="flex items-center gap-2"><i class="fas fa-shield-halved" style="color:var(--clay)"></i> Secure & encrypted checkout</div>
                    <div class="flex items-center gap-2"><i class="fas fa-rotate-left" style="color:var(--clay)"></i> 30-day easy return policy</div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<script>
const cartPrices = <?= json_encode(array_map(fn($k,$v)=>['key'=>$k,'price'=>$v['price'],'qty'=>$v['qty']],array_keys($items),array_values($items))) ?>;
let qtyMap = {};
<?php foreach($items as $key=>$item): ?>
qtyMap[<?= json_encode($key) ?>] = {qty: <?= $item['qty'] ?>, price: <?= $item['price'] ?>};
<?php endforeach; ?>

async function updateQty(key, delta){
    const cur = qtyMap[key].qty;
    const newQty = Math.max(0, cur + delta);
    const res = await fetch(APP_URL+'/cart/update',{method:'POST',headers:{'Content-Type':'application/x-www-form-urlencoded'},body:new URLSearchParams({key,qty:newQty})});
    const d = await res.json();
    if(newQty === 0){
        document.getElementById('item-'+key)?.remove();
        delete qtyMap[key];
    } else {
        qtyMap[key].qty = newQty;
        document.getElementById('qty-'+key).textContent = newQty;
        const sub = (qtyMap[key].price * newQty);
        document.getElementById('sub-'+key).textContent = '৳'+Math.round(sub).toLocaleString();
    }
    updateCartCount(d.count);
    recalcSummary();
}

async function removeItem(key){
    const res = await fetch(APP_URL+'/cart/remove',{method:'POST',headers:{'Content-Type':'application/x-www-form-urlencoded'},body:new URLSearchParams({key})});
    const d = await res.json();
    document.getElementById('item-'+key)?.remove();
    delete qtyMap[key];
    updateCartCount(d.count);
    recalcSummary();
    if(Object.keys(qtyMap).length===0) location.reload();
}

function recalcSummary(){
    let total = 0;
    for(const k in qtyMap) total += qtyMap[k].qty * qtyMap[k].price;
    document.getElementById('summary-subtotal').textContent = '৳'+Math.round(total).toLocaleString();
    document.getElementById('summary-total').textContent    = '৳'+Math.round(total).toLocaleString();
}
</script>
