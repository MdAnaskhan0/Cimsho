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

if (isset($_SESSION['form_errors'])) {
    echo '<div class="mb-5 p-4 rounded-xl bg-red-50 border border-red-200">
            <div class="flex items-start gap-3">
                <i class="fas fa-exclamation-triangle text-red-500 mt-0.5"></i>
                <div class="flex-1">
                    <p class="text-red-800 font-semibold text-sm mb-1">Please fix the following errors:</p>
                    <ul class="list-disc list-inside text-red-700 text-sm space-y-0.5">';
    foreach ($_SESSION['form_errors'] as $error) {
        echo '<li>' . htmlspecialchars($error) . '</li>';
    }
    echo '      </ul>
                </div>
                <button onclick="this.parentElement.parentElement.remove()" class="text-red-400 hover:text-red-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>';
    unset($_SESSION['form_errors']);
}
?>

<!-- Page Header -->
<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-xl font-extrabold text-slate-800">Delivery Settings</h2>
        <nav class="flex items-center gap-2 mt-1 text-xs text-slate-400">
            <a href="<?= APP_URL ?>/dashboard" class="hover:text-indigo-500 transition-colors">Dashboard</a>
            <i class="fas fa-chevron-right text-[9px]"></i>
            <span class="text-slate-600 font-medium">Delivery Settings</span>
        </nav>
    </div>
</div>

