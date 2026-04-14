<div class="max-w-3xl mx-auto px-4 sm:px-6 py-10">
    <h1 class="serif text-4xl font-light mb-2" style="color:var(--charcoal)">Change Password</h1>
    <p class="text-sm mb-8" style="color:var(--muted)">Keep your account secure with a strong password.</p>

    <?php include __DIR__.'/_nav.php'; ?>

    <div class="mt-8 max-w-md">
        <div class="bg-white rounded-2xl p-6 border" style="border-color:var(--border)">
            <form method="POST" action="<?= APP_URL ?>/account/change-password">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wide mb-1.5" style="color:var(--muted)">Current Password</label>
                        <input type="password" name="current_password" required placeholder="••••••••"
                               class="form-input w-full px-4 py-3 rounded-xl text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wide mb-1.5" style="color:var(--muted)">New Password</label>
                        <input type="password" name="new_password" required placeholder="Min. 8 characters"
                               class="form-input w-full px-4 py-3 rounded-xl text-sm" oninput="checkStr(this.value)">
                        <div class="flex gap-1 mt-2">
                            <?php for($i=0;$i<4;$i++): ?><div id="s<?= $i ?>" class="h-1 flex-1 rounded-full" style="background:#e5e7eb"></div><?php endfor; ?>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wide mb-1.5" style="color:var(--muted)">Confirm New Password</label>
                        <input type="password" name="confirm_password" required placeholder="Repeat new password"
                               class="form-input w-full px-4 py-3 rounded-xl text-sm">
                    </div>
                </div>
                <button type="submit" class="btn-clay w-full py-3.5 rounded-xl text-sm font-semibold mt-6">
                    Update Password
                </button>
            </form>
        </div>
    </div>
</div>
<script>
function checkStr(v){
    let s=0;
    if(v.length>=8)s++;if(/[A-Z]/.test(v))s++;if(/[0-9]/.test(v))s++;if(/[^A-Za-z0-9]/.test(v))s++;
    const c=['#ef4444','#f97316','#eab308','#22c55e'];
    for(let i=0;i<4;i++) document.getElementById('s'+i).style.background=i<s?c[s-1]:'#e5e7eb';
}
</script>
