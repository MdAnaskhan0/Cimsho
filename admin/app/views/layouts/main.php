<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'Dashboard') ?> — <?= APP_NAME ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
        .mono { font-family: 'JetBrains Mono', monospace; }

        :root {
            --sidebar-w: 260px;
            --topbar-h: 64px;
            --accent: #6366f1;
            --accent-dark: #4f46e5;
        }

        /* Sidebar */
        #sidebar {
            width: var(--sidebar-w);
            transition: width 0.3s cubic-bezier(.4,0,.2,1), transform 0.3s cubic-bezier(.4,0,.2,1);
            background: linear-gradient(160deg, #0f172a 0%, #1e1b4b 50%, #0f172a 100%);
        }
        #sidebar.collapsed { width: 72px; }
        #sidebar.collapsed .nav-label,
        #sidebar.collapsed .nav-arrow,
        #sidebar.collapsed .sidebar-logo-text,
        #sidebar.collapsed .submenu { display: none !important; }
        #sidebar.collapsed .nav-link { justify-content: center; padding: 12px; }
        #sidebar.collapsed .sidebar-logo { justify-content: center; }

        /* Sidebar scrollbar */
        #sidebar-nav::-webkit-scrollbar { width: 4px; }
        #sidebar-nav::-webkit-scrollbar-track { background: transparent; }
        #sidebar-nav::-webkit-scrollbar-thumb { background: rgba(255,255,255,.15); border-radius: 4px; }

        /* Nav item active/hover */
        .nav-link {
            transition: all 0.2s ease;
            border-left: 3px solid transparent;
        }
        .nav-link:hover { background: rgba(99,102,241,.15); border-left-color: rgba(99,102,241,.5); }
        .nav-link.active { background: rgba(99,102,241,.25); border-left-color: #6366f1; }
        .nav-link.active .nav-icon { color: #818cf8; }

        /* Submenu animation */
        .submenu { overflow: hidden; transition: max-height 0.3s ease, opacity 0.3s ease; max-height: 0; opacity: 0; }
        .submenu.open { max-height: 500px; opacity: 1; }

        /* Topbar */
        #topbar {
            height: var(--topbar-h);
            background: rgba(255,255,255,0.98);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid #e2e8f0;
        }

        /* Content area */
        #main-content {
            margin-left: var(--sidebar-w);
            margin-top: var(--topbar-h);
            transition: margin-left 0.3s cubic-bezier(.4,0,.2,1);
        }
        #main-content.expanded { margin-left: 72px; }

        /* Stat cards */
        .stat-card { transition: transform 0.2s ease, box-shadow 0.2s ease; }
        .stat-card:hover { transform: translateY(-3px); box-shadow: 0 20px 40px rgba(0,0,0,.08); }

        /* Status badge */
        .badge { display: inline-flex; align-items: center; padding: 2px 10px; border-radius: 999px; font-size: 11px; font-weight: 600; letter-spacing: .3px; }
        .badge-pending  { background:#fef9c3; color:#a16207; }
        .badge-confirmed{ background:#dbeafe; color:#1d4ed8; }
        .badge-shipped  { background:#ede9fe; color:#6d28d9; }
        .badge-delivered{ background:#dcfce7; color:#15803d; }
        .badge-cancelled{ background:#fee2e2; color:#b91c1c; }

        /* Toast */
        #toast { transition: all 0.4s cubic-bezier(.4,0,.2,1); }

        /* Dropdown */
        .dropdown-menu { transform-origin: top right; transition: all 0.2s cubic-bezier(.4,0,.2,1); }
        .dropdown-menu.hidden { transform: scale(.95); opacity: 0; pointer-events: none; }
        .dropdown-menu:not(.hidden) { transform: scale(1); opacity: 1; }

        /* Modal */
        #modal-overlay { transition: opacity 0.25s ease; }

        /* Scrollbar global */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
    </style>
</head>
<body class="bg-slate-50 h-full">

<!-- ===================== SIDEBAR ===================== -->
<aside id="sidebar" class="fixed top-0 left-0 h-full z-40 flex flex-col shadow-2xl">

    <!-- Logo -->
    <div class="sidebar-logo flex items-center gap-3 px-5 py-4 border-b border-white/10 flex-shrink-0" style="height:var(--topbar-h)">
        <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0" style="background:linear-gradient(135deg,#6366f1,#8b5cf6)">
            <i class="fas fa-store text-white text-sm"></i>
        </div>
        <div class="sidebar-logo-text">
            <div class="text-white font-bold text-base leading-tight tracking-tight"><?= APP_NAME ?></div>
            <div class="text-indigo-300 text-xs font-medium">Admin Panel</div>
        </div>
    </div>

    <!-- Nav -->
    <nav id="sidebar-nav" class="flex-1 overflow-y-auto py-4 px-3 space-y-1">

        <?php
        $currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $base = parse_url(APP_URL, PHP_URL_PATH);
        $seg = explode('/', trim(str_replace($base, '', $currentPath), '/'));
        $activePage = $seg[0] ?: 'dashboard';

        function navLink(string $href, string $icon, string $label, string $activePage, string $key): string {
            $active = ($activePage === $key) ? 'active' : '';
            return <<<HTML
            <a href="{$href}" class="nav-link {$active} flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-300 hover:text-white cursor-pointer no-underline" style="text-decoration:none">
                <span class="nav-icon w-5 text-center text-sm"><i class="fas {$icon}"></i></span>
                <span class="nav-label text-sm font-medium">{$label}</span>
            </a>
            HTML;
        }

        function navGroup(string $icon, string $label, array $children, string $activePage, string $key): string {
            $isOpen = ($activePage === $key) ? 'open' : '';
            $active = ($activePage === $key) ? 'active' : '';
            $childLinks = '';
            foreach ($children as $child) {
                $childActive = str_contains($_SERVER['REQUEST_URI'], $child['href']) ? 'text-indigo-300 font-semibold' : 'text-slate-400';
                $childLinks .= <<<HTML
                <a href="{$child['href']}" class="flex items-center gap-2 px-3 py-2 rounded-md hover:bg-white/10 hover:text-white text-sm {$childActive}" style="text-decoration:none">
                    <i class="fas fa-circle text-[5px] opacity-50"></i>
                    {$child['label']}
                </a>
                HTML;
            }
            return <<<HTML
            <div class="nav-group">
                <button onclick="toggleSubmenu(this)" class="nav-link {$active} w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-300 hover:text-white">
                    <span class="nav-icon w-5 text-center text-sm"><i class="fas {$icon}"></i></span>
                    <span class="nav-label text-sm font-medium flex-1 text-left">{$label}</span>
                    <span class="nav-arrow text-xs transition-transform duration-200"><i class="fas fa-chevron-down"></i></span>
                </button>
                <div class="submenu {$isOpen} pl-8 space-y-1 mt-1">
                    {$childLinks}
                </div>
            </div>
            HTML;
        }
        ?>

        <div class="px-2 py-1">
            <p class="nav-label text-xs font-semibold uppercase tracking-widest text-slate-500 mb-2">Main</p>
        </div>

        <?= navLink(APP_URL.'/dashboard', 'fa-gauge-high', 'Dashboard', $activePage, 'dashboard') ?>

        <div class="px-2 py-1 mt-3">
            <p class="nav-label text-xs font-semibold uppercase tracking-widest text-slate-500 mb-2">Catalog</p>
        </div>

        <?= navGroup('fa-boxes-stacked', 'Products', [
            ['href' => APP_URL.'/products', 'label' => 'All Products'],
            ['href' => APP_URL.'/products/create', 'label' => 'Add Product'],
            ['href' => APP_URL.'/products/stock', 'label' => 'Stock Management'],
        ], $activePage, 'products') ?>

        <?= navGroup('fa-layer-group', 'Categories', [
            ['href' => APP_URL.'/categories', 'label' => 'All Categories'],
            ['href' => APP_URL.'/sub-categories', 'label' => 'Sub Categories'],
        ], $activePage, 'categories') ?>

        <div class="px-2 py-1 mt-3">
            <p class="nav-label text-xs font-semibold uppercase tracking-widest text-slate-500 mb-2">Sales</p>
        </div>

        <?= navGroup('fa-bag-shopping', 'Orders', [
            ['href' => APP_URL.'/orders', 'label' => 'All Orders'],
            ['href' => APP_URL.'/orders/pending', 'label' => 'Pending Orders'],
            ['href' => APP_URL.'/orders/shipped', 'label' => 'Shipped'],
        ], $activePage, 'orders') ?>

        <?= navLink(APP_URL.'/payments', 'fa-credit-card', 'Payments', $activePage, 'payments') ?>
        <?= navLink(APP_URL.'/coupons', 'fa-tag', 'Coupons', $activePage, 'coupons') ?>

        <div class="px-2 py-1 mt-3">
            <p class="nav-label text-xs font-semibold uppercase tracking-widest text-slate-500 mb-2">Customers</p>
        </div>

        <?= navLink(APP_URL.'/users', 'fa-users', 'Customers', $activePage, 'users') ?>
        <?= navLink(APP_URL.'/reviews', 'fa-star', 'Reviews', $activePage, 'reviews') ?>

        <div class="px-2 py-1 mt-3">
            <p class="nav-label text-xs font-semibold uppercase tracking-widest text-slate-500 mb-2">Settings</p>
        </div>

        <?= navGroup('fa-gear', 'Settings', [
            ['href' => APP_URL.'/settings/shop', 'label' => 'Shop Settings'],
            ['href' => APP_URL.'/settings/delivery', 'label' => 'Delivery Settings'],
            ['href' => APP_URL.'/settings/site', 'label' => 'Site Settings'],
        ], $activePage, 'settings') ?>

    </nav>

    <!-- Sidebar footer -->
    <div class="px-4 py-3 border-t border-white/10 flex-shrink-0">
        <a href="<?= APP_URL ?>/logout" class="nav-label flex items-center gap-3 px-3 py-2 rounded-lg text-red-400 hover:bg-red-500/10 hover:text-red-300 transition-all text-sm font-medium" style="text-decoration:none">
            <i class="fas fa-right-from-bracket w-5 text-center"></i>
            <span>Logout</span>
        </a>
    </div>
</aside>

<!-- ===================== TOPBAR ===================== -->
<header id="topbar" class="fixed top-0 right-0 z-30 flex items-center px-6 shadow-sm" style="left:var(--sidebar-w); transition: left 0.3s cubic-bezier(.4,0,.2,1)">

    <!-- Left: Hamburger + Page title -->
    <div class="flex items-center gap-4 flex-1">
        <button id="sidebar-toggle" onclick="toggleSidebar()" class="w-9 h-9 flex items-center justify-center rounded-lg hover:bg-slate-100 text-slate-500 transition-colors">
            <i class="fas fa-bars"></i>
        </button>
        <div>
            <h1 class="text-slate-800 font-bold text-base leading-tight"><?= htmlspecialchars($pageTitle ?? 'Dashboard') ?></h1>
            <p class="text-slate-400 text-xs"><?= date('l, d F Y') ?></p>
        </div>
    </div>

    <!-- Right: Actions + Profile -->
    <div class="flex items-center gap-3">

        <!-- Notifications -->
        <div class="relative">
            <button onclick="toggleDropdown('notif-dropdown')" class="w-9 h-9 flex items-center justify-center rounded-lg hover:bg-slate-100 text-slate-500 transition-colors relative">
                <i class="fas fa-bell"></i>
                <?php if (($stats['pending_orders'] ?? 0) > 0): ?>
                <span class="absolute top-1.5 right-1.5 w-2 h-2 rounded-full bg-red-500"></span>
                <?php endif; ?>
            </button>
            <div id="notif-dropdown" class="dropdown-menu hidden absolute right-0 mt-2 w-72 bg-white rounded-xl shadow-xl border border-slate-100 z-50 top-full">
                <div class="px-4 py-3 border-b border-slate-100">
                    <p class="font-semibold text-slate-700 text-sm">Notifications</p>
                </div>
                <div class="py-2">
                    <?php if (($stats['pending_orders'] ?? 0) > 0): ?>
                    <a href="<?= APP_URL ?>/orders/pending" class="flex items-center gap-3 px-4 py-2.5 hover:bg-slate-50 transition-colors" style="text-decoration:none">
                        <div class="w-8 h-8 rounded-full bg-amber-100 flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-clock text-amber-500 text-xs"></i>
                        </div>
                        <div>
                            <p class="text-slate-700 text-sm font-medium"><?= $stats['pending_orders'] ?> Pending Orders</p>
                            <p class="text-slate-400 text-xs">Awaiting confirmation</p>
                        </div>
                    </a>
                    <?php else: ?>
                    <p class="px-4 py-3 text-slate-400 text-sm text-center">No new notifications</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Divider -->
        <div class="w-px h-7 bg-slate-200"></div>

        <!-- Profile dropdown -->
        <div class="relative">
            <button onclick="toggleDropdown('profile-dropdown')" class="flex items-center gap-3 pl-1 pr-3 py-1.5 rounded-xl hover:bg-slate-100 transition-colors">
                <div class="w-8 h-8 rounded-lg overflow-hidden bg-indigo-100 flex items-center justify-center flex-shrink-0">
                    <?php if (!empty($_SESSION['admin_avatar'])): ?>
                        <img src="<?= htmlspecialchars($_SESSION['admin_avatar']) ?>" class="w-full h-full object-cover" alt="">
                    <?php else: ?>
                        <span class="text-indigo-600 font-bold text-sm"><?= strtoupper(substr($_SESSION['admin_name'] ?? 'A', 0, 1)) ?></span>
                    <?php endif; ?>
                </div>
                <div class="hidden sm:block text-left">
                    <p class="text-slate-700 font-semibold text-xs leading-tight"><?= htmlspecialchars($_SESSION['admin_name'] ?? 'Admin') ?></p>
                    <p class="text-slate-400 text-xs"><?= htmlspecialchars($_SESSION['admin_username'] ?? '') ?></p>
                </div>
                <i class="fas fa-chevron-down text-slate-400 text-xs"></i>
            </button>

            <div id="profile-dropdown" class="dropdown-menu hidden absolute right-0 mt-2 w-52 bg-white rounded-xl shadow-xl border border-slate-100 z-50 top-full">
                <div class="px-4 py-3 border-b border-slate-100">
                    <p class="font-semibold text-slate-700 text-sm"><?= htmlspecialchars($_SESSION['admin_name'] ?? '') ?></p>
                    <p class="text-slate-400 text-xs">Administrator</p>
                </div>
                <div class="py-2">
                    <button onclick="closeDropdown('profile-dropdown'); openModal('change-password-modal')" class="w-full text-left flex items-center gap-3 px-4 py-2 hover:bg-slate-50 text-slate-600 text-sm transition-colors">
                        <i class="fas fa-lock w-4 text-slate-400"></i> Change Password
                    </button>
                    <div class="border-t border-slate-100 my-1"></div>
                    <a href="<?= APP_URL ?>/logout" class="flex items-center gap-3 px-4 py-2 hover:bg-red-50 text-red-500 text-sm transition-colors" style="text-decoration:none">
                        <i class="fas fa-right-from-bracket w-4"></i> Sign Out
                    </a>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- ===================== MAIN CONTENT ===================== -->
<main id="main-content" class="min-h-screen p-6">
    <?php require __DIR__ . '/' . ($content_view ?? 'pages/404') . '.php'; ?>
</main>

<!-- ===================== CHANGE PASSWORD MODAL ===================== -->
<div id="change-password-modal" class="fixed inset-0 z-50 hidden">
    <div id="modal-overlay" class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeModal('change-password-modal')"></div>
    <div class="relative flex items-center justify-center min-h-full p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md relative z-10 overflow-hidden">
            <!-- Header -->
            <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-indigo-100 flex items-center justify-center">
                        <i class="fas fa-lock text-indigo-600 text-sm"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800 text-sm">Change Password</h3>
                        <p class="text-slate-400 text-xs">Update your admin password</p>
                    </div>
                </div>
                <button onclick="closeModal('change-password-modal')" class="w-8 h-8 rounded-lg hover:bg-slate-100 flex items-center justify-center text-slate-400 transition-colors">
                    <i class="fas fa-xmark"></i>
                </button>
            </div>
            <!-- Body -->
            <div class="px-6 py-5 space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5 uppercase tracking-wide">Current Password</label>
                    <div class="relative">
                        <input type="password" id="cp-current" placeholder="••••••••"
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent pr-10">
                        <button type="button" onclick="togglePwd('cp-current')" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                            <i class="fas fa-eye text-xs"></i>
                        </button>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5 uppercase tracking-wide">New Password</label>
                    <div class="relative">
                        <input type="password" id="cp-new" placeholder="Min. 8 characters"
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent pr-10">
                        <button type="button" onclick="togglePwd('cp-new')" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                            <i class="fas fa-eye text-xs"></i>
                        </button>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5 uppercase tracking-wide">Confirm New Password</label>
                    <div class="relative">
                        <input type="password" id="cp-confirm" placeholder="Repeat new password"
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent pr-10">
                        <button type="button" onclick="togglePwd('cp-confirm')" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                            <i class="fas fa-eye text-xs"></i>
                        </button>
                    </div>
                </div>

                <!-- Strength indicator -->
                <div id="pwd-strength-bar" class="h-1 rounded-full bg-slate-100 overflow-hidden">
                    <div id="pwd-strength-fill" class="h-full rounded-full transition-all duration-300 bg-slate-300" style="width:0%"></div>
                </div>
                <p id="pwd-strength-text" class="text-xs text-slate-400"></p>

                <div id="cp-error" class="hidden text-red-500 text-xs bg-red-50 rounded-lg px-3 py-2"></div>
            </div>
            <!-- Footer -->
            <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex gap-3">
                <button onclick="closeModal('change-password-modal')" class="flex-1 px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 text-sm font-semibold hover:bg-slate-100 transition-colors">
                    Cancel
                </button>
                <button onclick="submitChangePassword()" class="flex-1 px-4 py-2.5 rounded-xl text-white text-sm font-semibold transition-all" style="background:linear-gradient(135deg,#6366f1,#8b5cf6)" id="cp-submit-btn">
                    Update Password
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ===================== TOAST ===================== -->
<div id="toast" class="fixed bottom-6 right-6 z-[100] flex items-center gap-3 px-5 py-3.5 rounded-xl shadow-xl text-white text-sm font-medium opacity-0 pointer-events-none max-w-xs" style="min-width:220px">
    <i id="toast-icon" class="fas fa-circle-check text-base"></i>
    <span id="toast-msg"></span>
</div>

<!-- ===================== SCRIPTS ===================== -->
<script>
const APP_URL = '<?= APP_URL ?>';
const CSRF    = '<?= $csrf ?? '' ?>';

// ---- Sidebar toggle ----
function toggleSidebar() {
    const sidebar  = document.getElementById('sidebar');
    const content  = document.getElementById('main-content');
    const topbar   = document.getElementById('topbar');
    sidebar.classList.toggle('collapsed');
    content.classList.toggle('expanded');
    topbar.style.left = sidebar.classList.contains('collapsed') ? '72px' : 'var(--sidebar-w)';
}

// ---- Submenu toggle ----
function toggleSubmenu(btn) {
    const submenu = btn.nextElementSibling;
    const arrow   = btn.querySelector('.nav-arrow i');
    const isOpen  = submenu.classList.contains('open');
    // Close all others
    document.querySelectorAll('.submenu.open').forEach(m => {
        m.classList.remove('open');
        const a = m.previousElementSibling?.querySelector('.nav-arrow i');
        if (a) a.style.transform = '';
    });
    if (!isOpen) {
        submenu.classList.add('open');
        if (arrow) arrow.style.transform = 'rotate(-180deg)';
    }
}

// ---- Dropdowns ----
function toggleDropdown(id) {
    const el = document.getElementById(id);
    el.classList.toggle('hidden');
}
function closeDropdown(id) {
    document.getElementById(id)?.classList.add('hidden');
}
document.addEventListener('click', (e) => {
    if (!e.target.closest('[onclick*="toggleDropdown"]') && !e.target.closest('.dropdown-menu')) {
        document.querySelectorAll('.dropdown-menu').forEach(d => d.classList.add('hidden'));
    }
});

// ---- Modal ----
function openModal(id) {
    const m = document.getElementById(id);
    m.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}
function closeModal(id) {
    const m = document.getElementById(id);
    m.classList.add('hidden');
    document.body.style.overflow = '';
}

// ---- Password visibility ----
function togglePwd(id) {
    const el = document.getElementById(id);
    el.type = el.type === 'password' ? 'text' : 'password';
}

// ---- Password strength ----
document.getElementById('cp-new')?.addEventListener('input', function() {
    const v = this.value;
    let score = 0;
    if (v.length >= 8) score++;
    if (/[A-Z]/.test(v)) score++;
    if (/[0-9]/.test(v)) score++;
    if (/[^A-Za-z0-9]/.test(v)) score++;
    const fill  = document.getElementById('pwd-strength-fill');
    const text  = document.getElementById('pwd-strength-text');
    const colors = ['#ef4444','#f97316','#eab308','#22c55e'];
    const labels = ['Weak','Fair','Good','Strong'];
    fill.style.width = (score * 25) + '%';
    fill.style.background = colors[score - 1] || '#cbd5e1';
    text.textContent = score > 0 ? labels[score - 1] : '';
    text.style.color  = colors[score - 1] || '#94a3b8';
});

// ---- Change Password submit ----
async function submitChangePassword() {
    const current = document.getElementById('cp-current').value;
    const newPwd  = document.getElementById('cp-new').value;
    const confirm = document.getElementById('cp-confirm').value;
    const errEl   = document.getElementById('cp-error');
    const btn     = document.getElementById('cp-submit-btn');

    errEl.classList.add('hidden');

    if (!current || !newPwd || !confirm) {
        errEl.textContent = 'All fields are required.';
        errEl.classList.remove('hidden');
        return;
    }
    if (newPwd !== confirm) {
        errEl.textContent = 'New passwords do not match.';
        errEl.classList.remove('hidden');
        return;
    }
    if (newPwd.length < 8) {
        errEl.textContent = 'Password must be at least 8 characters.';
        errEl.classList.remove('hidden');
        return;
    }

    btn.disabled = true;
    btn.textContent = 'Updating…';

    const fd = new FormData();
    fd.append('csrf_token', CSRF);
    fd.append('current_password', current);
    fd.append('new_password', newPwd);
    fd.append('confirm_password', confirm);

    try {
        const res  = await fetch(APP_URL + '/change-password', { method: 'POST', body: fd });
        const data = await res.json();
        if (data.success) {
            closeModal('change-password-modal');
            showToast(data.message, 'success');
            document.getElementById('cp-current').value = '';
            document.getElementById('cp-new').value     = '';
            document.getElementById('cp-confirm').value = '';
        } else {
            errEl.textContent = data.message;
            errEl.classList.remove('hidden');
        }
    } catch (e) {
        errEl.textContent = 'Something went wrong. Please try again.';
        errEl.classList.remove('hidden');
    }
    btn.disabled = false;
    btn.textContent = 'Update Password';
}

// ---- Toast ----
function showToast(msg, type = 'success') {
    const toast = document.getElementById('toast');
    const icon  = document.getElementById('toast-icon');
    const text  = document.getElementById('toast-msg');
    const colors = { success: '#22c55e', error: '#ef4444', warning: '#f59e0b', info: '#6366f1' };
    const icons  = { success: 'fa-circle-check', error: 'fa-circle-xmark', warning: 'fa-triangle-exclamation', info: 'fa-circle-info' };
    toast.style.background = colors[type] || colors.info;
    icon.className = `fas ${icons[type] || icons.info} text-base`;
    text.textContent = msg;
    toast.style.opacity = '1';
    toast.style.pointerEvents = 'auto';
    toast.style.transform = 'translateY(0)';
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.pointerEvents = 'none';
    }, 3500);
}

// Auto open active submenus
document.querySelectorAll('.nav-group').forEach(group => {
    const submenu = group.querySelector('.submenu');
    if (submenu?.classList.contains('open')) {
        const arrow = group.querySelector('.nav-arrow i');
        if (arrow) arrow.style.transform = 'rotate(-180deg)';
    }
});

// Flash from PHP
<?php if (!empty($_SESSION['flash'])): ?>
showToast('<?= addslashes($_SESSION['flash']['message']) ?>', '<?= $_SESSION['flash']['type'] ?>');
<?php unset($_SESSION['flash']); endif; ?>
</script>

</body>
</html>
