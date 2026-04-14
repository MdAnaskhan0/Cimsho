<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In — <?= APP_NAME ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }

        body {
            background: #0f172a;
            min-height: 100vh;
        }

        .bg-pattern {
            background-image:
                radial-gradient(circle at 20% 50%, rgba(99,102,241,0.15) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(139,92,246,0.1) 0%, transparent 40%),
                radial-gradient(circle at 50% 80%, rgba(16,185,129,0.05) 0%, transparent 40%);
        }

        .grid-overlay {
            background-image: linear-gradient(rgba(255,255,255,.03) 1px, transparent 1px),
                              linear-gradient(90deg, rgba(255,255,255,.03) 1px, transparent 1px);
            background-size: 40px 40px;
        }

        .glass-card {
            background: rgba(255,255,255,0.04);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.08);
        }

        .input-field {
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.1);
            color: white;
            transition: all 0.2s ease;
        }
        .input-field:focus {
            background: rgba(255,255,255,0.1);
            border-color: rgba(99,102,241,0.8);
            box-shadow: 0 0 0 3px rgba(99,102,241,0.15);
            outline: none;
        }
        .input-field::placeholder { color: rgba(255,255,255,0.3); }

        .btn-login {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            transition: all 0.2s ease;
            box-shadow: 0 4px 20px rgba(99,102,241,0.4);
        }
        .btn-login:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 30px rgba(99,102,241,0.5);
        }
        .btn-login:active { transform: translateY(0); }

        /* Floating orbs */
        @keyframes float1 { 0%,100%{transform:translate(0,0) scale(1)} 50%{transform:translate(20px,-30px) scale(1.05)} }
        @keyframes float2 { 0%,100%{transform:translate(0,0) scale(1)} 50%{transform:translate(-15px,20px) scale(0.98)} }
        .orb1 { animation: float1 8s ease-in-out infinite; }
        .orb2 { animation: float2 10s ease-in-out infinite; }

        /* Slide in */
        @keyframes slideUp { from{opacity:0;transform:translateY(24px)} to{opacity:1;transform:translateY(0)} }
        .slide-up { animation: slideUp 0.5s cubic-bezier(.4,0,.2,1) forwards; }

        .stat-pill {
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.08);
        }
    </style>
