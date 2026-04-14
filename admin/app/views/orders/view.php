<?php
$statusColors = [
    'pending' => ['bg' => 'bg-amber-50', 'text' => 'text-amber-700', 'border' => 'border-amber-200', 'label' => 'Pending'],
    'confirmed' => ['bg' => 'bg-blue-50', 'text' => 'text-blue-700', 'border' => 'border-blue-200', 'label' => 'Confirmed'],
    'shipped' => ['bg' => 'bg-purple-50', 'text' => 'text-purple-700', 'border' => 'border-purple-200', 'label' => 'Shipped'],
    'delivered' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'border' => 'border-emerald-200', 'label' => 'Delivered'],
    'cancelled' => ['bg' => 'bg-red-50', 'text' => 'text-red-700', 'border' => 'border-red-200', 'label' => 'Cancelled']
];

$nextStatuses = [
    'pending' => ['confirmed' => 'Confirm Order', 'cancelled' => 'Cancel Order'],
    'confirmed' => ['shipped' => 'Mark as Shipped', 'cancelled' => 'Cancel Order'],
    'shipped' => ['delivered' => 'Mark as Delivered'],
    'delivered' => [],
    'cancelled' => []
];
?>

<!-- Page Header -->
<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-xl font-extrabold text-slate-800">Order #<?= htmlspecialchars($order['order_number']) ?></h2>
        <nav class="flex items-center gap-2 mt-1 text-xs text-slate-400">
            <a href="<?= APP_URL ?>/dashboard" class="hover:text-indigo-500">Dashboard</a>
            <i class="fas fa-chevron-right text-[9px]"></i>
            <a href="<?= APP_URL ?>/orders" class="hover:text-indigo-500">Orders</a>
            <i class="fas fa-chevron-right text-[9px]"></i>
            <span class="text-slate-600 font-medium">#<?= htmlspecialchars($order['order_number']) ?></span>
        </nav>
    </div>
    <div class="flex items-center gap-3">
        <a href="<?= APP_URL ?>/orders" class="flex items-center gap-2 px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 text-sm font-semibold hover:bg-slate-50 transition-all">
            <i class="fas fa-arrow-left text-xs"></i>
            Back to Orders
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- Order Info & Status -->
    <div class="lg:col-span-2 space-y-6">

        <!-- Order Status Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 bg-gradient-to-r from-indigo-50 to-purple-50">
                <h3 class="font-bold text-slate-800 flex items-center gap-2">
                    <i class="fas fa-truck text-indigo-500"></i>
                    Order Status
                </h3>
            </div>
            <div class="p-5">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <p class="text-xs text-slate-400">Current Status</p>
                        <span class="badge <?= $statusColors[$order['order_status']]['bg'] ?> <?= $statusColors[$order['order_status']]['text'] ?> text-sm px-3 py-1.5 mt-1">
                            <?= $statusColors[$order['order_status']]['label'] ?>
                        </span>
                    </div>
                    <?php if ($order['tracking_number']): ?>
                        <div class="text-right">
                            <p class="text-xs text-slate-400">Tracking Number</p>
                            <p class="text-sm font-mono text-indigo-600"><?= htmlspecialchars($order['tracking_number']) ?></p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Status Update Form -->
                <?php if (!empty($nextStatuses[$order['order_status']])): ?>
                    <div class="mt-4 pt-4 border-t border-slate-100">
                        <form id="statusForm" class="space-y-3">
                            <input type="hidden" name="order_id" value="<?= $order['order_id'] ?>">
                            <input type="hidden" name="csrf_token" value="<?= $csrf ?>">

                            <div class="flex flex-wrap gap-3">
                                <?php foreach ($nextStatuses[$order['order_status']] as $status => $label): ?>
                                    <button type="button" onclick="updateStatus('<?= $status ?>')"
                                        class="px-4 py-2 rounded-xl <?= $status == 'cancelled' ? 'bg-red-500 hover:bg-red-600' : 'bg-indigo-600 hover:bg-indigo-700' ?> text-white text-sm font-semibold transition-colors">
                                        <i class="fas <?= $status == 'cancelled' ? 'fa-times' : 'fa-check' ?> mr-1"></i>
                                        <?= $label ?>
                                    </button>
                                <?php endforeach; ?>
                            </div>

                            <div id="noteField" class="hidden">
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Add Note (Optional)</label>
                                <textarea id="statusNote" rows="2" class="w-full px-4 py-2.5 rounded-xl border border-slate-200" placeholder="Add a note about this status change..."></textarea>
                            </div>
                        </form>

                        <!-- Tracking Number Update -->
                        <?php if ($order['order_status'] == 'shipped'): ?>
                            <div class="mt-4 pt-4 border-t border-slate-100">
                                <form id="trackingForm" class="flex gap-3">
                                    <input type="hidden" name="order_id" value="<?= $order['order_id'] ?>">
                                    <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
                                    <div class="flex-1">
                                        <input type="text" id="trackingNumber" name="tracking_number" value="<?= htmlspecialchars($order['tracking_number'] ?? '') ?>"
                                            placeholder="Enter tracking number" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm">
                                    </div>
                                    <button type="button" onclick="updateTracking()" class="px-4 py-2.5 rounded-xl bg-indigo-600 text-white text-sm font-semibold hover:bg-indigo-700">
                                        Update Tracking
                                    </button>
                                </form>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Order Items -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100">
                <h3 class="font-bold text-slate-800 flex items-center gap-2">
                    <i class="fas fa-boxes text-indigo-500"></i>
                    Order Items
                </h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500">Product</th>
                            <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500">Size</th>
                            <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500">Color</th>
                            <th class="text-center px-5 py-3 text-xs font-semibold text-slate-500">Qty</th>
                            <th class="text-right px-5 py-3 text-xs font-semibold text-slate-500">Price</th>
                            <th class="text-right px-5 py-3 text-xs font-semibold text-slate-500">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        <?php foreach ($order['items'] as $item): ?>
                            <tr>
                                <td class="px-5 py-3.5">
                                    <span class="text-sm font-medium text-slate-800"><?= htmlspecialchars($item['product_name'] ?? 'Product #' . $item['product_id']) ?></span>
                                </td>
                                <td class="px-5 py-3.5">
                                    <span class="text-sm text-slate-600"><?= htmlspecialchars($item['size'] ?? '-') ?></span>
                                </td>
                                <td class="px-5 py-3.5">
                                    <span class="text-sm text-slate-600"><?= htmlspecialchars($item['color'] ?? '-') ?></span>
                                </td>
                                <td class="px-5 py-3.5 text-center">
                                    <span class="text-sm text-slate-600"><?= $item['qty'] ?></span>
                                </td>
                                <td class="px-5 py-3.5 text-right">
                                    <span class="text-sm text-slate-600">৳ <?= number_format($item['unit_price'], 2) ?></span>
                                </td>
                                <td class="px-5 py-3.5 text-right">
                                    <span class="text-sm font-semibold text-slate-800">৳ <?= number_format($item['qty'] * $item['unit_price'], 2) ?></span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot class="bg-slate-50 border-t border-slate-100">
                        <tr>
                            <td colspan="5" class="px-5 py-3 text-right font-semibold text-slate-700">Subtotal:</td>
                            <td class="px-5 py-3 text-right font-semibold text-slate-800">৳ <?= number_format($order['total_amount'] - $order['shipping_charge'], 2) ?></td>
                        </tr>
                        <tr>
                            <td colspan="5" class="px-5 py-3 text-right font-semibold text-slate-700">Shipping Charge:</td>
                            <td class="px-5 py-3 text-right font-semibold text-slate-800">৳ <?= number_format($order['shipping_charge'], 2) ?></td>
                        </tr>
                        <tr class="border-t border-slate-200">
                            <td colspan="5" class="px-5 py-3 text-right font-bold text-slate-800">Total:</td>
                            <td class="px-5 py-3 text-right font-bold text-indigo-600 text-lg">৳ <?= number_format($order['total_amount'], 2) ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Status History -->
        <?php if (!empty($order['status_logs'])): ?>
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100">
                    <h3 class="font-bold text-slate-800 flex items-center gap-2">
                        <i class="fas fa-history text-indigo-500"></i>
                        Status History
                    </h3>
                </div>
                <div class="p-5">
                    <div class="space-y-3">
                        <?php foreach ($order['status_logs'] as $log): ?>
                            <div class="flex items-start gap-3">
                                <div class="w-2 h-2 rounded-full bg-indigo-500 mt-2"></div>
                                <div class="flex-1">
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm font-semibold text-slate-700 capitalize"><?= $log['status'] ?></span>
                                        <span class="text-xs text-slate-400"><?= date('d M, Y h:i A', strtotime($log['created_at'])) ?></span>
                                    </div>
                                    <?php if ($log['note']): ?>
                                        <p class="text-xs text-slate-500 mt-1"><?= htmlspecialchars($log['note']) ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Customer Info Sidebar -->
    <div class="space-y-6">

        <!-- Customer Information -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 bg-gradient-to-r from-indigo-50 to-purple-50">
                <h3 class="font-bold text-slate-800 flex items-center gap-2">
                    <i class="fas fa-user text-indigo-500"></i>
                    Customer Information
                </h3>
            </div>
            <div class="p-5 space-y-3">
                <div>
                    <p class="text-xs text-slate-400">Name</p>
                    <p class="text-sm font-medium text-slate-800"><?= htmlspecialchars($order['customer_name'] ?? 'Guest') ?></p>
                </div>
                <div>
                    <p class="text-xs text-slate-400">Email</p>
                    <p class="text-sm text-slate-600"><?= htmlspecialchars($order['customer_email'] ?? '-') ?></p>
                </div>
                <?php if ($order['customer_phone']): ?>
                    <div>
                        <p class="text-xs text-slate-400">Phone</p>
                        <p class="text-sm text-slate-600"><?= htmlspecialchars($order['customer_phone']) ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Shipping Address -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100">
                <h3 class="font-bold text-slate-800 flex items-center gap-2">
                    <i class="fas fa-map-marker-alt text-indigo-500"></i>
                    Shipping Address
                </h3>
            </div>
            <div class="p-5">
                <?php if ($order['address']): ?>
                    <div class="space-y-2">
                        <p class="text-sm font-medium text-slate-800"><?= htmlspecialchars($order['address']['full_name']) ?></p>
                        <p class="text-sm text-slate-600">📞 <?= htmlspecialchars($order['address']['phone']) ?></p>
                        <p class="text-sm text-slate-600"><?= htmlspecialchars($order['address']['address_line']) ?></p>
                        <p class="text-sm text-slate-600">
                            <?= htmlspecialchars($order['address']['area']) ?>, <?= htmlspecialchars($order['address']['city']) ?>
                            <?php if ($order['address']['postal_code']): ?> - <?= $order['address']['postal_code'] ?><?php endif; ?>
                        </p>
                    </div>
                <?php elseif ($order['customer_address']): ?>
                    <p class="text-sm text-slate-600"><?= nl2br(htmlspecialchars($order['customer_address'])) ?></p>
                <?php else: ?>
                    <p class="text-sm text-slate-400">No address information available</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100">
                <h3 class="font-bold text-slate-800 flex items-center gap-2">
                    <i class="fas fa-info-circle text-indigo-500"></i>
                    Order Summary
                </h3>
            </div>
            <div class="p-5 space-y-3">
                <div class="flex justify-between">
                    <span class="text-xs text-slate-400">Order Date</span>
                    <span class="text-xs text-slate-600"><?= date('d M, Y h:i A', strtotime($order['placed_at'])) ?></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-xs text-slate-400">Payment Method</span>
                    <span class="text-xs font-semibold text-slate-700 uppercase"><?= $order['payment_method'] ?></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-xs text-slate-400">Shipping Charge</span>
                    <span class="text-xs text-slate-600">৳ <?= number_format($order['shipping_charge'], 2) ?></span>
                </div>
                <?php if ($order['order_notes']): ?>
                    <div class="pt-3 border-t border-slate-100">
                        <p class="text-xs text-slate-400 mb-1">Order Notes</p>
                        <p class="text-xs text-slate-600"><?= nl2br(htmlspecialchars($order['order_notes'])) ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Print Invoice Button -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4">
            <button onclick="window.print()" class="w-full py-2.5 rounded-xl border border-slate-200 text-slate-600 text-sm font-semibold hover:bg-slate-50 transition-colors flex items-center justify-center gap-2">
                <i class="fas fa-print"></i> Print Invoice
            </button>
        </div>
    </div>
