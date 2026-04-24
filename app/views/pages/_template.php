<?php
/**
 * ═══════════════════════════════════════════════════════════
 * PAGE TEMPLATE — Use this file as a starting point for any
 * new page in the Cimsho client-side MVC project.
 *
 * HOW TO ADD A NEW PAGE:
 * ─────────────────────
 * 1. Copy this file to: app/views/pages/your-page-name.php
 * 2. Create a Controller: app/controllers/YourPageController.php
 * 3. Create a Model (if needed): app/models/YourPageModel.php
 * 4. Register the route in: public/index.php
 *
 * ROUTE EXAMPLE (in public/index.php):
 *   $router->get('/your-page', 'YourPageController', 'index');
 *   $router->post('/your-page', 'YourPageController', 'store');
 *
 * CONTROLLER EXAMPLE:
 *   require_once APP_ROOT . '/core/Controller.php';
 *   class YourPageController extends Controller {
 *       public function index(): void {
 *           $data = ['key' => 'value'];
 *           $this->view('pages.your-page-name', ['title' => 'Page Title', ...$data]);
 *       }
 *   }
 * ═══════════════════════════════════════════════════════════
 */

// ── Required partials (always include these) ──────────────
require APP_ROOT . '/app/views/partials/head.php';
require APP_ROOT . '/app/views/partials/navbar.php';
require APP_ROOT . '/app/views/partials/flash.php';
?>

<!-- ═══════════════════════════════════════════════
     PAGE HEADER (optional breadcrumb / title bar)
════════════════════════════════════════════════ -->
<section class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Breadcrumb -->
        <nav class="flex items-center gap-2 text-xs text-gray-400 mb-3">
            <a href="<?= APP_URL ?>/" class="hover:text-brand-600 transition-colors">Home</a>
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <span class="text-gray-700 font-medium"><?= htmlspecialchars($title ?? 'Page') ?></span>
        </nav>
        <h1 class="font-serif text-3xl font-bold text-gray-900"><?= htmlspecialchars($title ?? 'Page Title') ?></h1>
        <?php if (!empty($subtitle)): ?>
        <p class="text-gray-500 mt-1 text-sm"><?= htmlspecialchars($subtitle) ?></p>
        <?php endif; ?>
    </div>
</section>

<!-- ═══════════════════════════════════════════════
     MAIN PAGE CONTENT
     Replace / extend the sections below as needed.
════════════════════════════════════════════════ -->
<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    <!-- ── Example: Simple content card ───────────────────── -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
        <h2 class="font-serif text-2xl font-bold text-gray-900 mb-4">Section Title</h2>
        <p class="text-gray-600 leading-relaxed mb-6">
            Your page content goes here. This is the main content area for your new page.
            You can add as many sections, components, and layouts as needed.
        </p>

        <!-- ── Example: Two-column grid ───────────────────── -->
        <div class="grid md:grid-cols-2 gap-6 mt-8">
            <div class="bg-gray-50 rounded-xl p-6 border border-gray-100">
                <h3 class="font-semibold text-gray-800 mb-2">Column One</h3>
                <p class="text-sm text-gray-500">Content for the first column.</p>
            </div>
            <div class="bg-gray-50 rounded-xl p-6 border border-gray-100">
                <h3 class="font-semibold text-gray-800 mb-2">Column Two</h3>
                <p class="text-sm text-gray-500">Content for the second column.</p>
            </div>
        </div>
    </div>

    <!-- ── Example: Data list (loop over $items) ──────────── -->
    <?php if (!empty($items)): ?>
    <div class="mt-8 grid grid-cols-1 gap-4">
        <?php foreach ($items as $item): ?>
        <div class="bg-white border border-gray-100 rounded-xl p-5 flex items-center gap-4 shadow-sm card-hover">
            <div class="w-10 h-10 bg-brand-50 rounded-xl flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <p class="font-medium text-gray-800 text-sm truncate"><?= htmlspecialchars($item->name ?? '') ?></p>
                <p class="text-xs text-gray-400"><?= htmlspecialchars($item->description ?? '') ?></p>
            </div>
            <a href="#" class="text-brand-600 text-xs font-medium hover:text-brand-700">View →</a>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- ── Example: Form section ──────────────────────────── -->
    <!--
    <div class="mt-8 bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
        <h2 class="font-serif text-xl font-bold text-gray-900 mb-6">Form Title</h2>
        <form action="<?= APP_URL ?>/your-page" method="POST" class="space-y-5">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Field Label</label>
                <input type="text" name="field_name"
                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl bg-gray-50 text-sm focus:border-brand-400 focus:bg-white transition-colors"
                    placeholder="Placeholder text" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Textarea</label>
                <textarea name="message" rows="4"
                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl bg-gray-50 text-sm focus:border-brand-400 focus:bg-white transition-colors resize-none"
                    placeholder="Your message..."></textarea>
            </div>
            <button type="submit"
                class="btn-brand text-white font-semibold px-6 py-2.5 rounded-xl shadow hover:shadow-md transition-all text-sm">
                Submit
            </button>
        </form>
    </div>
    -->

</main>

<?php
// ── Required footer (always include) ─────────────────────
require APP_ROOT . '/app/views/partials/footer.php';
?>
