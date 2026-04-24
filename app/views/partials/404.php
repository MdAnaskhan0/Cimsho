<?php require APP_ROOT . '/app/views/partials/head.php'; ?>
<?php require APP_ROOT . '/app/views/partials/navbar.php'; ?>
<main class="min-h-[60vh] flex items-center justify-center px-4">
    <div class="text-center">
        <p class="text-brand-500 font-semibold text-sm uppercase tracking-widest mb-3">404 Error</p>
        <h1 class="font-serif text-5xl font-bold text-gray-900 mb-4">Page Not Found</h1>
        <p class="text-gray-500 mb-8">The page you're looking for doesn't exist or has been moved.</p>
        <a href="<?= APP_URL ?>/" class="btn-brand text-white font-semibold px-6 py-3 rounded-xl inline-block shadow hover:shadow-md transition-all">
            Back to Home
        </a>
    </div>
</main>
<?php require APP_ROOT . '/app/views/partials/footer.php'; ?>
