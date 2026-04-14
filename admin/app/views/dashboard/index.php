<?php
// Helper: format currency
function taka(float $amount): string {
    return '৳' . number_format($amount, 2);
}

// Build status map
$statusMap = [];
foreach ($ordersByStatus as $row) {
    $statusMap[$row['order_status']] = $row['count'];
}
$totalOrders = array_sum(array_column($ordersByStatus, 'count')) ?: 1;

$statusDefs = [
    'pending'   => ['label'=>'Pending',   'color'=>'#f59e0b', 'bg'=>'#fef9c3', 'text'=>'#a16207'],
    'confirmed' => ['label'=>'Confirmed', 'color'=>'#3b82f6', 'bg'=>'#dbeafe', 'text'=>'#1d4ed8'],
    'shipped'   => ['label'=>'Shipped',   'color'=>'#8b5cf6', 'bg'=>'#ede9fe', 'text'=>'#6d28d9'],
    'delivered' => ['label'=>'Delivered', 'color'=>'#22c55e', 'bg'=>'#dcfce7', 'text'=>'#15803d'],
    'cancelled' => ['label'=>'Cancelled', 'color'=>'#ef4444', 'bg'=>'#fee2e2', 'text'=>'#b91c1c'],
];

// Monthly chart data
$months   = array_column($monthlyRevenue, 'month');
$revenues = array_column($monthlyRevenue, 'revenue');
$orderCts = array_column($monthlyRevenue, 'orders');
?>

<!-- ===== STAT CARDS ===== -->
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-6">

    <?php
    $cards = [
        [
            'label'  => 'Total Revenue',
            'value'  => taka($stats['total_revenue']),
            'icon'   => 'fa-bangladeshi-taka-sign',
            'gradient' => 'linear-gradient(135deg,#6366f1,#8b5cf6)',
            'sub'    => 'All confirmed orders',
            'trend'  => '+12.4%',
            'up'     => true,
        ],
        [
            'label'  => 'Total Orders',
            'value'  => number_format($stats['total_orders']),
            'icon'   => 'fa-bag-shopping',
            'gradient' => 'linear-gradient(135deg,#0ea5e9,#38bdf8)',
            'sub'    => $stats['pending_orders'] . ' pending',
            'trend'  => '+8.2%',
            'up'     => true,
        ],
        [
            'label'  => 'Customers',
            'value'  => number_format($stats['total_users']),
            'icon'   => 'fa-users',
            'gradient' => 'linear-gradient(135deg,#10b981,#34d399)',
            'sub'    => 'Registered users',
            'trend'  => '+5.1%',
            'up'     => true,
        ],
        [
            'label'  => 'Active Products',
            'value'  => number_format($stats['total_products']),
            'icon'   => 'fa-boxes-stacked',
            'gradient' => 'linear-gradient(135deg,#f59e0b,#fbbf24)',
            'sub'    => count($lowStock) . ' low stock',
            'trend'  => '-2.3%',
            'up'     => false,
        ],
    ];
    foreach ($cards as $card): ?>
    <div class="stat-card bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
        <div class="flex items-start justify-between mb-4">
            <div class="w-11 h-11 rounded-xl flex items-center justify-center flex-shrink-0" style="background:<?= $card['gradient'] ?>">
                <i class="fas <?= $card['icon'] ?> text-white"></i>
            </div>
            <span class="text-xs font-semibold px-2 py-1 rounded-full <?= $card['up'] ? 'bg-emerald-50 text-emerald-600' : 'bg-red-50 text-red-500' ?>">
                <i class="fas <?= $card['up'] ? 'fa-arrow-trend-up' : 'fa-arrow-trend-down' ?> mr-1"></i><?= $card['trend'] ?>
            </span>
        </div>
        <p class="text-2xl font-extrabold text-slate-800 mb-1"><?= $card['value'] ?></p>
        <p class="text-slate-500 text-sm font-medium"><?= $card['label'] ?></p>
        <p class="text-slate-400 text-xs mt-1"><?= $card['sub'] ?></p>
    </div>
    <?php endforeach; ?>
</div>

