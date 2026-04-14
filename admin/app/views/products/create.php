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

$old = $_SESSION['old_input'] ?? [];
unset($_SESSION['old_input']);
?>

<!-- Page Header -->
<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-xl font-extrabold text-slate-800">Add New Product</h2>
        <nav class="flex items-center gap-2 mt-1 text-xs text-slate-400">
            <a href="<?= APP_URL ?>/dashboard" class="hover:text-indigo-500 transition-colors">Dashboard</a>
            <i class="fas fa-chevron-right text-[9px]"></i>
            <a href="<?= APP_URL ?>/products" class="hover:text-indigo-500 transition-colors">Products</a>
            <i class="fas fa-chevron-right text-[9px]"></i>
            <span class="text-slate-600 font-medium">Add New</span>
        </nav>
    </div>
    <div class="flex items-center gap-3">
        <a href="<?= APP_URL ?>/products" class="flex items-center gap-2 px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 text-sm font-semibold hover:bg-slate-50 transition-all">
            <i class="fas fa-arrow-left text-xs"></i>
            Back to Products
        </a>
    </div>
</div>

<!-- Product Form -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <form method="POST" action="<?= APP_URL ?>/products/store" enctype="multipart/form-data" class="p-6">
        <input type="hidden" name="csrf_token" value="<?= $csrf ?>">

        <!-- Basic Information -->
        <div class="mb-8">
            <h3 class="text-lg font-bold text-slate-800 mb-4 pb-2 border-b border-slate-200">Basic Information</h3>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Product Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="product_name" value="<?= htmlspecialchars($old['product_name'] ?? '') ?>" required
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">SKU</label>
                    <input type="text" name="sku" value="<?= htmlspecialchars($old['sku'] ?? '') ?>"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        placeholder="Leave empty for auto-generation">
                    <p class="text-xs text-slate-400 mt-1">Leave empty for auto-generation</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Brand</label>
                    <input type="text" name="brand" value="<?= htmlspecialchars($old['brand'] ?? '') ?>"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Material</label>
                    <input type="text" name="material" value="<?= htmlspecialchars($old['material'] ?? '') ?>"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Category</label>
                    <select name="category_id" id="category_id" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="">Select Category</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= $category['id'] ?>" <?= (isset($old['category_id']) && $old['category_id'] == $category['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($category['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Sub Category</label>
                    <select name="sub_category_id" id="sub_category_id" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="">Select Sub Category</option>
                    </select>
                </div>

                <div class="lg:col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Description</label>
                    <textarea name="product_description" rows="4"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"><?= htmlspecialchars($old['product_description'] ?? '') ?></textarea>
                </div>
            </div>
        </div>

        <!-- Product Sizes with Prices -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-4 pb-2 border-b border-slate-200">
                <h3 class="text-lg font-bold text-slate-800">Product Sizes & Pricing</h3>
                <button type="button" onclick="addSizeRow()" class="px-3 py-1.5 rounded-lg bg-indigo-50 text-indigo-600 text-sm font-semibold hover:bg-indigo-100 transition-colors">
                    <i class="fas fa-plus mr-1"></i> Add Size
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full" id="sizesTable">
                    <thead class="bg-slate-50 rounded-lg">
                        <tr>
                            <th class="px-3 py-2 text-left text-xs font-semibold text-slate-600">Size Name</th>
                            <th class="px-3 py-2 text-left text-xs font-semibold text-slate-600">Height (cm)</th>
                            <th class="px-3 py-2 text-left text-xs font-semibold text-slate-600">Width (cm)</th>
                            <th class="px-3 py-2 text-left text-xs font-semibold text-slate-600">Weight (kg)</th>
                            <th class="px-3 py-2 text-left text-xs font-semibold text-slate-600">Regular Price (৳)</th>
                            <th class="px-3 py-2 text-left text-xs font-semibold text-slate-600">Sale Price (৳)</th>
                            <th class="px-3 py-2 text-center text-xs font-semibold text-slate-600">Action</th>
                        </tr>
                    </thead>
                    <tbody id="sizesBody">
                        <tr class="size-row">
                            <td class="px-3 py-2"><input type="text" name="sizes[0][size_name]" placeholder="e.g., S, M, L, XL" class="w-24 px-2 py-1.5 rounded border border-slate-200 text-sm"></td>
                            <td class="px-3 py-2"><input type="number" step="0.01" name="sizes[0][height]" placeholder="Height" class="w-20 px-2 py-1.5 rounded border border-slate-200 text-sm"></td>
                            <td class="px-3 py-2"><input type="number" step="0.01" name="sizes[0][width]" placeholder="Width" class="w-20 px-2 py-1.5 rounded border border-slate-200 text-sm"></td>
                            <td class="px-3 py-2"><input type="number" step="0.01" name="sizes[0][weight]" placeholder="Weight" class="w-20 px-2 py-1.5 rounded border border-slate-200 text-sm"></td>
                            <td class="px-3 py-2"><input type="number" step="0.01" name="sizes[0][regular_price]" placeholder="Price" class="w-24 px-2 py-1.5 rounded border border-slate-200 text-sm font-semibold" required></td>
                            <td class="px-3 py-2"><input type="number" step="0.01" name="sizes[0][sale_price]" placeholder="Sale Price" class="w-24 px-2 py-1.5 rounded border border-slate-200 text-sm"></td>
                            <td class="px-3 py-2 text-center"><button type="button" onclick="removeSizeRow(this)" class="text-red-400 hover:text-red-600"><i class="fas fa-trash"></i></button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <p class="text-xs text-slate-400 mt-2">* Regular price is required for each size. Sale price is optional.</p>
        </div>

        <!-- Product Colors -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-4 pb-2 border-b border-slate-200">
                <h3 class="text-lg font-bold text-slate-800">Product Colors</h3>
                <button type="button" onclick="addColorRow()" class="px-3 py-1.5 rounded-lg bg-indigo-50 text-indigo-600 text-sm font-semibold hover:bg-indigo-100 transition-colors">
                    <i class="fas fa-plus mr-1"></i> Add Color
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full" id="colorsTable">
                    <thead class="bg-slate-50 rounded-lg">
                        <tr>
                            <th class="px-3 py-2 text-left text-xs font-semibold text-slate-600">Color Name</th>
                            <th class="px-3 py-2 text-left text-xs font-semibold text-slate-600">Color Code</th>
                            <th class="px-3 py-2 text-left text-xs font-semibold text-slate-600">Preview</th>
                            <th class="px-3 py-2 text-center text-xs font-semibold text-slate-600">Action</th>
                        </tr>
                    </thead>
                    <tbody id="colorsBody">
                        <tr class="color-row">
                            <td class="px-3 py-2"><input type="text" name="colors[0][color_name]" placeholder="e.g., Red, Blue, Black" class="w-32 px-2 py-1.5 rounded border border-slate-200 text-sm"></td>
                            <td class="px-3 py-2"><input type="text" name="colors[0][color_code]" placeholder="#FF0000" class="w-28 px-2 py-1.5 rounded border border-slate-200 text-sm color-code-input"></td>
                            <td class="px-3 py-2">
                                <div class="w-8 h-8 rounded-lg border border-slate-200 color-preview"></div>
                            </td>
                            <td class="px-3 py-2 text-center"><button type="button" onclick="removeColorRow(this)" class="text-red-400 hover:text-red-600"><i class="fas fa-trash"></i></button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Product Images -->
        <div class="mb-8">
            <h3 class="text-lg font-bold text-slate-800 mb-4 pb-2 border-b border-slate-200">Product Images</h3>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Upload Images (600x600px recommended)</label>
                <input type="file" name="images[]" multiple accept="image/jpeg,image/png,image/webp"
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <p class="text-xs text-slate-400 mt-2">
                    <i class="fas fa-info-circle"></i> You can upload multiple images. The first image will be set as primary.
                    Images will be automatically resized to 600x600px for optimal loading.
                </p>
            </div>
        </div>

        <!-- Status Options -->
        <div class="mb-8">
            <h3 class="text-lg font-bold text-slate-800 mb-4 pb-2 border-b border-slate-200">Status & Options</h3>
            <div class="flex items-center gap-6">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_featured" value="1" <?= isset($old['is_featured']) ? 'checked' : '' ?> class="w-4 h-4 text-indigo-600 rounded">
                    <span class="text-sm text-slate-700 font-medium">Featured Product</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" <?= (!isset($old['is_active']) || $old['is_active'] == '1') ? 'checked' : '' ?> class="w-4 h-4 text-indigo-600 rounded">
                    <span class="text-sm text-slate-700 font-medium">Active</span>
                </label>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex items-center justify-end gap-3 pt-6 mt-6 border-t border-slate-200">
            <a href="<?= APP_URL ?>/products" class="px-6 py-2.5 rounded-xl border border-slate-200 text-slate-600 text-sm font-semibold hover:bg-slate-50 transition-all">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2.5 rounded-xl text-white text-sm font-semibold transition-all hover:opacity-90" style="background:linear-gradient(135deg,#6366f1,#8b5cf6)">
                <i class="fas fa-save mr-2 text-xs"></i>
                Create Product
            </button>
        </div>
    </form>
</div>

<script>
    let sizeCounter = 1;
    let colorCounter = 1;

    // Add Size Row
    function addSizeRow() {
        const tbody = document.getElementById('sizesBody');
        const newRow = document.createElement('tr');
        newRow.className = 'size-row';
        newRow.innerHTML = `
        <td class="px-3 py-2"><input type="text" name="sizes[${sizeCounter}][size_name]" placeholder="e.g., S, M, L, XL" class="w-24 px-2 py-1.5 rounded border border-slate-200 text-sm"></td>
        <td class="px-3 py-2"><input type="number" step="0.01" name="sizes[${sizeCounter}][height]" placeholder="Height" class="w-20 px-2 py-1.5 rounded border border-slate-200 text-sm"></td>
        <td class="px-3 py-2"><input type="number" step="0.01" name="sizes[${sizeCounter}][width]" placeholder="Width" class="w-20 px-2 py-1.5 rounded border border-slate-200 text-sm"></td>
        <td class="px-3 py-2"><input type="number" step="0.01" name="sizes[${sizeCounter}][weight]" placeholder="Weight" class="w-20 px-2 py-1.5 rounded border border-slate-200 text-sm"></td>
        <td class="px-3 py-2"><input type="number" step="0.01" name="sizes[${sizeCounter}][regular_price]" placeholder="Price" class="w-24 px-2 py-1.5 rounded border border-slate-200 text-sm font-semibold" required></td>
        <td class="px-3 py-2"><input type="number" step="0.01" name="sizes[${sizeCounter}][sale_price]" placeholder="Sale Price" class="w-24 px-2 py-1.5 rounded border border-slate-200 text-sm"></td>
        <td class="px-3 py-2 text-center"><button type="button" onclick="removeSizeRow(this)" class="text-red-400 hover:text-red-600"><i class="fas fa-trash"></i></button></td>
    `;
        tbody.appendChild(newRow);
        sizeCounter++;
    }

    // Remove Size Row
    function removeSizeRow(button) {
        const row = button.closest('tr');
        if (document.querySelectorAll('.size-row').length > 1) {
            row.remove();
        } else {
            alert('You must have at least one size variation');
        }
    }

    // Add Color Row
    function addColorRow() {
        const tbody = document.getElementById('colorsBody');
        const newRow = document.createElement('tr');
        newRow.className = 'color-row';
        newRow.innerHTML = `
        <td class="px-3 py-2"><input type="text" name="colors[${colorCounter}][color_name]" placeholder="e.g., Red, Blue, Black" class="w-32 px-2 py-1.5 rounded border border-slate-200 text-sm"></td>
        <td class="px-3 py-2"><input type="text" name="colors[${colorCounter}][color_code]" placeholder="#FF0000" class="w-28 px-2 py-1.5 rounded border border-slate-200 text-sm color-code-input"></td>
        <td class="px-3 py-2"><div class="w-8 h-8 rounded-lg border border-slate-200 color-preview"></div></td>
        <td class="px-3 py-2 text-center"><button type="button" onclick="removeColorRow(this)" class="text-red-400 hover:text-red-600"><i class="fas fa-trash"></i></button></td>
    `;
        tbody.appendChild(newRow);
        colorCounter++;

        // Add event listener for color preview
        attachColorPreviewEvents();
    }

    // Remove Color Row
    function removeColorRow(button) {
        const row = button.closest('tr');
        row.remove();
    }

    // Color preview functionality
    function attachColorPreviewEvents() {
        document.querySelectorAll('.color-code-input').forEach(input => {
            input.removeEventListener('input', updateColorPreview);
            input.addEventListener('input', updateColorPreview);
        });
    }

    function updateColorPreview(e) {
        const preview = e.target.closest('tr').querySelector('.color-preview');
        if (preview) {
            preview.style.backgroundColor = e.target.value;
        }
    }

    // Load sub-categories based on category selection
    document.getElementById('category_id').addEventListener('change', function() {
        const categoryId = this.value;
        const subCategorySelect = document.getElementById('sub_category_id');

        if (categoryId) {
            fetch('<?= APP_URL ?>/products/get-subcategories?category_id=' + categoryId)
                .then(response => response.json())
                .then(data => {
                    subCategorySelect.innerHTML = '<option value="">Select Sub Category</option>';
                    if (data.success && data.data) {
                        data.data.forEach(sub => {
                            subCategorySelect.innerHTML += `<option value="${sub.id}">${sub.name}</option>`;
                        });
                    }
                });
        } else {
            subCategorySelect.innerHTML = '<option value="">Select Sub Category</option>';
        }
    });

    // Initialize color preview on page load
    document.addEventListener('DOMContentLoaded', function() {
        attachColorPreviewEvents();
    });
</script>