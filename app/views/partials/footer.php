<footer class="bg-gray-900 text-gray-300 mt-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-10">

            <!-- Brand -->
            <div class="md:col-span-1">
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-9 h-9 rounded-lg btn-brand flex items-center justify-center">
                        <span class="text-white font-bold text-lg leading-none">C</span>
                    </div>
                    <span class="font-serif text-xl font-bold text-white">Cimsho</span>
                </div>
                <p class="text-sm text-gray-400 leading-relaxed">
                    Curated home décor crafted with passion. Bringing art and culture into your living space.
                </p>
            </div>

            <!-- Shop -->
            <div>
                <h4 class="text-white font-semibold mb-4 text-sm uppercase tracking-wider">Shop</h4>
                <ul class="space-y-2.5 text-sm">
                    <li><a href="<?= APP_URL ?>/products" class="hover:text-brand-400 transition-colors">All Products</a></li>
                    <li><a href="<?= APP_URL ?>/categories" class="hover:text-brand-400 transition-colors">Categories</a></li>
                    <li><a href="<?= APP_URL ?>/products?featured=1" class="hover:text-brand-400 transition-colors">Featured</a></li>
                    <li><a href="<?= APP_URL ?>/products?sale=1" class="hover:text-brand-400 transition-colors">On Sale</a></li>
                </ul>
            </div>

            <!-- Account -->
            <div>
                <h4 class="text-white font-semibold mb-4 text-sm uppercase tracking-wider">Account</h4>
                <ul class="space-y-2.5 text-sm">
                    <li><a href="<?= APP_URL ?>/login" class="hover:text-brand-400 transition-colors">Sign In</a></li>
                    <li><a href="<?= APP_URL ?>/register" class="hover:text-brand-400 transition-colors">Create Account</a></li>
                    <li><a href="<?= APP_URL ?>/orders" class="hover:text-brand-400 transition-colors">Track Order</a></li>
                    <li><a href="<?= APP_URL ?>/account" class="hover:text-brand-400 transition-colors">My Profile</a></li>
                </ul>
            </div>

            <!-- Info -->
            <div>
                <h4 class="text-white font-semibold mb-4 text-sm uppercase tracking-wider">Information</h4>
                <ul class="space-y-2.5 text-sm">
                    <li><a href="<?= APP_URL ?>/about" class="hover:text-brand-400 transition-colors">About Us</a></li>
                    <li><a href="<?= APP_URL ?>/contact" class="hover:text-brand-400 transition-colors">Contact Us</a></li>
                    <li><a href="<?= APP_URL ?>/shipping" class="hover:text-brand-400 transition-colors">Shipping Policy</a></li>
                    <li><a href="<?= APP_URL ?>/returns" class="hover:text-brand-400 transition-colors">Return Policy</a></li>
                </ul>
            </div>
        </div>

        <div class="border-t border-gray-800 mt-10 pt-6 flex flex-col sm:flex-row justify-between items-center gap-4 text-xs text-gray-500">
            <span>&copy; <?= date('Y') ?> Cimsho. All rights reserved.</span>
            <div class="flex items-center gap-4">
                <span>Dhaka, Bangladesh</span>
                <span>|</span>
                <span>৳ BDT</span>
            </div>
        </div>
    </div>
</footer>
</body>
</html>