<!-- ===== CHARTS ROW ===== -->
<div class="grid grid-cols-1 xl:grid-cols-3 gap-5 mb-6">

    <!-- Revenue Chart -->
    <div class="xl:col-span-2 bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h3 class="font-bold text-slate-800">Revenue Overview</h3>
                <p class="text-slate-400 text-xs mt-0.5">Last 6 months performance</p>
            </div>
            <div class="flex items-center gap-3">
                <span class="flex items-center gap-1.5 text-xs text-slate-500"><span class="w-2.5 h-2.5 rounded-full inline-block" style="background:#6366f1"></span>Revenue</span>
                <span class="flex items-center gap-1.5 text-xs text-slate-500"><span class="w-2.5 h-2.5 rounded-full inline-block" style="background:#e2e8f0"></span>Orders</span>
            </div>
        </div>
        <div id="revenue-chart"></div>
    </div>

    <!-- Order Status Donut -->
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
        <div class="mb-5">
            <h3 class="font-bold text-slate-800">Order Status</h3>
            <p class="text-slate-400 text-xs mt-0.5">Current distribution</p>
        </div>
        <div id="donut-chart" class="mb-4"></div>
        <div class="space-y-2">
            <?php foreach ($statusDefs as $key => $def): ?>
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full flex-shrink-0" style="background:<?= $def['color'] ?>"></span>
                    <span class="text-slate-600 text-xs font-medium"><?= $def['label'] ?></span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-slate-800 text-xs font-bold"><?= $statusMap[$key] ?? 0 ?></span>
                    <span class="text-slate-400 text-xs">(<?= round((($statusMap[$key] ?? 0) / $totalOrders) * 100) ?>%)</span>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- ===== BOTTOM ROW ===== -->
<div class="grid grid-cols-1 xl:grid-cols-3 gap-5">

    <!-- Recent Orders -->
    <div class="xl:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
            <div>
                <h3 class="font-bold text-slate-800">Recent Orders</h3>
                <p class="text-slate-400 text-xs mt-0.5">Latest 8 orders</p>
            </div>
            <a href="<?= APP_URL ?>/orders" class="text-xs font-semibold text-indigo-500 hover:text-indigo-700 transition-colors" style="text-decoration:none">
                View all <i class="fas fa-arrow-right ml-1 text-[10px]"></i>
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-50">
                        <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Order</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Customer</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Amount</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <?php if (empty($recentOrders)): ?>
                    <tr><td colspan="5" class="px-5 py-8 text-center text-slate-400 text-sm">No orders yet</td></tr>
                    <?php else: ?>
                    <?php foreach ($recentOrders as $order): ?>
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-5 py-3.5">
                            <span class="mono text-xs font-semibold text-indigo-600">#<?= htmlspecialchars($order['order_number']) ?></span>
                        </td>
                        <td class="px-5 py-3.5">
                            <span class="text-slate-700 text-sm font-medium"><?= htmlspecialchars($order['customer_name'] ?? 'Guest') ?></span>
                        </td>
                        <td class="px-5 py-3.5">
                            <span class="text-slate-800 text-sm font-bold"><?= taka((float)$order['total_amount']) ?></span>
                        </td>
                        <td class="px-5 py-3.5">
                            <?php $s = $order['order_status']; $sd = $statusDefs[$s] ?? null; ?>
                            <?php if ($sd): ?>
                            <span class="badge" style="background:<?= $sd['bg'] ?>;color:<?= $sd['text'] ?>"><?= $sd['label'] ?></span>
                            <?php else: ?>
                            <span class="badge bg-slate-100 text-slate-500"><?= htmlspecialchars($s) ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="px-5 py-3.5">
                            <span class="text-slate-400 text-xs"><?= date('d M, g:ia', strtotime($order['placed_at'])) ?></span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Low Stock + Quick Links -->
    <div class="space-y-5">

        <!-- Low Stock -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100">
            <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
                <div>
                    <h3 class="font-bold text-slate-800">Low Stock Alert</h3>
                    <p class="text-slate-400 text-xs mt-0.5">Products below threshold</p>
                </div>
                <span class="w-7 h-7 rounded-lg bg-red-50 flex items-center justify-center">
                    <i class="fas fa-triangle-exclamation text-red-500 text-xs"></i>
                </span>
            </div>
            <div class="px-5 py-3 divide-y divide-slate-50">
                <?php if (empty($lowStock)): ?>
                <p class="py-4 text-center text-slate-400 text-sm"><i class="fas fa-check-circle text-emerald-400 mr-1"></i>All products well stocked</p>
                <?php else: ?>
                <?php foreach ($lowStock as $p): ?>
                <div class="flex items-center justify-between py-2.5">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-7 h-7 rounded-lg bg-slate-100 flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-box text-slate-400 text-xs"></i>
                        </div>
                        <span class="text-slate-700 text-xs font-medium truncate"><?= htmlspecialchars($p['product_name']) ?></span>
                    </div>
                    <span class="flex-shrink-0 ml-2 text-xs font-bold px-2 py-0.5 rounded-full <?= $p['product_stock'] == 0 ? 'bg-red-100 text-red-600' : 'bg-amber-100 text-amber-700' ?>">
                        <?= $p['product_stock'] === 0 ? 'Out' : $p['product_stock'] . ' left' ?>
                    </span>
                </div>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
            <h3 class="font-bold text-slate-800 mb-4">Quick Actions</h3>
            <div class="space-y-2">
                <?php
                $actions = [
                    ['href'=>'/products/create', 'icon'=>'fa-plus','label'=>'Add New Product','color'=>'indigo'],
                    ['href'=>'/orders/pending',  'icon'=>'fa-clock','label'=>'View Pending Orders','color'=>'amber'],
                    ['href'=>'/coupons',         'icon'=>'fa-tag',  'label'=>'Manage Coupons','color'=>'violet'],
                    ['href'=>'/settings/delivery','icon'=>'fa-truck','label'=>'Delivery Settings','color'=>'sky'],
                ];
                $clrs = ['indigo'=>'bg-indigo-50 text-indigo-600 hover:bg-indigo-100','amber'=>'bg-amber-50 text-amber-600 hover:bg-amber-100','violet'=>'bg-violet-50 text-violet-600 hover:bg-violet-100','sky'=>'bg-sky-50 text-sky-600 hover:bg-sky-100'];
                foreach ($actions as $a):
                ?>
                <a href="<?= APP_URL . $a['href'] ?>" class="flex items-center gap-3 p-3 rounded-xl transition-colors <?= $clrs[$a['color']] ?>" style="text-decoration:none">
                    <i class="fas <?= $a['icon'] ?> text-sm w-4 text-center"></i>
                    <span class="text-sm font-semibold"><?= $a['label'] ?></span>
                    <i class="fas fa-chevron-right text-[10px] ml-auto opacity-50"></i>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<!-- ApexCharts -->
