<?php
// Display messages
if (isset($_SESSION['success_message'])) {
    echo '<div class="mb-5 p-4 rounded-xl bg-emerald-50 border border-emerald-200">
            <div class="flex items-center gap-3">
                <i class="fas fa-check-circle text-emerald-500"></i>
                <span class="text-emerald-700 text-sm font-medium">' . htmlspecialchars($_SESSION['success_message']) . '</span>
                <button onclick="this.parentElement.parentElement.remove()" class="ml-auto text-emerald-400 hover:text-emerald-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>';
    unset($_SESSION['success_message']);
}

if (isset($_SESSION['error_message'])) {
    echo '<div class="mb-5 p-4 rounded-xl bg-red-50 border border-red-200">
            <div class="flex items-center gap-3">
                <i class="fas fa-exclamation-triangle text-red-500"></i>
                <span class="text-red-700 text-sm font-medium">' . htmlspecialchars($_SESSION['error_message']) . '</span>
                <button onclick="this.parentElement.parentElement.remove()" class="ml-auto text-red-400 hover:text-red-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>';
    unset($_SESSION['error_message']);
}
?>

<!-- Page Header -->
<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-xl font-extrabold text-slate-800">Categories</h2>
        <nav class="flex items-center gap-2 mt-1 text-xs text-slate-400">
            <a href="<?= APP_URL ?>/dashboard" class="hover:text-indigo-500 transition-colors" style="text-decoration:none">Dashboard</a>
            <i class="fas fa-chevron-right text-[9px]"></i>
            <span class="text-slate-600 font-medium">Categories</span>
        </nav>
    </div>
    <div class="flex items-center gap-3">
        <a href="<?= APP_URL ?>/categories/create" class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-white text-sm font-semibold transition-all hover:opacity-90" style="background:linear-gradient(135deg,#6366f1,#8b5cf6)">
            <i class="fas fa-plus text-xs"></i>
            Add New Category
        </a>
    </div>
</div>

<!-- Search & Filter Bar -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4 mb-5">
    <div class="flex flex-col sm:flex-row gap-3">
        <div class="relative flex-1">
            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"><i class="fas fa-search"></i></span>
            <input type="text"
                id="searchInput"
                placeholder="Search categories..."
                class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
        </div>
        <select id="statusFilter" class="px-4 py-2.5 rounded-xl border border-slate-200 text-sm text-slate-600 focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <option value="all">All Status</option>
            <option value="1">Active</option>
            <option value="0">Inactive</option>
        </select>
        <button onclick="resetFilters()" class="px-4 py-2.5 rounded-xl border border-slate-200 text-sm text-slate-600 hover:bg-slate-50 transition-colors flex items-center gap-2">
            <i class="fas fa-undo-alt text-xs"></i> Reset
        </button>
    </div>
</div>

<!-- Categories Table -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
        <h3 class="font-bold text-slate-700 text-sm">Showing <span id="recordCount"><?= count($categories) ?></span> of <span id="totalCount"><?= $total ?></span> records</h3>
        <div class="flex items-center gap-2">
            <button onclick="window.location.reload()" class="w-8 h-8 rounded-lg border border-slate-200 hover:bg-slate-50 transition-colors flex items-center justify-center text-slate-500 text-xs">
                <i class="fas fa-sync-alt"></i>
            </button>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full" id="categoriesTable">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-100">
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">ID</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Name</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Slug</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Sort Order</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Created</th>
                    <th class="text-right px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                <?php if (empty($categories)): ?>
                    <tr id="emptyRow">
                        <td colspan="7" class="px-5 py-16 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-14 h-14 rounded-2xl bg-slate-100 flex items-center justify-center">
                                    <i class="fas fa-folder-open text-slate-300 text-2xl"></i>
                                </div>
                                <div>
                                    <p class="text-slate-500 font-semibold text-sm">No categories found</p>
                                    <p class="text-slate-400 text-xs mt-1">Create your first category to get started.</p>
                                    <a href="<?= APP_URL ?>/categories/create" class="inline-flex items-center gap-2 mt-3 px-4 py-2 rounded-lg bg-indigo-50 text-indigo-600 text-xs font-semibold hover:bg-indigo-100 transition-colors">
                                        <i class="fas fa-plus text-xs"></i> Add Category
                                    </a>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($categories as $category): ?>
                        <tr class="hover:bg-slate-50/50 transition-colors category-row" data-status="<?= $category['is_active'] ?>" data-name="<?= strtolower(htmlspecialchars($category['name'])) ?>">
                            <td class="px-5 py-3.5">
                                <span class="text-slate-500 text-sm font-mono">#<?= $category['id'] ?></span>
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="text-slate-800 text-sm font-semibold"><?= htmlspecialchars($category['name']) ?></span>
                            </td>
                            <td class="px-5 py-3.5">
                                <code class="text-xs text-indigo-600 bg-indigo-50 px-2 py-1 rounded"><?= htmlspecialchars($category['slug']) ?></code>
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="text-slate-500 text-sm"><?= $category['sort_order'] ?></span>
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="badge <?= $category['is_active'] ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500' ?>">
                                    <?= $category['is_active'] ? 'Active' : 'Inactive' ?>
                                </span>
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="text-slate-400 text-xs"><?= date('d M, Y', strtotime($category['created_at'])) ?></span>
                            </td>
                            <td class="px-5 py-3.5">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="<?= APP_URL ?>/categories/edit/<?= $category['id'] ?>" class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 hover:bg-indigo-100 flex items-center justify-center text-xs transition-colors" title="Edit">
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>
                                    <button onclick="toggleStatus(<?= $category['id'] ?>)" class="w-8 h-8 rounded-lg bg-slate-50 text-slate-600 hover:bg-slate-100 flex items-center justify-center text-xs transition-colors" title="Toggle Status">
                                        <i class="fas <?= $category['is_active'] ? 'fa-eye-slash' : 'fa-eye' ?>"></i>
                                    </button>
                                    <button onclick="confirmDelete(<?= $category['id'] ?>, '<?= htmlspecialchars(addslashes($category['name'])) ?>')" class="w-8 h-8 rounded-lg bg-red-50 text-red-500 hover:bg-red-100 flex items-center justify-center text-xs transition-colors" title="Delete">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <?php if ($totalPages > 1): ?>
        <div class="flex items-center justify-between px-5 py-3 border-t border-slate-100">
            <p class="text-slate-400 text-xs">Showing page <?= $page ?> of <?= $totalPages ?></p>
            <div class="flex items-center gap-1">
                <?php if ($page > 1): ?>
                    <a href="?page=<?= $page - 1 ?>" class="w-8 h-8 rounded-lg border border-slate-200 hover:bg-slate-50 transition-colors flex items-center justify-center text-slate-500 text-xs">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                <?php endif; ?>

                <?php
                $start = max(1, $page - 2);
                $end = min($totalPages, $page + 2);
                for ($i = $start; $i <= $end; $i++):
                ?>
                    <a href="?page=<?= $i ?>" class="w-8 h-8 rounded-lg <?= $i == $page ? 'bg-indigo-600 text-white' : 'border border-slate-200 text-slate-500 hover:bg-slate-50' ?> flex items-center justify-center text-xs font-bold transition-colors">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>

                <?php if ($page < $totalPages): ?>
                    <a href="?page=<?= $page + 1 ?>" class="w-8 h-8 rounded-lg border border-slate-200 hover:bg-slate-50 transition-colors flex items-center justify-center text-slate-500 text-xs">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Delete Confirmation Modal (Hidden by default) -->
<div id="deleteModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden" style="backdrop-filter: blur(4px);">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full mx-4 transform transition-all">
        <div class="p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-red-500 text-lg"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-800">Delete Category</h3>
            </div>
            <p class="text-slate-600 text-sm mb-6">
                Are you sure you want to delete <strong id="deleteCategoryName"></strong>? This action cannot be undone.
            </p>
            <div class="flex items-center justify-end gap-3">
                <button onclick="closeDeleteModal()" class="px-4 py-2 rounded-lg border border-slate-200 text-slate-600 text-sm font-semibold hover:bg-slate-50 transition-colors">
                    Cancel
                </button>
                <form method="POST" id="deleteForm" action="">
                    <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
                    <button type="submit" class="px-4 py-2 rounded-lg bg-red-500 text-white text-sm font-semibold hover:bg-red-600 transition-colors">
                        Yes, Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    let deleteCategoryId = null;

    function confirmDelete(id, name) {
        deleteCategoryId = id;
        document.getElementById('deleteCategoryName').textContent = name;
        document.getElementById('deleteForm').action = '<?= APP_URL ?>/categories/delete/' + id;
        document.getElementById('deleteModal').classList.remove('hidden');
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
        deleteCategoryId = null;
    }

    function toggleStatus(id) {
        if (confirm('Change category status?')) {
            fetch('<?= APP_URL ?>/categories/toggle-status/' + id, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'csrf_token=<?= $csrf ?>'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Failed to change status');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred');
                });
        }
    }

    // Search and filter functionality
    function filterTable() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();
        const statusFilter = document.getElementById('statusFilter').value;
        const rows = document.querySelectorAll('.category-row');
        let visibleCount = 0;

        rows.forEach(row => {
            const name = row.getAttribute('data-name');
            const status = row.getAttribute('data-status');

            let matchesSearch = name.includes(searchTerm);
            let matchesStatus = statusFilter === 'all' || status === statusFilter;

            if (matchesSearch && matchesStatus) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        // Update record count
        document.getElementById('recordCount').textContent = visibleCount;

        // Show/hide empty state
        const emptyRow = document.getElementById('emptyRow');
        if (emptyRow) {
            if (visibleCount === 0 && rows.length > 0) {
                if (!document.getElementById('noResultsRow')) {
                    const tbody = document.querySelector('#categoriesTable tbody');
                    const noResultsRow = document.createElement('tr');
                    noResultsRow.id = 'noResultsRow';
                    noResultsRow.innerHTML = '<td colspan="7" class="px-5 py-16 text-center"><div class="flex flex-col items-center gap-3"><div class="w-14 h-14 rounded-2xl bg-slate-100 flex items-center justify-center"><i class="fas fa-search text-slate-300 text-2xl"></i></div><div><p class="text-slate-500 font-semibold text-sm">No matching categories found</p><p class="text-slate-400 text-xs mt-1">Try adjusting your search or filter</p></div></div></td>';
                    tbody.appendChild(noResultsRow);
                }
            } else {
                const noResultsRow = document.getElementById('noResultsRow');
                if (noResultsRow) noResultsRow.remove();
            }
        }
    }

    function resetFilters() {
        document.getElementById('searchInput').value = '';
        document.getElementById('statusFilter').value = 'all';
        filterTable();
    }

    // Add event listeners
    document.getElementById('searchInput').addEventListener('input', filterTable);
    document.getElementById('statusFilter').addEventListener('change', filterTable);

    // Close modal when clicking outside
    document.getElementById('deleteModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeDeleteModal();
        }
    });
</script>

<style>
    .badge {
        display: inline-flex;
        align-items: center;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        letter-spacing: 0.3px;
    }
</style>