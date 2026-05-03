<?php require __DIR__ . '/_sidebar_start.php'; ?>
      <h2 class="text-xl font-bold text-primary mb-5">My Profile</h2>
      <?php if (isset($_GET['saved'])): ?>
      <div class="bg-green-50 border border-green-200 text-green-700 rounded-xl px-4 py-3 text-sm mb-5 flex items-center gap-2">✅ Profile updated successfully!</div>
      <?php endif; ?>
      <div class="bg-white rounded-2xl border border-gray-100 p-6">
        <form action="<?= BASE_URL ?>/account/profile" method="post" class="space-y-4">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="text-xs font-bold text-gray-500 uppercase tracking-wide block mb-1.5">Full Name</label>
              <input type="text" name="name" value="<?= htmlspecialchars($user['name'] ?? '') ?>" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 text-sm" required>
            </div>
            <div>
              <label class="text-xs font-bold text-gray-500 uppercase tracking-wide block mb-1.5">Email</label>
              <input type="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>" class="w-full border-2 border-gray-100 rounded-xl px-4 py-3 text-sm bg-gray-50" readonly>
            </div>
            <div>
              <label class="text-xs font-bold text-gray-500 uppercase tracking-wide block mb-1.5">Phone</label>
              <input type="tel" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 text-sm">
            </div>
          </div>
          <hr class="my-2">
          <p class="text-sm font-bold text-gray-600">Change Password <span class="text-gray-400 font-normal">(leave blank to keep current)</span></p>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="text-xs font-bold text-gray-500 uppercase tracking-wide block mb-1.5">New Password</label>
              <input type="password" name="password" placeholder="New password" minlength="8" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 text-sm">
            </div>
          </div>
          <div class="flex gap-3">
            <button type="submit" class="btn-primary px-8 py-3 rounded-xl font-bold text-sm">Save Changes</button>
          </div>
        </form>
      </div>
<?php require __DIR__ . '/_sidebar_end.php'; ?>
