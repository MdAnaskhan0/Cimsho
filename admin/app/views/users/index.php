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

if (isset($_SESSION['error_message'])) {
    echo '<div class="mb-5 p-4 rounded-xl bg-red-50 border border-red-200">
            <div class="flex items-center gap-3">
                <i class="fas fa-exclamation-triangle text-red-500"></i>
                <span class="text-red-700 text-sm font-medium">' . htmlspecialchars($_SESSION['error_message']) . '</span>
                <button onclick="this.parentElement.parentElement.remove()" class="ml-auto text-red-400 hover:text-red-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>';
    unset($_SESSION['error_message']);
}
?>

<!-- Page Header -->
<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-xl font-extrabold text-slate-800">Customers</h2>
        <nav class="flex items-center gap-2 mt-1 text-xs text-slate-400">
            <a href="<?= APP_URL ?>/dashboard" class="hover:text-indigo-500 transition-colors">Dashboard</a>
            <i class="fas fa-chevron-right text-[9px]"></i>
            <span class="text-slate-600 font-medium">Customers</span>
        </nav>
    </div>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-5 mb-6">
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center">
                <i class="fas fa-users text-indigo-500"></i>
            </div>
            <span class="text-2xl font-bold text-slate-800"><?= number_format($stats['total_users'] ?? 0) ?></span>
        </div>
        <p class="text-slate-600 text-sm font-medium">Total Customers</p>
    </div>

    <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center">
                <i class="fas fa-user-check text-emerald-500"></i>
            </div>
            <span class="text-2xl font-bold text-slate-800"><?= number_format($stats['active_users'] ?? 0) ?></span>
        </div>
        <p class="text-slate-600 text-sm font-medium">Active Customers</p>
    </div>

    <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center">
                <i class="fas fa-user-slash text-red-500"></i>
            </div>
            <span class="text-2xl font-bold text-slate-800"><?= number_format($stats['inactive_users'] ?? 0) ?></span>
        </div>
        <p class="text-slate-600 text-sm font-medium">Inactive Customers</p>
    </div>

    <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center">
                <i class="fas fa-calendar-day text-amber-500"></i>
            </div>
            <span class="text-2xl font-bold text-slate-800"><?= number_format($stats['today_registered'] ?? 0) ?></span>
        </div>
        <p class="text-slate-600 text-sm font-medium">Registered Today</p>
    </div>

    <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 rounded-xl bg-purple-50 flex items-center justify-center">
                <i class="fas fa-chart-line text-purple-500"></i>
            </div>
            <span class="text-2xl font-bold text-slate-800"><?= number_format($stats['recent_registrations'] ?? 0) ?></span>
        </div>
        <p class="text-slate-600 text-sm font-medium">Last 7 Days</p>
    </div>
</div>

<!-- Search & Filter Bar -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4 mb-5">
    <form method="GET" action="<?= APP_URL ?>/users" class="flex flex-col sm:flex-row gap-3">
        <div class="relative flex-1">
            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"><i class="fas fa-search"></i></span>
            <input type="text"
                name="search"
                value="<?= htmlspecialchars($search ?? '') ?>"
                placeholder="Search by name, email or phone..."
                class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
        </div>
        <button type="submit" class="px-4 py-2.5 rounded-xl bg-indigo-600 text-white text-sm font-semibold hover:bg-indigo-700 transition-colors">
            Search
        </button>
        <?php if (!empty($search)): ?>
            <a href="<?= APP_URL ?>/users" class="px-4 py-2.5 rounded-xl border border-slate-200 text-sm text-slate-600 hover:bg-slate-50 transition-colors flex items-center gap-2">
                <i class="fas fa-times"></i> Clear
            </a>
        <?php endif; ?>
    </form>
</div>

