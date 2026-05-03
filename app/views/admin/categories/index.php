<?php require __DIR__ . '/../partials/header.php'; ?>
<?php if (isset($_GET['created'])): ?><div class="bg-green-50 border border-green-200 text-green-700 rounded-xl px-4 py-3 text-sm mb-4">✅ Category created!</div><?php endif; ?>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
  <!-- Add Category -->
  <div>
    <div class="bg-white rounded-2xl border border-gray-100 p-5 mb-5">
      <h3 class="font-bold mb-4">Add Category</h3>
      <form action="<?= BASE_URL ?>/admin/categories/create" method="post" class="space-y-3">
        <input type="text" name="name" required placeholder="Category Name" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm">
        <textarea name="description" placeholder="Description (optional)" rows="2" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm resize-none"></textarea>
        <input type="number" name="sort_order" value="0" placeholder="Sort Order" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm">
        <button type="submit" class="btn-primary w-full py-2.5 rounded-xl text-sm font-bold">Add Category</button>
      </form>
    </div>
  </div>

  <!-- Categories List -->
  <div class="lg:col-span-2">
    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
      <div class="px-5 py-4 border-b border-gray-100">
        <h3 class="font-bold">All Categories</h3>
      </div>
      <?php foreach ($categories as $cat): ?>
        <div class="border-b border-gray-50 last:border-0">
          <div class="flex items-center gap-3 px-5 py-4">
            <div class="flex-1">
              <p class="font-semibold text-sm"><?= htmlspecialchars($cat['name']) ?></p>
              <p class="text-xs text-gray-400"><?= count($cat['subcategories']) ?> subcategories · slug: <?= $cat['slug'] ?></p>
            </div>
            <div class="flex gap-2">
              <a href="<?= BASE_URL ?>/admin/categories/subcategories/<?= $cat['id'] ?>" class="text-xs px-3 py-1.5 rounded-lg border border-gray-200 hover:border-green-400 hover:text-green-600 transition-all">
                📂 Subcats
              </a>
              <button onclick="editCat(<?= $cat['id'] ?>, '<?= htmlspecialchars($cat['name']) ?>', '<?= htmlspecialchars($cat['description'] ?? '') ?>')" class="text-xs px-3 py-1.5 rounded-lg border border-gray-200 hover:border-blue-400 hover:text-blue-600 transition-all">Edit</button>
              <button onclick="deleteCat(<?= $cat['id'] ?>)" class="text-xs px-3 py-1.5 rounded-lg border border-gray-200 hover:border-red-400 hover:text-red-600 transition-all">Delete</button>
            </div>
          </div>
          <?php if (!empty($cat['subcategories'])): ?>
            <div class="px-10 pb-3 flex flex-wrap gap-2">
              <?php foreach ($cat['subcategories'] as $sub): ?>
                <span class="text-xs bg-gray-50 border border-gray-200 px-3 py-1 rounded-full text-gray-600"><?= htmlspecialchars($sub['name']) ?></span>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<!-- Edit Modal -->
<div id="edit-modal" class="fixed inset-0 bg-black/50 hidden z-50 flex items-center justify-center p-4">
  <div class="bg-white rounded-2xl p-6 w-full max-w-md">
    <h3 class="font-bold mb-4">Edit Category</h3>
    <form id="edit-form" method="post" class="space-y-3">
      <input type="text" name="name" id="edit-name" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm">
      <textarea name="description" id="edit-desc" rows="2" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm resize-none"></textarea>
      <input type="number" name="sort_order" value="0" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm">
      <div class="flex gap-2">
        <button type="submit" class="btn-primary flex-1 py-2.5 rounded-xl text-sm font-bold">Update</button>
        <button type="button" onclick="document.getElementById('edit-modal').classList.add('hidden')" class="flex-1 py-2.5 rounded-xl text-sm border border-gray-200">Cancel</button>
      </div>
    </form>
  </div>
</div>

<script>
  function editCat(id, name, desc) {
    document.getElementById('edit-name').value = name;
    document.getElementById('edit-desc').value = desc;
    document.getElementById('edit-form').action = '<?= BASE_URL ?>/admin/categories/edit/' + id;
    document.getElementById('edit-modal').classList.remove('hidden');
  }
  async function deleteCat(id) {
    if (!confirm('Delete this category?')) return;
    const data = await adminPost('<?= BASE_URL ?>/admin/categories/delete/' + id, {});
    if (data.success) {
      showAdminToast('Deleted!');
      setTimeout(() => location.reload(), 1000);
    }
  }
</script>

<?php require __DIR__ . '/../partials/footer.php'; ?>