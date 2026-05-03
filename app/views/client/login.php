<?php require __DIR__ . '/partials/header.php'; ?>
<div class="min-h-[60vh] flex items-center justify-center px-4 py-12">
  <div class="w-full max-w-md">
    <div class="text-center mb-8">
      <div class="w-14 h-14 bg-accent rounded-2xl flex items-center justify-center text-white font-bold text-2xl mx-auto mb-3">C</div>
      <h1 class="text-2xl font-extrabold text-primary">Welcome Back</h1>
      <p class="text-gray-400 text-sm mt-1">Login to your Cimsho account</p>
    </div>
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-8">
      <?php if (!empty($error)): ?>
      <div class="bg-red-50 border border-red-200 text-red-600 rounded-xl px-4 py-3 text-sm mb-5 flex items-center gap-2">
        <span>⚠️</span><?= htmlspecialchars($error) ?>
      </div>
      <?php endif; ?>
      <form action="<?= BASE_URL ?>/login" method="post" class="space-y-4">
        <div>
          <label class="text-xs font-bold text-gray-500 uppercase tracking-wide block mb-1.5">Email Address</label>
          <input type="email" name="email" required placeholder="your@email.com" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 text-sm transition-all">
        </div>
        <div>
          <label class="text-xs font-bold text-gray-500 uppercase tracking-wide block mb-1.5">Password</label>
          <div class="relative">
            <input type="password" name="password" id="pwd" required placeholder="••••••••" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 text-sm pr-10 transition-all">
            <button type="button" onclick="togglePwd()" class="absolute right-3 top-3 text-gray-400 hover:text-gray-600">👁</button>
          </div>
        </div>
        <button type="submit" class="btn-primary w-full py-3.5 rounded-xl font-bold text-sm">Login to Account</button>
      </form>
      <p class="text-center text-sm text-gray-400 mt-5">Don't have an account? <a href="<?= BASE_URL ?>/register" class="text-accent font-semibold hover:underline">Register here</a></p>
    </div>
  </div>
</div>
<script>function togglePwd(){const i=document.getElementById('pwd');i.type=i.type==='password'?'text':'password';}</script>
<?php require __DIR__ . '/partials/footer.php'; ?>
