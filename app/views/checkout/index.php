<?php
$cartItems = [];
$subtotal  = 0;
foreach($cart as $key=>$c){
    // Quick price from session is enough for display
    $subtotal += $c['qty'] * $c['price'];
}
$insideDhaka  = (float)($delivery['inside_dhaka_charge']??60);
$outsideDhaka = (float)($delivery['outside_dhaka_charge']??120);
$freeMin      = (float)($delivery['free_delivery_min_amount']??3000);
$shipping     = $subtotal>=$freeMin ? 0 : $insideDhaka;
$isLogged     = !empty($_SESSION['user_id']);
?>
<div class="max-w-6xl mx-auto px-4 sm:px-6 py-10">

    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 text-xs mb-8" style="color:var(--muted)">
        <a href="<?= APP_URL ?>/" style="text-decoration:none;color:var(--muted)">Home</a>
        <i class="fas fa-chevron-right text-[9px]"></i>
        <a href="<?= APP_URL ?>/cart" style="text-decoration:none;color:var(--muted)">Cart</a>
        <i class="fas fa-chevron-right text-[9px]"></i>
        <span style="color:var(--charcoal)">Checkout</span>
    </div>

    <!-- Steps -->
    <div class="flex items-center gap-2 mb-10">
        <?php foreach([['Cart','fa-bag-shopping'],['Checkout','fa-map-pin'],['Confirm','fa-check']] as $i=>[$label,$icon]): ?>
        <div class="flex items-center gap-2">
            <div class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold <?= $i<2?'text-white':'border-2' ?>" style="<?= $i===1?'background:var(--clay)':($i===0?'background:var(--charcoal)':'border-color:var(--border);color:var(--muted)') ?>">
                <?php if($i===0): ?><i class="fas <?= $icon ?> text-[10px]"></i><?php else: echo $i+1; endif; ?>
            </div>
            <span class="text-xs font-medium <?= $i===1?'':'opacity-50' ?>" style="color:var(--charcoal)"><?= $label ?></span>
        </div>
        <?php if($i<2): ?><div class="flex-1 h-px" style="background:var(--border)"></div><?php endif; ?>
        <?php endforeach; ?>
    </div>

    <form method="POST" action="<?= APP_URL ?>/checkout/place" id="checkout-form">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- Left: Delivery Info -->
            <div class="lg:col-span-2 space-y-6">

                <!-- Guest / Login prompt -->
                <?php if(!$isLogged): ?>
                <div class="bg-indigo-50 border border-indigo-100 rounded-2xl p-4 flex items-start gap-3">
                    <i class="fas fa-circle-info mt-0.5" style="color:#6366f1"></i>
                    <div class="text-sm">
                        <p class="font-semibold mb-0.5" style="color:#312e81">Have an account?</p>
                        <p style="color:#4338ca">
                            <a href="<?= APP_URL ?>/account/login" style="text-decoration:underline">Sign in</a> to auto-fill your address and track this order from your account.
                        </p>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Saved Addresses (logged in) -->
                <?php if($isLogged && !empty($addrs)): ?>
                <div class="bg-white rounded-2xl p-5 border" style="border-color:var(--border)">
                    <h2 class="font-bold text-base mb-4" style="color:var(--charcoal)">
                        <i class="fas fa-map-pin mr-2" style="color:var(--clay)"></i>Delivery Address
                    </h2>

                    <div class="space-y-3 mb-4" id="saved-addresses">
                        <?php foreach($addrs as $addr): $isDefault = (int)$addr['is_default']===1 || (!$default && $addr===$addrs[0]); ?>
                        <label class="flex items-start gap-3 p-4 rounded-xl border-2 cursor-pointer transition-all addr-card <?= $isDefault?'border-[var(--clay)]':'border-transparent' ?>" style="<?= $isDefault?'background:rgba(196,149,106,.05)':'background:var(--warm)' ?>">
                            <input type="radio" name="address_id" value="<?= $addr['id'] ?>" <?= $isDefault?'checked':'' ?> onchange="onAddressChange(this)"
                                   class="mt-1 flex-shrink-0 accent-[var(--clay)]">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-xs font-semibold uppercase tracking-wide px-2 py-0.5 rounded-md" style="background:rgba(196,149,106,.15);color:var(--clay)"><?= htmlspecialchars(ucfirst($addr['label'])) ?></span>
                                    <?php if($addr['is_default']): ?><span class="text-xs font-medium px-2 py-0.5 rounded-md bg-emerald-100 text-emerald-700">Default</span><?php endif; ?>
                                </div>
                                <p class="font-semibold text-sm"><?= htmlspecialchars($addr['full_name']) ?></p>
                                <p class="text-xs mt-0.5" style="color:var(--muted)"><?= htmlspecialchars($addr['phone']) ?></p>
                                <p class="text-xs mt-0.5" style="color:var(--muted)"><?= htmlspecialchars($addr['address_line'].', '.($addr['area']?$addr['area'].', ':'').$addr['city'].($addr['district']?', '.$addr['district']:'')) ?></p>
                            </div>
                            <a href="<?= APP_URL ?>/account/address/edit/<?= $addr['id'] ?>" class="text-xs flex-shrink-0" style="color:var(--clay);text-decoration:none">Edit</a>
                        </label>
                        <?php endforeach; ?>

                        <!-- New address option -->
                        <label class="flex items-center gap-3 p-4 rounded-xl border-2 border-dashed cursor-pointer transition-all hover:border-[var(--clay)]" style="border-color:var(--border);background:white">
                            <input type="radio" name="address_id" value="" onchange="onAddressChange(this)" class="flex-shrink-0 accent-[var(--clay)]">
                            <span class="text-sm font-medium flex items-center gap-2" style="color:var(--clay)">
                                <i class="fas fa-plus text-xs"></i> Use a different address
                            </span>
                        </label>
                    </div>

                    <!-- New address form (hidden by default) -->
                    <div id="new-address-form" class="hidden mt-4">
                        <?php include __DIR__.'/../account/_address_fields.php'; ?>
                    </div>
                </div>

                <?php else: ?>

                <!-- Guest delivery form OR logged in with no addresses -->
                <div class="bg-white rounded-2xl p-5 border" style="border-color:var(--border)">
                    <h2 class="font-bold text-base mb-5" style="color:var(--charcoal)">
                        <i class="fas fa-map-pin mr-2" style="color:var(--clay)"></i>Delivery Information
                    </h2>
                    <?php if(!$isLogged): ?>
                    <div class="mb-4">
                        <label class="block text-xs font-semibold uppercase tracking-wide mb-1.5" style="color:var(--muted)">Email Address</label>
                        <input type="email" name="email" placeholder="your@email.com" class="form-input w-full px-4 py-3 rounded-xl text-sm">
                        <p class="text-xs mt-1" style="color:var(--muted)">For order confirmation & tracking updates</p>
                    </div>
                    <?php endif; ?>
                    <?php include __DIR__.'/../account/_address_fields.php'; ?>
                </div>
                <?php endif; ?>

                <!-- Payment Method -->
                <div class="bg-white rounded-2xl p-5 border" style="border-color:var(--border)">
                    <h2 class="font-bold text-base mb-4" style="color:var(--charcoal)">
                        <i class="fas fa-credit-card mr-2" style="color:var(--clay)"></i>Payment Method
                    </h2>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <?php foreach([
                            ['cod',   'Cash on Delivery', 'fa-money-bill-wave', 'Pay when you receive your order'],
                            ['bkash', 'bKash',            'fa-mobile-screen',   'Pay via bKash mobile banking'],
                            ['card',  'Card Payment',     'fa-credit-card',     'Visa / Mastercard'],
                        ] as [$val,$label,$icon,$desc]): ?>
                        <label class="p-4 rounded-xl border-2 cursor-pointer transition-all payment-card" style="border-color:var(--border)">
                            <input type="radio" name="payment_method" value="<?= $val ?>" <?= $val==='cod'?'checked':'' ?> class="sr-only" onchange="selectPayment(this.closest('label'))">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0" style="background:rgba(196,149,106,.1)">
                                    <i class="fas <?= $icon ?> text-sm" style="color:var(--clay)"></i>
                                </div>
                                <div>
                                    <p class="font-semibold text-sm"><?= $label ?></p>
                                    <p class="text-xs" style="color:var(--muted)"><?= $desc ?></p>
                                </div>
                            </div>
                        </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Notes -->
                <div class="bg-white rounded-2xl p-5 border" style="border-color:var(--border)">
                    <h2 class="font-bold text-base mb-3" style="color:var(--charcoal)">Order Notes <span class="font-normal text-sm" style="color:var(--muted)">(Optional)</span></h2>
                    <textarea name="notes" rows="3" placeholder="Any special instructions for your order or delivery…" class="form-input w-full px-4 py-3 rounded-xl text-sm"></textarea>
                </div>
            </div>

            <!-- Right: Order Summary -->
            <div>
                <div class="bg-white rounded-2xl p-6 border sticky top-24" style="border-color:var(--border)">
                    <h2 class="font-bold text-base mb-5" style="color:var(--charcoal)">Order Summary</h2>

                    <!-- Items list -->
                    <div class="space-y-3 mb-5 pb-5 border-b" style="border-color:var(--border)">
                        <?php foreach($cart as $key=>$c):
                            require_once __DIR__.'/../../models/ProductModel.php';
                            $pm = new ProductModel();
                            $prod = $pm->getBySlugOrId((string)$c['product_id']);
                            $imgs = $pm->getImages($c['product_id']);
                            $img = !empty($imgs[0]['image_filename'])?UPLOAD_URL.$imgs[0]['image_filename']:null;
                        ?>
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-lg overflow-hidden flex-shrink-0" style="background:var(--warm)">
                                <?php if($img): ?><img src="<?= htmlspecialchars($img) ?>" class="w-full h-full object-cover" alt=""><?php else: ?><div class="w-full h-full flex items-center justify-center"><i class="fas fa-image text-sm" style="color:var(--clay)"></i></div><?php endif; ?>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-medium line-clamp-1" style="color:var(--charcoal)"><?= htmlspecialchars($prod['product_name']??'') ?></p>
                                <p class="text-xs" style="color:var(--muted)">Size: <?= htmlspecialchars($c['size']) ?> &bull; Qty: <?= $c['qty'] ?></p>
                            </div>
                            <p class="text-xs font-semibold flex-shrink-0" style="color:var(--clay)"><?= CURRENCY_SYMBOL.number_format($c['qty']*$c['price'],0) ?></p>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Coupon -->
                    <div class="mb-4">
                        <div class="flex gap-2">
                            <input type="text" id="coupon-input" placeholder="Coupon code" class="form-input flex-1 px-3 py-2.5 rounded-xl text-sm uppercase">
                            <button type="button" onclick="applyCoupon()" class="btn-outline px-4 py-2.5 rounded-xl text-xs font-semibold">Apply</button>
                        </div>
                        <p id="coupon-msg" class="text-xs mt-1.5 hidden"></p>
                    </div>

                    <!-- Totals -->
                    <div class="space-y-2.5 text-sm">
                        <div class="flex justify-between">
                            <span style="color:var(--muted)">Subtotal</span>
                            <span class="font-semibold"><?= CURRENCY_SYMBOL.number_format($subtotal,0) ?></span>
                        </div>
                        <div class="flex justify-between" id="discount-row" style="display:none!important">
                            <span style="color:var(--muted)">Discount</span>
                            <span class="font-semibold text-emerald-600" id="discount-val">−<?= CURRENCY_SYMBOL ?>0</span>
                        </div>
                        <div class="flex justify-between">
                            <span style="color:var(--muted)">Shipping</span>
                            <span id="shipping-val" class="font-semibold"><?= $shipping===0?'Free':CURRENCY_SYMBOL.number_format($shipping,0) ?></span>
                        </div>
                        <div class="border-t pt-3 flex justify-between" style="border-color:var(--border)">
                            <span class="font-bold">Total</span>
                            <span id="total-val" class="font-bold text-lg" style="color:var(--clay)"><?= CURRENCY_SYMBOL.number_format($subtotal+$shipping,0) ?></span>
                        </div>
                    </div>

                    <button type="submit" class="btn-clay w-full py-4 rounded-xl text-sm font-bold mt-5 flex items-center justify-center gap-2">
                        <i class="fas fa-lock text-xs"></i> Place Order
                    </button>
                    <p class="text-center text-xs mt-2" style="color:var(--muted)">By placing your order, you agree to our Terms & Conditions.</p>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
