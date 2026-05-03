<?php require __DIR__ . '/../partials/header.php'; ?>

<div class="mb-5 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold">Subcategories</h1>
        <p class="text-gray-500 text-sm mt-1">Category: <strong><?= htmlspecialchars($category['name']) ?></strong></p>
    </div>
    <a href="<?= BASE_URL ?>/admin/categories" class="text-sm text-blue-600 hover:text-blue-800">← Back to Categories</a>
</div>

<?php if (isset($_GET['created'])): ?>
    <div class="bg-green-50 border border-green-200 text-green-700 rounded-xl px-4 py-3 text-sm mb-4">✅ Subcategory created successfully!</div>
<?php endif; ?>
<?php if (isset($_GET['updated'])): ?>
    <div class="bg-blue-50 border border-blue-200 text-blue-700 rounded-xl px-4 py-3 text-sm mb-4">📝 Subcategory updated successfully!</div>
<?php endif; ?>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
    <!-- Add Subcategory Form -->
    <div>
        <div class="bg-white rounded-2xl border border-gray-100 p-5 sticky top-5">
            <h3 class="font-bold mb-4">Add New Subcategory</h3>
            <form action="<?= BASE_URL ?>/admin/categories/subcategory/create" method="post" class="space-y-3">
                <input type="hidden" name="category_id" value="<?= $category['id'] ?>">

                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Subcategory Name *</label>
                    <input type="text" name="name" required placeholder="e.g., Dining Tables" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Description</label>
                    <textarea name="description" placeholder="Optional description" rows="3" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm resize-none focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Sort Order</label>
                    <input type="number" name="sort_order" value="0" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm">
                </div>

                <div class="flex items-center gap-3">
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="is_active" value="1" checked class="rounded border-gray-300">
                        <span class="text-sm">Active</span>
                    </label>
                </div>

                <button type="submit" class="btn-primary w-full py-2.5 rounded-xl text-sm font-bold">Create Subcategory</button>
            </form>
        </div>
    </div>

    <!-- Subcategories List -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 bg-gray-50">
                <h3 class="font-bold">All Subcategories</h3>
                <p class="text-xs text-gray-500 mt-0.5">Total: <?= count($subcategories) ?></p>
            </div>

            <?php if (empty($subcategories)): ?>
                <div class="px-5 py-10 text-center text-gray-400">
                    <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                    <p>No subcategories yet. Create your first one!</p>
                </div>
            <?php else: ?>
                <div class="divide-y divide-gray-100">
                    <?php foreach ($subcategories as $sub): ?>
                        <div class="p-5 hover:bg-gray-50 transition-colors group" id="sub-<?= $sub['id'] ?>">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-1">
                                        <h4 class="font-semibold text-gray-800"><?= htmlspecialchars($sub['name']) ?></h4>
                                        <span class="text-xs px-2 py-0.5 rounded-full <?= $sub['is_active'] ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' ?>">
                                            <?= $sub['is_active'] ? 'Active' : 'Inactive' ?>
                                        </span>
                                        <span class="text-xs text-gray-400">#<?= $sub['sort_order'] ?></span>
                                    </div>
                                    <?php if ($sub['description']): ?>
                                        <p class="text-sm text-gray-500 mb-2"><?= htmlspecialchars($sub['description']) ?></p>
                                    <?php endif; ?>
                                    <div class="text-xs text-gray-400">Slug: <?= $sub['slug'] ?></div>
                                </div>
                                <div class="flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <button onclick="editSub(<?= $sub['id'] ?>, <?= $sub['category_id'] ?>, '<?= htmlspecialchars($sub['name']) ?>', '<?= htmlspecialchars($sub['description'] ?? '') ?>', <?= $sub['sort_order'] ?>, <?= $sub['is_active'] ?>)"
                                        class="text-xs px-3 py-1.5 rounded-lg border border-gray-200 hover:border-blue-400 hover:text-blue-600 transition-all">
                                        Edit
                                    </button>
                                    <button onclick="toggleSubStatus(<?= $sub['id'] ?>, <?= $sub['is_active'] ?>)"
                                        class="text-xs px-3 py-1.5 rounded-lg border border-gray-200 hover:border-yellow-400 hover:text-yellow-600 transition-all">
                                        <?= $sub['is_active'] ? 'Deactivate' : 'Activate' ?>
                                    </button>
                                    <button onclick="deleteSub(<?= $sub['id'] ?>)"
                                        class="text-xs px-3 py-1.5 rounded-lg border border-gray-200 hover:border-red-400 hover:text-red-600 transition-all">
                                        Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div id="edit-modal" class="fixed inset-0 bg-black/50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl p-6 w-full max-w-md">
        <h3 class="font-bold mb-4">Edit Subcategory</h3>
        <form id="edit-form" method="post" class="space-y-3">
            <input type="hidden" name="category_id" id="edit-cat-id">

            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Subcategory Name *</label>
                <input type="text" name="name" id="edit-name" required class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm">
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Description</label>
                <textarea name="description" id="edit-desc" rows="3" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm resize-none"></textarea>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Sort Order</label>
                <input type="number" name="sort_order" id="edit-sort" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm">
            </div>

            <div class="flex items-center gap-3">
                <label class="flex items-center gap-2">
                    <input type="checkbox" name="is_active" value="1" id="edit-active" class="rounded border-gray-300">
                    <span class="text-sm">Active</span>
                </label>
            </div>

            <div class="flex gap-2 pt-2">
                <button type="submit" class="btn-primary flex-1 py-2.5 rounded-xl text-sm font-bold">Update</button>
                <button type="button" onclick="closeModal()" class="flex-1 py-2.5 rounded-xl text-sm border border-gray-200">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
    function editSub(id, catId, name, desc, sortOrder, isActive) {
        document.getElementById('edit-cat-id').value = catId;
        document.getElementById('edit-name').value = name;
        document.getElementById('edit-desc').value = desc;
        document.getElementById('edit-sort').value = sortOrder;
        document.getElementById('edit-active').checked = isActive === 1;
        document.getElementById('edit-form').action = '<?= BASE_URL ?>/admin/categories/subcategory/edit/' + id;
        document.getElementById('edit-modal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('edit-modal').classList.add('hidden');
    }

    async function deleteSub(id) {
        if (!confirm('Delete this subcategory? Products under this will still exist but lose subcategory association.')) return;
        const data = await adminPost('<?= BASE_URL ?>/admin/categories/subcategory/delete/' + id, {});
        if (data.success) {
            showAdminToast('Subcategory deleted!');
            setTimeout(() => location.reload(), 1000);
        }
    }

    async function toggleSubStatus(id, currentStatus) {
        const action = currentStatus ? 'deactivate' : 'activate';
        if (!confirm(`Are you sure you want to ${action} this subcategory?`)) return;
        const data = await adminPost('<?= BASE_URL ?>/admin/categories/subcategory/toggle/' + id, {});
        if (data.success) {
            showAdminToast(`Subcategory ${data.is_active ? 'activated' : 'deactivated'}!`);
            setTimeout(() => location.reload(), 1000);
        }
    }
</script>

<?php require __DIR__ . '/../partials/footer.php'; ?>