<div class="min-h-[80vh] flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-md">

        <!-- Logo -->
        <div class="text-center mb-8">
            <a href="<?= APP_URL ?>/" style="text-decoration:none">
                <span class="serif text-4xl font-light" style="color:var(--charcoal)">Cimsho</span>
            </a>
            <p class="text-sm mt-2" style="color:var(--muted)">Sign in to your account</p>
        </div>

        <?php if(!empty($_SESSION['flash'])): $f=$_SESSION['flash']; unset($_SESSION['flash']); ?>
        <div class="flex items-center gap-2 p-3.5 rounded-xl mb-5 <?= $f['type']==='error'?'bg-red-50 border border-red-100':'bg-emerald-50 border border-emerald-100' ?>">
            <i class="fas <?= $f['type']==='error'?'fa-circle-xmark text-red-500':'fa-circle-check text-emerald-500' ?> text-sm"></i>
            <p class="text-sm <?= $f['type']==='error'?'text-red-700':'text-emerald-700' ?>"><?= htmlspecialchars($f['msg']) ?></p>
        </div>
        <?php endif; ?>

        <div class="bg-white rounded-3xl p-8 border shadow-sm" style="border-color:var(--border)">
            <form method="POST" action="<?= APP_URL ?>/auth/login">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wide mb-1.5" style="color:var(--muted)">Email Address</label>
                        <input type="email" name="email" required autofocus
                               placeholder="your@email.com"
                               class="form-input w-full px-4 py-3 rounded-xl text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wide mb-1.5" style="color:var(--muted)">Password</label>
                        <div class="relative">
                            <input type="password" name="password" id="pwd" required
                                   placeholder="••••••••"
                                   class="form-input w-full px-4 py-3 rounded-xl text-sm pr-11">
                            <button type="button" onclick="togglePwd()" class="absolute right-3.5 top-1/2 -translate-y-1/2" style="color:var(--muted)">
                                <i id="pwd-eye" class="fas fa-eye text-sm"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn-clay w-full py-3.5 rounded-xl text-sm font-semibold mt-6 flex items-center justify-center gap-2">
                    <i class="fas fa-right-to-bracket text-xs"></i> Sign In
                </button>
            </form>
        </div>

        <div class="text-center mt-6 text-sm" style="color:var(--muted)">
            Don't have an account?
            <a href="<?= APP_URL ?>/account/signup" class="font-semibold hover:underline ml-1" style="color:var(--clay);text-decoration:none">Create one</a>
        </div>

        <div class="text-center mt-3">
            <a href="<?= APP_URL ?>/checkout" class="text-xs" style="color:var(--muted);text-decoration:none">
                <i class="fas fa-arrow-right mr-1 text-[10px]"></i>Continue as Guest
            </a>
        </div>
    </div>
</div>

<script>
function togglePwd(){
    const p=document.getElementById('pwd'), e=document.getElementById('pwd-eye');
    p.type=p.type==='password'?'text':'password';
    e.className='fas '+(p.type==='password'?'fa-eye':'fa-eye-slash')+' text-sm';
}
</script>
