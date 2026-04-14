<?php
$statusSteps = ['pending','confirmed','shipped','delivered'];
$currentStep = array_search($o['order_status'],$statusSteps);
?>
<div class="max-w-3xl mx-auto px-4 sm:px-6 py-12">

    <!-- Success header -->
    <div class="text-center mb-10">
        <div class="w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-5 animate-bounce" style="background:rgba(196,149,106,.15)">
            <i class="fas fa-check text-3xl" style="color:var(--clay)"></i>
        </div>
        <h1 class="serif text-4xl font-light mb-2" style="color:var(--charcoal)">Order Confirmed!</h1>
        <p class="text-sm" style="color:var(--muted)">Thank you for your purchase. We'll get it ready for you.</p>
        <div class="mt-3 inline-flex items-center gap-2 px-5 py-2.5 rounded-full" style="background:var(--warm)">
            <span class="text-xs font-semibold uppercase tracking-wider" style="color:var(--muted)">Order Number</span>
            <span class="mono font-bold" style="color:var(--clay)"><?= htmlspecialchars($o['order_number']) ?></span>
        </div>
    </div>

    <!-- Status Tracker -->
    <div class="bg-white rounded-2xl p-6 border mb-6" style="border-color:var(--border)">
        <h2 class="font-bold text-sm uppercase tracking-wider mb-6" style="color:var(--muted)">Order Status</h2>
        <div class="flex items-center">
            <?php foreach($statusSteps as $i=>$step):
                $done   = $i <= $currentStep;
                $active = $i === $currentStep;
                $labels = ['pending'=>'Order Placed','confirmed'=>'Confirmed','shipped'=>'Shipped','delivered'=>'Delivered'];
                $icons  = ['pending'=>'fa-clock','confirmed'=>'fa-check','shipped'=>'fa-truck','delivered'=>'fa-house-chimney'];
            ?>
            <div class="flex flex-col items-center gap-2 flex-1">
                <div class="w-10 h-10 rounded-full flex items-center justify-center border-2 transition-all <?= $done?'text-white':'text-gray-300' ?>" style="<?= $done?'background:var(--clay);border-color:var(--clay)':'border-color:#e5e7eb;background:white' ?>">
                    <i class="fas <?= $icons[$step] ?> text-sm"></i>
                </div>
                <span class="text-xs font-medium text-center <?= $active?'':'opacity-50' ?>" style="<?= $active?'color:var(--clay)':'color:var(--muted)' ?>"><?= $labels[$step] ?></span>
            </div>
            <?php if($i<count($statusSteps)-1): ?>
            <div class="h-0.5 flex-1 -mt-6 mx-1" style="background:<?= $i<$currentStep?'var(--clay)':'#e5e7eb' ?>"></div>
            <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Order Details -->
    <div class="bg-white rounded-2xl border overflow-hidden mb-6" style="border-color:var(--border)">
        <div class="px-6 py-4 border-b" style="border-color:var(--border);background:var(--warm)">
            <h2 class="font-bold text-sm" style="color:var(--charcoal)">Order Items</h2>
        </div>
        <div class="divide-y" style="border-color:var(--border)">
            <?php foreach($items as $item):
                $img = !empty($item['image_filename'])?UPLOAD_URL.$item['image_filename']:null;
            ?>
            <div class="flex items-center gap-4 px-6 py-4">
                <div class="w-14 h-14 rounded-xl overflow-hidden flex-shrink-0" style="background:var(--warm)">
                    <?php if($img): ?><img src="<?= htmlspecialchars($img) ?>" class="w-full h-full object-cover" alt=""><?php else: ?><div class="w-full h-full flex items-center justify-center"><i class="fas fa-image" style="color:var(--clay)"></i></div><?php endif; ?>
                </div>
                <div class="flex-1">
                    <p class="font-medium text-sm" style="color:var(--charcoal)"><?= htmlspecialchars($item['product_name']??'') ?></p>
                    <p class="text-xs mt-0.5" style="color:var(--muted)">
                        Size: <?= htmlspecialchars($item['size']??'') ?>
                        <?php if($item['color']): ?> &bull; Color: <?= htmlspecialchars($item['color']) ?><?php endif; ?>
                        &bull; Qty: <?= $item['qty'] ?>
                    </p>
                </div>
                <p class="font-semibold text-sm" style="color:var(--clay)"><?= CURRENCY_SYMBOL.number_format($item['qty']*$item['unit_price'],0) ?></p>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="px-6 py-4 border-t" style="border-color:var(--border);background:var(--warm)">
            <div class="flex justify-between items-center text-sm">
                <span style="color:var(--muted)">Shipping</span>
                <span><?= $o['shipping_charge']>0?CURRENCY_SYMBOL.number_format($o['shipping_charge'],0):'Free' ?></span>
            </div>
            <div class="flex justify-between items-center font-bold mt-2">
                <span>Total Paid</span>
                <span class="text-lg" style="color:var(--clay)"><?= CURRENCY_SYMBOL.number_format($o['total_amount'],0) ?></span>
            </div>
        </div>
    </div>

    <!-- Delivery Info -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">
        <div class="bg-white rounded-2xl p-5 border" style="border-color:var(--border)">
            <h3 class="font-bold text-xs uppercase tracking-wider mb-3" style="color:var(--muted)">Delivery To</h3>
            <?php if($o['user_id'] && $o['address_id']): ?>
                <?php
                require_once __DIR__.'/../../models/UserModel.php';
                $um = new UserModel();
                $addr = $um->getAddress((int)$o['address_id'],(int)$o['user_id']);
                ?>
                <?php if($addr): ?>
                <p class="font-semibold text-sm"><?= htmlspecialchars($addr['full_name']) ?></p>
                <p class="text-xs mt-1" style="color:var(--muted)"><?= htmlspecialchars($addr['phone']) ?></p>
                <p class="text-xs mt-0.5" style="color:var(--muted)"><?= htmlspecialchars($addr['address_line'].', '.$addr['city']) ?></p>
                <?php endif; ?>
            <?php else: ?>
                <p class="font-semibold text-sm"><?= htmlspecialchars($o['guest_name']??'Guest') ?></p>
                <p class="text-xs mt-1" style="color:var(--muted)"><?= htmlspecialchars($o['guest_phone']??'') ?></p>
                <p class="text-xs mt-0.5" style="color:var(--muted)"><?= htmlspecialchars($o['guest_address']??'') ?></p>
            <?php endif; ?>
        </div>
        <div class="bg-white rounded-2xl p-5 border" style="border-color:var(--border)">
            <h3 class="font-bold text-xs uppercase tracking-wider mb-3" style="color:var(--muted)">Payment</h3>
            <div class="flex items-center gap-2">
                <i class="fas <?= $o['payment_method']==='bkash'?'fa-mobile-screen':($o['payment_method']==='card'?'fa-credit-card':'fa-money-bill-wave') ?> text-sm" style="color:var(--clay)"></i>
                <span class="font-semibold text-sm"><?= strtoupper($o['payment_method']) ?></span>
            </div>
            <p class="text-xs mt-1" style="color:var(--muted)">Placed: <?= date('d M Y, h:i A', strtotime($o['placed_at'])) ?></p>
        </div>
    </div>

    <!-- Actions -->
    <div class="flex flex-col sm:flex-row gap-3 justify-center">
        <?php if(!empty($_SESSION['user_id'])): ?>
        <a href="<?= APP_URL ?>/account/orders" class="btn-clay px-8 py-3.5 rounded-full text-sm font-semibold text-center" style="text-decoration:none">
            <i class="fas fa-list mr-2"></i>View My Orders
        </a>
        <?php else: ?>
        <a href="<?= APP_URL ?>/order/track" class="btn-clay px-8 py-3.5 rounded-full text-sm font-semibold text-center" style="text-decoration:none">
            <i class="fas fa-truck mr-2"></i>Track This Order
        </a>
        <?php endif; ?>
        <a href="<?= APP_URL ?>/shop" class="btn-outline px-8 py-3.5 rounded-full text-sm font-semibold text-center" style="text-decoration:none">
            <i class="fas fa-store mr-2"></i>Continue Shopping
        </a>
    </div>
</div>
