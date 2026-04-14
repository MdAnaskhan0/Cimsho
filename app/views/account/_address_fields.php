<?php $a = $addr ?? []; ?>
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <div>
        <label class="block text-xs font-semibold uppercase tracking-wide mb-1.5" style="color:var(--muted)">Full Name *</label>
        <input type="text" name="full_name" required value="<?= htmlspecialchars($a['full_name']??$_SESSION['user_name']??'') ?>"
               class="form-input w-full px-4 py-3 rounded-xl text-sm" placeholder="Your full name">
    </div>
    <div>
        <label class="block text-xs font-semibold uppercase tracking-wide mb-1.5" style="color:var(--muted)">Phone Number *</label>
        <input type="text" name="phone" required value="<?= htmlspecialchars($a['phone']??'') ?>"
               class="form-input w-full px-4 py-3 rounded-xl text-sm" placeholder="+880 17XX XXXXXX">
    </div>
    <div class="sm:col-span-2">
        <label class="block text-xs font-semibold uppercase tracking-wide mb-1.5" style="color:var(--muted)">Address Line *</label>
        <input type="text" name="address_line" required value="<?= htmlspecialchars($a['address_line']??'') ?>"
               class="form-input w-full px-4 py-3 rounded-xl text-sm" placeholder="House/Flat, Road, Block">
    </div>
    <div>
        <label class="block text-xs font-semibold uppercase tracking-wide mb-1.5" style="color:var(--muted)">Area / Thana</label>
        <input type="text" name="area" value="<?= htmlspecialchars($a['area']??'') ?>"
               class="form-input w-full px-4 py-3 rounded-xl text-sm" placeholder="e.g. Gulshan, Mirpur">
    </div>
    <div>
        <label class="block text-xs font-semibold uppercase tracking-wide mb-1.5" style="color:var(--muted)">City *</label>
        <input type="text" name="city" required value="<?= htmlspecialchars($a['city']??'Dhaka') ?>"
               class="form-input w-full px-4 py-3 rounded-xl text-sm" placeholder="City" id="city-field-<?= uniqid() ?>">
    </div>
    <div>
        <label class="block text-xs font-semibold uppercase tracking-wide mb-1.5" style="color:var(--muted)">District</label>
        <select name="district" class="form-input w-full px-4 py-3 rounded-xl text-sm">
            <option value="">Select District</option>
            <?php foreach(['Dhaka','Chattogram','Rajshahi','Khulna','Sylhet','Barishal','Rangpur','Mymensingh',
                'Gazipur','Narayanganj','Comilla','Cox\'s Bazar','Jashore','Bogura','Dinajpur'] as $d): ?>
            <option value="<?= $d ?>" <?= ($a['district']??'')===$d?'selected':'' ?>><?= $d ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div>
        <label class="block text-xs font-semibold uppercase tracking-wide mb-1.5" style="color:var(--muted)">Postal Code</label>
        <input type="text" name="postal_code" value="<?= htmlspecialchars($a['postal_code']??'') ?>"
               class="form-input w-full px-4 py-3 rounded-xl text-sm" placeholder="e.g. 1212">
    </div>
</div>
<?php if(isset($showLabel) && $showLabel): ?>
<div class="mt-4">
    <label class="block text-xs font-semibold uppercase tracking-wide mb-2" style="color:var(--muted)">Address Label</label>
    <div class="flex gap-3">
        <?php foreach(['home'=>'fa-house','office'=>'fa-briefcase','other'=>'fa-location-dot'] as $val=>$icon): ?>
        <label class="flex items-center gap-2 px-4 py-2.5 rounded-xl border-2 cursor-pointer transition-all text-sm font-medium" style="border-color:var(--border)">
            <input type="radio" name="label" value="<?= $val ?>" <?= ($a['label']??'home')===$val?'checked':'' ?> class="sr-only" onchange="this.closest('label').style.borderColor='var(--clay)'; document.querySelectorAll('[name=label]').forEach(r=>r!==this&&(r.closest('label').style.borderColor='var(--border)'))">
            <i class="fas <?= $icon ?> text-xs" style="color:var(--clay)"></i> <?= ucfirst($val) ?>
        </label>
        <?php endforeach; ?>
    </div>
</div>
<div class="mt-3 flex items-center gap-2">
    <input type="checkbox" name="is_default" id="is_default_cb" value="1" <?= !empty($a['is_default'])?'checked':'' ?> class="rounded">
    <label for="is_default_cb" class="text-sm cursor-pointer" style="color:var(--muted)">Set as default address</label>
</div>
<?php endif; ?>
