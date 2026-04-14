<?php
// Display errors if any
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

$old = $_SESSION['old_input'] ?? [];
unset($_SESSION['old_input']);
?>

<!-- Page Header -->
<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-xl font-extrabold text-slate-800">Add New Coupon</h2>
        <nav class="flex items-center gap-2 mt-1 text-xs text-slate-400">
            <a href="<?= APP_URL ?>/dashboard" class="hover:text-indigo-500 transition-colors">Dashboard</a>
            <i class="fas fa-chevron-right text-[9px]"></i>
            <a href="<?= APP_URL ?>/coupons" class="hover:text-indigo-500 transition-colors">Coupons</a>
            <i class="fas fa-chevron-right text-[9px]"></i>
            <span class="text-slate-600 font-medium">Add New</span>
        </nav>
    </div>
    <div class="flex items-center gap-3">
        <a href="<?= APP_URL ?>/coupons" class="flex items-center gap-2 px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 text-sm font-semibold hover:bg-slate-50 transition-all">
            <i class="fas fa-arrow-left text-xs"></i>
            Back to Coupons
        </a>
    </div>
</div>

<!-- Add Coupon Form -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <form method="POST" action="<?= APP_URL ?>/coupons/store" class="p-6">
        <input type="hidden" name="csrf_token" value="<?= $csrf ?>">

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            <!-- Left Column -->
            <div class="space-y-5">
                <!-- Coupon Code -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Coupon Code <span class="text-red-500">*</span>
                    </label>
                    <div class="flex gap-2">
                        <input type="text"
                            name="code"
                            id="couponCode"
                            value="<?= htmlspecialchars($old['code'] ?? $generatedCode) ?>"
                            class="flex-1 px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 uppercase"
                            placeholder="e.g., SUMMER2024"
                            required>
                        <button type="button"
                            onclick="generateCouponCode()"
                            class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 text-sm font-semibold hover:bg-slate-50 transition-all">
                            <i class="fas fa-sync-alt"></i> Generate
                        </button>
                    </div>
                    <p class="text-xs text-slate-400 mt-1.5">Unique code customers will enter at checkout.</p>
                </div>

                <!-- Discount Percentage -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Discount Percentage <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="number"
                            name="discount_pct"
                            step="0.01"
                            value="<?= htmlspecialchars($old['discount_pct'] ?? '') ?>"
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            placeholder="e.g., 10, 15.5"
                            required>
                        <span class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm">%</span>
                    </div>
                    <p class="text-xs text-slate-400 mt-1.5">Percentage discount off the total order amount.</p>
                </div>

                <!-- Minimum Order Amount -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Minimum Order Amount</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm">৳</span>
                        <input type="number"
                            name="min_order"
                            step="0.01"
                            value="<?= htmlspecialchars($old['min_order'] ?? '0') ?>"
                            class="w-full pl-8 pr-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            placeholder="0.00">
                    </div>
                    <p class="text-xs text-slate-400 mt-1.5">Minimum order value required to use this coupon. Leave 0 for no minimum.</p>
                </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-5">
                <!-- Maximum Uses -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Maximum Uses</label>
                    <input type="number"
                        name="max_uses"
                        value="<?= htmlspecialchars($old['max_uses'] ?? '100') ?>"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        placeholder="100">
                    <p class="text-xs text-slate-400 mt-1.5">Maximum number of times this coupon can be used.</p>
                </div>

                <!-- Expiry Date -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Expiry Date</label>
                    <input type="date"
                        name="expires_at"
                        value="<?= htmlspecialchars($old['expires_at'] ?? '') ?>"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <p class="text-xs text-slate-400 mt-1.5">Leave empty for no expiry date.</p>
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Status</label>
                    <div class="flex items-center gap-4">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="is_active" value="1" <?= (!isset($old['is_active']) || $old['is_active'] == '1') ? 'checked' : '' ?> class="w-4 h-4 text-indigo-600">
                            <span class="text-sm text-slate-600">Active</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="is_active" value="0" <?= (isset($old['is_active']) && $old['is_active'] == '0') ? 'checked' : '' ?> class="w-4 h-4 text-indigo-600">
                            <span class="text-sm text-slate-600">Inactive</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Preview Card -->
        <div class="mt-6 p-4 bg-gradient-to-r from-indigo-50 to-purple-50 rounded-xl">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-500 mb-1">Coupon Preview</p>
                    <code class="text-lg font-bold text-indigo-600" id="previewCode"><?= htmlspecialchars($old['code'] ?? $generatedCode) ?></code>
                    <p class="text-xs text-slate-500 mt-1" id="previewDiscount">Discount: <span id="previewDiscountValue">0</span>% off</p>
                </div>
                <div class="text-right">
                    <p class="text-xs text-slate-500">Min. Order</p>
                    <p class="font-semibold text-slate-700">৳ <span id="previewMinOrder">0</span></p>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex items-center justify-end gap-3 pt-6 mt-6 border-t border-slate-100">
            <a href="<?= APP_URL ?>/coupons" class="px-6 py-2.5 rounded-xl border border-slate-200 text-slate-600 text-sm font-semibold hover:bg-slate-50 transition-all">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2.5 rounded-xl text-white text-sm font-semibold transition-all hover:opacity-90" style="background:linear-gradient(135deg,#6366f1,#8b5cf6)">
                <i class="fas fa-save mr-2 text-xs"></i>
                Create Coupon
            </button>
        </div>
    </form>
</div>

<script>
    // Live preview update
    document.querySelector('input[name="code"]').addEventListener('input', function() {
        document.getElementById('previewCode').textContent = this.value.toUpperCase() || 'COUPON_CODE';
    });

    document.querySelector('input[name="discount_pct"]').addEventListener('input', function() {
        document.getElementById('previewDiscountValue').textContent = this.value || '0';
    });

    document.querySelector('input[name="min_order"]').addEventListener('input', function() {
        document.getElementById('previewMinOrder').textContent = parseFloat(this.value).toFixed(2) || '0';
    });

    // Generate random coupon code
    function generateCouponCode() {
        fetch('<?= APP_URL ?>/coupons/generate-code?prefix=COUPON_')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.querySelector('input[name="code"]').value = data.code;
                    document.getElementById('previewCode').textContent = data.code;
                }
            });
    }
</script>