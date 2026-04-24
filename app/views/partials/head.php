<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Page') ?> — <?= APP_NAME ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
    tailwind.config = {
        theme: {
            extend: {
                fontFamily: {
                    sans:    ['Inter', 'sans-serif'],
                    serif:   ['Playfair Display', 'serif'],
                },
                colors: {
                    brand: {
                        50:  '#fdf6ee',
                        100: '#faebd7',
                        200: '#f3d5a8',
                        300: '#eaba70',
                        400: '#e09d45',
                        500: '#d4842a',
                        600: '#b86820',
                        700: '#924f1d',
                        800: '#773f1e',
                        900: '#62341b',
                    },
                    neutral: {
                        850: '#1f1f1f',
                        950: '#0d0d0d',
                    }
                }
            }
        }
    }
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-serif { font-family: 'Playfair Display', serif; }
        .hero-gradient { background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 50%, #1a1a1a 100%); }
        .card-hover { transition: transform .25s ease, box-shadow .25s ease; }
        .card-hover:hover { transform: translateY(-4px); box-shadow: 0 20px 40px rgba(0,0,0,.12); }
        .btn-brand { background: linear-gradient(135deg, #d4842a, #b86820); }
        .btn-brand:hover { background: linear-gradient(135deg, #b86820, #924f1d); }
        input:focus, select:focus, textarea:focus { outline: none; box-shadow: 0 0 0 3px rgba(212,132,42,.25); }
        .flash-success { animation: slideDown .4s ease; }
        @keyframes slideDown { from { opacity:0; transform: translateY(-10px); } to { opacity:1; transform: translateY(0); } }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 antialiased">
