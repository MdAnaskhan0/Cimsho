<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>404 — Cimsho</title>
<script src="https://cdn.tailwindcss.com"></script>
<style>* { font-family: 'Trebuchet MS', Tahoma, Geneva, Verdana, sans-serif; }</style>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center">
<div class="text-center p-8">
  <div class="text-8xl mb-4">🔍</div>
  <h1 class="text-4xl font-extrabold text-gray-800 mb-2">404</h1>
  <p class="text-gray-400 mb-6">Page not found</p>
  <a href="<?= defined('BASE_URL') ? BASE_URL : '/' ?>/" class="inline-block bg-red-500 text-white px-6 py-3 rounded-xl font-bold text-sm hover:bg-red-600 transition-colors">Go Home</a>
</div>
</body>
</html>
