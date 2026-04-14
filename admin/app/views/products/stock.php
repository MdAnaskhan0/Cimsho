<!-- Page Header -->
<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-xl font-extrabold text-slate-800">Stock Management</h2>
        <nav class="flex items-center gap-2 mt-1 text-xs text-slate-400">
            <a href="<?= APP_URL ?>/dashboard" class="hover:text-indigo-500 transition-colors">Dashboard</a>
            <i class="fas fa-chevron-right text-[9px]"></i>
            <a href="<?= APP_URL ?>/products" class="hover:text-indigo-500 transition-colors">Products</a>
            <i class="fas fa-chevron-right text-[9px]"></i>
            <span class="text-slate-600 font-medium">Stock Management</span>
        </nav>
    </div>
</div>

<!-- Low Stock Alert -->
<div class="bg-red-50 border border-red-200 rounded-2xl p-4 mb-5">
    <div class="flex items-center gap-3">
        <i class="fas fa-exclamation-triangle text-red-500 text-xl"></i>
        <div>
            <h3 class="font-bold text-red-800">Low Stock Alert</h3>
            <p class="text-red-600 text-sm">Products with 10 or fewer units remaining</p>
        </div>
    </div>
</div>

<!-- Stock Table -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-100">
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500">Product</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500">SKU</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500">Current Stock</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500">Update Stock</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                <?php if (empty($allProducts)): ?>
                    <tr>
                        <td colspan="5" class="px-5 py-16 text-center">
                            <p class="text-slate-400">No products found</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($allProducts as $product): ?>
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-5 py-3.5">
                                <span class="font-semibold text-slate-800 text-sm"><?= htmlspecialchars($product['product_name']) ?></span>
                            </td>
                            <td class="px-5 py-3.5">
                                <code class="text-xs"><?= htmlspecialchars($product['sku'] ?? 'N/A') ?></code>
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="font-bold <?= $product['product_stock'] <= 5 ? 'text-red-500' : ($product['product_stock'] <= 10 ? 'text-amber-500' : 'text-slate-700') ?>">
                                    <?= $product['product_stock'] ?> units
                                </span>
                            </td>
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-2">
                                    <input type="number" id="stock_<?= $product['product_id'] ?>" value="<?= $product['product_stock'] ?>" class="w-24 px-3 py-1.5 rounded-lg border border-slate-200 text-sm text-center">
                                    <button onclick="updateStock(<?= $product['product_id'] ?>)" class="px-3 py-1.5 rounded-lg bg-indigo-50 text-indigo-600 text-xs font-semibold hover:bg-indigo-100">
                                        Update
                                    </button>
                                </div>
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="badge <?= $product['is_active'] ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500' ?>">
                                    <?= $product['is_active'] ? 'Active' : 'Inactive' ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    function updateStock(productId) {
        const stock = document.getElementById('stock_' + productId).value;

        fetch('<?= APP_URL ?>/products/update-stock', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'product_id=' + productId + '&stock=' + stock + '&csrf_token=<?= $csrf ?>'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Stock updated successfully!');
                    location.reload();
                } else {
                    alert('Failed to update stock');
                }
            });
    }
</script>

<style>
    .badge {
        display: inline-flex;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
    }
</style>