<?php require __DIR__ . '/partials/header.php'; ?>
<div class="min-h-[60vh] flex items-center justify-center px-4 py-12">
  <div class="w-full max-w-md">
    <div class="text-center mb-8">
      <div class="w-14 h-14 bg-accent rounded-2xl flex items-center justify-center text-white font-bold text-2xl mx-auto mb-3">C</div>
      <h1 class="text-2xl font-extrabold text-primary">Create Account</h1>
      <p class="text-gray-400 text-sm mt-1">Join Cimsho for the best shopping experience</p>
    </div>
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-8">
      <?php if (!empty($error)): ?>
      <div class="bg-red-50 border border-red-200 text-red-600 rounded-xl px-4 py-3 text-sm mb-5 flex items-center gap-2">
        <span>⚠️</span><?= htmlspecialchars($error) ?>
      </div>
      <?php endif; ?>
      <form action="<?= BASE_URL ?>/register" method="post" class="space-y-4">
        <div>
          <label class="text-xs font-bold text-gray-500 uppercase tracking-wide block mb-1.5">Full Name</label>
          <input type="text" name="name" required placeholder="Your full name" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 text-sm">
        </div>
        <div>
          <label class="text-xs font-bold text-gray-500 uppercase tracking-wide block mb-1.5">Email Address</label>
          <input type="email" name="email" required placeholder="your@email.com" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 text-sm">
        </div>
        <div>
          <label class="text-xs font-bold text-gray-500 uppercase tracking-wide block mb-1.5">Phone Number</label>
          <input type="tel" name="phone" placeholder="01XXXXXXXXX" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 text-sm">
        </div>
        <div>
          <label class="text-xs font-bold text-gray-500 uppercase tracking-wide block mb-1.5">Password</label>
          <input type="password" name="password" required placeholder="Min. 8 characters" minlength="8" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 text-sm">
        </div>
        <div>
          <label class="text-xs font-bold text-gray-500 uppercase tracking-wide block mb-1.5">Confirm Password</label>
          <input type="password" name="confirm_password" required placeholder="Repeat password" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 text-sm">
        </div>
        <button type="submit" class="btn-primary w-full py-3.5 rounded-xl font-bold text-sm">Create My Account</button>
      </form>
      <p class="text-center text-sm text-gray-400 mt-5">Already have an account? <a href="<?= BASE_URL ?>/login" class="text-accent font-semibold hover:underline">Login here</a></p>
    </div>
  </div>
</div>
<?php require __DIR__ . '/partials/footer.php'; ?>