<!-- Delivery Settings Form -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- Settings Form -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <form method="POST" action="<?= APP_URL ?>/settings/delivery/update" class="p-6">
                <input type="hidden" name="csrf_token" value="<?= $csrf ?>">

                <div class="space-y-6">
                    <!-- Inside Dhaka Delivery -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Inside Dhaka Delivery Charge
                        </label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm font-semibold">৳</span>
                            <input type="number"
                                name="inside_dhaka_charge"
                                step="0.01"
                                value="<?= $settings['inside_dhaka_charge'] ?>"
                                class="w-full pl-8 pr-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                required>
                        </div>
                        <p class="text-xs text-slate-400 mt-1.5">Standard delivery charge for deliveries inside Dhaka city.</p>
                    </div>

                    <!-- Outside Dhaka Delivery -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Outside Dhaka Delivery Charge
                        </label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm font-semibold">৳</span>
                            <input type="number"
                                name="outside_dhaka_charge"
                                step="0.01"
                                value="<?= $settings['outside_dhaka_charge'] ?>"
                                class="w-full pl-8 pr-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                required>
                        </div>
                        <p class="text-xs text-slate-400 mt-1.5">Standard delivery charge for deliveries outside Dhaka city.</p>
                    </div>

                    <!-- Express Delivery -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Express Delivery Charge
                        </label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm font-semibold">৳</span>
                            <input type="number"
                                name="express_delivery_charge"
                                step="0.01"
                                value="<?= $settings['express_delivery_charge'] ?>"
                                class="w-full pl-8 pr-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                required>
                        </div>
                        <p class="text-xs text-slate-400 mt-1.5">Additional charge for express/fast delivery service.</p>
                    </div>

                    <!-- Free Delivery Threshold -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Free Delivery Minimum Amount
                        </label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm font-semibold">৳</span>
                            <input type="number"
                                name="free_delivery_min_amount"
                                step="0.01"
                                value="<?= $settings['free_delivery_min_amount'] ?>"
                                class="w-full pl-8 pr-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                required>
                        </div>
                        <p class="text-xs text-slate-400 mt-1.5">Orders above this amount will get free delivery (0 charge).</p>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-end gap-3 pt-6 mt-8 border-t border-slate-100">
                    <button type="reset" class="px-6 py-2.5 rounded-xl border border-slate-200 text-slate-600 text-sm font-semibold hover:bg-slate-50 transition-all">
                        Reset
                    </button>
                    <button type="submit" class="px-6 py-2.5 rounded-xl text-white text-sm font-semibold transition-all hover:opacity-90" style="background:linear-gradient(135deg,#6366f1,#8b5cf6)">
                        <i class="fas fa-save mr-2 text-xs"></i>
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Right Sidebar - Info & Preview -->
    <div class="space-y-6">

        <!-- Current Charges Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
            <h3 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
                <i class="fas fa-truck text-indigo-500"></i>
                Current Delivery Charges
            </h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                    <span class="text-slate-600 text-sm">Inside Dhaka:</span>
                    <span class="font-bold text-slate-800">৳ <?= number_format($settings['inside_dhaka_charge'], 2) ?></span>
                </div>
                <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                    <span class="text-slate-600 text-sm">Outside Dhaka:</span>
                    <span class="font-bold text-slate-800">৳ <?= number_format($settings['outside_dhaka_charge'], 2) ?></span>
                </div>
                <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                    <span class="text-slate-600 text-sm">Express Delivery:</span>
                    <span class="font-bold text-slate-800">+ ৳ <?= number_format($settings['express_delivery_charge'], 2) ?></span>
                </div>
                <div class="flex items-center justify-between pt-2">
                    <span class="text-emerald-600 text-sm font-semibold">Free Delivery:</span>
                    <span class="font-bold text-emerald-600">Orders ≥ ৳ <?= number_format($settings['free_delivery_min_amount'], 2) ?></span>
                </div>
            </div>
        </div>

        <!-- Delivery Charge Calculator Preview -->
        <div class="bg-gradient-to-br from-indigo-50 to-purple-50 rounded-2xl p-5">
            <h3 class="font-bold text-slate-800 mb-3 flex items-center gap-2">
                <i class="fas fa-calculator text-indigo-500"></i>
                Delivery Charge Calculator
            </h3>
            <p class="text-xs text-slate-500 mb-4">Preview delivery charges based on location and order amount</p>

            <div class="space-y-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Delivery Location</label>
                    <select id="previewLocation" class="w-full px-3 py-2 rounded-lg border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="dhaka">Inside Dhaka</option>
                        <option value="outside">Outside Dhaka</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Order Amount (৳)</label>
                    <input type="number"
                        id="previewAmount"
                        value="1000"
                        step="100"
                        class="w-full px-3 py-2 rounded-lg border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Delivery Type</label>
                    <select id="previewType" class="w-full px-3 py-2 rounded-lg border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="standard">Standard Delivery</option>
                        <option value="express">Express Delivery</option>
                    </select>
                </div>

                <button onclick="calculatePreview()" class="w-full mt-2 px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-semibold hover:bg-indigo-700 transition-colors">
                    Calculate Charge
                </button>

                <div id="previewResult" class="mt-4 p-3 bg-white rounded-lg text-center hidden">
                    <p class="text-xs text-slate-500 mb-1">Delivery Charge</p>
                    <p class="text-2xl font-bold text-indigo-600" id="previewCharge">৳ 0.00</p>
                    <p class="text-xs text-slate-400 mt-1" id="previewNote"></p>
                </div>
            </div>
        </div>

        <!-- Info Card -->
        <div class="bg-amber-50 rounded-2xl p-4 border border-amber-200">
            <div class="flex items-start gap-3">
                <i class="fas fa-info-circle text-amber-500 mt-0.5"></i>
                <div>
                    <p class="text-xs font-semibold text-amber-800 mb-1">How delivery charges work:</p>
                    <ul class="text-xs text-amber-700 space-y-1">
                        <li>• Standard delivery applies to regular orders</li>
                        <li>• Express delivery adds extra charge on top</li>
                        <li>• Free delivery when order exceeds threshold</li>
                        <li>• Charges are calculated automatically at checkout</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Calculate delivery charge preview
    function calculatePreview() {
        const location = document.getElementById('previewLocation').value;
        const amount = parseFloat(document.getElementById('previewAmount').value) || 0;
        const deliveryType = document.getElementById('previewType').value;

        fetch('<?= APP_URL ?>/settings/delivery/calculate-preview', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `location=${location}&order_amount=${amount}&delivery_type=${deliveryType}&csrf_token=<?= $csrf ?>`
            })
            .then(response => response.json())
            .then(data => {
                const resultDiv = document.getElementById('previewResult');
                const chargeSpan = document.getElementById('previewCharge');
                const noteSpan = document.getElementById('previewNote');

                if (data.success) {
                    resultDiv.classList.remove('hidden');
                    chargeSpan.innerHTML = data.formatted_charge;

                    if (data.is_free) {
                        chargeSpan.className = 'text-2xl font-bold text-emerald-600';
                        noteSpan.innerHTML = '🎉 Free delivery applied!';
                    } else {
                        chargeSpan.className = 'text-2xl font-bold text-indigo-600';
                        if (data.remaining_for_free > 0) {
                            noteSpan.innerHTML = `Add ৳ ${data.remaining_for_free.toFixed(2)} more for free delivery`;
                        } else {
                            noteSpan.innerHTML = '';
                        }
                    }
                }
            });
    }

    // Auto-calculate on input changes
    document.getElementById('previewLocation').addEventListener('change', calculatePreview);
    document.getElementById('previewAmount').addEventListener('input', calculatePreview);
    document.getElementById('previewType').addEventListener('change', calculatePreview);

    // Initial calculation on page load
    document.addEventListener('DOMContentLoaded', function() {
        calculatePreview();
    });
</script>