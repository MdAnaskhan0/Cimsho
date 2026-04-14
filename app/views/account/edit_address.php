<div class="max-w-2xl mx-auto px-4 sm:px-6 py-10">
    <h1 class="serif text-4xl font-light mb-2" style="color:var(--charcoal)">Edit Address</h1>
    <p class="text-sm mb-8" style="color:var(--muted)">Update your delivery address.</p>

    <?php include __DIR__.'/_nav.php'; ?>

    <div class="mt-8 bg-white rounded-2xl p-6 border" style="border-color:var(--border)">
        <form method="POST" action="<?= APP_URL ?>/account/address/update/<?= $addr['id'] ?>">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
            <?php $showLabel=true; include __DIR__.'/_address_fields.php'; ?>
            <div class="flex gap-3 mt-6">
                <button type="submit" class="btn-clay px-8 py-3 rounded-xl text-sm font-semibold">Update Address</button>
                <a href="<?= APP_URL ?>/account/addresses" class="px-8 py-3 rounded-xl border text-sm font-medium hover:bg-[var(--warm)] transition-colors" style="border-color:var(--border);color:var(--muted);text-decoration:none">Cancel</a>
            </div>
        </form>
    </div>
</div>