</head>
<body class="bg-pattern grid-overlay min-h-screen flex">

    <!-- Left panel -->
    <div class="hidden lg:flex flex-col justify-between w-1/2 p-12 relative overflow-hidden">
        <!-- Orbs -->
        <div class="orb1 absolute top-1/4 left-1/4 w-64 h-64 rounded-full opacity-20" style="background:radial-gradient(circle, #6366f1, transparent)"></div>
        <div class="orb2 absolute bottom-1/4 right-1/4 w-80 h-80 rounded-full opacity-10" style="background:radial-gradient(circle, #8b5cf6, transparent)"></div>

        <!-- Logo -->
        <div class="flex items-center gap-3 relative z-10">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background:linear-gradient(135deg,#6366f1,#8b5cf6)">
                <i class="fas fa-store text-white text-sm"></i>
            </div>
            <div>
                <div class="text-white font-bold text-lg"><?= APP_NAME ?></div>
                <div class="text-indigo-400 text-xs">Admin Console</div>
            </div>
        </div>

        <!-- Middle content -->
        <div class="relative z-10 space-y-8">
            <div>
                <h2 class="text-4xl font-extrabold text-white leading-tight mb-4">
                    Your store,<br>
                    <span style="background:linear-gradient(135deg,#818cf8,#a78bfa);-webkit-background-clip:text;-webkit-text-fill-color:transparent">fully in control.</span>
                </h2>
                <p class="text-slate-400 text-base leading-relaxed max-w-sm">
                    Manage products, orders, customers and analytics from a single powerful dashboard.
                </p>
            </div>

            <!-- Stats pills -->
            <div class="flex flex-wrap gap-3">
                <div class="stat-pill rounded-full px-4 py-2 flex items-center gap-2">
                    <i class="fas fa-bag-shopping text-indigo-400 text-xs"></i>
                    <span class="text-white text-sm font-medium">Order Management</span>
                </div>
                <div class="stat-pill rounded-full px-4 py-2 flex items-center gap-2">
                    <i class="fas fa-chart-line text-emerald-400 text-xs"></i>
                    <span class="text-white text-sm font-medium">Revenue Analytics</span>
                </div>
                <div class="stat-pill rounded-full px-4 py-2 flex items-center gap-2">
                    <i class="fas fa-boxes-stacked text-violet-400 text-xs"></i>
                    <span class="text-white text-sm font-medium">Inventory Tracking</span>
                </div>
                <div class="stat-pill rounded-full px-4 py-2 flex items-center gap-2">
                    <i class="fas fa-users text-sky-400 text-xs"></i>
                    <span class="text-white text-sm font-medium">Customer Insights</span>
                </div>
            </div>
        </div>

        <p class="text-slate-600 text-xs relative z-10">© <?= date('Y') ?> <?= APP_NAME ?>. All rights reserved.</p>
    </div>

    <!-- Right panel: Login form -->
    <div class="flex-1 flex items-center justify-center p-6 lg:p-12">
        <div class="w-full max-w-md slide-up">

            <!-- Mobile logo -->
            <div class="flex lg:hidden items-center gap-3 mb-8">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center" style="background:linear-gradient(135deg,#6366f1,#8b5cf6)">
                    <i class="fas fa-store text-white text-sm"></i>
                </div>
                <span class="text-white font-bold"><?= APP_NAME ?></span>
            </div>

            <div class="glass-card rounded-2xl p-8">
                <div class="mb-7">
                    <h1 class="text-2xl font-extrabold text-white mb-1">Welcome back</h1>
                    <p class="text-slate-400 text-sm">Sign in to your admin account</p>
                </div>

                <?php if ($timeout): ?>
                <div class="mb-5 flex items-center gap-2 bg-amber-500/10 border border-amber-500/20 rounded-xl px-4 py-3">
                    <i class="fas fa-clock text-amber-400 text-sm"></i>
                    <p class="text-amber-300 text-sm">Session expired. Please sign in again.</p>
                </div>
                <?php endif; ?>

                <?php
                $flash = $_SESSION['flash'] ?? null;
                if ($flash && $flash['type'] === 'error'):
                    unset($_SESSION['flash']);
                ?>
                <div class="mb-5 flex items-center gap-2 bg-red-500/10 border border-red-500/20 rounded-xl px-4 py-3">
                    <i class="fas fa-circle-xmark text-red-400 text-sm"></i>
                    <p class="text-red-300 text-sm"><?= htmlspecialchars($flash['message']) ?></p>
                </div>
                <?php endif; ?>

                <form method="POST" action="<?= APP_URL ?>/login" id="login-form" class="space-y-5">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">

                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-2 uppercase tracking-wider">Username</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500"><i class="fas fa-user text-xs"></i></span>
                            <input type="text" name="username" required autocomplete="username"
                                   placeholder="Enter your username"
                                   class="input-field w-full pl-10 pr-4 py-3 rounded-xl text-sm">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-2 uppercase tracking-wider">Password</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500"><i class="fas fa-lock text-xs"></i></span>
                            <input type="password" id="pwd-input" name="password" required autocomplete="current-password"
                                   placeholder="••••••••••"
                                   class="input-field w-full pl-10 pr-11 py-3 rounded-xl text-sm">
                            <button type="button" onclick="togglePwd('pwd-input')" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-500 hover:text-slate-300 transition-colors">
                                <i id="pwd-eye" class="fas fa-eye text-xs"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" id="login-btn" class="btn-login w-full py-3 rounded-xl text-white font-semibold text-sm mt-2">
                        <span id="login-btn-text"><i class="fas fa-right-to-bracket mr-2"></i>Sign In</span>
                        <span id="login-btn-loader" class="hidden"><i class="fas fa-spinner fa-spin mr-2"></i>Signing in…</span>
                    </button>
                </form>
            </div>

            <p class="text-center text-slate-600 text-xs mt-5">
                Secure admin access — unauthorized use is prohibited.
            </p>
        </div>
    </div>

    <script>
    function togglePwd(id) {
        const el  = document.getElementById(id);
        const eye = document.getElementById('pwd-eye');
        if (el.type === 'password') {
            el.type = 'text';
            eye.className = 'fas fa-eye-slash text-xs';
        } else {
            el.type = 'password';
            eye.className = 'fas fa-eye text-xs';
        }
    }

    document.getElementById('login-form').addEventListener('submit', function() {
        document.getElementById('login-btn-text').classList.add('hidden');
        document.getElementById('login-btn-loader').classList.remove('hidden');
        document.getElementById('login-btn').disabled = true;
    });
    </script>
</body>
</html>
