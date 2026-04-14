<div class="max-w-3xl mx-auto px-4 sm:px-6 py-12">

    <div class="text-center mb-10">
        <div class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4" style="background:rgba(196,149,106,.1)">
            <i class="fas fa-truck text-2xl" style="color:var(--clay)"></i>
        </div>
        <h1 class="serif text-4xl font-light mb-2" style="color:var(--charcoal)">Track Your Order</h1>
        <p class="text-sm" style="color:var(--muted)">Enter your order number and email/phone to check status.</p>
    </div>

    <!-- Track form -->
    <div class="bg-white rounded-2xl p-6 border mb-8" style="border-color:var(--border)">
        <form method="POST" action="<?= APP_URL ?>/order/track" class="space-y-4">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wide mb-1.5" style="color:var(--muted)">Order Number</label>
                    <input type="text" name="order_number" required placeholder="e.g. ORD20260414XXXXX"
                           value="<?= htmlspecialchars($_POST['order_number']??'') ?>"
                           class="form-input w-full px-4 py-3 rounded-xl text-sm">
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wide mb-1.5" style="color:var(--muted)">Email or Phone</label>
                    <input type="text" name="contact" required placeholder="Your email or phone"
                           value="<?= htmlspecialchars($_POST['contact']??'') ?>"
                           class="form-input w-full px-4 py-3 rounded-xl text-sm">
                </div>
            </div>
            <button type="submit" class="btn-clay w-full py-3.5 rounded-xl text-sm font-semibold">
                <i class="fas fa-search mr-2"></i>Track Order
            </button>
        </form>
    </div>

    <?php if($error): ?>
    <div class="flex items-center gap-3 p-4 rounded-2xl mb-6 bg-red-50 border border-red-100">
        <i class="fas fa-circle-xmark text-red-500"></i>
        <p class="text-sm text-red-700"><?= htmlspecialchars($error) ?></p>
    </div>
    <?php endif; ?>

    <?php if($o): ?>
    <?php
    $statusSteps = ['pending','confirmed','shipped','delivered'];
    $currentStep = array_search($o['order_status'],$statusSteps);
    if($currentStep===false) $currentStep=0;
    ?>

    <!-- Order found -->
    <div class="space-y-5">

        <!-- Status tracker -->
        <div class="bg-white rounded-2xl p-6 border" style="border-color:var(--border)">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider mb-1" style="color:var(--muted)">Order</p>
                    <p class="mono font-bold text-lg" style="color:var(--clay)"><?= htmlspecialchars($o['order_number']) ?></p>
                </div>
                <div class="text-right">
                    <p class="text-xs font-semibold uppercase tracking-wider mb-1" style="color:var(--muted)">Placed On</p>
                    <p class="text-sm font-medium"><?= date('d M Y', strtotime($o['placed_at'])) ?></p>
                </div>
            </div>

            <!-- Step tracker -->
            <div class="flex items-center mb-8">
                <?php
                $labels = ['pending'=>'Placed','confirmed'=>'Confirmed','shipped'=>'Shipped','delivered'=>'Delivered'];
                $icons  = ['pending'=>'fa-clock','confirmed'=>'fa-thumbs-up','shipped'=>'fa-truck','delivered'=>'fa-house-chimney'];
                foreach($statusSteps as $i=>$step):
                    $done = $i <= $currentStep;
                    $active = $i === $currentStep;
                ?>
                <div class="flex flex-col items-center gap-2 flex-1">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center border-2" style="<?= $done?'background:var(--clay);border-color:var(--clay);color:white':'border-color:#e5e7eb;background:white;color:#d1d5db' ?>">
                        <i class="fas <?= $icons[$step] ?> text-sm"></i>
                    </div>
                    <span class="text-[10px] font-semibold text-center uppercase tracking-wide" style="<?= $active?'color:var(--clay)':'color:var(--muted)' ?>"><?= $labels[$step] ?></span>
                </div>
                <?php if($i<3): ?><div class="h-0.5 flex-1 -mt-6" style="background:<?= $i<$currentStep?'var(--clay)':'#e5e7eb' ?>"></div><?php endif; ?>
                <?php endforeach; ?>
            </div>

            <!-- Status log timeline -->
            <?php if(!empty($log)): ?>
            <div class="border-t pt-5" style="border-color:var(--border)">
                <p class="text-xs font-semibold uppercase tracking-wider mb-4" style="color:var(--muted)">Status Timeline</p>
                <div class="space-y-4">
                    <?php foreach(array_reverse($log) as $entry): ?>
                    <div class="flex items-start gap-3">
                        <div class="w-2.5 h-2.5 rounded-full mt-1.5 flex-shrink-0" style="background:var(--clay)"></div>
                        <div>
                            <p class="text-sm font-semibold capitalize" style="color:var(--charcoal)"><?= htmlspecialchars(str_replace('_',' ',$entry['status'])) ?></p>
                            <?php if($entry['note']): ?><p class="text-xs mt-0.5" style="color:var(--muted)"><?= htmlspecialchars($entry['note']) ?></p><?php endif; ?>
                            <p class="text-xs mt-0.5" style="color:var(--muted)"><?= date('d M Y, h:i A', strtotime($entry['created_at'])) ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Items -->
        <div class="bg-white rounded-2xl border overflow-hidden" style="border-color:var(--border)">
            <div class="px-6 py-4 border-b" style="border-color:var(--border);background:var(--warm)">
                <h3 class="font-bold text-sm" style="color:var(--charcoal)">Order Items</h3>
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
                        <p class="text-xs" style="color:var(--muted)">
                            <?php if($item['size']): ?>Size: <?= htmlspecialchars($item['size']) ?><?php endif; ?>
                            <?php if($item['color']): ?> &bull; <?= htmlspecialchars($item['color']) ?><?php endif; ?>
                            &bull; Qty: <?= $item['qty'] ?>
                        </p>
                    </div>
                    <p class="text-sm font-semibold" style="color:var(--clay)"><?= CURRENCY_SYMBOL.number_format($item['qty']*$item['unit_price'],0) ?></p>
                </div>
                <?php endforeach; ?>
            </div>
            <div class="px-6 py-4 border-t flex justify-between items-center" style="border-color:var(--border);background:var(--warm)">
                <span class="font-bold text-sm">Total</span>
                <span class="font-bold" style="color:var(--clay)"><?= CURRENCY_SYMBOL.number_format($o['total_amount'],0) ?></span>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if(empty($_SESSION['user_id'])): ?>
    <div class="mt-6 text-center py-4 rounded-2xl" style="background:var(--warm)">
        <p class="text-sm" style="color:var(--muted)">
            <a href="<?= APP_URL ?>/account/signup" style="color:var(--clay);text-decoration:none;font-weight:600">Create an account</a>
            to track orders easily and manage your profile.
        </p>
    </div>
    <?php endif; ?>
</div>
