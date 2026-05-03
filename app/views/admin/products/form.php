<?php require __DIR__ . '/../partials/header.php';
$isEdit = !empty($product);
$action = $isEdit ? BASE_URL . '/admin/products/edit/' . $product['product_id'] : BASE_URL . '/admin/products/create';
?>

<?php if (isset($_GET['updated'])): ?><div class="bg-green-50 border border-green-200 text-green-700 rounded-xl px-4 py-3 text-sm mb-4">✅ Product updated!</div><?php endif; ?>

<form action="<?= $action ?>" method="post" enctype="multipart/form-data" class="space-y-5">
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

    <!-- Main Info -->
    <div class="lg:col-span-2 space-y-5">
      <div class="bg-white rounded-2xl border border-gray-100 p-5">
        <h3 class="font-bold text-gray-800 mb-4">Product Information</h3>
        <div class="space-y-4">
          <div>
            <label class="text-xs font-bold text-gray-500 uppercase tracking-wide block mb-1.5">Product Name *</label>
            <input type="text" name="product_name" required value="<?= htmlspecialchars($product['product_name'] ?? '') ?>" placeholder="e.g. Premium Cotton T-Shirt" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm">
          </div>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="text-xs font-bold text-gray-500 uppercase tracking-wide block mb-1.5">SKU</label>
              <input type="text" name="sku" value="<?= htmlspecialchars($product['sku'] ?? '') ?>" placeholder="SKU-001" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm">
            </div>
            <div>
              <label class="text-xs font-bold text-gray-500 uppercase tracking-wide block mb-1.5">Brand</label>
              <input type="text" name="brand" value="<?= htmlspecialchars($product['brand'] ?? '') ?>" placeholder="Brand name" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm">
            </div>
          </div>
          <div>
            <label class="text-xs font-bold text-gray-500 uppercase tracking-wide block mb-1.5">Material</label>
            <input type="text" name="material" value="<?= htmlspecialchars($product['material'] ?? '') ?>" placeholder="e.g. 100% Cotton" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm">
          </div>
          <div>
            <label class="text-xs font-bold text-gray-500 uppercase tracking-wide block mb-1.5">Description</label>
            <textarea name="product_description" rows="4" placeholder="Detailed product description..." class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm resize-none"><?= htmlspecialchars($product['product_description'] ?? '') ?></textarea>
          </div>
        </div>
      </div>

      <!-- Sizes & Prices -->
      <div class="bg-white rounded-2xl border border-gray-100 p-5">
        <div class="flex items-center justify-between mb-4">
          <h3 class="font-bold text-gray-800">Sizes & Pricing</h3>
          <button type="button" onclick="addSizeRow()" class="text-xs text-red-500 font-semibold hover:underline">+ Add Size</button>
        </div>
        <div id="sizes-container" class="space-y-3">
          <?php $sizesData = $isEdit ? ($sizes ?? []) : []; ?>
          <?php if (!empty($sizesData)): ?>
            <?php foreach ($sizesData as $sz): ?>
              <div class="size-row grid grid-cols-2 md:grid-cols-4 gap-2 items-center bg-gray-50 rounded-xl p-3">
                <input type="text" name="size_name[]" value="<?= htmlspecialchars($sz['size_name']) ?>" placeholder="Size (S/M/L/XL)" class="border border-gray-200 rounded-lg px-3 py-2 text-sm col-span-2 md:col-span-1">
                <input type="number" name="regular_price[]" value="<?= $sz['regular_price'] ?>" placeholder="Regular Price" class="border border-gray-200 rounded-lg px-3 py-2 text-sm">
                <input type="number" name="sale_price[]" value="<?= $sz['sale_price'] ?? '' ?>" placeholder="Sale Price" class="border border-gray-200 rounded-lg px-3 py-2 text-sm">
                <button type="button" onclick="this.closest('.size-row').remove()" class="text-red-400 hover:text-red-600 text-lg md:col-span-1">×</button>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <div class="size-row grid grid-cols-2 md:grid-cols-4 gap-2 items-center bg-gray-50 rounded-xl p-3">
              <input type="text" name="size_name[]" placeholder="Size (S/M/L/XL)" class="border border-gray-200 rounded-lg px-3 py-2 text-sm col-span-2 md:col-span-1">
              <input type="number" name="regular_price[]" placeholder="Regular ৳" class="border border-gray-200 rounded-lg px-3 py-2 text-sm">
              <input type="number" name="sale_price[]" placeholder="Sale ৳" class="border border-gray-200 rounded-lg px-3 py-2 text-sm">
              <button type="button" onclick="this.closest('.size-row').remove()" class="text-red-400 hover:text-red-600 text-lg">×</button>
            </div>
          <?php endif; ?>
        </div>
      </div>

      <!-- Colors -->
      <div class="bg-white rounded-2xl border border-gray-100 p-5">
        <div class="flex items-center justify-between mb-4">
          <h3 class="font-bold text-gray-800">Colors</h3>
          <button type="button" onclick="addColorRow()" class="text-xs text-red-500 font-semibold hover:underline">+ Add Color</button>
        </div>
        <div id="colors-container" class="space-y-2">
          <?php $colorsData = $isEdit ? ($colors ?? []) : []; ?>
          <?php if (!empty($colorsData)): ?>
            <?php foreach ($colorsData as $cl): ?>
              <div class="color-row flex items-center gap-2">
                <input type="text" name="color_name[]" value="<?= htmlspecialchars($cl['color_name']) ?>" placeholder="Color name" class="flex-1 border border-gray-200 rounded-xl px-3 py-2 text-sm">
                <input type="color" name="color_code[]" value="<?= $cl['color_code'] ?? '#000000' ?>" class="w-10 h-10 rounded-lg border border-gray-200 cursor-pointer p-1">
                <button type="button" onclick="this.closest('.color-row').remove()" class="text-red-400 hover:text-red-600 text-lg">×</button>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <div class="color-row flex items-center gap-2">
              <input type="text" name="color_name[]" placeholder="Color name (e.g. Red)" class="flex-1 border border-gray-200 rounded-xl px-3 py-2 text-sm">
              <input type="color" name="color_code[]" value="#000000" class="w-10 h-10 rounded-lg border border-gray-200 cursor-pointer p-1">
              <button type="button" onclick="this.closest('.color-row').remove()" class="text-red-400 hover:text-red-600 text-lg">×</button>
            </div>
          <?php endif; ?>
        </div>
      </div>

      <!-- Images -->
      <div class="bg-white rounded-2xl border border-gray-100 p-5">
        <h3 class="font-bold text-gray-800 mb-4">Product Images</h3>
        <?php if ($isEdit && !empty($images)): ?>
          <div class="flex flex-wrap gap-2 mb-4">
            <?php foreach ($images as $img): ?>
              <div class="relative w-20 h-20 rounded-xl overflow-hidden border border-gray-200">
                <img src="<?= BASE_URL ?>/assets/images/products/<?= $img['image_filename'] ?>" class="w-full h-full object-contain p-1" onerror="this.src='<?= BASE_URL ?>/assets/images/placeholder.svg'">
                <?php if ($img['is_primary']): ?><div class="absolute top-0 left-0 right-0 bg-green-500 text-white text-xs text-center py-0.5">Main</div><?php endif; ?>
              </div>
            <?php endforeach; ?>
          </div>
          <p class="text-xs text-gray-400 mb-2">Upload new images to replace existing ones:</p>
        <?php endif; ?>
        <div class="border-2 border-dashed border-gray-200 rounded-xl p-8 text-center hover:border-red-300 transition-all">
          <input type="file" name="images[]" id="images" multiple accept="image/*" class="hidden" onchange="previewImages(this)">
          <label for="images" class="cursor-pointer">
            <div class="text-3xl mb-2">📸</div>
            <p class="text-sm text-gray-600 font-medium">Click to upload images</p>
            <p class="text-xs text-gray-400 mt-1">PNG, JPG up to 5MB each. First image becomes the main image.</p>
          </label>
        </div>
        <div id="img-preview" class="flex flex-wrap gap-2 mt-3"></div>
      </div>
    </div>

    <!-- Sidebar Options -->
    <div class="space-y-5">
      <div class="bg-white rounded-2xl border border-gray-100 p-5">
        <h3 class="font-bold text-gray-800 mb-4">Organization</h3>
        <div class="space-y-4">
          <div>
            <label class="text-xs font-bold text-gray-500 uppercase tracking-wide block mb-1.5">Category</label>
            <select name="category_id" id="category-select" onchange="loadSubcategories(this.value)" class="w-full border border-gray-200 rounded-xl px-3 py-3 text-sm">
              <option value="">Select Category</option>
              <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>" <?= ($product['category_id'] ?? '') == $cat['id'] ? 'selected' : '' ?>><?= htmlspecialchars($cat['name']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div>
            <label class="text-xs font-bold text-gray-500 uppercase tracking-wide block mb-1.5">Sub-Category</label>
            <select name="sub_category_id" id="subcategory-select" class="w-full border border-gray-200 rounded-xl px-3 py-3 text-sm">
              <option value="">-- None (Main Category only) --</option>
              <?php if (!empty($subcategories)): ?>
                <?php foreach ($subcategories as $sub): ?>
                  <option value="<?= $sub['id'] ?>" <?= (($product['sub_category_id'] ?? '') == $sub['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($sub['name']) ?>
                  </option>
                <?php endforeach; ?>
              <?php endif; ?>
            </select>
            <p class="text-xs text-gray-400 mt-1">Leave empty if this product doesn't need a sub-category</p>
          </div>
          <div>
            <label class="text-xs font-bold text-gray-500 uppercase tracking-wide block mb-1.5">Stock Quantity</label>
            <input type="number" name="product_stock" value="<?= $product['product_stock'] ?? 0 ?>" min="0" class="w-full border border-gray-200 rounded-xl px-3 py-3 text-sm">
          </div>
        </div>
      </div>

      <div class="bg-white rounded-2xl border border-gray-100 p-5">
        <h3 class="font-bold text-gray-800 mb-4">Options</h3>
        <label class="flex items-center gap-3 cursor-pointer">
          <div class="relative">
            <input type="checkbox" name="is_featured" value="1" <?= ($product['is_featured'] ?? 0) ? 'checked' : '' ?> class="sr-only peer" id="featured-toggle">
            <div class="w-11 h-6 bg-gray-200 peer-checked:bg-red-500 rounded-full transition-colors"></div>
            <div class="absolute top-0.5 left-0.5 bg-white w-5 h-5 rounded-full shadow transition-transform peer-checked:translate-x-5"></div>
          </div>
          <span class="text-sm font-medium">Featured Product</span>
        </label>
      </div>

      <div class="flex flex-col gap-3">
        <button type="submit" class="btn-primary w-full py-3 rounded-xl font-bold text-sm"><?= $isEdit ? 'Update Product' : 'Create Product' ?></button>
        <a href="<?= BASE_URL ?>/admin/products" class="w-full py-3 rounded-xl font-bold text-sm text-center border border-gray-200 text-gray-600 hover:bg-gray-50 transition-all">Cancel</a>
      </div>
    </div>
  </div>
</form>

<script>
  function addSizeRow() {
    const row = document.createElement('div');
    row.className = 'size-row grid grid-cols-2 md:grid-cols-4 gap-2 items-center bg-gray-50 rounded-xl p-3';
    row.innerHTML = `
    <input type="text" name="size_name[]" placeholder="Size" class="border border-gray-200 rounded-lg px-3 py-2 text-sm col-span-2 md:col-span-1">
    <input type="number" name="regular_price[]" placeholder="Regular ৳" class="border border-gray-200 rounded-lg px-3 py-2 text-sm">
    <input type="number" name="sale_price[]" placeholder="Sale ৳" class="border border-gray-200 rounded-lg px-3 py-2 text-sm">
    <button type="button" onclick="this.closest('.size-row').remove()" class="text-red-400 hover:text-red-600 text-lg">×</button>`;
    document.getElementById('sizes-container').appendChild(row);
  }

  function addColorRow() {
    const row = document.createElement('div');
    row.className = 'color-row flex items-center gap-2';
    row.innerHTML = `
    <input type="text" name="color_name[]" placeholder="Color name" class="flex-1 border border-gray-200 rounded-xl px-3 py-2 text-sm">
    <input type="color" name="color_code[]" value="#000000" class="w-10 h-10 rounded-lg border border-gray-200 cursor-pointer p-1">
    <button type="button" onclick="this.closest('.color-row').remove()" class="text-red-400 hover:text-red-600 text-lg">×</button>`;
    document.getElementById('colors-container').appendChild(row);
  }

  function previewImages(input) {
    const preview = document.getElementById('img-preview');
    preview.innerHTML = '';
    Array.from(input.files).forEach(file => {
      const reader = new FileReader();
      reader.onload = e => {
        const div = document.createElement('div');
        div.className = 'w-16 h-16 rounded-xl overflow-hidden border border-gray-200 bg-gray-50';
        div.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-contain p-1">`;
        preview.appendChild(div);
      };
      reader.readAsDataURL(file);
    });
  }

  async function loadSubcategories(catId) {
    const select = document.getElementById('subcategory-select');
    if (!catId) {
      select.innerHTML = '<option value="">Select Sub-Category</option>';
      return;
    }
    const res = await fetch('<?= BASE_URL ?>/ajax/get-subcategories', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded'
      },
      body: 'category_id=' + catId
    });
    const subs = await res.json();
    select.innerHTML = '<option value="">Select Sub-Category</option>';
    subs.forEach(sub => {
      select.innerHTML += `<option value="${sub.id}">${sub.name}</option>`;
    });
  }
</script>

<?php require __DIR__ . '/../partials/footer.php'; ?>