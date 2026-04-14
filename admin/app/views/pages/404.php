<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>404 — Page Not Found</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@600;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>* { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center">
    <div class="text-center">
        <div class="text-8xl font-extrabold text-indigo-200 mb-4">404</div>
        <h1 class="text-2xl font-bold text-slate-700 mb-2">Page Not Found</h1>
        <p class="text-slate-400 mb-6">The page you're looking for doesn't exist.</p>
        <a href="<?= APP_URL ?>/dashboard" class="px-6 py-2.5 rounded-xl text-white font-semibold text-sm" style="background:linear-gradient(135deg,#6366f1,#8b5cf6);text-decoration:none">
            Back to Dashboard
        </a>
    </div>
</body>
</html>