<!-- Customers Table -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
        <h3 class="font-bold text-slate-700 text-sm">
            Showing <span id="recordCount"><?= count($users) ?></span> of <span id="totalCount"><?= $total ?></span> customers
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
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">ID</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Customer</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Contact</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Addresses</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Orders</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Registered</th>
                    <th class="text-right px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                <?php if (empty($users)): ?>
                    <tr>
                        <td colspan="8" class="px-5 py-16 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-14 h-14 rounded-2xl bg-slate-100 flex items-center justify-center">
                                    <i class="fas fa-users-slash text-slate-300 text-2xl"></i>
                                </div>
                                <div>
                                    <p class="text-slate-500 font-semibold text-sm">No customers found</p>
                                    <p class="text-slate-400 text-xs mt-1">Customers will appear here when they register.</p>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($users as $user): ?>
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-5 py-3.5">
                                <span class="text-slate-500 text-sm font-mono">#<?= $user['user_id'] ?></span>
                            </td>

                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-3">
                                    <?php if ($user['avatar']): ?>
                                        <img src="<?= APP_URL ?>/uploads/avatars/<?= $user['avatar'] ?>" alt="<?= htmlspecialchars($user['name']) ?>" class="w-10 h-10 rounded-full object-cover border border-slate-200">
                                    <?php else: ?>
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-500 flex items-center justify-center text-white font-bold text-sm">
                                            <?= strtoupper(substr($user['name'], 0, 1)) ?>
                                        </div>
                                    <?php endif; ?>
                                    <div>
                                        <p class="font-semibold text-slate-800 text-sm"><?= htmlspecialchars($user['name']) ?></p>
                                        <p class="text-xs text-slate-400"><?= htmlspecialchars($user['role']) ?></p>
                                    </div>
                                </div>
                            </td>

                            <td class="px-5 py-3.5">
                                <div class="space-y-1">
                                    <p class="text-xs text-slate-600">
                                        <i class="fas fa-envelope text-slate-400 mr-1 text-[10px]"></i>
                                        <?= htmlspecialchars($user['email']) ?>
                                    </p>
                                    <?php if ($user['phone']): ?>
                                        <p class="text-xs text-slate-600">
                                            <i class="fas fa-phone text-slate-400 mr-1 text-[10px]"></i>
                                            <?= htmlspecialchars($user['phone']) ?>
                                        </p>
                                    <?php endif; ?>
                                </div>
                            </td>

                            <td class="px-5 py-3.5">
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-indigo-50 text-indigo-600 text-xs font-semibold">
                                    <i class="fas fa-map-marker-alt text-[10px]"></i>
                                    <?= $user['address_count'] ?> Address(es)
                                </span>
                            </td>

                            <td class="px-5 py-3.5">
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-emerald-50 text-emerald-600 text-xs font-semibold">
                                    <i class="fas fa-shopping-bag text-[10px]"></i>
                                    <?= $user['order_count'] ?> Orders
                                </span>
                            </td>

                            <td class="px-5 py-3.5">
                                <span class="badge <?= $user['is_active'] ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-700' ?>">
                                    <?= $user['is_active'] ? 'Active' : 'Inactive' ?>
                                </span>
                            </td>

                            <td class="px-5 py-3.5">
                                <span class="text-slate-400 text-xs"><?= date('d M, Y', strtotime($user['created_at'])) ?></span>
                            </td>

                            <td class="px-5 py-3.5">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="<?= APP_URL ?>/users/view/<?= $user['user_id'] ?>"
                                        class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 hover:bg-indigo-100 flex items-center justify-center text-xs transition-colors"
                                        title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <button onclick="toggleStatus(<?= $user['user_id'] ?>, <?= $user['is_active'] ?>)"
                                        class="w-8 h-8 rounded-lg bg-slate-50 text-slate-600 hover:bg-slate-100 flex items-center justify-center text-xs transition-colors"
                                        title="Toggle Status">
                                        <i class="fas <?= $user['is_active'] ? 'fa-ban' : 'fa-check-circle' ?>"></i>
                                    </button>
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

<script>
    function toggleStatus(id, currentStatus) {
        const action = currentStatus == 1 ? 'deactivate' : 'activate';
        if (confirm(`Are you sure you want to ${action} this customer?`)) {
            fetch('<?= APP_URL ?>/users/toggle-status/' + id, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'csrf_token=<?= $csrf ?>'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Failed to update status');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred');
                });
        }
    }
</script>

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