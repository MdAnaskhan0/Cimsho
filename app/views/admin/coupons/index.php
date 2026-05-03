<?php require __DIR__ . '/../partials/header.php'; ?>
<?php if (isset($_GET['created'])): ?><div class="bg-green-50 border border-green-200 text-green-700 rounded-xl px-4 py-3 text-sm mb-4">✅ Coupon created!</div><?php endif; ?>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
  <div>
    <div class="bg-white rounded-2xl border border-gray-100 p-5">
      <h3 class="font-bold mb-4">Create Coupon</h3>
      <form action="<?= BASE_URL ?>/admin/coupons/create" method="post" class="space-y-3">
        <div>
          <label class="text-xs font-bold text-gray-500 block mb-1">Coupon Code *</label>
          <input type="text" name="code" required placeholder="e.g. SAVE20" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm uppercase">
        </div>
        <div>
          <label class="text-xs font-bold text-gray-500 block mb-1">Discount % *</label>
          <input type="number" name="discount_pct" required min="1" max="100" placeholder="e.g. 20" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm">
        </div>
        <div>
          <label class="text-xs font-bold text-gray-500 block mb-1">Min. Order Amount (৳)</label>
          <input type="number" name="min_order" value="0" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm">
        </div>
        <div>
          <label class="text-xs font-bold text-gray-500 block mb-1">Max Uses</label>
          <input type="number" name="max_uses" value="100" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm">
        </div>
        <div>
          <label class="text-xs font-bold text-gray-500 block mb-1">Expires At</label>
          <input type="date" name="expires_at" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm">
        </div>
        <button type="submit" class="btn-primary w-full py-2.5 rounded-xl text-sm font-bold">Create Coupon</button>
      </form>
    </div>
  </div>
  <div class="lg:col-span-2">
    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
      <div class="px-5 py-4 border-b border-gray-100"><h3 class="font-bold">All Coupons</h3></div>
      <table class="w-full data-table">
        <thead><tr class="text-left text-xs text-gray-500 uppercase tracking-wide">
          <th class="px-5 py-3">Code</th>
          <th class="px-4 py-3">Discount</th>
          <th class="px-4 py-3">Min Order</th>
          <th class="px-4 py-3">Usage</th>
          <th class="px-4 py-3">Expires</th>
          <th class="px-4 py-3"></th>
        </tr></thead>
        <tbody class="divide-y divide-gray-50">
          <?php foreach ($coupons as $c): ?>
          <tr>
            <td class="px-5 py-3 font-bold text-sm font-mono text-red-600"><?= $c['code'] ?></td>
            <td class="px-4 py-3 text-sm"><?= $c['discount_pct'] ?>%</td>
            <td class="px-4 py-3 text-sm">৳<?= number_format($c['min_order'], 0) ?></td>
            <td class="px-4 py-3 text-sm"><?= $c['used_count'] ?>/<?= $c['max_uses'] ?></td>
            <td class="px-4 py-3 text-xs text-gray-400"><?= $c['expires_at'] ?? 'No expiry' ?></td>
            <td class="px-4 py-3"><button onclick="deleteCoupon(<?= $c['id'] ?>)" class="text-xs text-red-500 hover:underline">Delete</button></td>
          </tr>
          <?php endforeach; ?>
          <?php if (empty($coupons)): ?><tr><td colspan="6" class="px-5 py-8 text-center text-gray-400 text-sm">No coupons yet</td></tr><?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<script>
async function deleteCoupon(id) {
  if (!confirm('Delete this coupon?')) return;
  const data = await adminPost('<?= BASE_URL ?>/admin/coupons/delete/' + id, {});
  if (data.success) { showAdminToast('Coupon deleted'); setTimeout(() => location.reload(), 1000); }
}
</script>
<?php require __DIR__ . '/../partials/footer.php'; ?>
