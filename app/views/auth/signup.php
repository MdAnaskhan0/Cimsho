<div class="min-h-[80vh] flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-md">

        <div class="text-center mb-8">
            <a href="<?= APP_URL ?>/" style="text-decoration:none">
                <span class="serif text-4xl font-light" style="color:var(--charcoal)">Cimsho</span>
            </a>
            <p class="text-sm mt-2" style="color:var(--muted)">Create your account</p>
        </div>

        <?php if(!empty($_SESSION['flash'])): $f=$_SESSION['flash']; unset($_SESSION['flash']); ?>
        <div class="flex items-center gap-2 p-3.5 rounded-xl mb-5 <?= $f['type']==='error'?'bg-red-50 border border-red-100':'bg-emerald-50 border border-emerald-100' ?>">
            <i class="fas <?= $f['type']==='error'?'fa-circle-xmark text-red-500':'fa-circle-check text-emerald-500' ?> text-sm"></i>
            <p class="text-sm <?= $f['type']==='error'?'text-red-700':'text-emerald-700' ?>"><?= htmlspecialchars($f['msg']) ?></p>
        </div>
        <?php endif; ?>

        <div class="bg-white rounded-3xl p-8 border shadow-sm" style="border-color:var(--border)">
            <form method="POST" action="<?= APP_URL ?>/auth/signup">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wide mb-1.5" style="color:var(--muted)">Full Name</label>
                        <input type="text" name="name" required autofocus value="<?= htmlspecialchars($_POST['name']??'') ?>"
                               placeholder="Your full name"
                               class="form-input w-full px-4 py-3 rounded-xl text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wide mb-1.5" style="color:var(--muted)">Email Address</label>
                        <input type="email" name="email" required value="<?= htmlspecialchars($_POST['email']??'') ?>"
                               placeholder="your@email.com"
                               class="form-input w-full px-4 py-3 rounded-xl text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wide mb-1.5" style="color:var(--muted)">Phone Number</label>
                        <input type="text" name="phone" value="<?= htmlspecialchars($_POST['phone']??'') ?>"
                               placeholder="+880 17XX XXXXXX"
                               class="form-input w-full px-4 py-3 rounded-xl text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wide mb-1.5" style="color:var(--muted)">Password</label>
                        <div class="relative">
                            <input type="password" name="password" id="pwd" required
                                   placeholder="Min. 8 characters"
                                   class="form-input w-full px-4 py-3 rounded-xl text-sm pr-11"
                                   oninput="checkStrength(this.value)">
                            <button type="button" onclick="togglePwd()" class="absolute right-3.5 top-1/2 -translate-y-1/2" style="color:var(--muted)">
                                <i id="pwd-eye" class="fas fa-eye text-sm"></i>
                            </button>
                        </div>
                        <div class="flex gap-1 mt-2">
                            <?php for($i=0;$i<4;$i++): ?>
                            <div id="str-<?= $i ?>" class="h-1 flex-1 rounded-full transition-all" style="background:#e5e7eb"></div>
                            <?php endfor; ?>
                        </div>
                        <p id="str-label" class="text-xs mt-1" style="color:var(--muted)"></p>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wide mb-1.5" style="color:var(--muted)">Confirm Password</label>
                        <input type="password" name="confirm_password" required
                               placeholder="Repeat your password"
                               class="form-input w-full px-4 py-3 rounded-xl text-sm">
                    </div>
                </div>

                <button type="submit" class="btn-clay w-full py-3.5 rounded-xl text-sm font-semibold mt-6 flex items-center justify-center gap-2">
                    <i class="fas fa-user-plus text-xs"></i> Create Account
                </button>
            </form>
        </div>

        <div class="text-center mt-6 text-sm" style="color:var(--muted)">
            Already have an account?
            <a href="<?= APP_URL ?>/account/login" class="font-semibold ml-1" style="color:var(--clay);text-decoration:none">Sign In</a>
        </div>
    </div>
</div>

<script>
function togglePwd(){
    const p=document.getElementById('pwd'),e=document.getElementById('pwd-eye');
    p.type=p.type==='password'?'text':'password';
    e.className='fas '+(p.type==='password'?'fa-eye':'fa-eye-slash')+' text-sm';
}
function checkStrength(v){
    let s=0;
    if(v.length>=8)s++;if(/[A-Z]/.test(v))s++;if(/[0-9]/.test(v))s++;if(/[^A-Za-z0-9]/.test(v))s++;
    const colors=['#ef4444','#f97316','#eab308','#22c55e'];
    const labels=['Weak','Fair','Good','Strong'];
    for(let i=0;i<4;i++) document.getElementById('str-'+i).style.background=i<s?colors[s-1]:'#e5e7eb';
    document.getElementById('str-label').textContent=s>0?labels[s-1]:'';
    document.getElementById('str-label').style.color=colors[s-1]||'var(--muted)';
}
</script>
