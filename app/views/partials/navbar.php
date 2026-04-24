<nav class="sticky top-0 z-50 bg-white/95 backdrop-blur border-b border-gray-100 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">

            <!-- Logo - Responsive -->
            <a href="<?= APP_URL ?>/" class="flex items-center gap-2 group shrink-0">
                <!-- Mobile logo (visible on small screens) -->
                <div class="flex items-center gap-1">
                    <img src="<?= APP_URL ?>/public/assets/logo.png"
                        alt="Logo"
                        class="w-12 h-12 block sm:hidden">
                    <span class="sm:hidden sm:block text-xl font-bold text-[#D4852B]">Cimsho</span>
                </div>
                <!-- Desktop logo (visible on medium screens and up) -->
                <img src="<?= APP_URL ?>/public/assets/desk_logo.png"
                    alt="Desk Logo"
                    class="hidden sm:block h-8 w-auto">
            </a>

            <!-- Desktop Nav - Hidden on mobile -->
            <div class="hidden md:flex items-center gap-6 text-sm font-medium text-gray-600">
                <a href="<?= APP_URL ?>/" class="hover:text-brand-600 transition-colors <?= ($_SERVER['REQUEST_URI'] == '/' || $_SERVER['REQUEST_URI'] == '/index.php') ? 'text-brand-600' : '' ?>">Home</a>
                <a href="<?= APP_URL ?>/products" class="hover:text-brand-600 transition-colors <?= (strpos($_SERVER['REQUEST_URI'], '/products') !== false) ? 'text-brand-600' : '' ?>">Shop</a>
                <a href="<?= APP_URL ?>/categories" class="hover:text-brand-600 transition-colors <?= (strpos($_SERVER['REQUEST_URI'], '/categories') !== false) ? 'text-brand-600' : '' ?>">Categories</a>
                <a href="<?= APP_URL ?>/about" class="hover:text-brand-600 transition-colors <?= (strpos($_SERVER['REQUEST_URI'], '/about') !== false) ? 'text-brand-600' : '' ?>">About</a>
                <a href="<?= APP_URL ?>/contact" class="hover:text-brand-600 transition-colors <?= (strpos($_SERVER['REQUEST_URI'], '/contact') !== false) ? 'text-brand-600' : '' ?>">Contact</a>
            </div>

            <!-- Right actions -->
            <div class="flex items-center gap-2 sm:gap-3">
                <!-- Search icon (optional - mobile friendly) -->
                <button onclick="toggleSearch()" class="md:hidden p-2 text-gray-500 hover:text-brand-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>

                <!-- Cart icon -->
                <a href="<?= APP_URL ?>/cart" class="relative p-2 text-gray-500 hover:text-brand-600 transition-colors">
                    <svg class="w-5 h-5 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    <span class="absolute top-0.5 right-0.5 w-4 h-4 bg-brand-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center leading-none">0</span>
                </a>

                <?php if (isset($_SESSION['user_id'])): ?>
                    <!-- User dropdown -->
                    <div class="relative" x-data="{open:false}">
                        <button onclick="toggleDropdown(event)"
                            class="flex items-center gap-1 sm:gap-2 bg-gray-100 hover:bg-gray-200 rounded-full px-2 sm:px-3 py-1.5 transition-colors text-sm font-medium">
                            <div class="w-6 h-6 bg-brand-500 text-white rounded-full flex items-center justify-center text-xs font-bold shrink-0">
                                <?= strtoupper(substr($_SESSION['user_name'] ?? 'U', 0, 1)) ?>
                            </div>
                            <span class="hidden sm:block text-gray-700 max-w-[100px] truncate"><?= htmlspecialchars($_SESSION['user_name'] ?? 'Account') ?></span>
                            <svg class="w-3.5 h-3.5 text-gray-400 hidden sm:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div id="userDropMenu" class="hidden absolute right-0 mt-2 w-48 bg-white border border-gray-100 rounded-xl shadow-lg py-1 text-sm z-50">
                            <a href="<?= APP_URL ?>/account" class="flex items-center gap-2.5 px-4 py-2.5 text-gray-700 hover:bg-gray-50">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                My Account
                            </a>
                            <a href="<?= APP_URL ?>/orders" class="flex items-center gap-2.5 px-4 py-2.5 text-gray-700 hover:bg-gray-50">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                My Orders
                            </a>
                            <a href="<?= APP_URL ?>/wishlist" class="flex items-center gap-2.5 px-4 py-2.5 text-gray-700 hover:bg-gray-50">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                                Wishlist
                            </a>
                            <hr class="my-1 border-gray-100">
                            <form action="<?= APP_URL ?>/logout" method="POST">
                                <button type="submit" class="w-full flex items-center gap-2.5 px-4 py-2.5 text-red-500 hover:bg-red-50 text-left">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    Sign Out
                                </button>
                            </form>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="hidden sm:flex items-center gap-2">
                        <a href="<?= APP_URL ?>/login" class="text-sm font-medium text-gray-600 hover:text-brand-600 transition-colors px-3 py-1.5">
                            Sign In
                        </a>
                        <a href="<?= APP_URL ?>/register" class="btn-brand text-white text-sm font-semibold px-4 py-2 rounded-lg shadow-sm hover:shadow-md transition-all">
                            Sign Up
                        </a>
                    </div>
                    <!-- Mobile auth icons -->
                    <div class="sm:hidden flex items-center gap-1">
                        <a href="<?= APP_URL ?>/login" class="p-2 text-gray-500 hover:text-brand-600 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </a>
                    </div>
                <?php endif; ?>

                <!-- Mobile menu button -->
                <button onclick="toggleMobileMenu()" class="md:hidden p-2 text-gray-500 hover:text-brand-600 transition-colors">
                    <svg id="mobileMenuIcon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Navigation Menu -->
        <div id="mobileMenu" class="hidden md:hidden py-4 space-y-2 border-t border-gray-100">
            <a href="<?= APP_URL ?>/" class="flex items-center gap-3 py-3 px-2 rounded-lg text-gray-600 hover:bg-gray-50 hover:text-brand-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                Home
            </a>
            <a href="<?= APP_URL ?>/products" class="flex items-center gap-3 py-3 px-2 rounded-lg text-gray-600 hover:bg-gray-50 hover:text-brand-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
                Shop
            </a>
            <a href="<?= APP_URL ?>/categories" class="flex items-center gap-3 py-3 px-2 rounded-lg text-gray-600 hover:bg-gray-50 hover:text-brand-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
                Categories
            </a>
            <a href="<?= APP_URL ?>/about" class="flex items-center gap-3 py-3 px-2 rounded-lg text-gray-600 hover:bg-gray-50 hover:text-brand-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                About
            </a>
            <a href="<?= APP_URL ?>/contact" class="flex items-center gap-3 py-3 px-2 rounded-lg text-gray-600 hover:bg-gray-50 hover:text-brand-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                Contact
            </a>

            <?php if (!isset($_SESSION['user_id'])): ?>
                <hr class="my-2 border-gray-100">
                <a href="<?= APP_URL ?>/login" class="flex items-center gap-3 py-3 px-2 rounded-lg text-brand-600 hover:bg-brand-50 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                    </svg>
                    Sign In
                </a>
                <a href="<?= APP_URL ?>/register" class="flex items-center gap-3 py-3 px-2 rounded-lg bg-brand-500 text-white hover:bg-brand-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                    Sign Up
                </a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<!-- Mobile Search Bar (hidden by default) -->