<script>
// Revenue Area Chart
const months  = <?= json_encode($months) ?>;
const revData = <?= json_encode(array_map('floatval', $revenues)) ?>;
const ordData = <?= json_encode(array_map('intval', $orderCts)) ?>;

const revenueChart = new ApexCharts(document.getElementById('revenue-chart'), {
    series: [
        { name: 'Revenue (৳)', data: revData },
        { name: 'Orders', data: ordData },
    ],
    chart: { type: 'area', height: 220, toolbar: { show: false }, zoom: { enabled: false }, fontFamily: 'Plus Jakarta Sans' },
    colors: ['#6366f1', '#e2e8f0'],
    fill: {
        type: 'gradient',
        gradient: { opacityFrom: [0.3, 0.1], opacityTo: [0.05, 0.01], shadeIntensity: 1, type: 'vertical' }
    },
    stroke: { curve: 'smooth', width: [2.5, 2] },
    xaxis: { categories: months.map(m => { const [y,mo] = m.split('-'); return new Date(y,mo-1).toLocaleString('default',{month:'short'})+' '+y; }), labels: { style: { fontSize:'11px', colors:'#94a3b8' } }, axisBorder: { show:false }, axisTicks: { show:false } },
    yaxis: [
        { labels: { formatter: v => '৳'+Number(v).toLocaleString(), style:{ fontSize:'11px', colors:'#94a3b8' } } },
        { opposite: true, labels: { formatter: v => v+' orders', style:{ fontSize:'11px', colors:'#94a3b8' } } },
    ],
    grid: { borderColor: '#f1f5f9', strokeDashArray: 4 },
    tooltip: { y: [{ formatter: v => '৳'+Number(v).toLocaleString() }, { formatter: v => v+' orders' }] },
    legend: { show: false },
    dataLabels: { enabled: false },
    markers: { size: 4, colors: ['#6366f1','#e2e8f0'], strokeColors: '#fff', strokeWidth: 2 },
});
revenueChart.render();

// Donut Chart
const donutData = <?= json_encode(array_values(array_map(fn($k) => (int)($statusMap[$k] ?? 0), array_keys($statusDefs)))) ?>;
const donutLabels = <?= json_encode(array_values(array_column($statusDefs, 'label'))) ?>;
const donutColors = <?= json_encode(array_values(array_column($statusDefs, 'color'))) ?>;

const donut = new ApexCharts(document.getElementById('donut-chart'), {
    series: donutData,
    chart: { type: 'donut', height: 180, fontFamily: 'Plus Jakarta Sans' },
    labels: donutLabels,
    colors: donutColors,
    plotOptions: { pie: { donut: { size: '65%', labels: { show: true, total: { show: true, label: 'Total', fontSize:'12px', fontWeight:700, color:'#334155', formatter: w => w.globals.seriesTotals.reduce((a,b)=>a+b,0) } } } } },
    legend: { show: false },
    dataLabels: { enabled: false },
    stroke: { width: 2, colors: ['#fff'] },
    tooltip: { y: { formatter: v => v + ' orders' } },
});
donut.render();
</script>