let discountPct = 0;
let couponCode  = '';
const subtotal  = <?= $subtotal ?>;
const insideDhaka  = <?= $insideDhaka ?>;
const outsideDhaka = <?= $outsideDhaka ?>;
const freeMin      = <?= $freeMin ?>;

function onAddressChange(radio){
    const form = document.getElementById('new-address-form');
    const cards = document.querySelectorAll('.addr-card');
    cards.forEach(c=>{c.style.borderColor='transparent';c.style.background='var(--warm)';});
    if(radio.value===''){
        form?.classList.remove('hidden');
    } else {
        form?.classList.add('hidden');
        radio.closest('label').style.borderColor='var(--clay)';
        radio.closest('label').style.background='rgba(196,149,106,.05)';
        // Detect city for shipping
        const addr = radio.closest('label');
        const addrText = addr.textContent.toLowerCase();
        updateShipping(addrText.includes('dhaka'));
    }
}

function selectPayment(card){
    document.querySelectorAll('.payment-card').forEach(c=>{c.style.borderColor='var(--border)';c.style.background='white';});
    card.style.borderColor='var(--clay)';
    card.style.background='rgba(196,149,106,.05)';
}
// Init
document.querySelector('.payment-card input:checked')?.closest('label') && selectPayment(document.querySelector('.payment-card input:checked').closest('label'));

