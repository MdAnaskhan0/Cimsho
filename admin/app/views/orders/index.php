<?php
// Display messages
if (isset($_SESSION['success_message'])) {
    echo '<div class="mb-5 p-4 rounded-xl bg-emerald-50 border border-emerald-200">
            <div class="flex items-center gap-3">
                <i class="fas fa-check-circle text-emerald-500"></i>
                <span class="text-emerald-700 text-sm font-medium">' . htmlspecialchars($_SESSION['success_message']) . '</span>
                <button onclick="this.parentElement.parentElement.remove()" class="ml-auto text-emerald-400 hover:text-emerald-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>';
    unset($_SESSION['success_message']);
}

// Define status colors
$statusColors = [
    'pending' => ['bg' => 'bg-amber-50', 'text' => 'text-amber-700', 'label' => 'Pending'],
    'confirmed' => ['bg' => 'bg-blue-50', 'text' => 'text-blue-700', 'label' => 'Confirmed'],
    'shipped' => ['bg' => 'bg-purple-50', 'text' => 'text-purple-700', 'label' => 'Shipped'],
    'delivered' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'label' => 'Delivered'],
    'cancelled' => ['bg' => 'bg-red-50', 'text' => 'text-red-700', 'label' => 'Cancelled']
];
?>

<!-- Page Header -->
<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-xl font-extrabold text-slate-800"><?= $pageTitle ?></h2>
        <nav class="flex items-center gap-2 mt-1 text-xs text-slate-400">
            <a href="<?= APP_URL ?>/dashboard" class="hover:text-indigo-500 transition-colors">Dashboard</a>
            <i class="fas fa-chevron-right text-[9px]"></i>
            <span class="text-slate-600 font-medium">Orders</span>
        </nav>
    </div>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-6">
    <a href="<?= APP_URL ?>/orders" class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100 hover:shadow-md transition-all">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs text-slate-400">Total Orders</p>
                <p class="text-2xl font-bold text-slate-800"><?= number_format($stats['total_orders'] ?? 0) ?></p>
            </div>
            <i class="fas fa-shopping-bag text-indigo-400 text-xl"></i>
        </div>
    </a>

    <a href="<?= APP_URL ?>/orders/pending" class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100 hover:shadow-md transition-all">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs text-amber-500">Pending</p>
                <p class="text-2xl font-bold text-amber-600"><?= number_format($stats['pending_orders'] ?? 0) ?></p>
            </div>
            <i class="fas fa-clock text-amber-400 text-xl"></i>
        </div>
    </a>

    <a href="<?= APP_URL ?>/orders?status=confirmed" class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100 hover:shadow-md transition-all">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs text-blue-500">Confirmed</p>
                <p class="text-2xl font-bold text-blue-600"><?= number_format($stats['confirmed_orders'] ?? 0) ?></p>
            </div>
            <i class="fas fa-check-circle text-blue-400 text-xl"></i>
        </div>
    </a>

    <a href="<?= APP_URL ?>/orders/shipped" class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100 hover:shadow-md transition-all">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs text-purple-500">Shipped</p>
                <p class="text-2xl font-bold text-purple-600"><?= number_format($stats['shipped_orders'] ?? 0) ?></p>
            </div>
            <i class="fas fa-truck text-purple-400 text-xl"></i>
        </div>
    </a>

    <a href="<?= APP_URL ?>/orders?status=delivered" class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100 hover:shadow-md transition-all">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs text-emerald-500">Delivered</p>
                <p class="text-2xl font-bold text-emerald-600"><?= number_format($stats['delivered_orders'] ?? 0) ?></p>
            </div>
            <i class="fas fa-check-double text-emerald-400 text-xl"></i>
        </div>
    </a>

    <a href="<?= APP_URL ?>/orders?status=cancelled" class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100 hover:shadow-md transition-all">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs text-red-500">Cancelled</p>
                <p class="text-2xl font-bold text-red-600"><?= number_format($stats['cancelled_orders'] ?? 0) ?></p>
            </div>
            <i class="fas fa-ban text-red-400 text-xl"></i>
        </div>
    </a>
</div>

<!-- Search & Filter Bar -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4 mb-5">
    <form method="GET" action="<?= APP_URL ?>/orders" class="flex flex-col sm:flex-row gap-3">
        <div class="relative flex-1">
            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"><i class="fas fa-search"></i></span>
            <input type="text"
                name="search"
                value="<?= htmlspecialchars($search ?? '') ?>"
                placeholder="Search by order #, customer name or email..."
                class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
        </div>
        <button type="submit" class="px-4 py-2.5 rounded-xl bg-indigo-600 text-white text-sm font-semibold hover:bg-indigo-700 transition-colors">
            Search
        </button>
        <?php if (!empty($search)): ?>
            <a href="<?= APP_URL ?>/orders" class="px-4 py-2.5 rounded-xl border border-slate-200 text-sm text-slate-600 hover:bg-slate-50 transition-colors flex items-center gap-2">
                <i class="fas fa-times"></i> Clear
            </a>
        <?php endif; ?>
    </form>
