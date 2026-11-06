<ul id="syaratPw" class="text-xs space-y-0.5 text-gray-500">
  <li data-k="len">○ Minimal 12 karakter</li>
  <li data-k="up">○ Huruf besar (A-Z)</li>
  <li data-k="low">○ Huruf kecil (a-z)</li>
  <li data-k="num">○ Angka (0-9)</li>
  <li data-k="sym">○ Simbol (! @ # $ %)</li>
</ul>
<script>
  (() => {
    const input = document.querySelector('input[name=password]');
    const cek = {
      len: v => v.length >= 12, up: v => /[A-Z]/.test(v), low: v => /[a-z]/.test(v),
      num: v => /\d/.test(v), sym: v => /[^A-Za-z0-9]/.test(v),
    };
    input.addEventListener('input', () => {
      document.querySelectorAll('#syaratPw li').forEach(li => {
        const ok = cek[li.dataset.k](input.value);
        li.classList.toggle('text-green-700', ok);
        li.classList.toggle('text-gray-500', !ok);
        li.textContent = (ok ? '✔ ' : '○ ') + li.textContent.slice(2);
      });
    });
  })();
</script>