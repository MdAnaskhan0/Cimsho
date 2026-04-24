<?php require APP_ROOT . '/app/views/partials/head.php'; ?>
<?php require APP_ROOT . '/app/views/partials/navbar.php'; ?>
<?php require APP_ROOT . '/app/views/partials/flash.php'; ?>

<section class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <nav class="flex items-center gap-2 text-xs text-gray-400 mb-3">
            <a href="<?= APP_URL ?>/" class="hover:text-brand-600">Home</a>
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <span class="text-gray-700 font-medium">My Account</span>
        </nav>
        <h1 class="font-serif text-3xl font-bold text-gray-900">My Account</h1>
    </div>
</section>

<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="grid md:grid-cols-3 gap-8">

        <!-- Sidebar -->
        <aside class="md:col-span-1">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <!-- Avatar -->
                <div class="text-center mb-6">
                    <div class="w-20 h-20 bg-gradient-to-br from-brand-400 to-brand-600 rounded-2xl mx-auto flex items-center justify-center text-white font-bold text-3xl font-serif mb-3">
                        <?= strtoupper(substr($user->name ?? 'U', 0, 1)) ?>
                    </div>
                    <h2 class="font-semibold text-gray-900"><?= htmlspecialchars($user->name ?? '') ?></h2>
                    <p class="text-xs text-gray-400 mt-0.5"><?= htmlspecialchars($user->email ?? '') ?></p>
                    <span class="inline-block mt-2 bg-green-100 text-green-700 text-xs font-medium px-2.5 py-0.5 rounded-full">Active</span>
                </div>
                <!-- Nav links -->
                <nav class="space-y-1 text-sm">
                    <?php
                    $links = [
                        ['/account', 'My Profile', 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
                        ['/orders', 'My Orders', 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
                        ['/account/addresses', 'Saved Addresses', 'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z'],
                        ['/account/password', 'Change Password', 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z'],
                    ];
                    foreach ($links as [$href, $label, $icon]): ?>
                    <a href="<?= APP_URL . $href ?>"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-gray-600 hover:bg-brand-50 hover:text-brand-600 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?= $icon ?>"/>
                        </svg>
                        <?= $label ?>
                    </a>
                    <?php endforeach; ?>
                    <hr class="my-2 border-gray-100">
                    <form action="<?= APP_URL ?>/logout" method="POST">
                        <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-red-500 hover:bg-red-50 transition-colors text-left">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            Sign Out
                        </button>
                    </form>
                </nav>
            </div>
        </aside>

        <!-- Main content -->
        <div class="md:col-span-2 space-y-6">

            <!-- Profile info card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-7">
                <h3 class="font-serif text-lg font-bold text-gray-900 mb-5">Personal Information</h3>
                <div class="grid sm:grid-cols-2 gap-5">
                    <?php
                    $fields = [
                        ['Full Name', $user->name ?? '—'],
                        ['Email Address', $user->email ?? '—'],
                        ['Phone', $user->phone ?? '—'],
                        ['Member Since', date('d M, Y', strtotime($user->created_at ?? 'now'))],
                    ];
                    foreach ($fields as [$label, $value]): ?>
                    <div>
                        <p class="text-xs text-gray-400 font-medium uppercase tracking-wide mb-1"><?= $label ?></p>
                        <p class="text-sm font-medium text-gray-800"><?= htmlspecialchars($value) ?></p>
                    </div>
                    <?php endforeach; ?>
                </div>
                <div class="mt-6 pt-5 border-t border-gray-100">
                    <a href="<?= APP_URL ?>/account/edit"
                       class="btn-brand text-white text-sm font-semibold px-5 py-2 rounded-xl shadow hover:shadow-md transition-all inline-flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        Edit Profile
                    </a>
                </div>
            </div>

            <!-- Recent orders placeholder -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-7">
                <div class="flex items-center justify-between mb-5">
                    <h3 class="font-serif text-lg font-bold text-gray-900">Recent Orders</h3>
                    <a href="<?= APP_URL ?>/orders" class="text-xs text-brand-600 font-medium hover:text-brand-700">View all →</a>
                </div>
                <div class="text-center py-10 text-gray-400">
                    <svg class="w-12 h-12 mx-auto mb-3 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    <p class="text-sm">You haven't placed any orders yet.</p>
                    <a href="<?= APP_URL ?>/products" class="inline-block mt-4 btn-brand text-white text-xs font-semibold px-5 py-2 rounded-xl">Start Shopping</a>
                </div>
            </div>

        </div>
    </div>
</main>

<?php require APP_ROOT . '/app/views/partials/footer.php'; ?>
