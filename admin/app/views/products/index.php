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
        <h2 class="text-xl font-extrabold text-slate-800">Products</h2>
        <nav class="flex items-center gap-2 mt-1 text-xs text-slate-400">
            <a href="<?= APP_URL ?>/dashboard" class="hover:text-indigo-500 transition-colors">Dashboard</a>
            <i class="fas fa-chevron-right text-[9px]"></i>
            <span class="text-slate-600 font-medium">Products</span>
        </nav>
    </div>
    <div class="flex items-center gap-3">
        <a href="<?= APP_URL ?>/products/stock" class="flex items-center gap-2 px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 text-sm font-semibold hover:bg-slate-50 transition-all">
            <i class="fas fa-boxes text-xs"></i>
            Stock Management
        </a>
        <a href="<?= APP_URL ?>/products/create" class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-white text-sm font-semibold transition-all hover:opacity-90" style="background:linear-gradient(135deg,#6366f1,#8b5cf6)">
            <i class="fas fa-plus text-xs"></i>
            Add New Product
        </a>
    </div>
</div>

<!-- Search & Filter Bar -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4 mb-5">
    <div class="grid grid-cols-1 md:grid-cols-5 gap-3">
        <div class="relative md:col-span-2">
            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"><i class="fas fa-search"></i></span>
            <input type="text"
                id="searchInput"
                placeholder="Search by product name or SKU..."
                class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
        </div>

        <select id="categoryFilter" class="px-4 py-2.5 rounded-xl border border-slate-200 text-sm text-slate-600 focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <option value="all">All Categories</option>
            <?php
            // Get unique categories from products
            $categoriesList = [];
            foreach ($products as $product) {
                if (!empty($product['category_name']) && !in_array($product['category_name'], $categoriesList)) {
                    $categoriesList[] = $product['category_name'];
                }
            }
            foreach ($categoriesList as $cat): ?>
                <option value="<?= strtolower(htmlspecialchars($cat)) ?>"><?= htmlspecialchars($cat) ?></option>
            <?php endforeach; ?>
        </select>

        <select id="statusFilter" class="px-4 py-2.5 rounded-xl border border-slate-200 text-sm text-slate-600 focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <option value="all">All Status</option>
            <option value="1">Active</option>
            <option value="0">Inactive</option>
        </select>

        <select id="stockFilter" class="px-4 py-2.5 rounded-xl border border-slate-200 text-sm text-slate-600 focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <option value="all">All Stock</option>
            <option value="low">Low Stock (≤10)</option>
            <option value="out">Out of Stock (0)</option>
            <option value="in">In Stock (>0)</option>
        </select>

        <button onclick="resetFilters()" class="px-4 py-2.5 rounded-xl border border-slate-200 text-sm text-slate-600 hover:bg-slate-50 transition-colors flex items-center justify-center gap-2">
            <i class="fas fa-undo-alt text-xs"></i> Reset
        </button>
    </div>
</div>

<!-- Products Table -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
        <h3 class="font-bold text-slate-700 text-sm">
            Showing <span id="recordCount"><?= count($products) ?></span> of <span id="totalCount"><?= $total ?></span> products
        </h3>
        <div class="flex items-center gap-2">
            <button onclick="window.location.reload()" class="w-8 h-8 rounded-lg border border-slate-200 hover:bg-slate-50 transition-colors flex items-center justify-center text-slate-500 text-xs" title="Refresh">
                <i class="fas fa-sync-alt"></i>
            </button>
            <button onclick="exportToCSV()" class="w-8 h-8 rounded-lg border border-slate-200 hover:bg-slate-50 transition-colors flex items-center justify-center text-slate-500 text-xs" title="Export to CSV">
                <i class="fas fa-download"></i>
            </button>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full" id="productsTable">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-100">
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">ID</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Product</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">SKU</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Category</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Sub Category</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Price Range</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Stock</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Created</th>
                    <th class="text-right px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50" id="tableBody">
                <?php if (empty($products)): ?>
                    <tr id="emptyRow">
                        <td colspan="10" class="px-5 py-16 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-14 h-14 rounded-2xl bg-slate-100 flex items-center justify-center">
                                    <i class="fas fa-box-open text-slate-300 text-2xl"></i>
                                </div>
                                <div>
                                    <p class="text-slate-500 font-semibold text-sm">No products found</p>
                                    <p class="text-slate-400 text-xs mt-1">Create your first product to get started.</p>
                                    <a href="<?= APP_URL ?>/products/create" class="inline-flex items-center gap-2 mt-3 px-4 py-2 rounded-lg bg-indigo-50 text-indigo-600 text-xs font-semibold hover:bg-indigo-100 transition-colors">
                                        <i class="fas fa-plus text-xs"></i> Add Product
                                    </a>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($products as $product): ?>
                        <tr class="product-row hover:bg-slate-50/50 transition-colors"
                            data-status="<?= $product['is_active'] ?>"
                            data-stock="<?= $product['product_stock'] ?>"
                            data-category="<?= strtolower(htmlspecialchars($product['category_name'] ?? '')) ?>"
                            data-name="<?= strtolower(htmlspecialchars($product['product_name'])) ?>"
                            data-sku="<?= strtolower(htmlspecialchars($product['sku'] ?? '')) ?>">

                            <td class="px-5 py-3.5">
                                <span class="text-slate-500 text-sm font-mono">#<?= $product['product_id'] ?></span>
                            </td>

                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-3">
                                    <?php if ($product['primary_image']): ?>
                                        <img src="<?= APP_URL ?>/productImages/<?= $product['primary_image'] ?>" alt="<?= htmlspecialchars($product['product_name']) ?>" class="w-10 h-10 rounded-lg object-cover border border-slate-200">
                                    <?php else: ?>
                                        <div class="w-10 h-10 rounded-lg bg-slate-100 flex items-center justify-center border border-slate-200">
                                            <i class="fas fa-image text-slate-300 text-sm"></i>
                                        </div>
                                    <?php endif; ?>
                                    <div>
                                        <p class="font-semibold text-slate-800 text-sm" title="<?= htmlspecialchars($product['product_name']) ?>">
                                            <?= strlen($product['product_name']) > 30 ? htmlspecialchars(substr($product['product_name'], 0, 30)) . '...' : htmlspecialchars($product['product_name']) ?>
                                        </p>
                                        <?php if ($product['is_featured']): ?>
                                            <span class="text-xs text-amber-600 bg-amber-50 px-2 py-0.5 rounded-full inline-block mt-1">
                                                <i class="fas fa-star text-xs mr-1"></i>Featured
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>

                            <td class="px-5 py-3.5">
                                <code class="text-xs text-indigo-600 bg-indigo-50 px-2 py-1 rounded"><?= htmlspecialchars($product['sku'] ?? 'N/A') ?></code>
                            </td>

                            <td class="px-5 py-3.5">
                                <span class="text-slate-600 text-sm"><?= htmlspecialchars($product['category_name'] ?? '—') ?></span>
                            </td>

                            <td class="px-5 py-3.5">
                                <span class="text-slate-500 text-xs"><?= htmlspecialchars($product['sub_category_name'] ?? '—') ?></span>
                            </td>

                            <td class="px-5 py-3.5">
                                <?php
                                // Get price range from the pre-calculated fields
                                $minPrice = $product['min_price'] ?? null;
                                $maxPrice = $product['max_price'] ?? null;

                                if ($minPrice !== null && $minPrice > 0) {
                                    if ($minPrice == $maxPrice) {
                                        echo '<span class="font-semibold text-slate-700 text-sm">৳' . number_format($minPrice, 2) . '</span>';
                                    } else {
                                        echo '<span class="font-semibold text-slate-700 text-sm">৳' . number_format($minPrice, 2) . ' - ৳' . number_format($maxPrice, 2) . '</span>';
                                    }
                                } else {
                                    echo '<span class="text-slate-400 text-xs">No price set</span>';
                                }
                                ?>
                            </td>

                            <td class="px-5 py-3.5">
                                <?php
                                $stock = $product['product_stock'];
                                $stockClass = $stock <= 0 ? 'bg-red-100 text-red-700' : ($stock <= 10 ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700');
                                ?>
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-semibold <?= $stockClass ?>">
                                    <i class="fas <?= $stock <= 0 ? 'fa-times-circle' : ($stock <= 10 ? 'fa-exclamation-triangle' : 'fa-check-circle') ?> text-xs"></i>
                                    <?= $stock ?> units
                                </span>
                            </td>

                            <td class="px-5 py-3.5">
                                <span class="badge <?= $product['is_active'] ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500' ?>">
                                    <?= $product['is_active'] ? 'Active' : 'Inactive' ?>
                                </span>
                            </td>

                            <td class="px-5 py-3.5">
                                <span class="text-slate-400 text-xs"><?= date('d M, Y', strtotime($product['created_at'])) ?></span>
                            </td>

                            <td class="px-5 py-3.5">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="<?= APP_URL ?>/products/edit/<?= $product['product_id'] ?>"
                                        class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 hover:bg-indigo-100 flex items-center justify-center text-xs transition-colors"
                                        title="Edit Product">
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>
                                    <button onclick="toggleStatus(<?= $product['product_id'] ?>, <?= $product['is_active'] ?>)"
                                        class="w-8 h-8 rounded-lg bg-slate-50 text-slate-600 hover:bg-slate-100 flex items-center justify-center text-xs transition-colors"
                                        title="Toggle Status">
                                        <i class="fas <?= $product['is_active'] ? 'fa-eye-slash' : 'fa-eye' ?>"></i>
                                    </button>
                                    <button onclick="confirmDelete(<?= $product['product_id'] ?>, '<?= htmlspecialchars(addslashes($product['product_name'])) ?>')"
                                        class="w-8 h-8 rounded-lg bg-red-50 text-red-500 hover:bg-red-100 flex items-center justify-center text-xs transition-colors"
                                        title="Delete Product">
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
        <div class="flex items-center justify-between px-5 py-4 border-t border-slate-100">
            <p class="text-slate-400 text-xs">
                Showing <?= (($page - 1) * 20) + 1 ?> to <?= min($page * 20, $total) ?> of <?= $total ?> entries
            </p>
            <div class="flex items-center gap-1">
                <?php if ($page > 1): ?>
                    <a href="?page=<?= $page - 1 ?>" class="px-3 py-2 rounded-lg border border-slate-200 hover:bg-slate-50 transition-colors flex items-center justify-center text-slate-500 text-xs">
                        <i class="fas fa-chevron-left mr-1"></i> Previous
                    </a>
                <?php endif; ?>

                <?php
                $start = max(1, $page - 2);
                $end = min($totalPages, $page + 2);
                for ($i = $start; $i <= $end; $i++):
                ?>
                    <a href="?page=<?= $i ?>" class="min-w-[32px] h-8 rounded-lg <?= $i == $page ? 'bg-indigo-600 text-white' : 'border border-slate-200 text-slate-500 hover:bg-slate-50' ?> flex items-center justify-center text-xs font-bold transition-colors px-2">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>

                <?php if ($page < $totalPages): ?>
                    <a href="?page=<?= $page + 1 ?>" class="px-3 py-2 rounded-lg border border-slate-200 hover:bg-slate-50 transition-colors flex items-center justify-center text-slate-500 text-xs">
                        Next <i class="fas fa-chevron-right ml-1"></i>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden" style="backdrop-filter: blur(4px);">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full mx-4 transform transition-all">
        <div class="p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-red-500 text-lg"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-800">Delete Product</h3>
            </div>
            <p class="text-slate-600 text-sm mb-6">
                Are you sure you want to delete <strong id="deleteProductName"></strong>? This will also delete all sizes, colors, and images. This action cannot be undone.
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
    let deleteProductId = null;

    function confirmDelete(id, name) {
        deleteProductId = id;
        document.getElementById('deleteProductName').textContent = name;
        document.getElementById('deleteForm').action = '<?= APP_URL ?>/products/delete/' + id;
        document.getElementById('deleteModal').classList.remove('hidden');
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
        deleteProductId = null;
    }

    function toggleStatus(id, currentStatus) {
        const newStatus = currentStatus == 1 ? 0 : 1;
        if (confirm('Change product status?')) {
            fetch('<?= APP_URL ?>/products/toggle-status/' + id, {
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

    // Filter functionality
    function filterTable() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();
        const statusFilter = document.getElementById('statusFilter').value;
        const stockFilter = document.getElementById('stockFilter').value;
        const categoryFilter = document.getElementById('categoryFilter').value;

        const rows = document.querySelectorAll('.product-row');
        let visibleCount = 0;

        rows.forEach(row => {
            const name = row.getAttribute('data-name');
            const sku = row.getAttribute('data-sku');
            const status = row.getAttribute('data-status');
            const stock = parseInt(row.getAttribute('data-stock'));
            const category = row.getAttribute('data-category');

            let matchesSearch = name.includes(searchTerm) || sku.includes(searchTerm);
            let matchesStatus = statusFilter === 'all' || status === statusFilter;
            let matchesCategory = categoryFilter === 'all' || category === categoryFilter;
            let matchesStock = true;

            if (stockFilter === 'low') matchesStock = stock <= 10;
            else if (stockFilter === 'out') matchesStock = stock === 0;
            else if (stockFilter === 'in') matchesStock = stock > 0;

            if (matchesSearch && matchesStatus && matchesStock && matchesCategory) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        document.getElementById('recordCount').textContent = visibleCount;

        // Show/hide empty state
        const emptyRow = document.getElementById('emptyRow');
        const tbody = document.getElementById('tableBody');
        const noResultsRow = document.getElementById('noResultsRow');

        if (visibleCount === 0 && rows.length > 0) {
            if (!noResultsRow) {
                const newRow = document.createElement('tr');
                newRow.id = 'noResultsRow';
                newRow.innerHTML = '<td colspan="10" class="px-5 py-16 text-center"><div class="flex flex-col items-center gap-3"><div class="w-14 h-14 rounded-2xl bg-slate-100 flex items-center justify-center"><i class="fas fa-search text-slate-300 text-2xl"></i></div><div><p class="text-slate-500 font-semibold text-sm">No matching products found</p><p class="text-slate-400 text-xs mt-1">Try adjusting your search or filter criteria</p></div></div></td>';
                tbody.appendChild(newRow);
            }
        } else if (noResultsRow) {
            noResultsRow.remove();
        }
    }

    function resetFilters() {
        document.getElementById('searchInput').value = '';
        document.getElementById('statusFilter').value = 'all';
        document.getElementById('stockFilter').value = 'all';
        document.getElementById('categoryFilter').value = 'all';
        filterTable();
    }

    // Export to CSV
    function exportToCSV() {
        const rows = document.querySelectorAll('.product-row:not([style*="display: none"])');
        if (rows.length === 0) {
            alert('No products to export');
            return;
        }

        let csv = [];
        // Headers
        csv.push(['ID', 'Product Name', 'SKU', 'Category', 'Sub Category', 'Price Range', 'Stock', 'Status', 'Featured', 'Created Date'].join(','));

        // Data rows
        rows.forEach(row => {
            const cells = row.querySelectorAll('td');
            const id = cells[0]?.innerText.trim() || '';
            const product = cells[1]?.innerText.trim() || '';
            const sku = cells[2]?.innerText.trim() || '';
            const category = cells[3]?.innerText.trim() || '';
            const subCategory = cells[4]?.innerText.trim() || '';
            const price = cells[5]?.innerText.trim() || '';
            const stock = cells[6]?.innerText.trim() || '';
            const status = cells[7]?.innerText.trim() || '';
            const created = cells[8]?.innerText.trim() || '';

            csv.push([id, product, sku, category, subCategory, price, stock, status, created].join(','));
        });

        // Download CSV
        const blob = new Blob([csv.join('\n')], {
            type: 'text/csv'
        });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `products_export_${new Date().toISOString().slice(0,19)}.csv`;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        window.URL.revokeObjectURL(url);
    }

    // Event listeners
    document.getElementById('searchInput').addEventListener('input', filterTable);
    document.getElementById('statusFilter').addEventListener('change', filterTable);
    document.getElementById('stockFilter').addEventListener('change', filterTable);
    document.getElementById('categoryFilter').addEventListener('change', filterTable);

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

    .product-row {
        transition: background-color 0.2s ease;
    }

    /* Table hover effect */
    .product-row:hover {
        background-color: #f8fafc;
    }
</style>