</div>

<!-- Orders Table -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
        <h3 class="font-bold text-slate-700 text-sm">
            Showing <span id="recordCount"><?= count($orders) ?></span> of <span id="totalCount"><?= $total ?></span> orders
        </h3>
        <div class="flex items-center gap-2">
            <button onclick="window.location.reload()" class="w-8 h-8 rounded-lg border border-slate-200 hover:bg-slate-50 transition-colors flex items-center justify-center text-slate-500 text-xs">
                <i class="fas fa-sync-alt"></i>
            </button>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-100">
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Order #</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Customer</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Items</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Total</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Payment</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Date</th>
                    <th class="text-right px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                <?php if (empty($orders)): ?>
                    <tr>
                        <td colspan="8" class="px-5 py-16 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-14 h-14 rounded-2xl bg-slate-100 flex items-center justify-center">
                                    <i class="fas fa-inbox text-slate-300 text-2xl"></i>
                                </div>
                                <div>
                                    <p class="text-slate-500 font-semibold text-sm">No orders found</p>
                                    <p class="text-slate-400 text-xs mt-1">Orders will appear here when customers place them.</p>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($orders as $order): ?>
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-5 py-3.5">
                                <a href="<?= APP_URL ?>/orders/show/<?= $order['order_id'] ?>" class="font-mono text-sm font-semibold text-indigo-600 hover:text-indigo-800">
                                    #<?= htmlspecialchars($order['order_number']) ?>
                                </a>
                            </td>
                            <td class="px-5 py-3.5">
                                <div>
                                    <p class="text-sm font-medium text-slate-800"><?= htmlspecialchars($order['customer_name'] ?? 'Guest') ?></p>
                                    <p class="text-xs text-slate-400"><?= htmlspecialchars($order['customer_email'] ?? '') ?></p>
                                </div>
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="text-sm text-slate-600"><?= $order['item_count'] ?> item(s)</span>
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="font-bold text-slate-800">৳ <?= number_format($order['total_amount'], 2) ?></span>
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="text-xs uppercase font-semibold text-slate-600"><?= $order['payment_method'] ?></span>
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="badge <?= $statusColors[$order['order_status']]['bg'] ?> <?= $statusColors[$order['order_status']]['text'] ?>">
                                    <?= $statusColors[$order['order_status']]['label'] ?>
                                </span>
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="text-slate-400 text-xs"><?= date('d M, Y h:i A', strtotime($order['placed_at'])) ?></span>
                            </td>
                            <td class="px-5 py-3.5">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="<?= APP_URL ?>/orders/show/<?= $order['order_id'] ?>"
                                        class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 hover:bg-indigo-100 flex items-center justify-center text-xs transition-colors"
                                        title="View Order">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <?php if ($totalPages > 1 && empty($search)): ?>
        <div class="flex items-center justify-between px-5 py-4 border-t border-slate-100">
            <p class="text-slate-400 text-xs">
                Showing <?= (($page - 1) * 20) + 1 ?> to <?= min($page * 20, $total) ?> of <?= $total ?> entries
            </p>
            <div class="flex items-center gap-1">
                <?php if ($page > 1): ?>
                    <a href="?page=<?= $page - 1 ?>" class="px-3 py-2 rounded-lg border border-slate-200 hover:bg-slate-50 transition-colors flex items-center justify-center text-slate-500 text-xs">
                        <i class="fas fa-chevron-left mr-1"></i> Previous
                    </a>
                <?php endif; ?>

                <?php
                $start = max(1, $page - 2);
                $end = min($totalPages, $page + 2);
                for ($i = $start; $i <= $end; $i++):
                ?>
                    <a href="?page=<?= $i ?>" class="min-w-[32px] h-8 rounded-lg <?= $i == $page ? 'bg-indigo-600 text-white' : 'border border-slate-200 text-slate-500 hover:bg-slate-50' ?> flex items-center justify-center text-xs font-bold transition-colors px-2">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>

                <?php if ($page < $totalPages): ?>
                    <a href="?page=<?= $page + 1 ?>" class="px-3 py-2 rounded-lg border border-slate-200 hover:bg-slate-50 transition-colors flex items-center justify-center text-slate-500 text-xs">
                        Next <i class="fas fa-chevron-right ml-1"></i>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<style>
    .badge {
        display: inline-flex;
        align-items: center;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
    }
</style>