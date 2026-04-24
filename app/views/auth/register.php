<?php require APP_ROOT . '/app/views/partials/head.php'; ?>
<?php require APP_ROOT . '/app/views/partials/navbar.php'; ?>

<main class="min-h-[calc(100vh-4rem)] flex items-center justify-center px-4 py-12 bg-gradient-to-br from-gray-50 via-orange-50/20 to-gray-100">
    <div class="w-full max-w-md">

        <div class="bg-white rounded-2xl shadow-xl shadow-gray-200/60 overflow-hidden">

            <!-- Header -->
            <div class="btn-brand px-8 py-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-white font-serif text-xl font-bold">Create Account</h1>
                        <p class="text-orange-100 text-xs mt-0.5">Join thousands of happy customers</p>
                    </div>
                </div>
            </div>

            <div class="px-8 py-7">

                <!-- Errors -->
                <?php if (!empty($errors)): ?>
                <div class="mb-5 bg-red-50 border border-red-200 rounded-xl px-4 py-3 space-y-1">
                    <?php foreach ($errors as $e): ?>
                    <p class="text-red-600 text-sm flex items-center gap-2">
                        <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                        <?= htmlspecialchars($e) ?>
                    </p>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <!-- Form -->
                <form action="<?= APP_URL ?>/register" method="POST" class="space-y-4">

                    <!-- Full Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Full Name</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </span>
                            <input type="text" name="name" value="<?= htmlspecialchars($old['name'] ?? '') ?>"
                                placeholder="Your full name"
                                class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl bg-gray-50 text-sm focus:border-brand-400 focus:bg-white transition-colors"
                                required>
                        </div>
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Email Address</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </span>
                            <input type="email" name="email" value="<?= htmlspecialchars($old['email'] ?? '') ?>"
                                placeholder="you@example.com"
                                class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl bg-gray-50 text-sm focus:border-brand-400 focus:bg-white transition-colors"
                                required>
                        </div>
                    </div>

                    <!-- Phone -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Phone <span class="text-gray-400 font-normal">(optional)</span></label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                            </span>
                            <input type="tel" name="phone" value="<?= htmlspecialchars($old['phone'] ?? '') ?>"
                                placeholder="+880 1X XX XXX XXX"
                                class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl bg-gray-50 text-sm focus:border-brand-400 focus:bg-white transition-colors">
                        </div>
                    </div>

                    <!-- Password -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </span>
                            <input type="password" name="password" id="pass1"
                                placeholder="Min. 6 characters"
                                class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl bg-gray-50 text-sm focus:border-brand-400 focus:bg-white transition-colors"
                                required minlength="6">
                        </div>
                        <!-- Strength indicator -->
                        <div class="mt-1.5 flex gap-1">
                            <div id="s1" class="h-1 flex-1 rounded bg-gray-200 transition-colors"></div>
                            <div id="s2" class="h-1 flex-1 rounded bg-gray-200 transition-colors"></div>
                            <div id="s3" class="h-1 flex-1 rounded bg-gray-200 transition-colors"></div>
                            <div id="s4" class="h-1 flex-1 rounded bg-gray-200 transition-colors"></div>
                        </div>
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Confirm Password</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                            </span>
                            <input type="password" name="confirm_password" id="pass2"
                                placeholder="Re-enter password"
                                class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl bg-gray-50 text-sm focus:border-brand-400 focus:bg-white transition-colors"
                                required>
                        </div>
                    </div>

                    <!-- Terms -->
                    <div class="flex items-start gap-2.5">
                        <input type="checkbox" id="terms" required
                            class="w-4 h-4 rounded border-gray-300 text-brand-500 focus:ring-brand-400 cursor-pointer mt-0.5 shrink-0">
                        <label for="terms" class="text-xs text-gray-500 cursor-pointer leading-relaxed">
                            I agree to the <a href="#" class="text-brand-600 hover:underline font-medium">Terms of Service</a>
                            and <a href="#" class="text-brand-600 hover:underline font-medium">Privacy Policy</a>.
                        </label>
                    </div>

                    <button type="submit"
                        class="w-full btn-brand text-white font-semibold py-2.5 rounded-xl shadow hover:shadow-md transition-all text-sm tracking-wide">
                        Create My Account
                    </button>
                </form>

                <p class="text-center text-sm text-gray-500 mt-6">
                    Already have an account?
                    <a href="<?= APP_URL ?>/login" class="text-brand-600 font-semibold hover:text-brand-700">Sign in</a>
                </p>
            </div>
        </div>
    </div>
</main>

<script>
// Password strength meter
document.getElementById('pass1').addEventListener('input', function() {
    const v = this.value;
    let score = 0;
    if (v.length >= 6) score++;
    if (v.length >= 10) score++;
    if (/[A-Z]/.test(v) && /[0-9]/.test(v)) score++;
    if (/[^A-Za-z0-9]/.test(v)) score++;
    const colors = ['','bg-red-400','bg-yellow-400','bg-blue-400','bg-green-500'];
    for (let i = 1; i <= 4; i++) {
        const el = document.getElementById('s' + i);
        el.className = 'h-1 flex-1 rounded transition-colors ' + (i <= score ? colors[score] : 'bg-gray-200');
    }
});
</script>

<?php require APP_ROOT . '/app/views/partials/footer.php'; ?>
