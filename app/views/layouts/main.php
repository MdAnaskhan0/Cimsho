<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title??'Home') ?> — Cimsho</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;1,300;1,400&family=DM+Sans:wght@300;400;500;600&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --cream: #faf7f2;
            --warm:  #f0ebe0;
            --clay:  #c4956a;
            --clay-dark: #a87a52;
            --charcoal: #2c2c2c;
            --muted: #7a7062;
            --border: #e8e0d4;
        }
        * { font-family: 'DM Sans', sans-serif; }
        .serif { font-family: 'Cormorant Garamond', serif; }
        .mono  { font-family: 'DM Mono', monospace; }
        body   { background: var(--cream); color: var(--charcoal); }

        /* Navbar */
        #navbar { background: rgba(250,247,242,0.97); backdrop-filter: blur(12px); border-bottom: 1px solid var(--border); transition: all 0.3s ease; }
        #navbar.scrolled { box-shadow: 0 2px 20px rgba(44,44,44,0.08); }

        .nav-link { position:relative; color: var(--charcoal); font-size:13px; font-weight:500; letter-spacing:.5px; text-transform:uppercase; transition:color .2s; }
        .nav-link::after { content:''; position:absolute; bottom:-2px; left:0; width:0; height:1.5px; background:var(--clay); transition:width .3s ease; }
        .nav-link:hover { color:var(--clay); }
        .nav-link:hover::after { width:100%; }

        /* Dropdown */
        .nav-dropdown { position:relative; }
        .nav-dropdown .dropdown { display:none; position:absolute; top:calc(100% + 12px); left:50%; transform:translateX(-50%); background:white; border:1px solid var(--border); border-radius:12px; padding:8px; min-width:200px; box-shadow:0 20px 60px rgba(0,0,0,.1); z-index:200; }
        .nav-dropdown:hover .dropdown { display:block; }
        .dropdown a { display:block; padding:8px 14px; font-size:13px; color:var(--charcoal); border-radius:7px; transition:all .15s; }
        .dropdown a:hover { background:var(--warm); color:var(--clay); }

        /* Buttons */
        .btn-clay { background:var(--clay); color:white; transition:all .2s ease; }
        .btn-clay:hover { background:var(--clay-dark); transform:translateY(-1px); }
        .btn-outline { border:1.5px solid var(--clay); color:var(--clay); transition:all .2s ease; }
        .btn-outline:hover { background:var(--clay); color:white; }

        /* Cards */
        .product-card { transition:transform .3s ease, box-shadow .3s ease; }
        .product-card:hover { transform:translateY(-6px); box-shadow:0 20px 60px rgba(0,0,0,.1); }
        .product-card .overlay { opacity:0; transition:opacity .3s ease; }
        .product-card:hover .overlay { opacity:1; }

        /* Cart dot */
        #cart-count { background:var(--clay); }

        /* Forms */
        .form-input { border:1.5px solid var(--border); background:white; transition:border-color .2s; font-size:14px; }
        .form-input:focus { outline:none; border-color:var(--clay); }

        /* Rating stars */
        .star-filled { color:#f59e0b; }
        .star-empty  { color:#e5e7eb; }

        /* Status badges */
        .badge-pending   { background:#fef9c3;color:#92400e; }
        .badge-confirmed { background:#dbeafe;color:#1d4ed8; }
        .badge-shipped   { background:#ede9fe;color:#5b21b6; }
        .badge-delivered { background:#d1fae5;color:#065f46; }
        .badge-cancelled { background:#fee2e2;color:#991b1b; }

        /* Progress steps */
        .step-done .step-dot { background:var(--clay); border-color:var(--clay); }
        .step-done .step-line { background:var(--clay); }
        .step-active .step-dot { background:var(--clay); border-color:var(--clay); }
        .step-dot { width:14px;height:14px;border-radius:50%;border:2px solid #d1d5db;background:white; transition:all .3s; }
        .step-line { height:2px;flex:1;background:#e5e7eb;transition:background .3s; }

        /* Toast */
        #toast { transition: all .4s cubic-bezier(.4,0,.2,1); background:var(--charcoal); }

        /* Mobile menu */
        #mobile-menu { transform:translateX(-100%); transition:transform .35s cubic-bezier(.4,0,.2,1); }
        #mobile-menu.open { transform:translateX(0); }

        /* Image zoom */
        .img-zoom { transition:transform .5s ease; }
        .img-zoom:hover { transform:scale(1.04); }

        /* Smooth scroll */
        html { scroll-behavior:smooth; }

        ::-webkit-scrollbar { width:5px; }
        ::-webkit-scrollbar-track { background:var(--cream); }
        ::-webkit-scrollbar-thumb { background:var(--clay); border-radius:3px; }
    </style>
</head>
<body>

<!-- ======= NAVBAR ======= -->
<header id="navbar" class="fixed top-0 left-0 right-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <div class="flex items-center justify-between h-16">

            <!-- Logo -->
            <a href="<?= APP_URL ?>/" class="flex items-center gap-2.5" style="text-decoration:none">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background:var(--clay)">
                    <i class="fas fa-leaf text-white text-xs"></i>
                </div>
                <span class="serif text-2xl font-semibold tracking-wide" style="color:var(--charcoal)">Cimsho</span>
            </a>

            <!-- Desktop Nav -->
            <nav class="hidden md:flex items-center gap-8">
                <a href="<?= APP_URL ?>/" class="nav-link">Home</a>

                <?php
                require_once __DIR__.'/../../models/SettingsModel.php';
                $settingsModel = new SettingsModel();
                $navCats = $settingsModel->getCategories();
                foreach($navCats as $cat):
                    $subs = $cat['subs'] ? explode('|',$cat['subs']) : [];
                ?>
                <?php if(empty($subs)): ?>
                    <a href="<?= APP_URL ?>/shop?category=<?= $cat['id'] ?>" class="nav-link"><?= htmlspecialchars($cat['name']) ?></a>
                <?php else: ?>
                <div class="nav-dropdown">
                    <a href="<?= APP_URL ?>/shop?category=<?= $cat['id'] ?>" class="nav-link flex items-center gap-1">
                        <?= htmlspecialchars($cat['name']) ?>
                        <i class="fas fa-chevron-down text-[9px] opacity-60"></i>
                    </a>
                    <div class="dropdown">
                        <a href="<?= APP_URL ?>/shop?category=<?= $cat['id'] ?>">All <?= htmlspecialchars($cat['name']) ?></a>
                        <?php foreach($subs as $sub): [$sid,$sname,$sslug]=explode(':',$sub,3)+['','','']; ?>
                        <a href="<?= APP_URL ?>/shop?category=<?= $cat['id'] ?>&sub=<?= $sid ?>"><?= htmlspecialchars($sname) ?></a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
                <?php endforeach; ?>

                <a href="<?= APP_URL ?>/shop" class="nav-link">Shop</a>
                <a href="<?= APP_URL ?>/order/track" class="nav-link">Track Order</a>
            </nav>

            <!-- Right actions -->
            <div class="flex items-center gap-4">
                <!-- Search -->
                <button onclick="toggleSearch()" class="hidden md:flex w-8 h-8 items-center justify-center rounded-lg hover:bg-[var(--warm)] transition-colors text-[var(--muted)]">
                    <i class="fas fa-search text-sm"></i>
                </button>

                <!-- Account -->
                <?php if($this->isLoggedIn()??false||!empty($_SESSION['user_id'])): ?>
                <div class="relative group hidden md:block">
                    <a href="<?= APP_URL ?>/account" class="flex items-center gap-2 px-3 py-1.5 rounded-full hover:bg-[var(--warm)] transition-colors" style="text-decoration:none">
                        <div class="w-6 h-6 rounded-full flex items-center justify-center text-white text-xs" style="background:var(--clay)">
                            <?= strtoupper(substr($_SESSION['user_name']??'U',0,1)) ?>
                        </div>
                        <span class="text-xs font-medium text-[var(--charcoal)] hidden lg:block"><?= htmlspecialchars(explode(' ',$_SESSION['user_name']??'')[0]) ?></span>
                    </a>
                </div>
                <?php else: ?>
                <a href="<?= APP_URL ?>/account/login" class="hidden md:flex items-center gap-1.5 text-xs font-medium uppercase tracking-wide text-[var(--muted)] hover:text-[var(--clay)] transition-colors" style="text-decoration:none">
                    <i class="fas fa-user text-xs"></i> Sign In
                </a>
                <?php endif; ?>

                <!-- Cart -->
                <a href="<?= APP_URL ?>/cart" class="relative flex items-center justify-center w-9 h-9 rounded-lg hover:bg-[var(--warm)] transition-colors" style="text-decoration:none">
                    <i class="fas fa-bag-shopping text-[var(--charcoal)] text-sm"></i>
                    <span id="cart-count" class="absolute -top-0.5 -right-0.5 text-white text-[9px] font-bold w-4 h-4 rounded-full flex items-center justify-center <?= empty($_SESSION['cart'])?'hidden':'' ?>">
                        <?= array_sum(array_column($_SESSION['cart']??[],'qty')) ?>
                    </span>
                </a>

                <!-- Mobile burger -->
                <button onclick="toggleMobile()" class="md:hidden w-9 h-9 flex flex-col items-center justify-center gap-1.5 rounded-lg hover:bg-[var(--warm)] transition-colors">
                    <span class="w-5 h-0.5 bg-[var(--charcoal)] rounded"></span>
                    <span class="w-4 h-0.5 bg-[var(--charcoal)] rounded"></span>
                    <span class="w-5 h-0.5 bg-[var(--charcoal)] rounded"></span>
                </button>
            </div>
        </div>
    </div>

    <!-- Search bar -->
    <div id="search-bar" class="hidden border-t border-[var(--border)] bg-[var(--cream)] px-4 py-3">
        <form action="<?= APP_URL ?>/shop" method="GET" class="max-w-2xl mx-auto flex gap-2">
            <input type="text" name="q" placeholder="Search for wall art, decor, prints…" autofocus
                   value="<?= htmlspecialchars($_GET['q']??'') ?>"
                   class="form-input flex-1 px-4 py-2.5 rounded-xl text-sm">
            <button type="submit" class="btn-clay px-5 py-2.5 rounded-xl text-sm font-medium">
                <i class="fas fa-search mr-1"></i> Search
            </button>
        </form>
    </div>
</header>

<!-- Mobile Menu -->
<div id="mobile-overlay" onclick="toggleMobile()" class="fixed inset-0 bg-black/40 z-40 hidden"></div>
<div id="mobile-menu" class="fixed top-0 left-0 h-full w-72 bg-white z-50 shadow-2xl flex flex-col">
    <div class="flex items-center justify-between px-5 py-4 border-b border-[var(--border)]">
        <span class="serif text-xl font-semibold" style="color:var(--clay)">Cimsho</span>
        <button onclick="toggleMobile()" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-[var(--warm)]">
            <i class="fas fa-xmark"></i>
        </button>
    </div>
    <nav class="flex-1 overflow-y-auto px-4 py-5 space-y-1">
        <a href="<?= APP_URL ?>/" class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-[var(--warm)] text-sm font-medium" style="text-decoration:none">
            <i class="fas fa-house w-4 text-center text-[var(--muted)]"></i> Home
        </a>
        <a href="<?= APP_URL ?>/shop" class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-[var(--warm)] text-sm font-medium" style="text-decoration:none">
            <i class="fas fa-store w-4 text-center text-[var(--muted)]"></i> Shop
        </a>
        <?php foreach($navCats as $cat): ?>
        <a href="<?= APP_URL ?>/shop?category=<?= $cat['id'] ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-[var(--warm)] text-sm font-medium" style="text-decoration:none">
            <i class="fas fa-layer-group w-4 text-center text-[var(--muted)]"></i> <?= htmlspecialchars($cat['name']) ?>
        </a>
        <?php endforeach; ?>
        <a href="<?= APP_URL ?>/order/track" class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-[var(--warm)] text-sm font-medium" style="text-decoration:none">
            <i class="fas fa-truck w-4 text-center text-[var(--muted)]"></i> Track Order
        </a>
        <div class="border-t border-[var(--border)] my-3"></div>
        <?php if(!empty($_SESSION['user_id'])): ?>
        <a href="<?= APP_URL ?>/account" class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-[var(--warm)] text-sm font-medium" style="text-decoration:none">
            <i class="fas fa-user w-4 text-center text-[var(--muted)]"></i> My Account
        </a>
        <a href="<?= APP_URL ?>/account/orders" class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-[var(--warm)] text-sm font-medium" style="text-decoration:none">
            <i class="fas fa-bag-shopping w-4 text-center text-[var(--muted)]"></i> My Orders
        </a>
        <a href="<?= APP_URL ?>/auth/logout" class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-red-50 text-red-500 text-sm font-medium" style="text-decoration:none">
            <i class="fas fa-right-from-bracket w-4 text-center"></i> Logout
        </a>
        <?php else: ?>
        <a href="<?= APP_URL ?>/account/login" class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-[var(--warm)] text-sm font-medium" style="text-decoration:none">
            <i class="fas fa-right-to-bracket w-4 text-center text-[var(--muted)]"></i> Sign In
        </a>
        <a href="<?= APP_URL ?>/account/signup" class="flex items-center gap-3 px-3 py-2.5 rounded-xl bg-[var(--clay)] text-white text-sm font-medium" style="text-decoration:none">
            <i class="fas fa-user-plus w-4 text-center"></i> Create Account
        </a>
        <?php endif; ?>
    </nav>
</div>

<!-- Flash message -->
<?php if(!empty($_SESSION['flash'])): $f=$_SESSION['flash']; unset($_SESSION['flash']); ?>
<script>window._flashMsg='<?= addslashes($f['msg']) ?>';window._flashType='<?= $f['type'] ?>';</script>
<?php endif; ?>

<!-- Page content -->
<div class="pt-16">
    <?php require __DIR__.'/../'.$content_view.'.php'; ?>
</div>

<!-- ======= FOOTER ======= -->
<footer class="mt-20" style="background:var(--charcoal)">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-14">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-10">
            <div class="md:col-span-2">
                <div class="flex items-center gap-2.5 mb-4">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background:var(--clay)">
                        <i class="fas fa-leaf text-white text-xs"></i>
                    </div>
                    <span class="serif text-2xl font-semibold text-white">Cimsho</span>
                </div>
                <p class="text-sm leading-relaxed mb-5" style="color:#9ca3af">Artisan home decor crafted with passion. Each piece tells a story of Bangladesh's rich cultural heritage through contemporary design.</p>
                <div class="flex gap-3">
                    <?php foreach(['facebook-f','instagram','twitter'] as $s): ?>
                    <a href="#" class="w-9 h-9 rounded-lg flex items-center justify-center hover:bg-[var(--clay)] transition-colors" style="background:rgba(255,255,255,.08);text-decoration:none">
                        <i class="fab fa-<?= $s ?> text-white text-xs"></i>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>
            <div>
                <h4 class="text-white font-semibold text-sm uppercase tracking-wider mb-4">Shop</h4>
                <div class="space-y-2.5">
                    <a href="<?= APP_URL ?>/shop" class="block text-sm hover:text-white transition-colors" style="color:#9ca3af;text-decoration:none">All Products</a>
                    <a href="<?= APP_URL ?>/shop?category=1" class="block text-sm hover:text-white transition-colors" style="color:#9ca3af;text-decoration:none">Living Room</a>
                    <a href="<?= APP_URL ?>/shop?category=2" class="block text-sm hover:text-white transition-colors" style="color:#9ca3af;text-decoration:none">Bedroom</a>
                    <a href="<?= APP_URL ?>/order/track" class="block text-sm hover:text-white transition-colors" style="color:#9ca3af;text-decoration:none">Track Order</a>
                </div>
            </div>
            <div>
                <h4 class="text-white font-semibold text-sm uppercase tracking-wider mb-4">Account</h4>
                <div class="space-y-2.5">
                    <a href="<?= APP_URL ?>/account/login" class="block text-sm hover:text-white transition-colors" style="color:#9ca3af;text-decoration:none">Sign In</a>
                    <a href="<?= APP_URL ?>/account/signup" class="block text-sm hover:text-white transition-colors" style="color:#9ca3af;text-decoration:none">Create Account</a>
                    <a href="<?= APP_URL ?>/account/orders" class="block text-sm hover:text-white transition-colors" style="color:#9ca3af;text-decoration:none">My Orders</a>
                    <a href="<?= APP_URL ?>/account/addresses" class="block text-sm hover:text-white transition-colors" style="color:#9ca3af;text-decoration:none">My Addresses</a>
                </div>
            </div>
        </div>
        <div class="border-t mt-10 pt-6 flex flex-col sm:flex-row items-center justify-between gap-3" style="border-color:rgba(255,255,255,.1)">
            <p class="text-xs" style="color:#6b7280">© <?= date('Y') ?> Cimsho. All rights reserved.</p>
            <div class="flex items-center gap-4 text-xs" style="color:#6b7280">
                <span><i class="fas fa-shield-halved mr-1"></i>Secure Payments</span>
                <span><i class="fas fa-truck mr-1"></i>Fast Delivery</span>
                <span><i class="fas fa-rotate-left mr-1"></i>Easy Returns</span>
            </div>
        </div>
    </div>
</footer>

<!-- Toast notification -->
<div id="toast" class="fixed bottom-6 right-6 z-[200] flex items-center gap-3 px-5 py-3.5 rounded-2xl shadow-xl text-white text-sm font-medium opacity-0 pointer-events-none" style="min-width:240px;max-width:340px">
    <i id="toast-icon" class="fas fa-circle-check flex-shrink-0"></i>
    <span id="toast-text"></span>
</div>

<script>
const APP_URL='<?= APP_URL ?>';

// Toast
function showToast(msg, type='success'){
    const t=document.getElementById('toast'),i=document.getElementById('toast-icon'),tx=document.getElementById('toast-text');
    const colors={success:'#2d6a4f',error:'#9b2226',warning:'#ca6702',info:'#1d3557'};
    const icons={success:'fa-circle-check',error:'fa-circle-xmark',warning:'fa-triangle-exclamation',info:'fa-circle-info'};
    t.style.background=colors[type]||colors.info;
    i.className='fas '+icons[type];
    tx.textContent=msg;
    t.style.opacity='1'; t.style.pointerEvents='auto'; t.style.transform='translateY(0)';
    setTimeout(()=>{t.style.opacity='0';t.style.pointerEvents='none';},3500);
}
if(window._flashMsg) showToast(window._flashMsg, window._flashType||'info');

// Scroll navbar
window.addEventListener('scroll',()=>{
    document.getElementById('navbar').classList.toggle('scrolled',window.scrollY>20);
});

// Search
function toggleSearch(){
    const s=document.getElementById('search-bar');
    s.classList.toggle('hidden');
    if(!s.classList.contains('hidden')) s.querySelector('input').focus();
}

// Mobile menu
function toggleMobile(){
    const m=document.getElementById('mobile-menu'), o=document.getElementById('mobile-overlay');
    m.classList.toggle('open');
    o.classList.toggle('hidden');
    document.body.style.overflow=m.classList.contains('open')?'hidden':'';
}

// Update cart count
function updateCartCount(n){
    const el=document.getElementById('cart-count');
    el.textContent=n;
    el.classList.toggle('hidden',n===0);
}

// Add to cart helper
async function addToCart(data){
    const res=await fetch(APP_URL+'/cart/add',{method:'POST',headers:{'Content-Type':'application/x-www-form-urlencoded'},body:new URLSearchParams(data)});
    const d=await res.json();
    if(d.success){ updateCartCount(d.count); showToast(d.message,'success'); }
    else showToast(d.message||'Something went wrong.','error');
    return d;
}
</script>
</body>
</html>
