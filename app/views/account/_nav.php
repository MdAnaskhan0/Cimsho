<?php
$currentUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$base = parse_url(APP_URL, PHP_URL_PATH);
$seg = trim(str_replace($base, '', $currentUri), '/');
$navItems = [
    ['href'=>'account',           'label'=>'Overview',   'icon'=>'fa-gauge-high'],
    ['href'=>'account/orders',    'label'=>'My Orders',  'icon'=>'fa-bag-shopping'],
    ['href'=>'account/addresses', 'label'=>'Addresses',  'icon'=>'fa-map-pin'],
    ['href'=>'account/profile',   'label'=>'Profile',    'icon'=>'fa-user'],
    ['href'=>'account/change-password','label'=>'Password','icon'=>'fa-lock'],
];
?>
<div class="flex gap-1 overflow-x-auto pb-1 border-b" style="border-color:var(--border)">
    <?php foreach($navItems as $item):
        $isActive = $seg === $item['href'] || ($seg==='' && $item['href']==='account');
    ?>
    <a href="<?= APP_URL ?>/<?= $item['href'] ?>"
       class="flex items-center gap-2 px-4 py-2.5 rounded-t-xl text-xs font-semibold whitespace-nowrap transition-all"
       style="<?= $isActive?'color:var(--clay);border-bottom:2px solid var(--clay)':'color:var(--muted)' ?>;text-decoration:none">
        <i class="fas <?= $item['icon'] ?> text-xs"></i>
        <?= $item['label'] ?>
    </a>
    <?php endforeach; ?>
</div>
