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

// Display old input
$old = $_SESSION['old_input'] ?? [];
unset($_SESSION['old_input']);
?>

<!-- Page Header -->
<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-xl font-extrabold text-slate-800">Add New Category</h2>
        <nav class="flex items-center gap-2 mt-1 text-xs text-slate-400">
            <a href="<?= APP_URL ?>/dashboard" class="hover:text-indigo-500 transition-colors" style="text-decoration:none">Dashboard</a>
            <i class="fas fa-chevron-right text-[9px]"></i>
            <a href="<?= APP_URL ?>/categories" class="hover:text-indigo-500 transition-colors" style="text-decoration:none">Categories</a>
            <i class="fas fa-chevron-right text-[9px]"></i>
            <span class="text-slate-600 font-medium">Add New</span>
        </nav>
    </div>
    <div class="flex items-center gap-3">
        <a href="<?= APP_URL ?>/categories" class="flex items-center gap-2 px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 text-sm font-semibold hover:bg-slate-50 transition-all">
            <i class="fas fa-arrow-left text-xs"></i>
            Back to Categories
        </a>
    </div>
</div>

<!-- Add Category Form -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <form method="POST" action="<?= APP_URL ?>/categories/store" class="p-6">
        <input type="hidden" name="csrf_token" value="<?= $csrf ?>">

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            <!-- Left Column -->
            <div class="space-y-5">
                <!-- Category Name -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Category Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                        name="name"
                        value="<?= htmlspecialchars($old['name'] ?? '') ?>"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                        placeholder="e.g., Electronics, Clothing, Books"
                        required>
                    <p class="text-xs text-slate-400 mt-1.5">This will be displayed on the frontend.</p>
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Description</label>
                    <textarea name="description"
                        rows="5"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all resize-y"
                        placeholder="Enter category description..."><?= htmlspecialchars($old['description'] ?? '') ?></textarea>
                    <p class="text-xs text-slate-400 mt-1.5">Optional. Describe what this category is about.</p>
                </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-5">
                <!-- Sort Order -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Sort Order</label>
                    <input type="number"
                        name="sort_order"
                        value="<?= htmlspecialchars($old['sort_order'] ?? '0') ?>"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                        placeholder="0">
                    <p class="text-xs text-slate-400 mt-1.5">Lower numbers appear first in the list.</p>
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Status</label>
                    <div class="flex items-center gap-4">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="is_active" value="1" <?= (!isset($old['is_active']) || $old['is_active'] == '1') ? 'checked' : '' ?> class="w-4 h-4 text-indigo-600 focus:ring-indigo-500">
                            <span class="text-sm text-slate-600">Active</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="is_active" value="0" <?= (isset($old['is_active']) && $old['is_active'] == '0') ? 'checked' : '' ?> class="w-4 h-4 text-indigo-600 focus:ring-indigo-500">
                            <span class="text-sm text-slate-600">Inactive</span>
                        </label>
                    </div>
                    <p class="text-xs text-slate-400 mt-1.5">Inactive categories won't be visible to customers.</p>
                </div>

                <!-- Preview Info -->
                <div class="bg-slate-50 rounded-xl p-4 mt-4">
                    <div class="flex items-start gap-3">
                        <i class="fas fa-info-circle text-indigo-400 mt-0.5 text-sm"></i>
                        <div class="flex-1">
                            <p class="text-xs text-slate-600 font-semibold mb-1">Slug will be auto-generated:</p>
                            <code class="text-xs text-indigo-600 bg-white px-2 py-1 rounded inline-block" id="slug-preview">
                                <?= APP_URL ?>/category/...
                            </code>
                            <p class="text-xs text-slate-400 mt-2">Slug is automatically created from the category name and used for SEO-friendly URLs.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex items-center justify-end gap-3 pt-6 mt-6 border-t border-slate-100">
            <a href="<?= APP_URL ?>/categories" class="px-6 py-2.5 rounded-xl border border-slate-200 text-slate-600 text-sm font-semibold hover:bg-slate-50 transition-all">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2.5 rounded-xl text-white text-sm font-semibold transition-all hover:opacity-90" style="background:linear-gradient(135deg,#6366f1,#8b5cf6)">
                <i class="fas fa-save mr-2 text-xs"></i>
                Create Category
            </button>
        </div>
    </form>
</div>

<script>
    // Live slug preview
    document.querySelector('input[name="name"]').addEventListener('input', function() {
        let name = this.value;
        let slug = name.toLowerCase()
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/^-+|-+$/g, '');

        let preview = document.getElementById('slug-preview');
        if (slug) {
            preview.innerHTML = '<?= APP_URL ?>/category/' + slug;
        } else {
            preview.innerHTML = '<?= APP_URL ?>/category/...';
        }
    });
</script>