<?php
$statusDefs = [
    'pending'   =>['label'=>'Pending',  'bg'=>'#fef9c3','text'=>'#a16207'],
    'confirmed' =>['label'=>'Confirmed','bg'=>'#dbeafe','text'=>'#1d4ed8'],
    'shipped'   =>['label'=>'Shipped',  'bg'=>'#ede9fe','text'=>'#5b21b6'],
    'delivered' =>['label'=>'Delivered','bg'=>'#d1fae5','text'=>'#065f46'],
    'cancelled' =>['label'=>'Cancelled','bg'=>'#fee2e2','text'=>'#991b1b'],
];
?>
<div class="max-w-5xl mx-auto px-4 sm:px-6 py-10">
    <h1 class="serif text-4xl font-light mb-2" style="color:var(--charcoal)">My Orders</h1>
    <p class="text-sm mb-8" style="color:var(--muted)">Track and manage your orders.</p>

    <?php include __DIR__.'/_nav.php'; ?>

    <div class="mt-8">
    <?php if(empty($orders)): ?>
    <div class="flex flex-col items-center justify-center py-20 text-center bg-white rounded-2xl border" style="border-color:var(--border)">
        <i class="fas fa-bag-shopping text-4xl mb-4" style="color:var(--clay)"></i>
        <h2 class="serif text-2xl font-light mb-2">No orders yet</h2>
        <p class="text-sm mb-6" style="color:var(--muted)">Your orders will show up here once you shop.</p>
        <a href="<?= APP_URL ?>/shop" class="btn-clay px-8 py-3 rounded-full text-sm font-semibold" style="text-decoration:none">Start Shopping</a>
    </div>
    <?php else: ?>
    <div class="space-y-4">
        <?php foreach($orders as $ord):
            $sd = $statusDefs[$ord['order_status']]??['label'=>ucfirst($ord['order_status']),'bg'=>'#f1f5f9','text'=>'#64748b'];
        ?>
        <div class="bg-white rounded-2xl border overflow-hidden" style="border-color:var(--border)">
            <div class="flex flex-wrap items-center justify-between gap-3 px-5 py-4 border-b" style="border-color:var(--border);background:var(--warm)">
                <div class="flex items-center gap-4 flex-wrap">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider mb-0.5" style="color:var(--muted)">Order</p>
                        <p class="mono font-bold text-sm" style="color:var(--clay)">#<?= htmlspecialchars($ord['order_number']) ?></p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider mb-0.5" style="color:var(--muted)">Placed</p>
                        <p class="text-sm font-medium"><?= date('d M Y', strtotime($ord['placed_at'])) ?></p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider mb-0.5" style="color:var(--muted)">Total</p>
                        <p class="text-sm font-bold" style="color:var(--clay)"><?= CURRENCY_SYMBOL.number_format($ord['total_amount'],0) ?></p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <span class="text-xs font-bold px-3 py-1.5 rounded-full" style="background:<?= $sd['bg'] ?>;color:<?= $sd['text'] ?>"><?= $sd['label'] ?></span>
                    <a href="<?= APP_URL ?>/account/order/<?= $ord['order_id'] ?>" class="text-xs font-semibold px-4 py-1.5 rounded-full border hover:bg-[var(--clay)] hover:text-white hover:border-[var(--clay)] transition-all" style="border-color:var(--clay);color:var(--clay);text-decoration:none">
                        View Details
                    </a>
                </div>
            </div>
            <div class="px-5 py-3">
                <p class="text-xs" style="color:var(--muted)">
                    <i class="fas fa-box text-[10px] mr-1" style="color:var(--clay)"></i>
                    <?= $ord['item_count'] ?> item<?= $ord['item_count']!=1?'s':'' ?>
                    &bull;
                    <i class="fas fa-credit-card text-[10px] mr-1" style="color:var(--clay)"></i>
                    <?= strtoupper($ord['payment_method']) ?>
                </p>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
    </div>
</div>