<div id="mobileSearch" class="hidden md:hidden bg-white border-b border-gray-100 px-4 py-3">
    <form action="<?= APP_URL ?>/products" method="GET" class="flex gap-2">
        <input type="text" name="search" placeholder="Search products..."
            class="flex-1 px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-brand-500 text-sm">
        <button type="submit" class="px-4 py-2 bg-brand-500 text-white rounded-lg hover:bg-brand-600 transition-colors text-sm font-medium">
            Search
        </button>
    </form>
</div>

<script>
    // Toggle user dropdown
    function toggleDropdown(event) {
        event.stopPropagation();
        const menu = document.getElementById('userDropMenu');
        menu.classList.toggle('hidden');
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        const btn = document.getElementById('userDropBtn');
        const menu = document.getElementById('userDropMenu');
        if (menu && btn && !btn.contains(e.target) && !menu.contains(e.target)) {
            menu.classList.add('hidden');
        }
    });

    // Toggle mobile menu
    function toggleMobileMenu() {
        const mobileMenu = document.getElementById('mobileMenu');
        const icon = document.getElementById('mobileMenuIcon');
        mobileMenu.classList.toggle('hidden');

        // Change icon based on menu state
        if (!mobileMenu.classList.contains('hidden')) {
            icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>';
        } else {
            icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>';
        }
    }

    // Toggle mobile search
    function toggleSearch() {
        const searchBar = document.getElementById('mobileSearch');
        searchBar.classList.toggle('hidden');
    }

    // Close mobile menu when clicking on a link (for better UX)
    document.querySelectorAll('#mobileMenu a').forEach(link => {
        link.addEventListener('click', () => {
            document.getElementById('mobileMenu').classList.add('hidden');
            // Reset menu icon
            const icon = document.getElementById('mobileMenuIcon');
            icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>';
        });
    });

    // Handle window resize - close mobile menu on desktop view
    window.addEventListener('resize', function() {
        if (window.innerWidth >= 768) {
            document.getElementById('mobileMenu').classList.add('hidden');
            document.getElementById('mobileSearch')?.classList.add('hidden');
            // Reset menu icon
            const icon = document.getElementById('mobileMenuIcon');
            if (icon) {
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>';
            }
        }
    });
</script>

<style>
    /* Additional mobile optimizations */
    @media (max-width: 640px) {
        .btn-brand {
            padding-left: 0.75rem;
            padding-right: 0.75rem;
            font-size: 0.75rem;
        }
    }

    /* Active link styling */
    #mobileMenu a:active {
        transform: scale(0.98);
    }

    /* Smooth transitions */
    #mobileMenu,
    #userDropMenu,
    #mobileSearch {
        transition: all 0.2s ease;
    }
</style>