</div>

<script>
    function updateStatus(status) {
        let note = '';
        if (status === 'cancelled') {
            note = prompt('Please provide a reason for cancellation:');
            if (note === null) return;
        }

        const formData = new FormData();
        formData.append('order_id', <?= $order['order_id'] ?>);
        formData.append('status', status);
        formData.append('note', note || '');
        formData.append('csrf_token', '<?= $csrf ?>');

        fetch('<?= APP_URL ?>/orders/update-status', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Failed to update status: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred');
            });
    }

    function updateTracking() {
        const trackingNumber = document.getElementById('trackingNumber').value;

        const formData = new FormData();
        formData.append('order_id', <?= $order['order_id'] ?>);
        formData.append('tracking_number', trackingNumber);
        formData.append('csrf_token', '<?= $csrf ?>');

        fetch('<?= APP_URL ?>/orders/update-tracking', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Tracking number updated successfully!');
                } else {
                    alert('Failed to update tracking number');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred');
            });
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

    @media print {

        .sidebar,
        nav,
        .actions,
        button,
        .sticky,
        .topbar {
            display: none !important;
        }

        body {
            background: white;
            padding: 0;
            margin: 0;
        }

        .bg-white {
            box-shadow: none;
            border: 1px solid #e2e8f0;
        }
    }
</style>