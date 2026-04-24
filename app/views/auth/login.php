<?php require APP_ROOT . '/app/views/partials/head.php'; ?>
<?php require APP_ROOT . '/app/views/partials/navbar.php'; ?>

<main class="min-h-[calc(100vh-4rem)] flex items-center justify-center px-4 py-12 bg-gradient-to-br from-gray-50 via-orange-50/20 to-gray-100">
    <div class="w-full max-w-md">

        <!-- Card -->
        <div class="bg-white rounded-2xl shadow-xl shadow-gray-200/60 overflow-hidden">

            <!-- Header strip -->
            <div class="btn-brand px-8 py-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-white font-serif text-xl font-bold">Welcome back</h1>
                        <p class="text-orange-100 text-xs mt-0.5">Sign in to your Cimsho account</p>
                    </div>
                </div>
            </div>

            <div class="px-8 py-7">

                <!-- Errors -->
                <?php if (!empty($errors)): ?>
                <div class="mb-5 bg-red-50 border border-red-200 rounded-xl px-4 py-3">
                    <?php foreach ($errors as $e): ?>
                    <p class="text-red-600 text-sm flex items-center gap-2">
                        <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                        <?= htmlspecialchars($e) ?>
                    </p>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <!-- Form -->
                <form action="<?= APP_URL ?>/login" method="POST" class="space-y-5">

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Email Address</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                                </svg>
                            </span>
                            <input type="email" name="email" value="<?= htmlspecialchars($old['email'] ?? '') ?>"
                                placeholder="you@example.com"
                                class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl bg-gray-50 text-sm focus:border-brand-400 focus:bg-white transition-colors"
                                required>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between items-center mb-1.5">
                            <label class="block text-sm font-medium text-gray-700">Password</label>
                            <a href="<?= APP_URL ?>/forgot-password" class="text-xs text-brand-600 hover:text-brand-700 font-medium">Forgot password?</a>
                        </div>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </span>
                            <input type="password" name="password" id="passwordInput"
                                placeholder="••••••••"
                                class="w-full pl-10 pr-11 py-2.5 border border-gray-200 rounded-xl bg-gray-50 text-sm focus:border-brand-400 focus:bg-white transition-colors"
                                required>
                            <button type="button" onclick="togglePass()" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-gray-600">
                                <svg id="eyeIcon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="checkbox" id="remember" name="remember"
                            class="w-4 h-4 rounded border-gray-300 text-brand-500 focus:ring-brand-400 cursor-pointer">
                        <label for="remember" class="text-sm text-gray-600 cursor-pointer">Remember me for 30 days</label>
                    </div>

                    <button type="submit"
                        class="w-full btn-brand text-white font-semibold py-2.5 rounded-xl shadow hover:shadow-md transition-all text-sm tracking-wide">
                        Sign In to Account
                    </button>
                </form>

                <p class="text-center text-sm text-gray-500 mt-6">
                    Don't have an account?
                    <a href="<?= APP_URL ?>/register" class="text-brand-600 font-semibold hover:text-brand-700">Create one free</a>
                </p>
            </div>
        </div>
    </div>
</main>

<script>
function togglePass() {
    const input = document.getElementById('passwordInput');
    input.type = input.type === 'password' ? 'text' : 'password';
}
</script>

<?php require APP_ROOT . '/app/views/partials/footer.php'; ?>
