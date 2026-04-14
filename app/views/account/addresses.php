<div class="max-w-4xl mx-auto px-4 sm:px-6 py-10">
    <h1 class="serif text-4xl font-light mb-2" style="color:var(--charcoal)">My Addresses</h1>
    <p class="text-sm mb-8" style="color:var(--muted)">Manage your saved delivery addresses.</p>

    <?php include __DIR__.'/_nav.php'; ?>

    <div class="mt-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
            <?php foreach($addrs as $addr):
                $labelIcons=['home'=>'fa-house','office'=>'fa-briefcase','other'=>'fa-location-dot'];
            ?>
            <div class="bg-white rounded-2xl p-5 border relative <?= $addr['is_default']?'':'opacity-90' ?>" style="border-color:<?= $addr['is_default']?'var(--clay)':'var(--border)' ?>">
                <?php if($addr['is_default']): ?>
                <span class="absolute top-3 right-3 text-[10px] font-bold px-2 py-0.5 rounded-full" style="background:rgba(196,149,106,.15);color:var(--clay)">DEFAULT</span>
                <?php endif; ?>
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background:rgba(196,149,106,.1)">
                        <i class="fas <?= $labelIcons[$addr['label']]??'fa-map-pin' ?> text-xs" style="color:var(--clay)"></i>
                    </div>
                    <span class="text-xs font-bold uppercase tracking-wider" style="color:var(--clay)"><?= ucfirst($addr['label']) ?></span>
                </div>
                <p class="font-semibold text-sm mb-0.5"><?= htmlspecialchars($addr['full_name']) ?></p>
                <p class="text-xs" style="color:var(--muted)"><?= htmlspecialchars($addr['phone']) ?></p>
                <p class="text-xs mt-0.5" style="color:var(--muted)"><?= htmlspecialchars($addr['address_line'].', '.($addr['area']?$addr['area'].', ':'').$addr['city'].($addr['district']?', '.$addr['district']:'')) ?></p>

                <div class="flex items-center gap-3 mt-4 pt-4 border-t" style="border-color:var(--border)">
                    <a href="<?= APP_URL ?>/account/address/edit/<?= $addr['id'] ?>" class="text-xs font-medium" style="color:var(--clay);text-decoration:none"><i class="fas fa-pencil mr-1"></i>Edit</a>
                    <?php if(!$addr['is_default']): ?>
                    <button onclick="setDefault(<?= $addr['id'] ?>)" class="text-xs font-medium" style="color:var(--muted)"><i class="fas fa-star mr-1"></i>Set Default</button>
                    <form method="POST" action="<?= APP_URL ?>/account/address/delete/<?= $addr['id'] ?>" class="ml-auto" onsubmit="return confirm('Remove this address?')">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
                        <button type="submit" class="text-xs font-medium hover:text-red-500 transition-colors" style="color:var(--muted)"><i class="fas fa-trash mr-1"></i>Remove</button>
                    </form>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>

            <!-- Add new address card -->
            <button onclick="document.getElementById('add-addr-modal').classList.remove('hidden')"
                    class="rounded-2xl p-5 border-2 border-dashed flex flex-col items-center justify-center gap-3 min-h-[160px] hover:border-[var(--clay)] hover:bg-[var(--warm)] transition-all" style="border-color:var(--border)">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background:rgba(196,149,106,.1)">
                    <i class="fas fa-plus" style="color:var(--clay)"></i>
                </div>
                <span class="text-sm font-semibold" style="color:var(--clay)">Add New Address</span>
            </button>
        </div>
    </div>
</div>

<!-- Add Address Modal -->
<div id="add-addr-modal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="this.closest('#add-addr-modal').classList.add('hidden')"></div>
    <div class="relative flex items-center justify-center min-h-full p-4">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-lg relative z-10 overflow-hidden max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between px-6 py-5 border-b sticky top-0 bg-white" style="border-color:var(--border)">
                <h3 class="font-bold text-base">Add New Address</h3>
                <button onclick="document.getElementById('add-addr-modal').classList.add('hidden')" class="w-8 h-8 rounded-lg hover:bg-[var(--warm)] flex items-center justify-center" style="color:var(--muted)">
                    <i class="fas fa-xmark"></i>
                </button>
            </div>
            <form method="POST" action="<?= APP_URL ?>/account/address/add" class="px-6 py-5">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
                <?php $showLabel=true; include __DIR__.'/_address_fields.php'; ?>
                <div class="flex gap-3 mt-6">
                    <button type="submit" class="btn-clay flex-1 py-3 rounded-xl text-sm font-semibold">Save Address</button>
                    <button type="button" onclick="document.getElementById('add-addr-modal').classList.add('hidden')"
                            class="px-5 py-3 rounded-xl border text-sm font-medium hover:bg-[var(--warm)] transition-colors" style="border-color:var(--border);color:var(--muted)">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
async function setDefault(id){
    await fetch(APP_URL+'/account/address/default/'+id,{method:'POST'});
    location.reload();
}
</script>
