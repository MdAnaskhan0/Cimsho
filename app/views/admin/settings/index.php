<?php require __DIR__ . '/../partials/header.php'; ?>

<?php if (isset($_GET['saved'])): ?>
  <div class="bg-green-50 border border-green-200 text-green-700 rounded-xl px-4 py-3 text-sm mb-4">✅ Settings saved!</div>
<?php endif; ?>

<?php if (isset($_SESSION['success'])): ?>
  <div class="bg-green-50 border border-green-200 text-green-700 rounded-xl px-4 py-3 text-sm mb-4">✅ <?= $_SESSION['success'] ?></div>
  <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
  <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 text-sm mb-4">❌ <?= $_SESSION['error'] ?></div>
  <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
  <!-- Logo Settings -->
  <div class="bg-white rounded-2xl border border-gray-100 p-6">
    <h3 class="font-bold text-gray-800 mb-5">Logo Settings</h3>

    <!-- Site Logo -->
    <div class="mb-6 pb-6 border-b border-gray-100">
      <label class="text-sm font-bold text-gray-700 block mb-2">Site Logo (Navbar)</label>
      <?php
      $siteLogo = $settings['site_logo'] ?? '';
      $footerLogo = $settings['footer_logo'] ?? '';
      ?>
      <?php if ($siteLogo): ?>
        <div class="mb-3 p-3 bg-gray-50 rounded-xl">
          <img src="<?= BASE_URL . $siteLogo ?>" alt="Site Logo" class="h-12 object-contain mb-2">
          <button type="button" onclick="removeLogo('site_logo')" class="text-xs text-red-500 hover:text-red-700">Remove Logo</button>
        </div>
      <?php endif; ?>
      <form action="<?= BASE_URL ?>/admin/settings/upload-logo" method="post" enctype="multipart/form-data" class="mt-2">
        <input type="hidden" name="logo_type" value="site_logo">
        <div class="flex gap-2">
          <input type="file" name="logo" accept="image/*" required class="flex-1 text-sm border border-gray-200 rounded-xl px-3 py-2">
          <button type="submit" class="btn-primary px-4 py-2 rounded-xl text-sm">Upload</button>
        </div>
        <p class="text-xs text-gray-400 mt-2">Recommended size: 200x60px. Formats: PNG, JPG, SVG</p>
      </form>
    </div>

    <!-- Footer Logo -->
    <div>
      <label class="text-sm font-bold text-gray-700 block mb-2">Footer Logo</label>
      <?php if ($footerLogo): ?>
        <div class="mb-3 p-3 bg-gray-50 rounded-xl">
          <img src="<?= BASE_URL . $footerLogo ?>" alt="Footer Logo" class="h-10 object-contain mb-2">
          <button type="button" onclick="removeLogo('footer_logo')" class="text-xs text-red-500 hover:text-red-700">Remove Logo</button>
        </div>
      <?php endif; ?>
      <form action="<?= BASE_URL ?>/admin/settings/upload-logo" method="post" enctype="multipart/form-data" class="mt-2">
        <input type="hidden" name="logo_type" value="footer_logo">
        <div class="flex gap-2">
          <input type="file" name="logo" accept="image/*" required class="flex-1 text-sm border border-gray-200 rounded-xl px-3 py-2">
          <button type="submit" class="btn-primary px-4 py-2 rounded-xl text-sm">Upload</button>
        </div>
        <p class="text-xs text-gray-400 mt-2">Recommended size: 200x60px. Formats: PNG, JPG, SVG</p>
      </form>
    </div>
  </div>

  <!-- Site Information -->
  <div class="bg-white rounded-2xl border border-gray-100 p-6">
    <h3 class="font-bold text-gray-800 mb-5">Site Information</h3>
    <form action="<?= BASE_URL ?>/admin/settings" method="post">
      <div class="mb-4">
        <label class="text-xs font-bold text-gray-500 uppercase tracking-wide block mb-1.5">Site Name</label>
        <input type="text" name="site_name" value="<?= htmlspecialchars($settings['site_name'] ?? 'Cimsho') ?>" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm">
      </div>
      <button type="submit" class="btn-primary px-6 py-2 rounded-xl text-sm">Save Site Info</button>
    </form>
  </div>

  <!-- Delivery Charges (existing) -->
  <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 p-6">
    <h3 class="font-bold text-gray-800 mb-5">Delivery Charges</h3>
    <form action="<?= BASE_URL ?>/admin/settings" method="post">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="text-xs font-bold text-gray-500 uppercase tracking-wide block mb-1.5">Inside Dhaka (৳)</label>
          <input type="number" name="inside_dhaka_charge" value="<?= $delivery['inside_dhaka_charge'] ?>" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm">
          <p class="text-xs text-gray-400 mt-1">Dhaka, Narayanganj, Gazipur, Manikganj</p>
        </div>
        <div>
          <label class="text-xs font-bold text-gray-500 uppercase tracking-wide block mb-1.5">Outside Dhaka (৳)</label>
          <input type="number" name="outside_dhaka_charge" value="<?= $delivery['outside_dhaka_charge'] ?>" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm">
          <p class="text-xs text-gray-400 mt-1">Rest of Bangladesh</p>
        </div>
        <div>
          <label class="text-xs font-bold text-gray-500 uppercase tracking-wide block mb-1.5">Free Delivery Min. Amount (৳)</label>
          <input type="number" name="free_delivery_min_amount" value="<?= $delivery['free_delivery_min_amount'] ?>" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm">
          <p class="text-xs text-gray-400 mt-1">Orders above this amount get free delivery</p>
        </div>
        <div>
          <label class="text-xs font-bold text-gray-500 uppercase tracking-wide block mb-1.5">Express Delivery Charge (৳)</label>
          <input type="number" name="express_delivery_charge" value="<?= $delivery['express_delivery_charge'] ?>" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm">
        </div>
      </div>
      <div class="mt-4">
        <button type="submit" class="btn-primary px-6 py-2 rounded-xl text-sm">Save Delivery Settings</button>
      </div>
    </form>
  </div>
</div>

<script>
  function removeLogo(type) {
    if (confirm('Are you sure you want to remove this logo?')) {
      const form = document.createElement('form');
      form.method = 'post';
      form.action = '<?= BASE_URL ?>/admin/settings/remove-logo';

      const input = document.createElement('input');
      input.type = 'hidden';
      input.name = 'logo_type';
      input.value = type;

      form.appendChild(input);
      document.body.appendChild(form);
      form.submit();
    }
  }
</script>

<?php require __DIR__ . '/../partials/footer.php'; ?>D