// City change for delivery calc
document.querySelector('[name="city"]')?.addEventListener('input', function(){
    updateShipping(this.value.toLowerCase().includes('dhaka'));
});
document.querySelectorAll('[name="address_id"]').forEach(r=>r.addEventListener('change',()=>onAddressChange(r)));

function updateShipping(insideDhakaFlag){
    const sub = subtotal*(1-discountPct/100);
    let ship = sub>=freeMin ? 0 : (insideDhakaFlag?insideDhaka:outsideDhaka);
    document.getElementById('shipping-val').textContent = ship===0?'Free':'৳'+ship.toLocaleString();
    recalcTotal();
}

async function applyCoupon(){
    const code = document.getElementById('coupon-input').value.trim();
    if(!code) return;
    const fd = new FormData();
    fd.append('code',code); fd.append('amount',subtotal);
    const res = await fetch(APP_URL+'/checkout/coupon',{method:'POST',body:fd});
    const d   = await res.json();
    const msg = document.getElementById('coupon-msg');
    msg.classList.remove('hidden');
    if(d.success){
        discountPct = parseFloat(d.discount_pct);
        couponCode  = d.code;
        msg.style.color='var(--clay)';
        msg.textContent='✓ Coupon applied! '+discountPct+'% off';
        document.getElementById('discount-row').style.display='flex';
        recalcTotal();
    } else {
        msg.style.color='#ef4444';
        msg.textContent=d.message;
    }
}

function recalcTotal(){
    const discount = subtotal*discountPct/100;
    const shippingText = document.getElementById('shipping-val').textContent;
    const ship = shippingText==='Free'?0:parseFloat(shippingText.replace('৳','').replace(',',''));
    if(discountPct>0) document.getElementById('discount-val').textContent='−৳'+Math.round(discount).toLocaleString();
    const total = subtotal - discount + ship;
    document.getElementById('total-val').textContent='৳'+Math.round(total).toLocaleString();
}

// Form submit guard
document.getElementById('checkout-form').addEventListener('submit',function(e){
    const btn=this.querySelector('[type=submit]');
    btn.disabled=true; btn.innerHTML='<i class="fas fa-spinner fa-spin mr-2"></i>Placing Order…';
});
</script>
