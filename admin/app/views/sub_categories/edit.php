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
?>

<!-- Page Header -->
<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-xl font-extrabold text-slate-800">Edit Sub Category</h2>
        <nav class="flex items-center gap-2 mt-1 text-xs text-slate-400">
            <a href="<?= APP_URL ?>/dashboard" class="hover:text-indigo-500 transition-colors" style="text-decoration:none">Dashboard</a>
            <i class="fas fa-chevron-right text-[9px]"></i>
            <a href="<?= APP_URL ?>/sub-categories" class="hover:text-indigo-500 transition-colors" style="text-decoration:none">Sub Categories</a>
            <i class="fas fa-chevron-right text-[9px]"></i>
            <span class="text-slate-600 font-medium">Edit: <?= htmlspecialchars($subCategory['name']) ?></span>
        </nav>
    </div>
    <div class="flex items-center gap-3">
        <a href="<?= APP_URL ?>/sub-categories" class="flex items-center gap-2 px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 text-sm font-semibold hover:bg-slate-50 transition-all">
            <i class="fas fa-arrow-left text-xs"></i>
            Back to Sub Categories
        </a>
    </div>
</div>

<!-- Edit Sub Category Form -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <form method="POST" action="<?= APP_URL ?>/sub-categories/update/<?= $subCategory['id'] ?>" class="p-6">
        <input type="hidden" name="csrf_token" value="<?= $csrf ?>">

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            <!-- Left Column -->
            <div class="space-y-5">
                <!-- Parent Category -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Parent Category <span class="text-red-500">*</span>
                    </label>
                    <select name="category_id"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                        required>
                        <option value="">Select Category</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= $category['id'] ?>" <?= $category['id'] == $subCategory['category_id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($category['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Sub Category Name -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Sub Category Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                        name="name"
                        value="<?= htmlspecialchars($subCategory['name']) ?>"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                        required>
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Description</label>
                    <textarea name="description"
                        rows="5"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all resize-y"><?= htmlspecialchars($subCategory['description'] ?? '') ?></textarea>
                </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-5">
                <!-- Sort Order -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Sort Order</label>
                    <input type="number"
                        name="sort_order"
                        value="<?= $subCategory['sort_order'] ?>"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Status</label>
                    <div class="flex items-center gap-4">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="is_active" value="1" <?= $subCategory['is_active'] == 1 ? 'checked' : '' ?> class="w-4 h-4 text-indigo-600">
                            <span class="text-sm text-slate-600">Active</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="is_active" value="0" <?= $subCategory['is_active'] == 0 ? 'checked' : '' ?> class="w-4 h-4 text-indigo-600">
                            <span class="text-sm text-slate-600">Inactive</span>
                        </label>
                    </div>
                </div>

                <!-- Info Box -->
                <div class="bg-slate-50 rounded-xl p-4">
                    <div class="flex items-start gap-3">
                        <i class="fas fa-link text-indigo-400 mt-0.5 text-sm"></i>
                        <div class="flex-1">
                            <p class="text-xs text-slate-600 font-semibold mb-1">Current URL:</p>
                            <code class="text-xs text-indigo-600 bg-white px-2 py-1 rounded inline-block">
                                <?= APP_URL ?>/category/<?= $subCategory['slug'] ?>
                            </code>
                            <p class="text-xs text-slate-400 mt-2">Created: <?= date('F d, Y', strtotime($subCategory['created_at'])) ?></p>
                            <p class="text-xs text-slate-400">Last updated: <?= date('F d, Y', strtotime($subCategory['updated_at'])) ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex items-center justify-end gap-3 pt-6 mt-6 border-t border-slate-100">
            <a href="<?= APP_URL ?>/sub-categories" class="px-6 py-2.5 rounded-xl border border-slate-200 text-slate-600 text-sm font-semibold hover:bg-slate-50 transition-all">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2.5 rounded-xl text-white text-sm font-semibold transition-all hover:opacity-90" style="background:linear-gradient(135deg,#6366f1,#8b5cf6)">
                <i class="fas fa-save mr-2 text-xs"></i>
                Update Sub Category
            </button>
        </div>
    </form>
</div>