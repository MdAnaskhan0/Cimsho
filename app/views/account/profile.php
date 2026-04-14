<div class="max-w-3xl mx-auto px-4 sm:px-6 py-10">
    <h1 class="serif text-4xl font-light mb-2" style="color:var(--charcoal)">Edit Profile</h1>
    <p class="text-sm mb-8" style="color:var(--muted)">Update your personal information.</p>

    <?php include __DIR__.'/_nav.php'; ?>

    <div class="mt-8 bg-white rounded-2xl p-6 border" style="border-color:var(--border)">
        <form method="POST" action="<?= APP_URL ?>/account/profile/update">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
            <div class="space-y-5">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wide mb-1.5" style="color:var(--muted)">Full Name</label>
                    <input type="text" name="name" required value="<?= htmlspecialchars($u['name']) ?>"
                           class="form-input w-full px-4 py-3 rounded-xl text-sm">
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wide mb-1.5" style="color:var(--muted)">Email Address</label>
                    <input type="email" value="<?= htmlspecialchars($u['email']) ?>" disabled
                           class="form-input w-full px-4 py-3 rounded-xl text-sm opacity-60 cursor-not-allowed">
                    <p class="text-xs mt-1" style="color:var(--muted)">Email cannot be changed.</p>
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wide mb-1.5" style="color:var(--muted)">Phone Number</label>
                    <input type="text" name="phone" value="<?= htmlspecialchars($u['phone']??'') ?>"
                           placeholder="+880 17XX XXXXXX"
                           class="form-input w-full px-4 py-3 rounded-xl text-sm">
                </div>
            </div>
            <div class="flex gap-3 mt-6">
                <button type="submit" class="btn-clay px-8 py-3 rounded-xl text-sm font-semibold">Save Changes</button>
                <a href="<?= APP_URL ?>/account" class="px-8 py-3 rounded-xl text-sm font-semibold border transition-colors hover:bg-[var(--warm)]" style="border-color:var(--border);color:var(--muted);text-decoration:none">Cancel</a>
            </div>
        </form>
    </div>
</div>
