<?php
// Display errors if any
if (isset($_SESSION['form_errors'])) {
    echo '<div class="mb-5 p-4 rounded-xl bg-red-50 border border-red-200">...'; // Same as create.php
    unset($_SESSION['form_errors']);
}
?>

<!-- Page Header -->
<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-xl font-extrabold text-slate-800">Edit Category</h2>
        <nav class="flex items-center gap-2 mt-1 text-xs text-slate-400">
            <a href="<?= APP_URL ?>/dashboard" class="hover:text-indigo-500 transition-colors" style="text-decoration:none">Dashboard</a>
            <i class="fas fa-chevron-right text-[9px]"></i>
            <a href="<?= APP_URL ?>/categories" class="hover:text-indigo-500 transition-colors" style="text-decoration:none">Categories</a>
            <i class="fas fa-chevron-right text-[9px]"></i>
            <span class="text-slate-600 font-medium">Edit: <?= htmlspecialchars($category['name']) ?></span>
        </nav>
    </div>
    <div class="flex items-center gap-3">
        <a href="<?= APP_URL ?>/categories" class="flex items-center gap-2 px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 text-sm font-semibold hover:bg-slate-50 transition-all">
            <i class="fas fa-arrow-left text-xs"></i>
            Back to Categories
        </a>
    </div>
</div>

<!-- Edit Category Form -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <form method="POST" action="<?= APP_URL ?>/categories/update/<?= $category['id'] ?>" class="p-6">
        <input type="hidden" name="csrf_token" value="<?= $csrf ?>">

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Same form fields as create.php but with values pre-filled -->
            <div class="space-y-5">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Category Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                        name="name"
                        value="<?= htmlspecialchars($category['name']) ?>"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                        required>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Description</label>
                    <textarea name="description"
                        rows="5"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all resize-y"><?= htmlspecialchars($category['description'] ?? '') ?></textarea>
                </div>
            </div>

            <div class="space-y-5">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Sort Order</label>
                    <input type="number"
                        name="sort_order"
                        value="<?= $category['sort_order'] ?>"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Status</label>
                    <div class="flex items-center gap-4">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="is_active" value="1" <?= $category['is_active'] == 1 ? 'checked' : '' ?> class="w-4 h-4 text-indigo-600">
                            <span class="text-sm text-slate-600">Active</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="is_active" value="0" <?= $category['is_active'] == 0 ? 'checked' : '' ?> class="w-4 h-4 text-indigo-600">
                            <span class="text-sm text-slate-600">Inactive</span>
                        </label>
                    </div>
                </div>

                <div class="bg-slate-50 rounded-xl p-4">
                    <div class="flex items-start gap-3">
                        <i class="fas fa-link text-indigo-400 mt-0.5 text-sm"></i>
                        <div class="flex-1">
                            <p class="text-xs text-slate-600 font-semibold mb-1">Current URL:</p>
                            <code class="text-xs text-indigo-600 bg-white px-2 py-1 rounded inline-block">
                                <?= APP_URL ?>/category/<?= $category['slug'] ?>
                            </code>
                            <p class="text-xs text-slate-400 mt-2">Created: <?= date('F d, Y', strtotime($category['created_at'])) ?></p>
                            <p class="text-xs text-slate-400">Last updated: <?= date('F d, Y', strtotime($category['updated_at'])) ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3 pt-6 mt-6 border-t border-slate-100">
            <a href="<?= APP_URL ?>/categories" class="px-6 py-2.5 rounded-xl border border-slate-200 text-slate-600 text-sm font-semibold hover:bg-slate-50 transition-all">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2.5 rounded-xl text-white text-sm font-semibold transition-all hover:opacity-90" style="background:linear-gradient(135deg,#6366f1,#8b5cf6)">
                <i class="fas fa-save mr-2 text-xs"></i>
                Update Category
            </button>
        </div>
    </form>
</div>