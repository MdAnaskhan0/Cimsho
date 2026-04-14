<?php
// Display messages (same as shop settings)
if (isset($_SESSION['success_message'])) {
    echo '<div class="mb-5 p-4 rounded-xl bg-emerald-50 border border-emerald-200">...</div>';
    unset($_SESSION['success_message']);
}
?>

<!-- Page Header -->
<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-xl font-extrabold text-slate-800">Site Settings</h2>
        <nav class="flex items-center gap-2 mt-1 text-xs text-slate-400">
            <a href="<?= APP_URL ?>/dashboard" class="hover:text-indigo-500 transition-colors">Dashboard</a>
            <i class="fas fa-chevron-right text-[9px]"></i>
            <span class="text-slate-600 font-medium">Site Settings</span>
        </nav>
    </div>
</div>

<!-- Site Settings Form -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <form method="POST" action="<?= APP_URL ?>/settings/site/update" class="p-6">
        <input type="hidden" name="csrf_token" value="<?= $csrf ?>">

        <div class="space-y-8">

            <!-- Basic Site Info -->
            <div>
                <h3 class="text-lg font-bold text-slate-800 mb-4 pb-2 border-b border-slate-200">Basic Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Site Name</label>
                        <input type="text" name="site_name" value="<?= htmlspecialchars($settings['site_name'] ?? '') ?>" class="w-full px-4 py-2.5 rounded-xl border border-slate-200">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Site Tagline</label>
                        <input type="text" name="site_tagline" value="<?= htmlspecialchars($settings['site_tagline'] ?? '') ?>" class="w-full px-4 py-2.5 rounded-xl border border-slate-200">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Site Email</label>
                        <input type="email" name="site_email" value="<?= htmlspecialchars($settings['site_email'] ?? '') ?>" class="w-full px-4 py-2.5 rounded-xl border border-slate-200">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Site Phone</label>
                        <input type="text" name="site_phone" value="<?= htmlspecialchars($settings['site_phone'] ?? '') ?>" class="w-full px-4 py-2.5 rounded-xl border border-slate-200">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Site Address</label>
                        <textarea name="site_address" rows="2" class="w-full px-4 py-2.5 rounded-xl border border-slate-200"><?= htmlspecialchars($settings['site_address'] ?? '') ?></textarea>
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div>
                <h3 class="text-lg font-bold text-slate-800 mb-4 pb-2 border-b border-slate-200">Contact Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Contact Email</label>
                        <input type="email" name="contact_email" value="<?= htmlspecialchars($settings['contact_email'] ?? '') ?>" class="w-full px-4 py-2.5 rounded-xl border border-slate-200">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Contact Phone</label>
                        <input type="text" name="contact_phone" value="<?= htmlspecialchars($settings['contact_phone'] ?? '') ?>" class="w-full px-4 py-2.5 rounded-xl border border-slate-200">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Contact Address</label>
                        <textarea name="contact_address" rows="2" class="w-full px-4 py-2.5 rounded-xl border border-slate-200"><?= htmlspecialchars($settings['contact_address'] ?? '') ?></textarea>
                    </div>
                </div>
            </div>

            <!-- Date & Time Settings -->
            <div>
                <h3 class="text-lg font-bold text-slate-800 mb-4 pb-2 border-b border-slate-200">Date & Time Settings</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Timezone</label>
                        <select name="timezone" class="w-full px-4 py-2.5 rounded-xl border border-slate-200">
                            <option value="Asia/Dhaka" <?= ($settings['timezone'] ?? '') == 'Asia/Dhaka' ? 'selected' : '' ?>>Asia/Dhaka (GMT+6)</option>
                            <option value="Asia/Kolkata" <?= ($settings['timezone'] ?? '') == 'Asia/Kolkata' ? 'selected' : '' ?>>Asia/Kolkata (GMT+5:30)</option>
                            <option value="Asia/Dubai" <?= ($settings['timezone'] ?? '') == 'Asia/Dubai' ? 'selected' : '' ?>>Asia/Dubai (GMT+4)</option>
                            <option value="America/New_York" <?= ($settings['timezone'] ?? '') == 'America/New_York' ? 'selected' : '' ?>>America/New_York (GMT-5)</option>
                            <option value="Europe/London" <?= ($settings['timezone'] ?? '') == 'Europe/London' ? 'selected' : '' ?>>Europe/London (GMT+0)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Date Format</label>
                        <select name="date_format" class="w-full px-4 py-2.5 rounded-xl border border-slate-200">
                            <option value="d M, Y" <?= ($settings['date_format'] ?? '') == 'd M, Y' ? 'selected' : '' ?>>15 Jan, 2024</option>
                            <option value="Y-m-d" <?= ($settings['date_format'] ?? '') == 'Y-m-d' ? 'selected' : '' ?>>2024-01-15</option>
                            <option value="m/d/Y" <?= ($settings['date_format'] ?? '') == 'm/d/Y' ? 'selected' : '' ?>>01/15/2024</option>
                            <option value="d/m/Y" <?= ($settings['date_format'] ?? '') == 'd/m/Y' ? 'selected' : '' ?>>15/01/2024</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Time Format</label>
                        <select name="time_format" class="w-full px-4 py-2.5 rounded-xl border border-slate-200">
                            <option value="h:i A" <?= ($settings['time_format'] ?? '') == 'h:i A' ? 'selected' : '' ?>>02:30 PM</option>
                            <option value="H:i" <?= ($settings['time_format'] ?? '') == 'H:i' ? 'selected' : '' ?>>14:30</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Maintenance Mode -->
            <div>
                <h3 class="text-lg font-bold text-slate-800 mb-4 pb-2 border-b border-slate-200">Maintenance Mode</h3>
                <div class="space-y-4">
                    <div class="flex items-center gap-4">
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="maintenance_mode" value="1" <?= ($settings['maintenance_mode'] ?? '0') == '1' ? 'checked' : '' ?> class="rounded">
                            <span class="text-sm text-slate-700 font-semibold">Enable Maintenance Mode</span>
                        </label>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Maintenance Message</label>
                        <textarea name="maintenance_message" rows="3" class="w-full px-4 py-2.5 rounded-xl border border-slate-200"><?= htmlspecialchars($settings['maintenance_message'] ?? '') ?></textarea>
                    </div>
                </div>
            </div>

            <!-- Analytics & Tracking -->
            <div>
                <h3 class="text-lg font-bold text-slate-800 mb-4 pb-2 border-b border-slate-200">Analytics & Tracking</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Google Analytics ID</label>
                        <input type="text" name="google_analytics_id" value="<?= htmlspecialchars($settings['google_analytics_id'] ?? '') ?>" class="w-full px-4 py-2.5 rounded-xl border border-slate-200" placeholder="G-XXXXXXXXXX">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Facebook Pixel ID</label>
                        <input type="text" name="facebook_pixel_id" value="<?= htmlspecialchars($settings['facebook_pixel_id'] ?? '') ?>" class="w-full px-4 py-2.5 rounded-xl border border-slate-200" placeholder="XXXXXXXXXXXXX">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Header Scripts</label>
                        <textarea name="header_scripts" rows="4" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 font-mono text-sm" placeholder="<script>..."></textarea>
                        <p class="text-xs text-slate-400 mt-1">These scripts will be added before the closing &lt;/head&gt; tag</p>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Footer Scripts</label>
                        <textarea name="footer_scripts" rows="4" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 font-mono text-sm" placeholder="<script>..."></textarea>
                        <p class="text-xs text-slate-400 mt-1">These scripts will be added before the closing &lt;/body&gt; tag</p>
                    </div>
                </div>
            </div>

            <!-- Cookie Consent -->
            <div>
                <h3 class="text-lg font-bold text-slate-800 mb-4 pb-2 border-b border-slate-200">Cookie Consent</h3>
                <div class="space-y-4">
                    <div class="flex items-center gap-4">
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="cookie_consent_enabled" value="1" <?= ($settings['cookie_consent_enabled'] ?? '1') == '1' ? 'checked' : '' ?> class="rounded">
                            <span class="text-sm text-slate-700 font-semibold">Enable Cookie Consent Banner</span>
                        </label>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Cookie Consent Message</label>
                        <textarea name="cookie_consent_message" rows="2" class="w-full px-4 py-2.5 rounded-xl border border-slate-200"><?= htmlspecialchars($settings['cookie_consent_message'] ?? '') ?></textarea>
                    </div>
                </div>
            </div>

            <!-- Legal Pages -->
            <div>
                <h3 class="text-lg font-bold text-slate-800 mb-4 pb-2 border-b border-slate-200">Legal Pages</h3>
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Privacy Policy URL</label>
                        <input type="text" name="privacy_policy_url" value="<?= htmlspecialchars($settings['privacy_policy_url'] ?? '') ?>" class="w-full px-4 py-2.5 rounded-xl border border-slate-200" placeholder="/privacy-policy">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Terms & Conditions URL</label>
                        <input type="text" name="terms_url" value="<?= htmlspecialchars($settings['terms_url'] ?? '') ?>" class="w-full px-4 py-2.5 rounded-xl border border-slate-200" placeholder="/terms">
                    </div>
                </div>
            </div>

            <!-- Content Pages -->
            <div>
                <h3 class="text-lg font-bold text-slate-800 mb-4 pb-2 border-b border-slate-200">Content Pages</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">About Us</label>
                        <textarea name="about_us" rows="6" class="w-full px-4 py-2.5 rounded-xl border border-slate-200"><?= htmlspecialchars($settings['about_us'] ?? '') ?></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Shipping Policy</label>
                        <textarea name="shipping_policy" rows="6" class="w-full px-4 py-2.5 rounded-xl border border-slate-200"><?= htmlspecialchars($settings['shipping_policy'] ?? '') ?></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Return Policy</label>
                        <textarea name="return_policy" rows="6" class="w-full px-4 py-2.5 rounded-xl border border-slate-200"><?= htmlspecialchars($settings['return_policy'] ?? '') ?></textarea>
                    </div>
                </div>
            </div>

            <!-- Payment Methods -->
            <div>
                <h3 class="text-lg font-bold text-slate-800 mb-4 pb-2 border-b border-slate-200">Payment Methods</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Accepted Payment Methods</label>
                        <div class="flex flex-wrap gap-4">
                            <?php
                            $paymentMethodsArray = $settings['payment_methods_array'] ?? [];
                            ?>
                            <label class="flex items-center gap-2">
                                <input type="checkbox" name="payment_methods[]" value="bkash" <?= in_array('bkash', $paymentMethodsArray) ? 'checked' : '' ?> class="rounded">
                                <span>bKash</span>
                            </label>
                            <label class="flex items-center gap-2">
                                <input type="checkbox" name="payment_methods[]" value="nagad" <?= in_array('nagad', $paymentMethodsArray) ? 'checked' : '' ?> class="rounded">
                                <span>Nagad</span>
                            </label>
                            <label class="flex items-center gap-2">
                                <input type="checkbox" name="payment_methods[]" value="rocket" <?= in_array('rocket', $paymentMethodsArray) ? 'checked' : '' ?> class="rounded">
                                <span>Rocket</span>
                            </label>
                            <label class="flex items-center gap-2">
                                <input type="checkbox" name="payment_methods[]" value="credit_card" <?= in_array('credit_card', $paymentMethodsArray) ? 'checked' : '' ?> class="rounded">
                                <span>Credit Card</span>
                            </label>
                            <label class="flex items-center gap-2">
                                <input type="checkbox" name="payment_methods[]" value="bank_transfer" <?= in_array('bank_transfer', $paymentMethodsArray) ? 'checked' : '' ?> class="rounded">
                                <span>Bank Transfer</span>
                            </label>
                            <label class="flex items-center gap-2">
                                <input type="checkbox" name="payment_methods[]" value="cod" <?= in_array('cod', $paymentMethodsArray) ? 'checked' : '' ?> class="rounded">
                                <span>Cash on Delivery</span>
                            </label>
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