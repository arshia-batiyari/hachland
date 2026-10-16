{{-- قبل از مانیفست گوش می‌دهیم. اگر این شنونده داخل فایل defer باشد،
     کروم روی بازدید دوم رویداد نصب را قبل از اجرای اسکریپت می‌فرستد و دکمه بی‌اثر می‌ماند. --}}
<script>
window.__hachInstall = window.__hachInstall || null;
window.addEventListener('beforeinstallprompt', function (event) {
  event.preventDefault();
  window.__hachInstall = event;
  window.dispatchEvent(new Event('hach-install-ready'));
});
document.addEventListener('click', function (event) {
  var close = event.target.closest && event.target.closest('.js-pwa-dismiss');
  if (!close) return;
  var banner = close.closest('.js-pwa-banner');
  if (!banner) return;
  event.preventDefault();
  banner.classList.remove('is-visible');
  banner.setAttribute('hidden', '');
  banner.style.display = 'none';
  try {
    localStorage.setItem(banner.getAttribute('data-dismiss-key') || 'pwa_banner_dismissed_at', String(Date.now()));
  } catch (err) {}
}, true);
</script>
