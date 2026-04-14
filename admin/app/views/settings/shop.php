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
        <h2 class="text-xl font-extrabold text-slate-800">Shop Settings</h2>
        <nav class="flex items-center gap-2 mt-1 text-xs text-slate-400">
            <a href="<?= APP_URL ?>/dashboard" class="hover:text-indigo-500 transition-colors">Dashboard</a>
            <i class="fas fa-chevron-right text-[9px]"></i>
            <span class="text-slate-600 font-medium">Shop Settings</span>
        </nav>
    </div>
</div>

<!-- Shop Settings Form -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <form method="POST" action="<?= APP_URL ?>/settings/shop/update" class="p-6">
        <input type="hidden" name="csrf_token" value="<?= $csrf ?>">

        <div class="space-y-8">

            <!-- General Settings -->
            <div>
                <h3 class="text-lg font-bold text-slate-800 mb-4 pb-2 border-b border-slate-200">General Settings</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Shop Name</label>
                        <input type="text" name="shop_name" value="<?= htmlspecialchars($settings['shop_name'] ?? '') ?>" class="w-full px-4 py-2.5 rounded-xl border border-slate-200">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Shop Email</label>
                        <input type="email" name="shop_email" value="<?= htmlspecialchars($settings['shop_email'] ?? '') ?>" class="w-full px-4 py-2.5 rounded-xl border border-slate-200">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Shop Phone</label>
                        <input type="text" name="shop_phone" value="<?= htmlspecialchars($settings['shop_phone'] ?? '') ?>" class="w-full px-4 py-2.5 rounded-xl border border-slate-200">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Shop Address</label>
                        <textarea name="shop_address" rows="2" class="w-full px-4 py-2.5 rounded-xl border border-slate-200"><?= htmlspecialchars($settings['shop_address'] ?? '') ?></textarea>
                    </div>
                </div>
            </div>

            <!-- Currency & Tax Settings -->
            <div>
                <h3 class="text-lg font-bold text-slate-800 mb-4 pb-2 border-b border-slate-200">Currency & Tax Settings</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Currency</label>
                        <select name="currency" class="w-full px-4 py-2.5 rounded-xl border border-slate-200">
                            <option value="BDT" <?= ($settings['currency'] ?? '') == 'BDT' ? 'selected' : '' ?>>BDT - Bangladeshi Taka</option>
                            <option value="USD" <?= ($settings['currency'] ?? '') == 'USD' ? 'selected' : '' ?>>USD - US Dollar</option>
                            <option value="EUR" <?= ($settings['currency'] ?? '') == 'EUR' ? 'selected' : '' ?>>EUR - Euro</option>
                            <option value="GBP" <?= ($settings['currency'] ?? '') == 'GBP' ? 'selected' : '' ?>>GBP - British Pound</option>
                            <option value="INR" <?= ($settings['currency'] ?? '') == 'INR' ? 'selected' : '' ?>>INR - Indian Rupee</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Currency Symbol</label>
                        <input type="text" name="currency_symbol" value="<?= htmlspecialchars($settings['currency_symbol'] ?? '৳') ?>" class="w-full px-4 py-2.5 rounded-xl border border-slate-200">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Tax Percentage (%)</label>
                        <input type="number" name="tax_percentage" step="0.01" value="<?= $settings['tax_percentage'] ?? '0' ?>" class="w-full px-4 py-2.5 rounded-xl border border-slate-200">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Minimum Order Amount (৳)</label>
                        <input type="number" name="minimum_order_amount" step="0.01" value="<?= $settings['minimum_order_amount'] ?? '0' ?>" class="w-full px-4 py-2.5 rounded-xl border border-slate-200">
                    </div>
                </div>
            </div>

            <!-- Order Settings -->
            <div>
                <h3 class="text-lg font-bold text-slate-800 mb-4 pb-2 border-b border-slate-200">Order Settings</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Order Prefix</label>
                        <input type="text" name="order_prefix" value="<?= htmlspecialchars($settings['order_prefix'] ?? 'ORD') ?>" class="w-full px-4 py-2.5 rounded-xl border border-slate-200">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Invoice Prefix</label>
                        <input type="text" name="invoice_prefix" value="<?= htmlspecialchars($settings['invoice_prefix'] ?? 'INV') ?>" class="w-full px-4 py-2.5 rounded-xl border border-slate-200">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Max Quantity Per Order</label>
                        <input type="number" name="max_quantity_per_order" value="<?= $settings['max_quantity_per_order'] ?? '100' ?>" class="w-full px-4 py-2.5 rounded-xl border border-slate-200">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Low Stock Threshold</label>
                        <input type="number" name="low_stock_threshold" value="<?= $settings['low_stock_threshold'] ?? '10' ?>" class="w-full px-4 py-2.5 rounded-xl border border-slate-200">
                        <p class="text-xs text-slate-400 mt-1">Alert when stock reaches this number</p>
                    </div>
                    <div class="flex items-center gap-4">
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="allow_backorder" value="1" <?= ($settings['allow_backorder'] ?? '0') == '1' ? 'checked' : '' ?> class="rounded">
                            <span class="text-sm text-slate-700">Allow Backorder</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Display Settings -->
            <div>
                <h3 class="text-lg font-bold text-slate-800 mb-4 pb-2 border-b border-slate-200">Display Settings</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Items Per Page</label>
                        <select name="items_per_page" class="w-full px-4 py-2.5 rounded-xl border border-slate-200">
                            <option value="12" <?= ($settings['items_per_page'] ?? '20') == '12' ? 'selected' : '' ?>>12 items</option>
                            <option value="20" <?= ($settings['items_per_page'] ?? '20') == '20' ? 'selected' : '' ?>>20 items</option>
                            <option value="24" <?= ($settings['items_per_page'] ?? '20') == '24' ? 'selected' : '' ?>>24 items</option>
                            <option value="36" <?= ($settings['items_per_page'] ?? '20') == '36' ? 'selected' : '' ?>>36 items</option>
                            <option value="48" <?= ($settings['items_per_page'] ?? '20') == '48' ? 'selected' : '' ?>>48 items</option>
                        </select>
                    </div>
                    <div class="flex items-center gap-4 mt-2">
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="enable_reviews" value="1" <?= ($settings['enable_reviews'] ?? '1') == '1' ? 'checked' : '' ?> class="rounded">
                            <span class="text-sm text-slate-700">Enable Product Reviews</span>
                        </label>
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="enable_wishlist" value="1" <?= ($settings['enable_wishlist'] ?? '1') == '1' ? 'checked' : '' ?> class="rounded">
                            <span class="text-sm text-slate-700">Enable Wishlist</span>
                        </label>
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="enable_compare" value="1" <?= ($settings['enable_compare'] ?? '1') == '1' ? 'checked' : '' ?> class="rounded">
                            <span class="text-sm text-slate-700">Enable Compare</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Social Media Links -->
            <div>
                <h3 class="text-lg font-bold text-slate-800 mb-4 pb-2 border-b border-slate-200">Social Media Links</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Facebook URL</label>
                        <input type="url" name="social_facebook" value="<?= htmlspecialchars($settings['social_facebook'] ?? '') ?>" class="w-full px-4 py-2.5 rounded-xl border border-slate-200">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Instagram URL</label>
                        <input type="url" name="social_instagram" value="<?= htmlspecialchars($settings['social_instagram'] ?? '') ?>" class="w-full px-4 py-2.5 rounded-xl border border-slate-200">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Twitter URL</label>
                        <input type="url" name="social_twitter" value="<?= htmlspecialchars($settings['social_twitter'] ?? '') ?>" class="w-full px-4 py-2.5 rounded-xl border border-slate-200">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">YouTube URL</label>
                        <input type="url" name="social_youtube" value="<?= htmlspecialchars($settings['social_youtube'] ?? '') ?>" class="w-full px-4 py-2.5 rounded-xl border border-slate-200">
                    </div>
                </div>
            </div>

            <!-- SEO Settings -->
            <div>
                <h3 class="text-lg font-bold text-slate-800 mb-4 pb-2 border-b border-slate-200">SEO Settings</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Meta Title</label>
                        <input type="text" name="meta_title" value="<?= htmlspecialchars($settings['meta_title'] ?? '') ?>" class="w-full px-4 py-2.5 rounded-xl border border-slate-200">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Meta Description</label>
                        <textarea name="meta_description" rows="3" class="w-full px-4 py-2.5 rounded-xl border border-slate-200"><?= htmlspecialchars($settings['meta_description'] ?? '') ?></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Meta Keywords</label>
                        <input type="text" name="meta_keywords" value="<?= htmlspecialchars($settings['meta_keywords'] ?? '') ?>" class="w-full px-4 py-2.5 rounded-xl border border-slate-200" placeholder="keyword1, keyword2, keyword3">
                    </div>
                </div>
            </div>

            <!-- Footer Settings -->
            <div>
                <h3 class="text-lg font-bold text-slate-800 mb-4 pb-2 border-b border-slate-200">Footer Settings</h3>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Footer Text</label>
                    <textarea name="footer_text" rows="2" class="w-full px-4 py-2.5 rounded-xl border border-slate-200"><?= htmlspecialchars($settings['footer_text'] ?? '') ?></textarea>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex items-center justify-end gap-3 pt-6 mt-8 border-t border-slate-100">
            <button type="reset" class="px-6 py-2.5 rounded-xl border border-slate-200 text-slate-600 text-sm font-semibold hover:bg-slate-50 transition-all">
                Reset
            </button>
            <button type="submit" class="px-6 py-2.5 rounded-xl text-white text-sm font-semibold transition-all hover:opacity-90" style="background:linear-gradient(135deg,#6366f1,#8b5cf6)">
                <i class="fas fa-save mr-2 text-xs"></i>
                Save Settings
            </button>
        </div>
    </form>
</div>