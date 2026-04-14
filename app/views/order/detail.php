<?php
$statusSteps = ['pending','confirmed','shipped','delivered'];
$currentStep = array_search($o['order_status'],$statusSteps);
if($currentStep===false) $currentStep = 0;
$statusDefs = [
    'pending'   =>['label'=>'Pending',  'bg'=>'#fef9c3','text'=>'#a16207'],
    'confirmed' =>['label'=>'Confirmed','bg'=>'#dbeafe','text'=>'#1d4ed8'],
    'shipped'   =>['label'=>'Shipped',  'bg'=>'#ede9fe','text'=>'#5b21b6'],
    'delivered' =>['label'=>'Delivered','bg'=>'#d1fae5','text'=>'#065f46'],
    'cancelled' =>['label'=>'Cancelled','bg'=>'#fee2e2','text'=>'#991b1b'],
];
$sd = $statusDefs[$o['order_status']]??['label'=>ucfirst($o['order_status']),'bg'=>'#f1f5f9','text'=>'#64748b'];
?>
<div class="max-w-4xl mx-auto px-4 sm:px-6 py-10">

    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 text-xs mb-6" style="color:var(--muted)">
        <a href="<?= APP_URL ?>/account" style="text-decoration:none;color:var(--muted)">Account</a>
        <i class="fas fa-chevron-right text-[9px]"></i>
        <a href="<?= APP_URL ?>/account/orders" style="text-decoration:none;color:var(--muted)">My Orders</a>
        <i class="fas fa-chevron-right text-[9px]"></i>
        <span style="color:var(--charcoal)">#<?= htmlspecialchars($o['order_number']) ?></span>
    </div>

    <div class="flex items-center justify-between mb-8 flex-wrap gap-3">
        <div>
            <h1 class="serif text-3xl font-light" style="color:var(--charcoal)">Order #<?= htmlspecialchars($o['order_number']) ?></h1>
            <p class="text-sm mt-1" style="color:var(--muted)">Placed on <?= date('d M Y, h:i A', strtotime($o['placed_at'])) ?></p>
        </div>
        <span class="text-sm font-bold px-4 py-2 rounded-full" style="background:<?= $sd['bg'] ?>;color:<?= $sd['text'] ?>"><?= $sd['label'] ?></span>
    </div>

    <!-- Status tracker -->
    <?php if($o['order_status']!=='cancelled'): ?>
    <div class="bg-white rounded-2xl p-6 border mb-6" style="border-color:var(--border)">
        <div class="flex items-center">
            <?php
            $labels=['pending'=>'Order Placed','confirmed'=>'Confirmed','shipped'=>'Shipped','delivered'=>'Delivered'];
            $icons =['pending'=>'fa-clock','confirmed'=>'fa-thumbs-up','shipped'=>'fa-truck','delivered'=>'fa-house-chimney'];
            foreach($statusSteps as $i=>$step):
                $done=$i<=$currentStep; $active=$i===$currentStep;
            ?>
            <div class="flex flex-col items-center gap-2 flex-1">
                <div class="w-10 h-10 rounded-full flex items-center justify-center border-2" style="<?= $done?'background:var(--clay);border-color:var(--clay);color:white':'border-color:#e5e7eb;background:white;color:#d1d5db' ?>">
                    <i class="fas <?= $icons[$step] ?> text-sm"></i>
                </div>
                <span class="text-[10px] font-semibold text-center" style="<?= $active?'color:var(--clay)':'color:var(--muted)' ?>"><?= $labels[$step] ?></span>
            </div>
            <?php if($i<3): ?><div class="h-0.5 flex-1 -mt-6" style="background:<?= $i<$currentStep?'var(--clay)':'#e5e7eb' ?>"></div><?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Items -->
        <div class="lg:col-span-2 space-y-4">
            <div class="bg-white rounded-2xl border overflow-hidden" style="border-color:var(--border)">
                <div class="px-5 py-4 border-b" style="border-color:var(--border);background:var(--warm)">
                    <h2 class="font-bold text-sm">Order Items</h2>
                </div>
                <div class="divide-y" style="border-color:var(--border)">
                    <?php foreach($items as $item):
                        $img=!empty($item['image_filename'])?UPLOAD_URL.$item['image_filename']:null;
                    ?>
                    <div class="flex items-center gap-4 px-5 py-4">
                        <div class="w-16 h-16 rounded-xl overflow-hidden flex-shrink-0" style="background:var(--warm)">
                            <?php if($img): ?><img src="<?= htmlspecialchars($img) ?>" class="w-full h-full object-cover" alt=""><?php else: ?><div class="w-full h-full flex items-center justify-center"><i class="fas fa-image" style="color:var(--clay)"></i></div><?php endif; ?>
                        </div>
                        <div class="flex-1">
                            <a href="<?= APP_URL ?>/product/<?= $item['product_id'] ?>" class="font-medium text-sm hover:text-[var(--clay)] transition-colors" style="color:var(--charcoal);text-decoration:none"><?= htmlspecialchars($item['product_name']??'') ?></a>
                            <p class="text-xs mt-0.5" style="color:var(--muted)">
                                <?php if($item['size']): ?>Size: <?= htmlspecialchars($item['size']) ?><?php endif; ?>
                                <?php if($item['color']): ?> &bull; <?= htmlspecialchars($item['color']) ?><?php endif; ?>
                                &bull; Qty: <?= $item['qty'] ?>
                            </p>
                        </div>
                        <p class="text-sm font-semibold flex-shrink-0" style="color:var(--clay)"><?= CURRENCY_SYMBOL.number_format($item['qty']*$item['unit_price'],0) ?></p>
                    </div>
                    <?php endforeach; ?>
                </div>
                <div class="px-5 py-4 border-t space-y-2 text-sm" style="border-color:var(--border);background:var(--warm)">
                    <div class="flex justify-between"><span style="color:var(--muted)">Shipping</span><span><?= $o['shipping_charge']>0?CURRENCY_SYMBOL.number_format($o['shipping_charge'],0):'Free' ?></span></div>
                    <div class="flex justify-between font-bold text-base">
                        <span>Total</span>
                        <span style="color:var(--clay)"><?= CURRENCY_SYMBOL.number_format($o['total_amount'],0) ?></span>
                    </div>
                </div>
            </div>

            <!-- Status Timeline -->
            <?php if(!empty($log)): ?>
            <div class="bg-white rounded-2xl p-5 border" style="border-color:var(--border)">
                <h2 class="font-bold text-sm mb-4">Order Timeline</h2>
                <div class="space-y-4">
                    <?php foreach(array_reverse($log) as $entry): ?>
                    <div class="flex items-start gap-3">
                        <div class="w-2.5 h-2.5 rounded-full mt-1.5 flex-shrink-0" style="background:var(--clay)"></div>
                        <div>
                            <p class="text-sm font-semibold capitalize"><?= htmlspecialchars(str_replace('_',' ',$entry['status'])) ?></p>
                            <?php if($entry['note']): ?><p class="text-xs mt-0.5" style="color:var(--muted)"><?= htmlspecialchars($entry['note']) ?></p><?php endif; ?>
                            <p class="text-xs" style="color:var(--muted)"><?= date('d M Y, h:i A', strtotime($entry['created_at'])) ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Summary sidebar -->
        <div class="space-y-4">
            <div class="bg-white rounded-2xl p-5 border" style="border-color:var(--border)">
                <h3 class="font-bold text-sm mb-4" style="color:var(--muted)">Payment Info</h3>
                <div class="flex items-center gap-2 mb-1">
                    <i class="fas <?= $o['payment_method']==='bkash'?'fa-mobile-screen':($o['payment_method']==='card'?'fa-credit-card':'fa-money-bill-wave') ?>" style="color:var(--clay)"></i>
                    <span class="font-semibold text-sm"><?= strtoupper($o['payment_method']) ?></span>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-5 border" style="border-color:var(--border)">
                <h3 class="font-bold text-sm mb-4" style="color:var(--muted)">Delivery Address</h3>
                <?php if($o['address_id']):
                    require_once __DIR__.'/../../models/UserModel.php';
                    $um=new UserModel();
                    $addr=$um->getAddress((int)$o['address_id'],(int)$o['user_id']);
                    if($addr): ?>
                    <p class="font-semibold text-sm"><?= htmlspecialchars($addr['full_name']) ?></p>
                    <p class="text-xs mt-0.5" style="color:var(--muted)"><?= htmlspecialchars($addr['phone']) ?></p>
                    <p class="text-xs mt-0.5" style="color:var(--muted)"><?= htmlspecialchars($addr['address_line'].', '.$addr['city']) ?></p>
                    <?php endif; ?>
                <?php else: ?>
                    <p class="font-semibold text-sm"><?= htmlspecialchars($o['guest_name']??'') ?></p>
                    <p class="text-xs mt-0.5" style="color:var(--muted)"><?= htmlspecialchars($o['guest_phone']??'') ?></p>
                    <p class="text-xs mt-0.5" style="color:var(--muted)"><?= htmlspecialchars($o['guest_address']??'') ?></p>
                <?php endif; ?>
            </div>

            <a href="<?= APP_URL ?>/account/orders" class="flex items-center justify-center gap-2 w-full py-3 rounded-xl border text-sm font-medium hover:bg-[var(--warm)] transition-colors" style="border-color:var(--border);color:var(--muted);text-decoration:none">
                <i class="fas fa-arrow-left text-xs"></i> Back to Orders
            </a>
        </div>
    </div>
</div>
