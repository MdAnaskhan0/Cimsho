<?php require __DIR__ . '/../partials/header.php'; ?>
<div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
  <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
    <h3 class="font-bold">Product Reviews</h3>
    <span class="text-sm text-gray-400"><?= count($reviews) ?> reviews</span>
  </div>
  <div class="overflow-x-auto">
    <table class="w-full data-table">
      <thead><tr class="text-left text-xs text-gray-500 uppercase tracking-wide">
        <th class="px-5 py-3">Customer</th>
        <th class="px-4 py-3">Product</th>
        <th class="px-4 py-3">Rating</th>
        <th class="px-4 py-3">Review</th>
        <th class="px-4 py-3">Date</th>
        <th class="px-4 py-3"></th>
      </tr></thead>
      <tbody class="divide-y divide-gray-50">
        <?php foreach ($reviews as $r): ?>
        <tr>
          <td class="px-5 py-3 text-sm font-semibold"><?= htmlspecialchars($r['user_name']) ?></td>
          <td class="px-4 py-3 text-sm text-gray-600 max-w-xs truncate"><?= htmlspecialchars($r['product_name']) ?></td>
          <td class="px-4 py-3 text-yellow-400"><?= str_repeat('★', $r['rating']) ?><span class="text-gray-300"><?= str_repeat('★', 5-$r['rating']) ?></span></td>
          <td class="px-4 py-3 text-sm text-gray-500 max-w-xs truncate"><?= htmlspecialchars($r['review'] ?? '—') ?></td>
          <td class="px-4 py-3 text-xs text-gray-400"><?= date('d M Y', strtotime($r['created_at'])) ?></td>
          <td class="px-4 py-3"><button onclick="deleteReview(<?= $r['id'] ?>)" class="text-xs text-red-500 hover:underline">Delete</button></td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($reviews)): ?><tr><td colspan="6" class="px-5 py-10 text-center text-gray-400">No reviews yet</td></tr><?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
<script>
async function deleteReview(id) {
  if (!confirm('Delete this review?')) return;
  const data = await adminPost('<?= BASE_URL ?>/admin/reviews/delete/' + id, {});
  if (data.success) { showAdminToast('Review deleted'); setTimeout(() => location.reload(), 800); }
}
</script>
<?php require __DIR__ . '/../partials/footer.php'; ?>
