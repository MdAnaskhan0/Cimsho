<?php require __DIR__ . '/_sidebar_start.php'; ?>
      <div class="flex items-center justify-between mb-5">
        <h2 class="text-xl font-bold text-primary">My Addresses</h2>
        <button onclick="document.getElementById('add-addr-form').classList.toggle('hidden')" class="btn-primary px-5 py-2 rounded-xl text-sm font-bold">+ Add Address</button>
      </div>
      <?php if (isset($_GET['saved'])): ?>
      <div class="bg-green-50 border border-green-200 text-green-700 rounded-xl px-4 py-3 text-sm mb-5">✅ Address saved!</div>
      <?php endif; ?>

      <!-- Add Address Form -->
      <div id="add-addr-form" class="hidden bg-white rounded-2xl border border-gray-100 p-5 mb-5">
        <h3 class="font-bold text-primary mb-4">New Address</h3>
        <form action="<?= BASE_URL ?>/account/addresses" method="post" class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <input type="hidden" name="address_id" value="">
          <div>
            <label class="text-xs font-bold text-gray-500 block mb-1">Label</label>
            <select name="label" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm">
              <option value="home">Home</option>
              <option value="office">Office</option>
              <option value="other">Other</option>
            </select>
          </div>
          <div>
            <label class="text-xs font-bold text-gray-500 block mb-1">Full Name</label>
            <input type="text" name="full_name" required placeholder="Recipient name" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm">
          </div>
          <div>
            <label class="text-xs font-bold text-gray-500 block mb-1">Phone</label>
            <input type="tel" name="phone" required placeholder="01XXXXXXXXX" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm">
          </div>
          <div>
            <label class="text-xs font-bold text-gray-500 block mb-1">City</label>
            <input type="text" name="city" value="Dhaka" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm">
          </div>
          <div>
            <label class="text-xs font-bold text-gray-500 block mb-1">District</label>
            <input type="text" name="district" placeholder="e.g. Dhaka" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm">
          </div>
          <div>
            <label class="text-xs font-bold text-gray-500 block mb-1">Area / Thana</label>
            <input type="text" name="area" placeholder="e.g. Mirpur" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm">
          </div>
          <div class="md:col-span-2">
            <label class="text-xs font-bold text-gray-500 block mb-1">Full Address</label>
            <textarea name="address_line" required placeholder="House, Road, Block..." rows="2" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm resize-none"></textarea>
          </div>
          <div class="md:col-span-2 flex items-center gap-2">
            <input type="checkbox" name="is_default" value="1" id="is_default" class="w-4 h-4 accent-accent">
            <label for="is_default" class="text-sm text-gray-600">Set as default address</label>
          </div>
          <div class="md:col-span-2 flex gap-3">
            <button type="submit" class="btn-primary px-6 py-2.5 rounded-xl text-sm font-bold">Save Address</button>
            <button type="button" onclick="document.getElementById('add-addr-form').classList.add('hidden')" class="px-6 py-2.5 rounded-xl text-sm border border-gray-200 text-gray-600">Cancel</button>
          </div>
        </form>
      </div>

      <!-- Saved Addresses -->
      <?php if (empty($addresses)): ?>
        <div class="bg-white rounded-2xl border border-gray-100 p-10 text-center">
          <p class="text-gray-400">No addresses saved yet.</p>
        </div>
      <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <?php foreach ($addresses as $addr): ?>
          <div class="bg-white rounded-2xl border border-gray-100 p-5">
            <div class="flex items-start justify-between mb-2">
              <div class="flex items-center gap-2">
                <span class="text-xs bg-primary/10 text-primary px-2 py-0.5 rounded-full font-medium capitalize"><?= $addr['label'] ?></span>
                <?php if ($addr['is_default']): ?><span class="text-xs text-green-600 font-medium">✓ Default</span><?php endif; ?>
              </div>
            </div>
            <p class="font-bold text-sm"><?= htmlspecialchars($addr['full_name']) ?></p>
            <p class="text-xs text-gray-500"><?= htmlspecialchars($addr['phone']) ?></p>
            <p class="text-xs text-gray-500 mt-1"><?= htmlspecialchars($addr['address_line']) ?></p>
            <p class="text-xs text-gray-500"><?= htmlspecialchars(implode(', ', array_filter([$addr['area'], $addr['city'], $addr['district']]))) ?></p>
          </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
<?php require __DIR__ . '/_sidebar_end.php'; ?>
