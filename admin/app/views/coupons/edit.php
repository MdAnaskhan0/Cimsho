<?php
// Display errors if any
if (isset($_SESSION['form_errors'])) {
    echo '<div class="mb-5 p-4 rounded-xl bg-red-50 border border-red-200">...';
    unset($_SESSION['form_errors']);
}
?>

<!-- Page Header -->
<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-xl font-extrabold text-slate-800">Edit Coupon</h2>
        <nav class="flex items-center gap-2 mt-1 text-xs text-slate-400">
            <a href="<?= APP_URL ?>/dashboard">Dashboard</a>
            <i class="fas fa-chevron-right text-[9px]"></i>
            <a href="<?= APP_URL ?>/coupons">Coupons</a>
            <i class="fas fa-chevron-right text-[9px]"></i>
            <span class="text-slate-600">Edit: <?= htmlspecialchars($coupon['code']) ?></span>
        </nav>
    </div>
    <div>
        <a href="<?= APP_URL ?>/coupons" class="flex items-center gap-2 px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 text-sm">
            <i class="fas fa-arrow-left text-xs"></i> Back
        </a>
    </div>
</div>

<!-- Edit Coupon Form -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <form method="POST" action="<?= APP_URL ?>/coupons/update/<?= $coupon['id'] ?>" class="p-6">
        <input type="hidden" name="csrf_token" value="<?= $csrf ?>">

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            <div class="space-y-5">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Coupon Code <span class="text-red-500">*</span></label>
                    <input type="text" name="code" value="<?= htmlspecialchars($coupon['code']) ?>" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 uppercase">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Discount Percentage <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <input type="number" step="0.01" name="discount_pct" value="<?= $coupon['discount_pct'] ?>" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200">
                        <span class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400">%</span>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Minimum Order Amount</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">৳</span>
                        <input type="number" step="0.01" name="min_order" value="<?= $coupon['min_order'] ?>" class="w-full pl-8 pr-4 py-2.5 rounded-xl border border-slate-200">
                    </div>
                </div>
            </div>

            <div class="space-y-5">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Maximum Uses</label>
                    <input type="number" name="max_uses" value="<?= $coupon['max_uses'] ?>" class="w-full px-4 py-2.5 rounded-xl border border-slate-200">
                    <p class="text-xs text-slate-400 mt-1">Used <?= $coupon['used_count'] ?> times so far</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Expiry Date</label>
                    <input type="date" name="expires_at" value="<?= $coupon['expires_at'] ?>" class="w-full px-4 py-2.5 rounded-xl border border-slate-200">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Status</label>
                    <div class="flex items-center gap-4">
                        <label class="flex items-center gap-2">
                            <input type="radio" name="is_active" value="1" <?= $coupon['is_active'] ? 'checked' : '' ?>>
                            <span>Active</span>
                        </label>
                        <label class="flex items-center gap-2">
                            <input type="radio" name="is_active" value="0" <?= !$coupon['is_active'] ? 'checked' : '' ?>>
                            <span>Inactive</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3 pt-6 mt-6 border-t">
            <a href="<?= APP_URL ?>/coupons" class="px-6 py-2.5 rounded-xl border border-slate-200">Cancel</a>
            <button type="submit" class="px-6 py-2.5 rounded-xl text-white" style="background:linear-gradient(135deg,#6366f1,#8b5cf6)">Update Coupon</button>
        </div>
    </form>
</div>