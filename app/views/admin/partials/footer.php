    </main>
  </div>
</div>

<!-- Toast -->
<div id="admin-toast" class="fixed bottom-5 right-5 z-50 bg-white border border-gray-100 shadow-xl rounded-xl px-5 py-3 flex items-center gap-3 translate-y-20 opacity-0 transition-all duration-300">
  <div id="admin-toast-icon" class="w-7 h-7 rounded-full bg-green-500 text-white flex items-center justify-center text-xs font-bold">✓</div>
  <span id="admin-toast-msg" class="text-sm font-medium"></span>
</div>

<script>
function showAdminToast(msg, type='success') {
  const t = document.getElementById('admin-toast');
  const icon = document.getElementById('admin-toast-icon');
  icon.style.background = type === 'success' ? '#10b981' : '#e94560';
  icon.textContent = type === 'success' ? '✓' : '✕';
  document.getElementById('admin-toast-msg').textContent = msg;
  t.classList.remove('translate-y-20','opacity-0');
  setTimeout(() => t.classList.add('translate-y-20','opacity-0'), 3000);
}

async function adminPost(url, data) {
  const res = await fetch(url, {
    method: 'POST',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: new URLSearchParams(data)
  });
  return res.json();
}

function confirmDelete(msg = 'Are you sure?') {
  return confirm(msg);
}
</script>
</body>
</html>
