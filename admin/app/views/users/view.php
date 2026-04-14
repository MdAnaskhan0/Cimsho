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
?>

<!-- Page Header -->
<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-xl font-extrabold text-slate-800">Customer Details</h2>
        <nav class="flex items-center gap-2 mt-1 text-xs text-slate-400">
            <a href="<?= APP_URL ?>/dashboard" class="hover:text-indigo-500 transition-colors">Dashboard</a>
            <i class="fas fa-chevron-right text-[9px]"></i>
            <a href="<?= APP_URL ?>/users" class="hover:text-indigo-500 transition-colors">Customers</a>
            <i class="fas fa-chevron-right text-[9px]"></i>
            <span class="text-slate-600 font-medium"><?= htmlspecialchars($user['name']) ?></span>
        </nav>
    </div>
    <div class="flex items-center gap-3">
        <a href="<?= APP_URL ?>/users" class="flex items-center gap-2 px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 text-sm font-semibold hover:bg-slate-50 transition-all">
            <i class="fas fa-arrow-left text-xs"></i>
            Back to Customers
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- Customer Profile Card -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden sticky top-6">
            <div class="p-6 text-center border-b border-slate-100">
                <?php if ($user['avatar']): ?>
                    <img src="<?= APP_URL ?>/uploads/avatars/<?= $user['avatar'] ?>" alt="<?= htmlspecialchars($user['name']) ?>" class="w-24 h-24 rounded-full object-cover border-4 border-indigo-100 mx-auto mb-4">
                <?php else: ?>
                    <div class="w-24 h-24 rounded-full bg-gradient-to-br from-indigo-500 to-purple-500 flex items-center justify-center text-white text-3xl font-bold mx-auto mb-4">
                        <?= strtoupper(substr($user['name'], 0, 1)) ?>
                    </div>
                <?php endif; ?>

                <h3 class="text-lg font-bold text-slate-800"><?= htmlspecialchars($user['name']) ?></h3>
                <p class="text-xs text-slate-400 mt-1">Customer since <?= date('F Y', strtotime($user['created_at'])) ?></p>

                <div class="mt-3">
                    <span class="badge <?= $user['is_active'] ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-700' ?>">
                        <?= $user['is_active'] ? 'Active Account' : 'Inactive Account' ?>
                    </span>
                </div>
            </div>

            <div class="p-4 space-y-3">
                <div class="flex items-center gap-3 text-sm p-2 rounded-lg hover:bg-slate-50">
                    <i class="fas fa-envelope text-slate-400 w-5"></i>
                    <div>
                        <p class="text-xs text-slate-400">Email</p>
                        <p class="text-slate-700"><?= htmlspecialchars($user['email']) ?></p>
                    </div>
                </div>

                <?php if ($user['phone']): ?>
                    <div class="flex items-center gap-3 text-sm p-2 rounded-lg hover:bg-slate-50">
                        <i class="fas fa-phone text-slate-400 w-5"></i>
                        <div>
                            <p class="text-xs text-slate-400">Phone</p>
                            <p class="text-slate-700"><?= htmlspecialchars($user['phone']) ?></p>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="flex items-center gap-3 text-sm p-2 rounded-lg hover:bg-slate-50">
                    <i class="fas fa-calendar-alt text-slate-400 w-5"></i>
                    <div>
                        <p class="text-xs text-slate-400">Registered On</p>
                        <p class="text-slate-700"><?= date('F d, Y h:i A', strtotime($user['created_at'])) ?></p>
                    </div>
                </div>

                <div class="flex items-center gap-3 text-sm p-2 rounded-lg hover:bg-slate-50">
                    <i class="fas fa-tag text-slate-400 w-5"></i>
                    <div>
                        <p class="text-xs text-slate-400">Role</p>
                        <p class="text-slate-700 capitalize"><?= $user['role'] ?></p>
                    </div>
                </div>
            </div>

            <div class="p-4 border-t border-slate-100">
                <button onclick="toggleStatus(<?= $user['user_id'] ?>, <?= $user['is_active'] ?>)"
                    class="w-full py-2.5 rounded-xl <?= $user['is_active'] ? 'bg-red-50 text-red-600 hover:bg-red-100' : 'bg-emerald-50 text-emerald-600 hover:bg-emerald-100' ?> text-sm font-semibold transition-colors">
                    <i class="fas <?= $user['is_active'] ? 'fa-ban' : 'fa-check-circle' ?> mr-2"></i>
                    <?= $user['is_active'] ? 'Deactivate Account' : 'Activate Account' ?>
                </button>
            </div>
        </div>
    </div>

    <!-- Customer Details -->
    <div class="lg:col-span-2 space-y-6">

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 rounded-2xl p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-indigo-600 font-semibold">Total Orders</p>
                        <p class="text-2xl font-bold text-indigo-800"><?= number_format($user['total_orders'] ?? 0) ?></p>
                    </div>
                    <i class="fas fa-shopping-bag text-indigo-400 text-2xl"></i>
                </div>
            </div>

            <div class="bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-2xl p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-emerald-600 font-semibold">Total Spent</p>
                        <p class="text-2xl font-bold text-emerald-800">৳ <?= number_format($user['total_spent'] ?? 0, 2) ?></p>
                    </div>
                    <i class="fas fa-money-bill-wave text-emerald-400 text-2xl"></i>
                </div>
            </div>

            <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-2xl p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-purple-600 font-semibold">Saved Addresses</p>
                        <p class="text-2xl font-bold text-purple-800"><?= count($user['addresses']) ?></p>
                    </div>
                    <i class="fas fa-map-marker-alt text-purple-400 text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Addresses Section -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100">
                <h3 class="font-bold text-slate-800 flex items-center gap-2">
                    <i class="fas fa-map-marker-alt text-indigo-500"></i>
                    Saved Addresses
                </h3>
            </div>

            <div class="p-5">
                <?php if (empty($user['addresses'])): ?>
                    <div class="text-center py-8">
                        <i class="fas fa-map-marker-alt text-slate-300 text-4xl mb-3"></i>
                        <p class="text-slate-400">No saved addresses found</p>
                    </div>
                <?php else: ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <?php foreach ($user['addresses'] as $address): ?>
                            <div class="border border-slate-100 rounded-xl p-4 hover:shadow-md transition-shadow">
                                <div class="flex items-start justify-between mb-2">
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-<?= $address['label'] == 'home' ? 'home' : ($address['label'] == 'office' ? 'briefcase' : 'map-pin') ?> text-indigo-500"></i>
                                        <span class="font-semibold text-slate-700 capitalize"><?= $address['label'] ?></span>
                                        <?php if ($address['is_default']): ?>
                                            <span class="text-xs bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded-full">Default</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <p class="text-sm text-slate-700 font-medium mt-2"><?= htmlspecialchars($address['full_name']) ?></p>
                                <p class="text-xs text-slate-500 mt-1">📞 <?= htmlspecialchars($address['phone']) ?></p>
                                <p class="text-xs text-slate-500 mt-1">
                                    <?= htmlspecialchars($address['address_line']) ?>
                                </p>
                                <p class="text-xs text-slate-500">
                                    <?= htmlspecialchars($address['area']) ?>, <?= htmlspecialchars($address['city']) ?>
                                    <?php if ($address['postal_code']): ?> - <?= $address['postal_code'] ?><?php endif; ?>
                                </p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100">
                <h3 class="font-bold text-slate-800 flex items-center gap-2">
                    <i class="fas fa-bolt text-amber-500"></i>
                    Quick Actions
                </h3>
            </div>
            <div class="p-5">
                <div class="flex flex-wrap gap-3">
                    <a href="mailto:<?= $user['email'] ?>" class="px-4 py-2 rounded-xl bg-indigo-50 text-indigo-600 text-sm font-semibold hover:bg-indigo-100 transition-colors">
                        <i class="fas fa-envelope mr-2"></i> Send Email
                    </a>
                    <?php if ($user['phone']): ?>
                        <a href="tel:<?= $user['phone'] ?>" class="px-4 py-2 rounded-xl bg-emerald-50 text-emerald-600 text-sm font-semibold hover:bg-emerald-100 transition-colors">
                            <i class="fas fa-phone mr-2"></i> Call Customer
                        </a>
                    <?php endif; ?>
                    <a href="<?= APP_URL ?>/orders?user_id=<?= $user['user_id'] ?>" class="px-4 py-2 rounded-xl bg-purple-50 text-purple-600 text-sm font-semibold hover:bg-purple-100 transition-colors">
                        <i class="fas fa-shopping-bag mr-2"></i> View Orders
                    </a>
                </div>
            </div>
        </div>
    </div>
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