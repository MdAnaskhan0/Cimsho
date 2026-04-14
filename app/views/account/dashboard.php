<?php
$statusDefs = [
    'pending'   =>['label'=>'Pending',  'bg'=>'#fef9c3','text'=>'#a16207'],
    'confirmed' =>['label'=>'Confirmed','bg'=>'#dbeafe','text'=>'#1d4ed8'],
    'shipped'   =>['label'=>'Shipped',  'bg'=>'#ede9fe','text'=>'#5b21b6'],
    'delivered' =>['label'=>'Delivered','bg'=>'#d1fae5','text'=>'#065f46'],
    'cancelled' =>['label'=>'Cancelled','bg'=>'#fee2e2','text'=>'#991b1b'],
];
?>
<div class="max-w-6xl mx-auto px-4 sm:px-6 py-10">

    <!-- Header -->
    <div class="flex items-start justify-between mb-8 flex-wrap gap-4">
        <div>
            <p class="text-xs font-semibold uppercase tracking-wider mb-1" style="color:var(--clay)">My Account</p>
            <h1 class="serif text-4xl font-light" style="color:var(--charcoal)">Welcome back, <?= htmlspecialchars(explode(' ',$u['name'])[0]) ?></h1>
        </div>
        <a href="<?= APP_URL ?>/auth/logout" class="flex items-center gap-2 text-xs font-medium px-4 py-2 rounded-xl border hover:bg-red-50 hover:border-red-200 hover:text-red-500 transition-all" style="border-color:var(--border);color:var(--muted);text-decoration:none">
            <i class="fas fa-right-from-bracket"></i> Sign Out
        </a>
    </div>

    <!-- Account nav tabs -->
    <?php include __DIR__.'/_nav.php'; ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-8">

        <!-- Profile summary card -->
        <div class="bg-white rounded-2xl p-6 border" style="border-color:var(--border)">
            <div class="flex items-center gap-4 mb-5">
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-white text-xl font-bold flex-shrink-0" style="background:var(--clay)">
                    <?= strtoupper(substr($u['name'],0,1)) ?>
                </div>
                <div>
                    <p class="font-bold" style="color:var(--charcoal)"><?= htmlspecialchars($u['name']) ?></p>
                    <p class="text-xs" style="color:var(--muted)"><?= htmlspecialchars($u['email']) ?></p>
                    <?php if($u['phone']): ?><p class="text-xs" style="color:var(--muted)"><?= htmlspecialchars($u['phone']) ?></p><?php endif; ?>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-2">
                <a href="<?= APP_URL ?>/account/profile" class="text-center py-2.5 rounded-xl text-xs font-semibold transition-colors hover:text-white" style="background:var(--warm);color:var(--clay);text-decoration:none"
                   onmouseover="this.style.background='var(--clay)'" onmouseout="this.style.background='var(--warm)'">
                    <i class="fas fa-pencil mr-1"></i>Edit Profile
                </a>
                <a href="<?= APP_URL ?>/account/change-password" class="text-center py-2.5 rounded-xl text-xs font-semibold transition-colors" style="background:var(--warm);color:var(--muted);text-decoration:none"
                   onmouseover="this.style.background='var(--clay);this.style.color='white'" onmouseout="this.style.background='var(--warm)'">
                    <i class="fas fa-lock mr-1"></i>Password
                </a>
            </div>
        </div>

        <!-- Quick stats -->
        <div class="lg:col-span-2 grid grid-cols-3 gap-4">
            <?php
            $stats = [
                ['label'=>'Total Orders','value'=>count($orders),'icon'=>'fa-bag-shopping'],
                ['label'=>'Delivered',   'value'=>count(array_filter($orders,fn($o)=>$o['order_status']==='delivered')),'icon'=>'fa-check-circle'],
                ['label'=>'Addresses',   'value'=>count($addrs),'icon'=>'fa-map-pin'],
            ];
            foreach($stats as $stat): ?>
            <div class="bg-white rounded-2xl p-5 border text-center" style="border-color:var(--border)">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center mx-auto mb-3" style="background:rgba(196,149,106,.1)">
                    <i class="fas <?= $stat['icon'] ?> text-sm" style="color:var(--clay)"></i>
                </div>
                <p class="serif text-3xl font-semibold mb-1" style="color:var(--charcoal)"><?= $stat['value'] ?></p>
                <p class="text-xs" style="color:var(--muted)"><?= $stat['label'] ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="mt-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-bold text-base" style="color:var(--charcoal)">Recent Orders</h2>
            <a href="<?= APP_URL ?>/account/orders" class="text-xs font-medium" style="color:var(--clay);text-decoration:none">View All <i class="fas fa-arrow-right ml-1 text-[10px]"></i></a>
        </div>

        <?php if(empty($orders)): ?>
        <div class="bg-white rounded-2xl p-10 border text-center" style="border-color:var(--border)">
            <i class="fas fa-bag-shopping text-3xl mb-3 block" style="color:var(--clay)"></i>
            <p class="font-semibold mb-1">No orders yet</p>
            <p class="text-sm mb-4" style="color:var(--muted)">Start shopping and your orders will appear here.</p>
            <a href="<?= APP_URL ?>/shop" class="btn-clay px-6 py-2.5 rounded-full text-sm font-medium" style="text-decoration:none">Shop Now</a>
        </div>
        <?php else: ?>
        <div class="bg-white rounded-2xl border overflow-hidden" style="border-color:var(--border)">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr style="background:var(--warm)">
                            <th class="text-left px-5 py-3 text-xs font-semibold uppercase tracking-wider" style="color:var(--muted)">Order</th>
                            <th class="text-left px-5 py-3 text-xs font-semibold uppercase tracking-wider" style="color:var(--muted)">Date</th>
                            <th class="text-left px-5 py-3 text-xs font-semibold uppercase tracking-wider" style="color:var(--muted)">Items</th>
                            <th class="text-left px-5 py-3 text-xs font-semibold uppercase tracking-wider" style="color:var(--muted)">Total</th>
                            <th class="text-left px-5 py-3 text-xs font-semibold uppercase tracking-wider" style="color:var(--muted)">Status</th>
                            <th class="px-5 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y" style="border-color:var(--border)">
                        <?php foreach(array_slice($orders,0,5) as $ord):
                            $sd = $statusDefs[$ord['order_status']]??['label'=>ucfirst($ord['order_status']),'bg'=>'#f1f5f9','text'=>'#64748b'];
                        ?>
                        <tr class="hover:bg-[var(--warm)] transition-colors">
                            <td class="px-5 py-3.5">
                                <span class="mono text-xs font-bold" style="color:var(--clay)">#<?= htmlspecialchars($ord['order_number']) ?></span>
                            </td>
                            <td class="px-5 py-3.5 text-xs" style="color:var(--muted)"><?= date('d M Y', strtotime($ord['placed_at'])) ?></td>
                            <td class="px-5 py-3.5 text-xs font-medium"><?= $ord['item_count'] ?> item<?= $ord['item_count']!=1?'s':'' ?></td>
                            <td class="px-5 py-3.5 text-sm font-semibold" style="color:var(--clay)"><?= CURRENCY_SYMBOL.number_format($ord['total_amount'],0) ?></td>
                            <td class="px-5 py-3.5">
                                <span class="text-xs font-semibold px-2.5 py-1 rounded-full" style="background:<?= $sd['bg'] ?>;color:<?= $sd['text'] ?>"><?= $sd['label'] ?></span>
                            </td>
                            <td class="px-5 py-3.5">
                                <a href="<?= APP_URL ?>/account/order/<?= $ord['order_id'] ?>" class="text-xs font-medium" style="color:var(--clay);text-decoration:none">View</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Default Address -->
    <?php if(!empty($addrs)): $def = array_filter($addrs,fn($a)=>$a['is_default']); $def = reset($def)?:$addrs[0]; ?>
    <div class="mt-6 bg-white rounded-2xl p-5 border" style="border-color:var(--border)">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-bold text-base" style="color:var(--charcoal)">Default Address</h2>
            <a href="<?= APP_URL ?>/account/addresses" class="text-xs font-medium" style="color:var(--clay);text-decoration:none">Manage Addresses <i class="fas fa-arrow-right ml-1 text-[10px]"></i></a>
        </div>
        <div class="flex items-start gap-3">
            <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0" style="background:rgba(196,149,106,.1)">
                <i class="fas fa-map-pin text-sm" style="color:var(--clay)"></i>
            </div>
            <div class="text-sm">
                <p class="font-semibold"><?= htmlspecialchars($def['full_name']) ?></p>
                <p style="color:var(--muted)"><?= htmlspecialchars($def['phone']) ?></p>
                <p style="color:var(--muted)"><?= htmlspecialchars($def['address_line'].', '.($def['area']?$def['area'].', ':'').$def['city'].($def['district']?', '.$def['district']:'')) ?></p>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>
