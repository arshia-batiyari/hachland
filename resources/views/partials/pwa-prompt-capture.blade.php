{{-- قبل از مانیفست گوش می‌دهیم. اگر این شنونده داخل فایل defer باشد،
     کروم روی بازدید دوم رویداد نصب را قبل از اجرای اسکریپت می‌فرستد و دکمه بی‌اثر می‌ماند. --}}
<script>
window.__hachInstall = window.__hachInstall || null;
window.addEventListener('beforeinstallprompt', function (event) {
  event.preventDefault();
  window.__hachInstall = event;
  window.dispatchEvent(new Event('hach-install-ready'));
});
</script>
