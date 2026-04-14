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
        <h2 class="text-xl font-extrabold text-slate-800">Coupons</h2>
        <nav class="flex items-center gap-2 mt-1 text-xs text-slate-400">
            <a href="<?= APP_URL ?>/dashboard" class="hover:text-indigo-500 transition-colors">Dashboard</a>
            <i class="fas fa-chevron-right text-[9px]"></i>
            <span class="text-slate-600 font-medium">Coupons</span>
        </nav>
    </div>
    <div class="flex items-center gap-3">
        <a href="<?= APP_URL ?>/coupons/create" class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-white text-sm font-semibold transition-all hover:opacity-90" style="background:linear-gradient(135deg,#6366f1,#8b5cf6)">
            <i class="fas fa-plus text-xs"></i>
            Add New Coupon
        </a>
    </div>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-6">
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center">
                <i class="fas fa-tag text-indigo-500"></i>
            </div>
            <span class="text-2xl font-bold text-slate-800"><?= $stats['total_coupons'] ?? 0 ?></span>
        </div>
        <p class="text-slate-600 text-sm font-medium">Total Coupons</p>
    </div>

    <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center">
                <i class="fas fa-check-circle text-emerald-500"></i>
            </div>
            <span class="text-2xl font-bold text-slate-800"><?= $stats['active_coupons'] ?? 0 ?></span>
        </div>
        <p class="text-slate-600 text-sm font-medium">Active Coupons</p>
    </div>

    <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center">
                <i class="fas fa-users text-amber-500"></i>
            </div>
            <span class="text-2xl font-bold text-slate-800"><?= number_format($stats['total_uses'] ?? 0) ?></span>
        </div>
        <p class="text-slate-600 text-sm font-medium">Total Uses</p>
    </div>

    <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 rounded-xl bg-purple-50 flex items-center justify-center">
                <i class="fas fa-percent text-purple-500"></i>
            </div>
            <span class="text-2xl font-bold text-slate-800"><?= round($stats['avg_discount'] ?? 0, 1) ?>%</span>
        </div>
        <p class="text-slate-600 text-sm font-medium">Avg. Discount</p>
    </div>
</div>

<!-- Search & Filter Bar -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4 mb-5">
    <div class="flex flex-col sm:flex-row gap-3">
        <div class="relative flex-1">
            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"><i class="fas fa-search"></i></span>
            <input type="text" id="searchInput" placeholder="Search by coupon code..." class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
        </div>
        <select id="statusFilter" class="px-4 py-2.5 rounded-xl border border-slate-200 text-sm text-slate-600 focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <option value="all">All Status</option>
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
            <option value="expired">Expired</option>
            <option value="exhausted">Exhausted</option>
        </select>
        <button onclick="resetFilters()" class="px-4 py-2.5 rounded-xl border border-slate-200 text-sm text-slate-600 hover:bg-slate-50 transition-colors flex items-center gap-2">
            <i class="fas fa-undo-alt text-xs"></i> Reset
        </button>
    </div>
</div>

<!-- Coupons Table -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
        <h3 class="font-bold text-slate-700 text-sm">Showing <span id="recordCount"><?= count($coupons) ?></span> of <span id="totalCount"><?= $total ?></span> coupons</h3>
        <div class="flex items-center gap-2">
            <button onclick="window.location.reload()" class="w-8 h-8 rounded-lg border border-slate-200 hover:bg-slate-50 transition-colors flex items-center justify-center text-slate-500 text-xs">
                <i class="fas fa-sync-alt"></i>
            </button>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full" id="couponsTable">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-100">
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">ID</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Coupon Code</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Discount</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Min. Order</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Usage</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Expires</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Created</th>
                    <th class="text-right px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50" id="tableBody">
                <?php if (empty($coupons)): ?>
                    <tr id="emptyRow">
                        <td colspan="9" class="px-5 py-16 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-14 h-14 rounded-2xl bg-slate-100 flex items-center justify-center">
                                    <i class="fas fa-tag text-slate-300 text-2xl"></i>
                                </div>
                                <div>
                                    <p class="text-slate-500 font-semibold text-sm">No coupons found</p>
                                    <p class="text-slate-400 text-xs mt-1">Create your first coupon to get started.</p>
                                    <a href="<?= APP_URL ?>/coupons/create" class="inline-flex items-center gap-2 mt-3 px-4 py-2 rounded-lg bg-indigo-50 text-indigo-600 text-xs font-semibold hover:bg-indigo-100 transition-colors">
                                        <i class="fas fa-plus text-xs"></i> Add Coupon
                                    </a>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($coupons as $coupon): ?>
                        <tr class="coupon-row hover:bg-slate-50/50 transition-colors"
                            data-status="<?= $coupon['status'] ?>"
                            data-code="<?= strtolower(htmlspecialchars($coupon['code'])) ?>">

                            <td class="px-5 py-3.5">
                                <span class="text-slate-500 text-sm font-mono">#<?= $coupon['id'] ?></span>
                            </td>

                            <td class="px-5 py-3.5">
                                <code class="text-sm font-bold text-indigo-600 bg-indigo-50 px-3 py-1.5 rounded-lg"><?= htmlspecialchars($coupon['code']) ?></code>
                            </td>

                            <td class="px-5 py-3.5">
                                <span class="font-semibold text-emerald-600 text-sm"><?= $coupon['discount_pct'] ?>% OFF</span>
                            </td>

                            <td class="px-5 py-3.5">
                                <span class="text-slate-600 text-sm">৳ <?= number_format($coupon['min_order'], 2) ?></span>
                            </td>

                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-2">
                                    <span class="text-sm font-semibold text-slate-700"><?= $coupon['used_count'] ?></span>
                                    <span class="text-slate-400 text-xs">/ <?= $coupon['max_uses'] ?></span>
                                    <?php
                                    $percentage = ($coupon['used_count'] / $coupon['max_uses']) * 100;
                                    $barColor = $percentage >= 90 ? 'bg-red-500' : ($percentage >= 70 ? 'bg-amber-500' : 'bg-emerald-500');
                                    ?>
                                    <div class="flex-1 max-w-20 bg-slate-100 rounded-full h-1.5">
                                        <div class="<?= $barColor ?> h-1.5 rounded-full" style="width: <?= $percentage ?>%"></div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-5 py-3.5">
                                <?php if ($coupon['expires_at']): ?>
                                    <?php
                                    $expiryDate = new DateTime($coupon['expires_at']);
                                    $today = new DateTime();
                                    $isExpired = $expiryDate < $today;
                                    ?>
                                    <span class="text-xs <?= $isExpired ? 'text-red-500' : 'text-slate-500' ?>">
                                        <?= date('d M, Y', strtotime($coupon['expires_at'])) ?>
                                        <?php if ($isExpired): ?>
                                            <span class="block text-red-400 text-[10px]">Expired</span>
                                        <?php endif; ?>
                                    </span>
                                <?php else: ?>
                                    <span class="text-xs text-slate-400">Never expires</span>
                                <?php endif; ?>
                            </td>

                            <td class="px-5 py-3.5">
                                <?php
                                $statusConfig = [
                                    'active' => ['class' => 'bg-emerald-50 text-emerald-700', 'text' => 'Active'],
                                    'inactive' => ['class' => 'bg-slate-100 text-slate-500', 'text' => 'Inactive'],
                                    'expired' => ['class' => 'bg-red-50 text-red-700', 'text' => 'Expired'],
                                    'exhausted' => ['class' => 'bg-amber-50 text-amber-700', 'text' => 'Exhausted']
                                ];
                                $config = $statusConfig[$coupon['status']] ?? $statusConfig['inactive'];
                                ?>
                                <span class="badge <?= $config['class'] ?>">
                                    <?= $config['text'] ?>
                                </span>
                            </td>

                            <td class="px-5 py-3.5">
                                <span class="text-slate-400 text-xs"><?= date('d M, Y', strtotime($coupon['created_at'])) ?></span>
                            </td>

                            <td class="px-5 py-3.5">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="<?= APP_URL ?>/coupons/edit/<?= $coupon['id'] ?>"
                                        class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 hover:bg-indigo-100 flex items-center justify-center text-xs transition-colors"
                                        title="Edit Coupon">
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>
                                    <?php if ($coupon['status'] != 'expired' && $coupon['status'] != 'exhausted'): ?>
                                        <button onclick="toggleStatus(<?= $coupon['id'] ?>, <?= $coupon['is_active'] ?>)"
                                            class="w-8 h-8 rounded-lg bg-slate-50 text-slate-600 hover:bg-slate-100 flex items-center justify-center text-xs transition-colors"
                                            title="Toggle Status">
                                            <i class="fas <?= $coupon['is_active'] ? 'fa-eye-slash' : 'fa-eye' ?>"></i>
                                        </button>
                                    <?php endif; ?>
                                    <button onclick="confirmDelete(<?= $coupon['id'] ?>, '<?= htmlspecialchars(addslashes($coupon['code'])) ?>')"
                                        class="w-8 h-8 rounded-lg bg-red-50 text-red-500 hover:bg-red-100 flex items-center justify-center text-xs transition-colors"
                                        title="Delete Coupon">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </td>
                            </td>
                        <?php endforeach; ?>
                    <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <?php if ($totalPages > 1): ?>
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

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden" style="backdrop-filter: blur(4px);">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full mx-4">
        <div class="p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-red-500 text-lg"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-800">Delete Coupon</h3>
            </div>
            <p class="text-slate-600 text-sm mb-6">
                Are you sure you want to delete coupon <strong id="deleteCouponCode"></strong>? This action cannot be undone.
            </p>
            <div class="flex items-center justify-end gap-3">
                <button onclick="closeDeleteModal()" class="px-4 py-2 rounded-lg border border-slate-200 text-slate-600 text-sm font-semibold hover:bg-slate-50 transition-colors">
                    Cancel
                </button>
                <form method="POST" id="deleteForm" action="">
                    <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
                    <button type="submit" class="px-4 py-2 rounded-lg bg-red-500 text-white text-sm font-semibold hover:bg-red-600 transition-colors">
                        Yes, Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function confirmDelete(id, code) {
        document.getElementById('deleteCouponCode').textContent = code;
        document.getElementById('deleteForm').action = '<?= APP_URL ?>/coupons/delete/' + id;
        document.getElementById('deleteModal').classList.remove('hidden');
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
    }

    function toggleStatus(id, currentStatus) {
        if (confirm('Change coupon status?')) {
            fetch('<?= APP_URL ?>/coupons/toggle-status/' + id, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'csrf_token=<?= $csrf ?>'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) location.reload();
                    else alert('Failed to change status');
                });
        }
    }

    // Filter functionality
    function filterTable() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();
        const statusFilter = document.getElementById('statusFilter').value;
        const rows = document.querySelectorAll('.coupon-row');
        let visibleCount = 0;

        rows.forEach(row => {
            const code = row.getAttribute('data-code');
            const status = row.getAttribute('data-status');

            let matchesSearch = code.includes(searchTerm);
            let matchesStatus = statusFilter === 'all' || status === statusFilter;

            if (matchesSearch && matchesStatus) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        document.getElementById('recordCount').textContent = visibleCount;
    }

    function resetFilters() {
        document.getElementById('searchInput').value = '';
        document.getElementById('statusFilter').value = 'all';
        filterTable();
    }

    document.getElementById('searchInput').addEventListener('input', filterTable);
    document.getElementById('statusFilter').addEventListener('change', filterTable);
    document.getElementById('deleteModal').addEventListener('click', function(e) {
        if (e.target === this) closeDeleteModal();
    });
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